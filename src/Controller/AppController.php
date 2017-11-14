<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{   
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */

    public $dateFrom;
    public $dateTo;
    public $helpers = ['Html' => ['className' => 'AclHtml']];
    public $appSettings = null;

    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');

        $authConfigFormFields = ['username' => 'username','password' => 'password'];
        $authConfigLoginAction = ['controller' => 'Users','action' => 'login'];
        $authConfigLogoutAction = ['controller'=>'users','action'=>'login'];
        $authConfigUserModel = 'Users';

        $this->loadComponent('Auth', [
            'authorize' => 
                'Controller',
                'authenticate' => [
                    'Form' => ['fields' => $authConfigFormFields,'userModel'=>$authConfigUserModel],
                    //'Basic' => ['fields' => $authConfigFormFields,'userModel'=>$authConfigUserModel]
                ],
                'loginAction' =>$authConfigLoginAction,
                'logoutRedirect'=>$authConfigLogoutAction
            ]
        );

        $this->set('online', $this->_loggedIn());
        $this->set('authUser', $this->Auth->user());
        $this->set('appSettings', $this->appSettings());
        $this->set('appTransactionStatuses', $this->appTransactionStatuses());
        $this->set('appTransactionCurrencies', $this->appTransactionCurrencies());
        $this->set('appTransactionCurrencies', $this->appTransactionCurrencies());
        $this->set('offline', (int) Configure::read('offline'));

        if (!empty($_GET['date_from_ranger'])) {
            $this->request->data['date_from_ranger'] = $_GET['date_from_ranger'];  
            $this->request->data['date_to_ranger'] = $_GET['date_to_ranger'];  
        }

        if(!empty($this->request->data['date_from_ranger']))
        {
            $this->dateTo = $this->request->data['date_to_ranger'];
            // $this->dateTo = $this->dateTo['year'] . '-' . $this->dateTo['month'] . '-' . $this->dateTo['day'];

            $this->dateFrom = $this->request->data['date_from_ranger'];
            // $this->dateFrom = $this->dateFrom['year'] . '-' . $this->dateFrom['month'] . '-' . $this->dateFrom['day'];
        }else{
            $this->dateTo = date('Y-m-d');
            $this->dateFrom = date('Y-m-d',strtotime($this->dateTo . ' -2days'));

            if ($this->request->params['controller']=='Dashboards') {
                $this->dateFrom = date('Y-m-d',strtotime($this->dateTo . ' -1month'));
            }
        }

        $this->set('dateTo', $this->dateTo);
        $this->set('dateFrom', $this->dateFrom);
		//echo '<pre>';
		//print_r($this->appTransactionTypes());
		//exit;
        $this->set('menuTransactionTypes', $this->appTransactionTypes());
        $this->set('menuAccountTypes', $this->appAccountTypes());       
        $this->set('sample_tts', $this->sample_tts());  
    }

    public function refreshSessionCache($key='')
    {
        if ($key) {
            $this->request->session()->write($key,null);
            return;
        }
        
        $this->request->session()->write('appSettings',null);
        $this->request->session()->write('appTransactionTypes',null);
        $this->request->session()->write('appAccountTypes',null);
        $this->request->session()->write('appTransactionCurrencies',null);
    }

    public function appAccountTypes(){

        if(!$this->Auth->User('id')) return null;

        $appAccountTypes = $this->request->session()->read('appAccountTypes');
        if (empty($appAccountTypes)) {
            $this->loadModel('AccountTypes');
            $appAccountTypes = $this->AccountTypes->find()
            ->where(['AccountTypes.add_to_menu'=>1])
            ->limit(200)
            ->order(['AccountTypes.name ASC'])
            ->toArray();
            
            $this->request->session()->write('appAccountTypes',$appAccountTypes);
            $this->request->session()->write('appAccountTypes',null);
        }
        return $appAccountTypes;
    }

    public function sample_tts(){
        $resp = [];

        for ($i=0; $i < 16; $i++) { 
            $resp[] = [
                'id' => $i,
                'menu_link_title' => "T.Type $i",
                'currency_id' => ($i<5)?"USD":(($i<10)?"SDG":"GBP"),
                'to_account' => [
                    'id' => $i,
                    'name' => "To Account $i",
                ],
                'from_account' => [
                    'id' => ($i +1),
                    'name' =>"From Account " . ($i+1)
                ]
            ];
        }

        return json_decode(json_encode($resp));
        
    }
    
    public function appTransactionTypes(){

        if(!$this->Auth->User('id')) return null;

        $appTransactionTypes = $this->request->session()->read('appTransactionTypes');
        $conditions = [];
        if (empty($appTransactionTypes)) {

            if($this->Auth->User('role')=='super_admin'){
                $conditions = [
                    'TransactionTypes.add_to_menu'=>true,
                    'TransactionTypes.super_admin_menu_only'=>true,
                    'TransactionTypes.branch_id'=>$this->Auth->User('branch_id')
                ];
            }else{
                $conditions = [
                    'TransactionTypes.add_to_menu'=>true,
                    'TransactionTypes.super_admin_menu_only'=>false,
                    'TransactionTypes.branch_id'=>$this->Auth->User('branch_id')
                ];
            }
            

            $this->loadModel('TransactionTypes');
            $appTransactionTypes = $this->TransactionTypes->find()
            ->select([
                    'TransactionTypes.id',
                    'TransactionTypes.menu_link_title',
                    'TransactionTypes.currency_id',
                    'TransactionTypes.priority',
					'TransactionTypes.balance_sheet_side',
                    'FromAccounts.id',
                    'FromAccounts.name',
                    'ToAccounts.id',
                    'ToAccounts.name',
                ])
            ->where($conditions)
            ->contain(['FromAccounts','ToAccounts'])
            ->limit(200)
            ->order(['TransactionTypes.currency_id DESC,TransactionTypes.balance_sheet_side DESC,TransactionTypes.priority ASC'])
            // ->group(['TransactionTypes.currency_id'])
            ->toArray();

            // pr(json_decode(json_encode($appTransactionTypes),true));
            // exit();
            
            $this->request->session()->write('appTransactionTypes',$appTransactionTypes);
        }
        return $appTransactionTypes;
    }

    public function appSettings($branch_id = null, $refresh = false){
        if(empty($branch_id)) {
            $branch_id = $this->Auth->User('branch_id');
        }

        $appSettings = $this->request->session()->read('appSettings' . $branch_id);

        if (empty($appSettings) || $refresh) {
            $this->loadModel('AppSettings');
            $appSettings = $this->AppSettings->find()
                ->where(['AppSettings.branch_id'=>$branch_id])
                ->first();

            if($this->Auth->User('id')){
                if(empty($appSettings->id)){
                    $this->Flash->warning(__('Settings for this branch do not exist. Please as Super admin to create then now. They will affect the system badly'));
                }
            }
            $this->request->session()->write('appSettings' . $branch_id,$appSettings);
        }
        $this->appSettings = $appSettings;
        return $appSettings;
    }

    public function cleanCustomFields($input){
        $output = [];
        if (!empty($input)) {
            $_ccfs = explode(',', $input);
            foreach ($_ccfs as $ccf ) {
                $ccf = str_replace(' ', "_", trim($ccf));
                $x = strtolower(trim($ccf));
                if (!empty($x)) {
                    $output[] = $x;
                }
            }
        }
        return $output;
    }

    public function appTransactionStatuses(){
        $appTransactionStatuses = $this->request->session()->read('appTransactionStatuses');
        if (empty($appTransactionStatuses)) {
            $this->loadModel('TransactionStatuses');
            $appTransactionStatuses = $this->TransactionStatuses->find('list')
                // ->where(['AppSettings.branch_id'=>$this->Auth->User('branch_id')])
                ->toArray();
            $this->request->session()->write('appTransactionStatuses',$appTransactionStatuses);
        }
        
        return $appTransactionStatuses;
    }

    public $appTransactionCurrencies = ['UGX'];
    public function appTransactionCurrencies(){
        $appTransactionCurrencies = $this->request->session()->read('appTransactionCurrencies');
        if (empty($appSettings)) {
            $this->loadModel('Currencies');
            $appTransactionCurrencies = $this->Currencies->find('list',[
                'order'=>['Currencies.priority ASC']
            ])
            ->toArray();
            $this->request->session()->write('appTransactionCurrencies',$appTransactionCurrencies);
        }
        $this->appTransactionCurrencies = $appTransactionCurrencies;
        return $appTransactionCurrencies;
    }

    public function isAuthorized($user)
    {
        if(empty($this->Auth->user('id'))){
            $this->Flash->error('Access Denied!');
            return false;
        }

        // super Admin has access to everything
        if($this->Auth->user('role')=='super_admin'){
            return true;
        }

        if(!$this->actionAllowed($this->Auth->user('role_id'))){
            return false;
        }

        return true;
    }   

    public function _loggedIn() {
        $logged_in = FALSE;
        if (!empty($this->Auth->user('id'))) {
            $logged_in = TRUE;
        }
        return $logged_in;
    }

    public function actionAllowed($user_role_id){
        $action = $this->request->params['action'];
        if(in_array($action, ['login','logout','register'])){
            return true;
        }

        $controller = $this->request->params['controller'];
        $user_id = $this->request->session()->read('Auth.User.id');
        $sessionKey = "ACLHtml.User.$controller.$action.$user_id";
        $rolePermission = $this->request->session()->read($sessionKey);

        if(empty($rolePermission)){
            $RolePermissions = TableRegistry::get('RolePermissions');
            $rolePermission = $RolePermissions->find()
            ->select(['RolePermissions.id','RolePermissions.enabled'])
            ->where([
                'RolePermissions.controller'=>$controller,
                'RolePermissions.action'=>$action,
                'RolePermissions.role_id'=>$user_role_id
            ])->first();
            $this->request->session()->write($sessionKey,$rolePermission);
        }

        if(!empty($rolePermission->id) && $rolePermission->enabled){
            return true;
        }
        return false;
    }

    /**
     * Before render callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return void
     */
    public function beforeRender(Event $event)
    {

        if (!array_key_exists('_serialize', $this->viewVars) &&
            in_array($this->response->type(), ['application/json', 'application/xml'])
        ) {
            $this->set('_serialize', true);
        }
    }
}

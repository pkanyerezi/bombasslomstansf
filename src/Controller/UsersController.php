<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{
    public function beforeFilter(\Cake\Event\Event $event)
    {
        $this->Auth->allow(['add']);
    }

    public function isAuthorized($user)
    {
        $action = $this->request->params['action'];
        if(in_array($action,array('add'))){
            return true;
        }
        return parent::isAuthorized($user);
    }

    public function logout($key=null,$arg0=null)
    {
        $this->refreshSessionCache();
        
        $this->request->session()->write('ACLHtml',[]);
        if($key=='blackhole')
            $this->Flash->error('Please Login.');
        elseif ($key=='Validate'){
            $this->Flash->error('Please kindly take a minute to re-verify your account phone number. Sorry for the inconvenience.');
            $this->redirect(['action'=>'forgot_password/'.$this->request->session()->read('phoneToValidate').'/Validate']);
        }else
            $this->Flash->success('You are now logged out.');
        return $this->redirect($this->Auth->logout());
    }

    public function login()
    {   
        $this->request->session()->write('ACLHtml',[]);
        if(!empty($this->Auth->user('id'))){
            $this->Flash->success('You have been loged out!');
            $this->redirect($this->Auth->logout());
        }
        
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);
				$this->appSettings($user['branch_id'], true);
                return $this->redirect($this->Auth->redirectUrl());
            }else{
                
                $this->Flash->error('Username/Password is incorrect');
                return $this->redirect(['action'=>'login']);
            }
        }
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $conditions = [];
        if (!in_array($this->Auth->User('role'), ['super_admin'])) {
            $conditions['Users.branch_id'] = $this->Auth->User('branch_id');
        }

        $this->paginate = [
            'conditions'=>$conditions,
            'contain'=>['Branches']
        ];

        $users = $this->paginate($this->Users);

        $this->set(compact('users'));
        $this->set('_serialize', ['users']);
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        /*$user = $this->Users->get($id, [
            'contain' => ['Deliveries', 'Expenses', 'FuelTrackDowns', 'Performances', 'PettyCashTransactions', 'StockPileTransactions']
        ]);*/

        $user = $this->Users->get($id, [
            'contain' => ['Branches']
        ]);

        $this->set('user', $user);
        $this->set('_serialize', ['user']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        //Allow to add a super admin incase there's none
        // $super_admin_exists = $this->Users->Roles->find()->where(['Roles.alias'=>'admin'])->count();
        $super_admin_exists = $this->Users->find()->where(['Users.role'=>'super_admin'])->count();
        if($super_admin_exists)
        {
            if (!$this->Auth->User()) 
            {
                $this->Flash->error('Please request super admin to create an account for you. Thanks.');
                if ($this->Auth->User()) {
                    return $this->redirect(['action' => 'view',$this->Auth->User()]);
                } else {
                    return $this->redirect(['action' => 'login']);
                }
            }
        }

        //If logged in
        $AuthUser = $this->Auth->User();
        if (!$AuthUser) {
            $this->request->data['role']='super_admin';
        }

        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $userRole = $this->Users->Roles->get($this->request->data['role_id']);
            $this->request->data['role'] = $userRole->alias;
            
            
            $user = $this->Users->patchEntity($user, $this->request->data);
            $user->role = $this->request->data['role'];

            if (!in_array($this->Auth->User('role'), ['super_admin'])) {
                $user->branch_id = $this->Auth->User('branch_id');
            }

            $resultUsers = $this->Users->save($user);
            if ($resultUsers) {
                if (!$AuthUser) {
                    $this->Flash->success(__('Admin account created. Login to continue'));
                    return $this->redirect(['action' => 'login']);
                }else{
                    $this->Flash->success(__('Account created'));
                    return $this->redirect(['action' => 'index']);
                }
            } else {
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }
        }

        if (in_array($this->Auth->User('role'), ['super_admin'])) {
            $branches = $this->Users->Branches->find('list');
        }

        $roles = $this->Users->Roles->find('list')->where(['NOT'=>['Roles.alias'=>'super_admin']]);
        $this->set(compact('user','branches','roles'));
        $this->set('_serialize', ['user','branches','roles']);
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->data);

            if(empty($this->request->data('role_id'))){
                $this->request->data['role_id'] = $user->role_id;
            }

            $userRole = $this->Users->Roles->get($this->request->data['role_id']);
            $user->role = $userRole->alias;
            
            if ($this->Users->save($user)) {
                if ($user->id==$this->Auth->User('id')) {
                    $user = $this->Users->get($id);
                    $this->Auth->setUser($user->toArray());
                }
    
                if($user->role=='super_admin') $this->refreshSessionCache();

                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }
        }
        $branches = $this->Users->Branches->find('list');
        $roles = $this->Users->Roles->find('list');

        $this->set(compact('user','branches','roles'));
        $this->set('_serialize', ['user','branches','roles']);
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        if (!in_array($this->Auth->User('role'), ['super_admin'])) {
            $user = $this->Users->find()
            ->where([
                'id'=>$id,
                'branch_id'=>$this->Auth->User('branch_id')
            ])->first();
        }else{
            $user = $this->Users->find()
            ->where([
                'id'=>$id,
                'NOT'=>['role'=>'super_admin']
            ])->first();
        }

        if (empty($user->id)) {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }else{
            if ($this->Users->delete($user)) {
                $this->Flash->success(__('The user has been deleted.'));
            } else {
                $this->Flash->error(__('The user could not be deleted. Please, try again.'));
            }
        }
        return $this->redirect(['action' => 'index']);
    }
}

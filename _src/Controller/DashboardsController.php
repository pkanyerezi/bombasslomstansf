<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;

/**
 * AppSettings Controller
 *
 * @property \App\Model\Table\AppSettingsTable $AppSettings
 */
class DashboardsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
    	$this->loadModel('Transactions');
        //Get the transaction stats of this merchant account
        $stats = $this->Transactions->find()
        ->select(['total_transactions'=>'COUNT(Transactions.id)','Transactions.transaction_status_id'])
        ->where(['Transactions.branch_id'=>$this->Auth->User('branch_id')])
        ->group(['Transactions.transaction_status_id']);
        $this->set('stats',$stats);
    }
}

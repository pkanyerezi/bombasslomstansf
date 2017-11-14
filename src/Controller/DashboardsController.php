<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\Core\Configure;

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

        // Total of transactions that need to be synched
        if($this->Auth->User('role')=='super_admin' && ((int) Configure::read('offline'))){

            $unSyncedCount = $this->Transactions->find()
            ->where([
                'Transactions.offline'=>true,
                'Transactions.transaction_status_id'=>$this->appSettings->final_transaction_status_id,
                'Transactions.parent_transaction_id'=>0,
            ])->count();

            $this->set('unSyncedTransactionsCount',$unSyncedCount);
        }

    }
	
	public function dailyBalancing(){
		// Filters
    	$date = (isset($_REQUEST['date_from_ranger']))?$_REQUEST['date_from_ranger']:date('Y-m-d');
		$branch_id = (isset($_REQUEST['branch_id']))?$_REQUEST['branch_id']:0;
		$_GET['branch_id'] = $branch_id;
		$transaction_status_id = (isset($_REQUEST['transaction_status_id']))?$_REQUEST['transaction_status_id']:0;
		
		
		
		$this->loadModel('Transactions');
        //Get the transaction stats of this merchant account
        $stats = $this->Transactions->find()
        ->select([
			'total_transactions'=>'COUNT(Transactions.id)',
			'Transactions.transaction_status_id'
		])
        ->where([
			'Transactions.branch_id'=>$branch_id
		])
        ->group(['Transactions.transaction_status_id']);
        $this->set('stats',$stats);

        // Total of transactions that need to be synched
        if($this->Auth->User('role')=='super_admin' && ((int) Configure::read('offline'))){

            $unSyncedCount = $this->Transactions->find()
            ->where([
                'Transactions.offline'=>true,
                'Transactions.transaction_status_id'=>$this->appSettings->final_transaction_status_id,
                'Transactions.parent_transaction_id'=>0,
            ])->count();

            $this->set('unSyncedTransactionsCount',$unSyncedCount);
        }
		
		$branches = $this->Transactions->Branches->find('list', ['limit' => 200]);
		
		
		// Sending
		// CreditTransactions
		$conditions = [
			'Transactions.balance_sheet_side' => 1,
			'Transactions.parent_transaction_id' => 0,

			'Transactions.to_branch_id <>' => 9,
			'Transactions.from_branch_id <>' => 9,
			'DATE(Transactions.date)'=>$date,
		];
		if(!empty($transaction_status_id)){
			$conditions['Transactions.transaction_status_id'] = $transaction_status_id;
		}
		if(!empty($branch_id)){
			$conditions['Transactions.to_branch_id'] = $branch_id;
		}
		$totalTransactionsSentToday = $this->Transactions->find()
        ->select([
			'total_transactions'=>'COUNT(Transactions.id)',
			'total_amount'=>'SUM(Transactions.value)',
			'Transactions.currency_id'
		])
        ->where($conditions)
        ->group(['Transactions.currency_id']);
		
		// totalTransactionsSentTodayCommission
		$conditions = [
			//'Transactions.balance_sheet_side' => 1,
			'Transactions.parent_transaction_id >' => 0,
			'Transactions.to_branch_id <>' => 9,
			'Transactions.from_branch_id <>' => 9,
			'DATE(Transactions.date)'=>$date,
			"((SELECT trans.balance_sheet_side from transactions as trans where trans.id=Transactions.parent_transaction_id) = 1)"
		];
		if(!empty($branch_id)){
			//$conditions['Transactions.from_branch_id'] = $branch_id;
			$conditions['Transactions.to_branch_id'] = $branch_id;
			/*$conditions=[
			'OR'=>[
					'Transactions.to_branch_id'=> $branch_id,
					'Transactions.from_branch_id' => $branch_id
				]
			];*/
		}
		$totalTransactionsSentTodayCommission = $this->Transactions->find()
        ->select([
			'total_transactions'=>'COUNT(Transactions.id)',
			'total_amount'=>'SUM(Transactions.value)',
			'Transactions.currency_id'
		])
        ->where($conditions)
        ->group(['Transactions.currency_id']);
		
		
		// Recieving
		// DebitTransactions
		// totalTransactionsPaidOutToday
		$conditions = [
			'Transactions.balance_sheet_side' => 0,
			'Transactions.parent_transaction_id' => 0,
			'Transactions.to_branch_id <>' => 9,
			'Transactions.from_branch_id <>' => 9,
			'DATE(Transactions.date)'=>$date,
		];
		if(!empty($transaction_status_id)){
			$conditions['Transactions.transaction_status_id'] = $transaction_status_id;
		}
		if(!empty($branch_id)){
			$conditions['Transactions.from_branch_id'] = $branch_id;
		}
		$totalTransactionsPaidOutToday = $this->Transactions->find()
        ->select([
			'total_transactions'=>'COUNT(Transactions.id)',
			'total_amount'=>'SUM(Transactions.value)',
			'Transactions.currency_id'
		])
        ->where($conditions)
        ->group(['Transactions.currency_id']);
		
		// totalTransactionsPaidOutTodayCommission
		$conditions = [
			//'Transactions.balance_sheet_side' => 1,
			'Transactions.parent_transaction_id >' => 0,
			'Transactions.to_branch_id <>' => 9,
			'Transactions.from_branch_id <>' => 9,
			'DATE(Transactions.date)'=>$date,
			"((SELECT trans.balance_sheet_side from transactions as trans where trans.id=Transactions.parent_transaction_id) = 0)"
		];
		
		// SELECT COUNT(`Transactions`.`id`) AS `total_transactions`, SUM(`Transactions`.`value`) AS `total_amount`, `Transactions`.`currency_id` AS `Transactions__currency_id`, `ParentTransactions`.`balance_sheet_side` AS `ParentTransactions__balance_sheet_side` FROM `transactions` `Transactions` WHERE (`Transactions`.`parent_transaction_id` > :c0 AND DATE(`Transactions`.`date`) = :c1 AND `ParentTransactions`.`balance_sheet_side` = :c2) GROUP BY `Transactions`.`currency_id` 
		if(!empty($transaction_status_id)){
			$conditions['Transactions.transaction_status_id'] = $transaction_status_id;
		}
		if(!empty($branch_id)){
			$conditions['Transactions.from_branch_id'] = $branch_id;
		}
		
		$totalTransactionsPaidOutTodayCommission = $this->Transactions->find()
        ->select([
			'total_transactions'=>'COUNT(Transactions.id)',
			'total_amount'=>'SUM(Transactions.value)',
			'Transactions.currency_id',
		])
		->contain(['ParentTransactions'])
        ->where($conditions)
        ->group(['Transactions.currency_id']);
		
		
		// totalTransactionsPaidOutTodayForPrevious
		$conditions = [
			'Transactions.balance_sheet_side' => 0,
			'Transactions.parent_transaction_id' => 0,
			'DATE(Transactions.modified)'=>$date,
			'DATE(Transactions.date) != DATE(Transactions.modified)',
		];
		if(!empty($transaction_status_id)){
			$conditions['Transactions.transaction_status_id'] = $transaction_status_id;
		}
		if(!empty($branch_id)){
			$conditions['Transactions.to_branch_id'] = $branch_id;
		}
		$totalTransactionsPaidOutTodayForPrevious = $this->Transactions->find()
        ->select([
			'total_transactions'=>'COUNT(Transactions.id)',
			'total_amount'=>'SUM(Transactions.value)',
			'Transactions.currency_id'
		])
        ->where($conditions)
        ->group(['Transactions.currency_id']);
		
		// totalTransactionsPaidOutTodayForPreviousCommission
		$conditions = [
			'Transactions.parent_transaction_id >' => 0,
			'DATE(Transactions.modified)'=>$date,
			'DATE(Transactions.date) != DATE(Transactions.modified)',
			"((SELECT trans.balance_sheet_side from transactions as trans where trans.id=Transactions.parent_transaction_id) = 0)"
		];
		if(!empty($transaction_status_id)){
			$conditions['Transactions.transaction_status_id'] = $transaction_status_id;
		}
		if(!empty($branch_id)){
			$conditions['Transactions.from_branch_id'] = $branch_id;
		}
		$totalTransactionsPaidOutTodayForPreviousCommission = $this->Transactions->find()
        ->select([
			'total_transactions'=>'COUNT(Transactions.id)',
			'total_amount'=>'SUM(Transactions.value)',
			'Transactions.currency_id'
		])
        ->where($conditions)
        ->group(['Transactions.currency_id']);
		
		
		$this->set('dateFrom', $date);
		$this->set('branches',$branches);
		$this->set('totalTransactionsSentToday', $totalTransactionsSentToday);
		$this->set('totalTransactionsSentTodayCommission', $totalTransactionsSentTodayCommission);
		$this->set('totalTransactionsPaidOutToday', $totalTransactionsPaidOutToday);
		$this->set('totalTransactionsPaidOutTodayCommission', $totalTransactionsPaidOutTodayCommission);
		$this->set('totalTransactionsPaidOutTodayForPrevious', $totalTransactionsPaidOutTodayForPrevious);
		$this->set('totalTransactionsPaidOutTodayForPreviousCommission', $totalTransactionsPaidOutTodayForPreviousCommission);
	}
}

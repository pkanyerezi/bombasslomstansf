<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Utility\Hash;
use Cake\Core\Configure;

/**
 * Transactions Controller
 *
 * @property \App\Model\Table\TransactionsTable $Transactions
 */
class TransactionsController extends AppController
{

    // Sends receipts/transactions created offline to the online system
    public function sync_offline_online(){

        if(!((int) Configure::read('offline'))){
            echo json_encode([
                'msg'=>'App Should Operate in offline mode. Check the config file.', 
                'status'=>false
            ]);
            exit();
        }

        $transactions = $this->Transactions->find()
        ->select([
            'Transactions.id',
            'Transactions.transaction_type_id',
            'Transactions.created_by_id',
            'Transactions.modified_by_id',
            'Transactions.offline',
            'Transactions.offline_form_data',
        ])
        ->contain([])
        ->where([
            'Transactions.offline'=>0,
            'Transactions.parent_transaction_id'=>0,
        ])
        ->order(['Transactions.created'=>'DESC'])
        ->limit(100);

        if($transactions->count()){
            $data = json_encode($transactions->toArray());
            $results = $this->postData($data);
            if($results){
                foreach ($results as $key => $value) {
                    // Update all the transactions with the ids returned
                    // set offline=false
                }
            }
        }

        exit();
    }

    private function postData(){
        return [];
    }

    public function receipt($id = null)
    {
        $conditions = [];
        $conditions['OR']['Transactions.to_branch_id'] = $this->Auth->User('branch_id');
        $conditions['OR']['Transactions.from_branch_id'] = $this->Auth->User('branch_id');

        $transaction = $this->Transactions->get($id, [
            'contain' => [
                'TransactionTypes',
                'TransactionStatuses',
                'Branches',
                'ToBranches',
                'FromBranches',
                'Customers',
                'CreatedBy',
				'CompletedBy'
            ],
            'conditions' => $conditions
        ]);

        // pr($transaction->toArray());
        // exit();

        // include the other transactions that were generated together with the receipt e.g the commission transaction

        $parentTransactionIDs = [$id];
        $childTransactionCollection = [];
        $conditions = [];
        do{

            $conditions['Transactions.parent_transaction_id IN'] = $parentTransactionIDs;
            $conditions['TransactionTypes.show_on_receipt'] = true;
            $childTransactions = $this->Transactions
            ->find()
            ->select([
                'Transactions.id',
                'Transactions.value',
                'Transactions.currency_id',
                'TransactionTypes.name',
                'TransactionTypes.name_on_receipt'
            ])
            ->where($conditions)
            ->contain(['TransactionTypes']);

            $childTransactionCollection[] = $childTransactions;
            $parentTransactionIDs = Hash::extract($childTransactions->toArray(), '{n}.id');
        }while ( count($parentTransactionIDs));

        $this->set(compact('transaction','childTransactionCollection'));
        $this->set('_serialize', ['transaction','childTransactionCollection']);
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index($transactionTypeId=null)
    {
        $conditions = [];
        
        if(!empty($_GET['account_id'])){
            // $conditions['TransactionTypes.from_account_id'] = $_GET['account_id'];
            $conditions['OR']['TransactionTypes.to_account_id'] = $_GET['account_id'];
            $conditions['OR']['TransactionTypes.from_account_id'] = $_GET['account_id'];
            $conditions['Transactions.branch_id'] = $this->Auth->User('branch_id');
            $this->loadModel('Accounts');
            if($this->Auth->User('role')=='super_admin'){
                $account = $this->Accounts
                ->find()
                ->where(['id'=>$_GET['account_id']])
                ->select(['currency_id','branch_id'])
                ->contain([])
                ->first();
                if(!empty($account->branch_id)){
                    $conditions['Transactions.branch_id'] = $account->branch_id;
                }
            }
        }else{
            if (empty($_GET['q'])) {
                $conditions['Transactions.branch_id'] = $this->Auth->User('branch_id');
            }
        }


        if (!empty($_GET['transaction_status_id'])) {
            $conditions['Transactions.transaction_status_id'] = $_GET['transaction_status_id'];
        }

        if (!empty($transactionTypeId)) {
            $conditions['TransactionTypes.id'] = $transactionTypeId;
        }

        if (!empty($_GET['q'])) {
            $conditions['OR']['Transactions.reference_code LIKE'] = '%' . $_GET['q'] . '%';
            $conditions['OR']['Transactions.custom_fields LIKE'] = '%' . $_GET['q'] . '%';
        }

        if(!empty($_GET['use_date_range'])){
            $conditions['DATE(Transactions.date) >='] = $this->dateFrom;
            $conditions['DATE(Transactions.date) <='] = $this->dateTo;
        }

        if(!in_array($this->Auth->User('role'), ['super_admin','admin','manager'])){
            if(!empty($_GET['created_by_id'])) $conditions['Transactions.created_by_id'] = $this->Auth->User('id');
            if(!empty($_GET['modified_by_id']))  $conditions['Transactions.modified_by_id'] = $this->Auth->User('id');
            if(!empty($_GET['completed_by_id']))  $conditions['Transactions.completed_by_id'] = $this->Auth->User('id');
        }else{
            if(!empty($_GET['created_by_id'])) $conditions['Transactions.created_by_id'] = $_GET['created_by_id'];
            if(!empty($_GET['modified_by_id']))  $conditions['Transactions.modified_by_id'] = $_GET['modified_by_id'];
            if(!empty($_GET['completed_by_id']))  $conditions['Transactions.completed_by_id'] = $_GET['completed_by_id'];
        }

        $this->paginate = [
            'contain' => ['TransactionTypes','ParentTransactions','TransactionStatuses','Customers'],
            'conditions'=>$conditions,
            'order'=>[
                'Transactions.created DESC','Transactions.id ASC',
            ]
        ];

        $transactions = $this->paginate($this->Transactions);

        $this->set(compact('transactions','transactionTypeId'));
        $this->set('_serialize', ['transactions']);
    }

    public function statement($transactionTypeId=null)
    {
        if(empty($_GET['date_to'])) $_GET['date_to'] = date('Y-m-d');
        if(empty($_GET['currency_id'])){
            $currency_ids = array_keys($this->appTransactionCurrencies);
            $_GET['currency_id'] = $currency_ids[0];
        }

        $fields = [
            'Transactions.value',
            'Transactions.id',
            'Transactions.date',
            'Transactions.currency_id',
            'Transactions.balance_sheet_side',
            'TransactionTypes.id',
            'TransactionTypes.name',
        ];

        // Set Conditions
        $conditions = ['Transactions.currency_id'=>$_GET['currency_id']];

        if(empty($_GET['transaction_status_id'])) $_GET['transaction_status_id']=$this->appSettings->final_transaction_status_id;
        $conditions['Transactions.transaction_status_id'] = $_GET['transaction_status_id'];
        
        if (!empty($transactionTypeId)) {
            $conditions['TransactionTypes.id'] = $transactionTypeId;
        }

        //if(!empty($_GET['use_date_range'])){
            $conditions['DATE(Transactions.date) >='] = $this->dateFrom;
            $conditions['DATE(Transactions.date) <='] = $this->dateTo;
        //}

        $conditions['Transactions.branch_id'] = $this->Auth->User('branch_id');
        if(!in_array($this->Auth->User('role'), ['super_admin','admin','manager'])){
            if(empty($_GET['modified_by_id']) && empty($_GET['modified_by_id']) && empty($_GET['modified_by_id'])){
                $conditions['OR']['Transactions.completed_by_id'] = $this->Auth->User('id');
                $conditions['OR']['Transactions.created_by_id'] = $this->Auth->User('id');
            }else{
                if(!empty($_GET['created_by_id'])) $conditions['Transactions.created_by_id'] = $this->Auth->User('id');
                if(!empty($_GET['modified_by_id']))  $conditions['Transactions.modified_by_id'] = $this->Auth->User('id');
                if(!empty($_GET['completed_by_id']))  $conditions['Transactions.completed_by_id'] = $this->Auth->User('id');
            }
        }else{
            if(!empty($_GET['created_by_id'])) $conditions['Transactions.created_by_id'] = $_GET['created_by_id'];
            if(!empty($_GET['modified_by_id']))  $conditions['Transactions.modified_by_id'] = $_GET['modified_by_id'];
            if(!empty($_GET['completed_by_id']))  $conditions['Transactions.completed_by_id'] = $_GET['completed_by_id'];
        }

        $this->paginate = [
            'contain' => ['TransactionTypes'],
            'fields'=> $fields,
            'conditions'=>$conditions,
            'limit'=>100
        ];
        $transactions = $this->paginate($this->Transactions);

        // We now need to get the previous total sums Per Currency
        if($this->request->params['paging']['Transactions']['page']==1){
            $conditions['DATE(Transactions.date) >='] = '1900-01-01';
            $conditions['DATE(Transactions.date) <'] = $this->dateFrom;

            // $conditions = [];

            $previousSummary = $this->Transactions->find()
            ->select([
                'total_value' => 'SUM(Transactions.value)',
                'Transactions.balance_sheet_side',
                'Transactions.currency_id'
            ])
            ->contain(['TransactionTypes'])
            ->where($conditions)
            ->group(['Transactions.currency_id','Transactions.balance_sheet_side'])
            ->order('Transactions.currency_id DESC');

            // pr(json_decode(json_encode($previousSummary->toArray()),true));
            // exit();
        }

        $this->set(compact('transactions','transactionTypeId','previousSummary'));
        $this->set('_serialize', ['transactions']);
    }
    
    public function balance($transactionTypeId=null)
    {
        if(empty($_GET['date_to'])) $_GET['date_to'] = date('Y-m-d');
        if(empty($_GET['currency_id'])){
            $currency_ids = array_keys($this->appTransactionCurrencies);
            $_GET['currency_id'] = $currency_ids[0];
        }
        
        $fields = [
            'Transactions__value'=>'SUM(Transactions.value)',
            'Transactions.id',
            'Transactions.currency_id',
            'Transactions.balance_sheet_side',
            'TransactionTypes.id',
            'TransactionTypes.name'
        ];

        // Set Conditions
        $conditions = ['Transactions.currency_id'=>$_GET['currency_id']];

        if(empty($_GET['transaction_status_id'])) $_GET['transaction_status_id']=$this->appSettings->final_transaction_status_id;
        $conditions['Transactions.transaction_status_id'] = $_GET['transaction_status_id'];

        if (!empty($_GET['customer_id'])) {
            $conditions['Transactions.customer_id'] = $_GET['customer_id'];
        }

        if (!empty($transactionTypeId)) {
            $conditions['TransactionTypes.id'] = $transactionTypeId;
        }

        if(!empty($_GET['use_date_range'])){
            $conditions['DATE(Transactions.date) >='] = $this->dateFrom;
            $conditions['DATE(Transactions.date) <='] = $this->dateTo;
        }

        $conditions['Transactions.branch_id'] = $this->Auth->User('branch_id');
        if(!in_array($this->Auth->User('role'), ['super_admin','admin','manager'])){
            if(empty($_GET['modified_by_id']) && empty($_GET['modified_by_id']) && empty($_GET['modified_by_id'])){
                $conditions['OR']['Transactions.completed_by_id'] = $this->Auth->User('id');
                $conditions['OR']['Transactions.created_by_id'] = $this->Auth->User('id');
            }else{
                if(!empty($_GET['created_by_id'])) $conditions['Transactions.created_by_id'] = $this->Auth->User('id');
                if(!empty($_GET['modified_by_id']))  $conditions['Transactions.modified_by_id'] = $this->Auth->User('id');
                if(!empty($_GET['completed_by_id']))  $conditions['Transactions.completed_by_id'] = $this->Auth->User('id');
            }
        }else{

            if(!empty($_GET['created_by_id'])) $conditions['Transactions.created_by_id'] = $_GET['created_by_id'];
            if(!empty($_GET['modified_by_id']))  $conditions['Transactions.modified_by_id'] = $_GET['modified_by_id'];
            if(!empty($_GET['completed_by_id']))  $conditions['Transactions.completed_by_id'] = $_GET['completed_by_id'];
        }

        $this->paginate = [
            'contain' => ['TransactionTypes'],
            'conditions'=>$conditions,
            'fields'=>$fields,
            'group'=>['Transactions.transaction_type_id']
        ];
        
        $transactions = $this->paginate($this->Transactions);

        $this->set(compact('transactions','transactionTypeId'));
        $this->set('_serialize', ['transactions']);
    }

    /**
     * View method
     *
     * @param string|null $id Transaction id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $conditions = [];
        $conditions['OR']['Transactions.branch_id'] = $this->Auth->User('branch_id');
        $conditions['OR']['Transactions.to_branch_id'] = $this->Auth->User('branch_id');
        $conditions['OR']['Transactions.from_branch_id'] = $this->Auth->User('branch_id');

        $transaction = $this->Transactions->get($id, [
            'contain' => [
                'TransactionTypes','ParentTransactions','TransactionStatuses','Branches','Customers','CreatedBy','ModifiedBy',
                'TargetUser','CompletedBy',
            ],
            'conditions' => $conditions
        ]);

        $this->set('transaction', $transaction);
        $this->set('_serialize', ['transaction']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add_old($transactionTypeId)
    {
        $transaction = $this->Transactions->newEntity();
        $transactionType = $this->Transactions->TransactionTypes->find()->where(['TransactionTypes.id'=>$transactionTypeId])->contain(['CommissionStructures'])->first();

        if($transactionType->on_create_lock_to_branch_from && $this->Auth->User('branch_id')!=$transactionType->from_branch_id){
            $this->Flash->error(__('Access denied. Only branch ' .$transactionType->from_branch_id . ' can create this transaction.'));
            return $this->redirect(['action' => 'index','controller'=>'Transactions']);
        }

        if($transactionType->super_admin_menu_only && !in_array($this->Auth->User('role'), ['super_admin'])){
            $this->Flash->error(__('Access Denied. Only Super Admin can perform this transaction.'));
            return $this->redirect(['action' => 'index','controller'=>'Transactions']);
        }

        if(!$transactionType->super_admin_menu_only && in_array($this->Auth->User('role'), ['super_admin'])){
            $this->Flash->error(__('Super Admin should not perform this action. Please delegate a user'));
            return $this->redirect(['action' => 'index','controller'=>'Transactions']);
        }

        $appSettings = $this->appSettings();
        if ($transactionType->account_type_id == $appSettings->customer_account_type_id) {
            if (empty($_GET['entity_id'])) {
                $this->Flash->error(__('Please take action via the customer record.'));
                return $this->redirect(['action' => 'index','controller'=>'Customers']);
            }else{
                $customer = $this->Transactions->Customers->get($_GET['entity_id']);
                $this->set('customer',$customer);
                $_GET['customer_id'] = $_GET['entity_id'];
            }
        }

        $transactionStatuses = $this->Transactions->TransactionStatuses->find('list');

        
        if ($this->request->is('post')) {
            
            $this->request->data['offline'] = (int) Configure::read('offline');
            $this->request->data['transaction_type_id'] = $transactionTypeId;


            if (empty($this->request->data['created_by'])) {
                $this->request->data['created_by'] = $this->Auth->User('id');
            }

            if (empty($this->request->data['system_comment'])) {
                $this->request->data['system_comment'] = '';
            }

            if (empty($this->request->data['quantity'])) {
                $this->request->data['quantity'] = 1;
            }

            if (empty($this->request->data['amount'])) {
                $this->request->data['amount'] = $this->request->data['value'];
            }


            // Handle custome data fields
            $commission = null;
            if (isset($this->request->data['custom_fields'])) {
                if (isset($this->request->data['custom_fields']['commission']) && !empty($this->request->data['custom_fields']['commission'])) {
                    $commission = $this->request->data['custom_fields']['commission'];

                    if (empty($transactionType->commission_structure_id)) {
                        $this->Flash->error(__('Please create a transaction type to handle commissions.'));
                        return $this->redirect(['action' => 'add',$transactionTypeId]);
                    }

                }

                if (isset($this->request->data['custom_fields']['reference_code']) && !empty($this->request->data['custom_fields']['reference_code'])) {
                    $this->request->data['reference_code'] = $this->request->data['custom_fields']['reference_code'];
                }

                $custom_fields = $this->request->data['custom_fields'];
                $this->request->data['custom_fields'] = json_encode($this->request->data['custom_fields']);
            }else{
                $this->request->data['custom_fields'] = json_encode([]);
            }

            //Make sure that the commission is input before continuing if set as required
            if($this->appSettings->require_commission_input){
                if (!empty($transactionType->commission_structure_id) && empty($custom_fields['commission'])) {
                    $this->Flash->error(__('Transaction requires commission'));
                    return $this->redirect(['action' => 'add',$transactionTypeId]);
                }
            }

            $this->request->data['value'] = $this->request->data['quantity']*$this->request->data['amount'];
            if (empty($commission) && !empty($transactionType->commission_structure)) {
                if(!empty($this->request->data['commission_structure_id'])){
                    
                    // Make sure that when a commissionStructure is selected and its transactionType
                    // is of a different curency compared to the default transactionType for the default
                    // commissionStructure, A commission amount is required/provided by force
                    if(!$this->appSettings->require_commission_input){
                        $commission_structure = $this->Transactions->TransactionTypes->CommissionStructures->get(
                            $this->request->data['commission_structure_id'],
                            [
                                'contain'=>['TransactionTypes'],
                                'fields'=>[
                                    'CommissionStructures.id',
                                    'CommissionStructures.name',
                                    'CommissionStructures.transaction_type_id',
                                    'CommissionStructures.pricing_structure',
                                    'TransactionTypes.currency_id',
                                ]
                            ]
                        );
                        if($commission_structure->transaction_type->currency_id!=$transactionType->currency_id){
                            if (!empty($transactionType->commission_structure_id) && empty($custom_fields['commission'])) {
                                $this->Flash->error(__('Transaction requires commission'));
                                return $this->redirect(['action' => 'add',$transactionTypeId]);
                            }
                        }
                    }else{
                        $commission_structure = $this->Transactions->TransactionTypes->CommissionStructures->get(
                            $this->request->data['commission_structure_id']
                        );
                    }

                    $commission = $this->Transactions->getCommission(
                        $this->request->data['value'],$commission_structure
                    );
                }else{
                    $commission = $this->Transactions->getCommission(
                        $this->request->data['value'],$transactionType->commission_structure
                    );
                }
            }
            $this->request->data['commission'] = $commission;
            

            if (empty($this->request->data('reference_code'))) {
                $this->request->data['reference_code'] = $transactionType->branch_id . '-' . time();
            }

            if (!empty($_GET['customer_id'])) {
                $this->request->data['customer_id'] = $_GET['customer_id'];
            }

            $this->request->data['created_by_id'] = $this->Auth->User('id');

            // Transactions in their final states at the time of creation need to indicate who completed the transaction.
            if($transactionType->transaction_status_id == $this->appSettings->final_transaction_status_id){
                $this->request->data['completed_by_id'] = $this->Auth->User('id');
                if(in_array($this->Auth->User('role'), ['super_admin','admin','manager'])){
                    if(!empty($this->request->data('target_user_id'))){
                        $this->request->data['completed_by_id'] = $this->request->data['target_user_id'];
                    }
                }
            }
            

            // Save transaction
            $transaction = $this->Transactions->patchEntity($transaction, $this->request->data);
            
            if(!empty($this->request->data['commission_structure_id'])){
                $transaction->commission_structure_id = $this->request->data['commission_structure_id'];
            }

            $transaction->currency_id = $transactionType->currency_id;
            $transaction->branch_id = $transactionType->branch_id;
            $transaction->from_branch_id = $transactionType->from_branch_id;
            $transaction->to_branch_id = $transactionType->to_branch_id;
            $transaction->transaction_status_id = $transactionType->transaction_status_id;//Use the default set
            $transaction->balance_sheet_side = $transactionType->balance_sheet_side;
            $transaction->from_account_id = $transactionType->from_account_id;
            $transaction->to_account_id = $transactionType->to_account_id;
            $transaction->completor_branch_id = $transactionType->to_branch_id;


            $transaction = $this->Transactions->save($transaction);
            if ($transaction) {
                // $this->Flash->success(__('Transaction saved. Continue adding another transaction.'));
                return $this->redirect(['action' => 'receipt',$transaction->id]);
            } else {
                $this->Flash->error(__('The transaction could not be saved. Please, try again.'));
            }
        }

        $custom_fields = json_decode($transactionType->custom_fields,true);
        if (isset($custom_fields['commission']) && empty($transactionType->commission_structure_id)) {
            $this->Flash->error(__('Please create a transaction type to handle commissions.'));
            return $this->redirect(['action' => 'add',$transactionTypeId,'controller'=>'TransactionTypes']);
        }
        $transaction->transaction_status_id = $transactionType->transaction_status_id;


        if($transactionType->require_target_user){
            $target_users = $this->Transactions->TargetUser->find('list', [
                'limit' => 200,
                'conditions'=>[
                    'branch_id'=>$transactionType->to_branch_id
                ]
            ]);
        }

        // Lets enable the person be able to add a commission of any currency set
        // it will find them using tags
        $relatedCommissionStructures = null;
        if(!empty($transactionType->commission_structure->tag)){
            $relatedCommissionStructures = $this->Transactions->TransactionTypes->CommissionStructures
            ->find()
            ->select([
                'CommissionStructures.id',
                'CommissionStructures.name',
                'TransactionTypes.id',
                'TransactionTypes.name',
                'TransactionTypes.currency_id',
                ])
            ->contain(['TransactionTypes'])
            ->where(['CommissionStructures.tag'=>$transactionType->commission_structure->tag]);
        }
        $this->set(compact('relatedCommissionStructures'));

        $this->set(compact('transaction', 'transactionType','transactionTypeId','transactionStatuses','target_users'));
        $this->set('_serialize', ['transaction']);
    }


    public function add($transactionTypeId)
    {
        $transaction = $this->Transactions->newEntity();
        $transactionType = $this->Transactions->TransactionTypes->find()->where(['TransactionTypes.id'=>$transactionTypeId])->contain(['CommissionStructures'])->first();

        if($transactionType->on_create_lock_to_branch_from && $this->Auth->User('branch_id')!=$transactionType->branch_id){
            $this->Flash->error(__('Access denied. Only branch ' .$transactionType->branch_id . ' can create this transaction.'));
            return $this->redirect(['action' => 'index','controller'=>'Transactions']);
        }

        if($transactionType->super_admin_menu_only && !in_array($this->Auth->User('role'), ['super_admin'])){
            $this->Flash->error(__('Access Denied. Only Super Admin can perform this transaction.'));
            return $this->redirect(['action' => 'index','controller'=>'Transactions']);
        }

        if(!$transactionType->super_admin_menu_only && in_array($this->Auth->User('role'), ['super_admin'])){
            $this->Flash->error(__('Super Admin should not perform this action. Please delegate a user'));
            return $this->redirect(['action' => 'index','controller'=>'Transactions']);
        }
		
		if (empty($this->request->data('reference_code'))) {
			//$this->request->data['reference_code'] = $transactionType->from_branch_id . '-' . time();
		}
		
		// If its a receiving/debit transaction/transfer then we should populate the transactionReferenceField
		if($transactionType->to_branch_id!=$transactionType->from_branch_id && $transactionType->balance_sheet_side !=0){
			//transaction_reference
			//$currentTransactionReference = $this->Transactions->TransactionTypes->Branches->get($transactionType->from_branch_id);
			//$this->request->data['reference_code'] = $transactionType->from_branch_id . '-' . ($currentTransactionReference->transaction_reference + 1);
			
			$currentTransactionReference = $this->Transactions->TransactionTypes->Branches->get($transactionType->to_branch_id);
			$this->request->data['reference_code'] = $transactionType->from_branch_id . '-' . $transactionType->to_branch_id . '-' . ($currentTransactionReference->transaction_reference + 1);
		}elseif($transactionType->to_branch_id!=$transactionType->from_branch_id && $transactionType->balance_sheet_side !=1){
			//transaction_reference
			//$currentTransactionReference = $this->Transactions->TransactionTypes->Branches->get($transactionType->from_branch_id);
			//$this->request->data['reference_code'] = $transactionType->from_branch_id . '-' . ($currentTransactionReference->transaction_reference + 1);
			
			$currentTransactionReference = $this->Transactions->TransactionTypes->Branches->get($transactionType->from_branch_id);
			$this->request->data['reference_code'] = $transactionType->from_branch_id . '-' . $transactionType->to_branch_id . '-' . ($currentTransactionReference->transaction_reference_for_receiving + 1);
		} 
		
        $appSettings = $this->appSettings();
        if ($transactionType->account_type_id == $appSettings->customer_account_type_id) {
            if (empty($_GET['entity_id'])) {
                $this->Flash->error(__('Please take action via the customer record.'));
                return $this->redirect(['action' => 'index','controller'=>'Customers']);
            }else{
                $customer = $this->Transactions->Customers->get($_GET['entity_id']);
                $this->set('customer',$customer);
                $_GET['customer_id'] = $_GET['entity_id'];
            }
        }

        $transactionStatuses = $this->Transactions->TransactionStatuses->find('list');
        
        if ($this->request->is('post')) {

            $this->loadComponent('TransactionLib');
            $this->TransactionLib->transaction = $transaction;
            $this->TransactionLib->transactionType = $transactionType;

            if($this->TransactionLib->add($this->request->data,$transactionTypeId)){
                return $this->redirect(
                    ['action' => 'receipt',$this->TransactionLib->transaction->id]
                );
            }else{
                $this->Flash->error(__($this->TransactionLib->errorMsg));
                return $this->redirect(['action' => 'add',$transactionTypeId]);
            }

        }

        $custom_fields = json_decode($transactionType->custom_fields,true);
        if (isset($custom_fields['commission']) && empty($transactionType->commission_structure_id)) {
            $this->Flash->error(__('Please create a transaction type to handle commissions.'));
            return $this->redirect(['action' => 'add',$transactionTypeId,'controller'=>'TransactionTypes']);
        }
        $transaction->transaction_status_id = $transactionType->transaction_status_id;


        if($transactionType->require_target_user){
            $target_users = $this->Transactions->TargetUser->find('list', [
                'limit' => 200,
                'conditions'=>[
                    'branch_id'=>$transactionType->to_branch_id
                ]
            ]);
        }

        // Lets enable the person be able to add a commission of any currency set
        // it will find them using tags
        $relatedCommissionStructures = null;
        if(!empty($transactionType->commission_structure->tag)){
            $relatedCommissionStructures = $this->Transactions->TransactionTypes->CommissionStructures
            ->find()
            ->select([
                'CommissionStructures.id',
                'CommissionStructures.name',
                'TransactionTypes.id',
                'TransactionTypes.name',
                'TransactionTypes.currency_id',
                ])
            ->contain(['TransactionTypes'])
            ->where(['CommissionStructures.tag'=>$transactionType->commission_structure->tag]);
        }
        $this->set(compact('relatedCommissionStructures'));

        $this->set(compact('transaction', 'transactionType','transactionTypeId','transactionStatuses','target_users'));
        $this->set('_serialize', ['transaction']);
    }


    

    /**
     * Edit method
     *
     * @param string|null $id Transaction id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {

        $transaction = $this->Transactions->get($id, [
            'contain' => ['TransactionTypes']
        ]);
        $transactionType = $this->Transactions->TransactionTypes->find()->where(['TransactionTypes.id'=>$transaction->transaction_type_id])->contain(['CommissionStructures'])->first();
        $transactionStatuses = $this->Transactions->TransactionStatuses->find('list');

        if ($this->request->is(['patch', 'post', 'put'])) {

            if (empty($this->request->data['quantity'])) {
                $this->request->data['quantity'] = 1;
            }

            if (isset($this->request->data['custom_fields'])) {
                $custom_fields = json_encode($this->request->data['custom_fields']);
                $old_custom_fields = json_decode($transaction->custom_fields,true);

                $newCustomFields = array_merge($old_custom_fields,$this->request->data['custom_fields']);
                $this->request->data['custom_fields'] = json_encode($newCustomFields);
            }else{
                $this->request->data['custom_fields'] = json_encode([]);
            }

            if (empty($this->request->data('reference_code'))) {
                $this->request->data['reference_code'] = $transaction->branch_id . '-' . time();
            }

            $this->request->data['modified_by_id'] = $this->Auth->User('id');

            $transaction = $this->Transactions->patchEntity($transaction, $this->request->data);
            $transaction->currency_id = $transactionType->currency_id;
            if(!empty($this->request->data['commission_structure_id'])){
                $transaction->commission_structure_id = $this->request->data['commission_structure_id'];
            }
            if ($this->Transactions->save($transaction)) {
                $this->Flash->success(__('The transaction has been saved.'));
                return $this->redirect(['action' => 'receipt',$id]);
            } else {
                $this->Flash->error(__('The transaction could not be saved. Please, try again.'));
            }
        }

        // it will find them using tags
        $relatedCommissionStructures = null;
        if(!empty($transactionType->commission_structure->tag)){
            $relatedCommissionStructures = $this->Transactions->TransactionTypes->CommissionStructures
            ->find()
            ->select([
                'CommissionStructures.id',
                'CommissionStructures.name',
                'TransactionTypes.id',
                'TransactionTypes.name',
                'TransactionTypes.currency_id',
                ])
            ->contain(['TransactionTypes'])
            ->where(['CommissionStructures.tag'=>$transactionType->commission_structure->tag]);
        }
        $this->set(compact('relatedCommissionStructures'));
        $this->set(compact('transaction','transactionStatuses'));
        $this->set('_serialize', ['transaction','transactionStatuses']);
    }


    /*
    // Older version with ability to edit the commission
    public function edit($id = null)
    {

        $this->Flash->warning(__('Transactions Should not be edited. Consider deleting(Via Admin) and re-creating it again with the same details.'));
        return $this->redirect(['action' => 'view',$id]);

        $transaction = $this->Transactions->get($id, [
            'contain' => ['TransactionTypes']
        ]);
        $transactionType = $this->Transactions->TransactionTypes->find()->where(['TransactionTypes.id'=>$transaction->transaction_type_id])->contain(['CommissionStructures'])->first();
        $transactionStatuses = $this->Transactions->TransactionStatuses->find('list');

        if ($this->request->is(['patch', 'post', 'put'])) {

            if (empty($this->request->data['quantity'])) {
                $this->request->data['quantity'] = 1;
            }

            if (empty($this->request->data['amount'])) {
                $this->request->data['amount'] = $this->request->data['value'];
            }

            if (isset($this->request->data['custom_fields'])) {
                $custom_fields = json_encode($this->request->data['custom_fields']);
                $old_custom_fields = json_decode($transaction->custom_fields,true);

                // Update the commission field incase it has changed
                $commission = 0;
                $oldCommission = 0;
                if (isset($this->request->data['custom_fields']['commission']) && !empty($this->request->data['custom_fields']['commission'])) {
                    $commission = $this->request->data['custom_fields']['commission'];

                    if (empty($transaction->transaction_type->commission_structure_id)) {
                        $this->Flash->error(__('Please create a transaction type to handle commissions.'));
                        return $this->redirect(['action' => 'edit',$id]);
                    }

                    if (isset($this->request->data['custom_fields']['reference_code']) && !empty($this->request->data['custom_fields']['reference_code'])) {
                        $this->request->data['reference_code'] = $this->request->data['custom_fields']['reference_code'];
                    }

                    // pr($old_custom_fields);
                    if (isset($old_custom_fields['commission']) && !empty($old_custom_fields['commission'])) {
                        $oldCommission = ($old_custom_fields['commission']);
                    }
                }
                
                $this->request->data['oldCommission'] = $oldCommission;

                if (empty($commission) && !empty($transactionType->commission_structure)) {

                    if(!empty($this->request->data['commission_structure_id'])){
                        $commission_structure = $this->Transactions->TransactionTypes->CommissionStructures->get(
                            $this->request->data['commission_structure_id']
                        );
                        $commission = $this->Transactions->getCommission(
                            $transaction->value,$commission_structure
                        );
                    }else{
                        $commission = $this->Transactions->getCommission(
                            $transaction->value,$transactionType->commission_structure
                        );
                    }

                }
                $this->request->data['commission'] = $commission;

                $this->request->data['custom_fields'] = $custom_fields;
            }else{
                $this->request->data['custom_fields'] = json_encode([]);
            }

            if (empty($this->request->data('reference_code'))) {
                $this->request->data['reference_code'] = $transaction->to_branch_id . '-' . time();
            }

            $this->request->data['modified_by_id'] = $this->Auth->User('id');

            $transaction = $this->Transactions->patchEntity($transaction, $this->request->data);
            $transaction->currency_id = $transactionType->currency_id;
            if(!empty($this->request->data['commission_structure_id'])){
                $transaction->commission_structure_id = $this->request->data['commission_structure_id'];
            }
            if ($this->Transactions->save($transaction)) {
                $this->Flash->success(__('The transaction has been saved.'));
                return $this->redirect(['action' => 'receipt',$id]);
            } else {
                $this->Flash->error(__('The transaction could not be saved. Please, try again.'));
            }
        }

        // it will find them using tags
        $relatedCommissionStructures = null;
        if(!empty($transactionType->commission_structure->tag)){
            $relatedCommissionStructures = $this->Transactions->TransactionTypes->CommissionStructures
            ->find()
            ->select([
                'CommissionStructures.id',
                'CommissionStructures.name',
                'TransactionTypes.id',
                'TransactionTypes.name',
                'TransactionTypes.currency_id',
                ])
            ->contain(['TransactionTypes'])
            ->where(['CommissionStructures.tag'=>$transactionType->commission_structure->tag]);
        }
        $this->set(compact('relatedCommissionStructures'));
        $this->set(compact('transaction','transactionStatuses'));
        $this->set('_serialize', ['transaction','transactionStatuses']);
    }
    */

    // Used mainly by cashier to complete the transaction and set it to it's final transaction status
    public function completeTransaction($id = null)
    {
        // Only a member from the target branch should be able to complete this transaction.
        $transaction = $this->Transactions->get($id, [
            'contain' => ['TransactionStatuses','Branches']
        ]);

        if($transaction->completor_branch_id!=$this->Auth->User('branch_id')){
            $this->Flash->error(__('Transaction can only be completed by someone in branch ' . $transaction->completor_branch_id));
            return $this->redirect(['action' => 'index']);
        }
/*
        if (!$transaction->parent_transaction_id){
            $this->Flash->error(__('Transfer is missing it\'s Parent Transaction'));
            return $this->redirect(['action' => 'index']);
        }*/

        if($transaction->transaction_status->id==$this->appSettings->final_transaction_status_id){
            $this->Flash->error(__('Transfer/Transaction is already complete'));
            return $this->redirect(['action' => 'index']);
        }

        if($transaction->branch_id!=$transaction->to_branch_id){
            $this->Flash->error(__('BranchId and BranchToId does not match.'));
            return $this->redirect(['action' => 'index']);
        }

        if(!(empty($transaction->target_user_id) || $transaction->target_user_id==$this->Auth->User('id'))){
            $this->Flash->error(__('This transaction can only be completed by User:' . $transaction->target_user_id));
            return $this->redirect(['action' => 'index']);
        }

        if($this->Auth->User('role')=='super_admin'){
            $this->Flash->error(__('Super Admin should not complete these transactions.'));
            return $this->redirect(['action' => 'index']);
        }


        if ($this->request->is(['patch', 'post', 'put'])) {

            $this->request->data['completed_by_id'] = $this->Auth->User('id');
            $this->request->data['transaction_status_id'] = $this->appSettings->final_transaction_status_id;
            
            $transaction = $this->Transactions->patchEntity($transaction, $this->request->data);
            $transaction = $this->Transactions->save($transaction);
            if ($this->Transactions->save($transaction)) {

                // $this->Transactions->completeRelatedTransactions($transaction);

                $this->Flash->success(__('Completed.'));
                if(!empty($transaction->parent_transaction_id)){
                    return $this->redirect(['action' => 'receipt',$transaction->parent_transaction_id]);
                }
                return $this->redirect(['action' => 'receipt',$id]);
            } else {
                $this->Flash->error(__('The transaction could not be saved. Please, try again.'));
            }
        }
        return $this->redirect(['action' => 'receipt',$id]);
    }
	
	public function cancelTransaction($id = null)
    {
        // Only a member from the target branch should be able to complete this transaction.
        $transaction = $this->Transactions->get($id, [
            'contain' => ['TransactionStatuses','Branches']
        ]);

        /*if($transaction->completor_branch_id!=$this->Auth->User('branch_id')){
            $this->Flash->error(__('Transaction can only be cancelled by someone in branch ' . $transaction->completor_branch_id));
            return $this->redirect(['action' => 'index']);
        }*/
/*
        if (!$transaction->parent_transaction_id){
            $this->Flash->error(__('Transfer is missing it\'s Parent Transaction'));
            return $this->redirect(['action' => 'index']);
        }*/

        if($transaction->transaction_status->id==$this->appSettings->cancel_transaction_status_id){
            $this->Flash->error(__('Transfer/Transaction is already cancelled'));
            return $this->redirect(['action' => 'index']);
        }

        /*if($transaction->branch_id!=$transaction->to_branch_id){
            $this->Flash->error(__('BranchId and BranchToId does not match.'));
            return $this->redirect(['action' => 'index']);
        }*/

        if(!(empty($transaction->target_user_id) || $transaction->target_user_id==$this->Auth->User('id'))){
            $this->Flash->error(__('This transaction can only be cancelled by User:' . $transaction->target_user_id));
            return $this->redirect(['action' => 'index']);
        }

        if($this->Auth->User('role')=='super_admin'){
            $this->Flash->error(__('Super Admin should not cancel these transactions.'));
            return $this->redirect(['action' => 'index']);
        }


        if ($this->request->is(['patch', 'post', 'put'])) {

            $this->request->data['completed_by_id'] = $this->Auth->User('id');
            $this->request->data['transaction_status_id'] = $this->appSettings->cancel_transaction_status_id;
            
            $transaction = $this->Transactions->patchEntity($transaction, $this->request->data);
            $transaction = $this->Transactions->save($transaction);
            if ($this->Transactions->save($transaction)) {

               // $this->Transactions->cancelRelatedTransactions($transaction);

                $this->Flash->success(__('Cancelled.'));
                if(!empty($transaction->parent_transaction_id)){
                    return $this->redirect(['action' => 'receipt',$transaction->parent_transaction_id]);
                }
                return $this->redirect(['action' => 'receipt',$id]);
            } else {
                $this->Flash->error(__('The transaction could not be updated. Please, try again.'));
            }
        }
        return $this->redirect(['action' => 'receipt',$id]);
    }

    /**
     * Delete method
     *
     * @param string|null $id Transaction id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $transaction = $this->Transactions->get($id,[
            'contain'=>['TransactionStatuses']
        ]);

        if ($transaction->parent_transaction_id) {
            $this->Flash->error(__('Transaction depends on transaction below, It\'s the one you should delete. All child transactions will be deleted too.'));
            return $this->redirect(['action' => 'view',$transaction->parent_transaction_id]);
        }

        $canDelete = false;
        if($transaction->transaction_status->id!=$this->appSettings->final_transaction_status_id){
            if(!$transaction->parent_transaction_id && $transaction->from_branch_id==$this->Auth->User('branch_id')){
                $canDelete = true;
            }else{
                $this->Flash->error(__('Since the transaction is not complete yet. Only someone in Branch ' . $transaction->from_branch_id . ' can delete it.'));
            }
        }else{
            if($transaction->completor_branch_id==$this->Auth->User('branch_id')){
                $canDelete = true;
            }else{
                $this->Flash->error(__('Since the transaction complete. Only someone in Branch ' . $transaction->completor_branch_id . ' can delete it.'));
            }
        }

        if($canDelete){
            if ($this->Transactions->delete($transaction)) {
                $this->Flash->success(__('The transaction has been deleted.'));
            } else {
                $this->Flash->error(__('The transaction could not be deleted. Please, try again.'));
            }
        }

        return $this->redirect(['action' => 'index']);
    }
}

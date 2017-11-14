<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Transactions Controller
 *
 * @property \App\Model\Table\TransactionsTable $Transactions
 */
class TransactionsController extends AppController
{
    public function receipt($id = null)
    {
        $conditions = [];
        $conditions['OR']['Transactions.to_branch_id'] = $this->Auth->User('branch_id');
        $conditions['OR']['Transactions.from_branch_id'] = $this->Auth->User('branch_id');

        $transaction = $this->Transactions->get($id, [
            'contain' => ['TransactionTypes','TransactionStatuses','Branches','ToBranches','FromBranches','Customers','CreatedBy'],
            'conditions' => $conditions
        ]);

        // include the other transactions that were generated together with the receipt e.g the commission transaction

        $conditions['Transactions.parent_transaction_id'] = $id;
        $childTransactions = $this->Transactions
        ->find()
        ->select([
            'Transactions.value',
            'TransactionTypes.name'
        ])
        ->where($conditions)
        ->contain(['TransactionTypes']);

        $this->set(compact('transaction','childTransactions'));
        $this->set('_serialize', ['transaction','childTransactions']);
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
            $conditions['Transactions.branch_id'] = $this->Auth->User('branch_id');
        }


        if (!empty($_GET['transaction_status_id'])) {
            $conditions['Transactions.transaction_status_id'] = $_GET['transaction_status_id'];
        }

        if (!empty($transactionTypeId)) {
            $conditions['TransactionTypes.id'] = $transactionTypeId;
        }

        if (!empty($_GET['q'])) {
            $conditions['Transactions.reference_code LIKE'] = '%' . $_GET['q'] . '%';
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
    public function add($transactionTypeId)
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
            
            $this->request->data['created_by'] = $this->Auth->User('id');
            $this->request->data['system_comment'] = '';

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

                $custom_fields = json_encode($this->request->data['custom_fields']);
                $this->request->data['custom_fields'] = $custom_fields;
            }else{
                $this->request->data['custom_fields'] = json_encode([]);
            }

            $this->request->data['value'] = $this->request->data['quantity']*$this->request->data['amount'];
            if (empty($commission) && !empty($transactionType->commission_structure)) {
               $commission = $this->Transactions->getCommission($this->request->data['value'],$transactionType->commission_structure);
            }
            $this->request->data['commission'] = $commission;
            

            if (empty($this->request->data('reference_code'))) {
                $this->request->data['reference_code'] = $transactionType->to_branch_id . '-' . time();
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
            
            $transaction->currency_id = $transactionType->currency_id;
            $transaction->branch_id = $transactionType->branch_id;
            $transaction->from_branch_id = $transactionType->from_branch_id;
            $transaction->to_branch_id = $transactionType->to_branch_id;
            $transaction->transaction_status_id = $transactionType->transaction_status_id;//Use the default set
            $transaction->balance_sheet_side = $transactionType->balance_sheet_side;
            $transaction->from_account_id = $transactionType->from_account_id;
            $transaction->to_account_id = $transactionType->to_account_id;

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
            $this->Flash->success(__('Please create a transaction type to handle commissions.'));
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
                   $commission = $this->Transactions->getCommission($transaction->value,$transactionType->commission_structure);
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
            if ($this->Transactions->save($transaction)) {
                $this->Flash->success(__('The transaction has been saved.'));
                return $this->redirect(['action' => 'receipt',$id]);
            } else {
                $this->Flash->error(__('The transaction could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('transaction','transactionStatuses'));
        $this->set('_serialize', ['transaction','transactionStatuses']);
    }

    // Used mainly by cashier to complete the transaction and set it to it's final transaction status
    public function completeTransaction($id = null)
    {
        // Only a member from the target branch should be able to complete this transaction.
        $transaction = $this->Transactions->get($id, [
            'contain' => [],
            'conditions' => [
                'branch_id'=>$this->Auth->User('branch_id')
            ]
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {

            // If this transaction was intended to be completed by the target user, then only that user should complete it
            if(!(empty($transaction->target_user_id) || $transaction->target_user_id==$this->Auth->User('id'))){
                $this->Flash->error(__('This transaction can only be completed by User:' . $transaction->target_user_id));
                return $this->redirect(['action' => 'index']);
            }

            $this->request->data['completed_by_id'] = $this->Auth->User('id');
            $transaction = $this->Transactions->patchEntity($transaction, $this->request->data);
            if ($this->Transactions->save($transaction)) {
                $this->Flash->success(__('The transaction has been saved.'));
                return $this->redirect(['action' => 'receipt',$id]);
            } else {
                $this->Flash->error(__('The transaction could not be saved. Please, try again.'));
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
        $transaction = $this->Transactions->get($id);

        if ($transaction->parent_transaction_id) {
            $this->Flash->error(__('Transaction depends on transaction below, It\'s the one you should delete. All child transactions will be deleted too.'));
            return $this->redirect(['action' => 'view',$transaction->parent_transaction_id]);
        }

        if ($this->Transactions->delete($transaction)) {
            $this->Flash->success(__('The transaction has been deleted.'));
        } else {
            $this->Flash->error(__('The transaction could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}

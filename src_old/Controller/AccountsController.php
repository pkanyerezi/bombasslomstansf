<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Accounts Controller
 *
 * @property \App\Model\Table\AccountsTable $Accounts
 */
class AccountsController extends AppController
{

    public function accountBalance(){
        if(empty($_GET['transaction_status_id'])) $_GET['transaction_status_id']=$this->appSettings->final_transaction_status_id;
        
        if(empty($_GET['date_to'])) $_GET['date_to'] = date('Y-m-d');

        $date_to = $_GET['date_to'];
        $transaction_status_id = $_GET['transaction_status_id'];
        $branch_id = $this->Auth->User('branch_id');

        $other_condition = '';
        if(!in_array($this->Auth->User('role'), ['super_admin','admin','manager'])){
            $user_id = $this->Auth->User('id');
            $other_condition = " AND (t.completed_by_id='$user_id' OR t.created_by_id='$user_id')";
        }

        if (!empty($_GET['customer_id'])) {
            $query = $this->Accounts->find()->select([
                'total_debit' => '(SELECT SUM(t.value) as TransactionTypes__total_value FROM transactions t WHERE Accounts.id=t.from_account_id AND DATE(t.created) <= \''.$date_to.'\' ' . $other_condition . ' AND t.transaction_status_id='.$transaction_status_id.' AND t.branch_id='.$branch_id.' AND t.balance_sheet_side=0 AND t.customer_id='.$_GET['customer_id'].')',

                'total_credit' => '(SELECT SUM(t.value) as TransactionTypes__total_value FROM transactions t WHERE Accounts.id=t.to_account_id AND DATE(t.created) <= \''.$date_to.'\' ' . $other_condition . ' AND t.transaction_status_id='.$transaction_status_id.' AND t.branch_id='.$branch_id.' AND t.balance_sheet_side=1 AND t.customer_id='.$_GET['customer_id'].')',
                'Accounts.name',
                'Accounts.currency_id',
                'Accounts.branch_id',
                'Accounts.id',
                'Accounts.initial_balance',
            ])
            ->contain([])
            ->where(['branch_id'=>$branch_id])
            ->limit(100);
        }else{
            $query = $this->Accounts->find()->select([
                'total_debit' => '(SELECT SUM(t.value) as TransactionTypes__total_value FROM transactions t WHERE Accounts.id=t.from_account_id AND DATE(t.created) <= \''.$date_to.'\' ' . $other_condition . ' AND t.transaction_status_id='.$transaction_status_id.' AND t.branch_id='.$branch_id.' AND t.balance_sheet_side=0)',

                'total_credit' => '(SELECT SUM(t.value) as TransactionTypes__total_value FROM transactions t WHERE Accounts.id=t.to_account_id AND DATE(t.created) <= \''.$date_to.'\' ' . $other_condition . ' AND t.transaction_status_id='.$transaction_status_id.' AND t.branch_id='.$branch_id.' AND t.balance_sheet_side=1)',
                'Accounts.name',
                'Accounts.currency_id',
                'Accounts.branch_id',
                'Accounts.id',
                'Accounts.initial_balance',
            ])
            ->contain([])
            ->where(['branch_id'=>$branch_id])
            ->limit(100);
        }
        
        $accounts = $this->paginate($query);

        $this->set(compact('accounts','branch_id'));
        $this->set('_serialize', ['accounts']);
    }

    public function _accountBalance(){
        if(empty($_GET['transaction_status_id'])) $_GET['transaction_status_id']=$this->appSettings->final_transaction_status_id;
        
        if(empty($_GET['date_to'])) $_GET['date_to'] = date('Y-m-d');

        $date_to = $_GET['date_to'];
        $transaction_status_id = $_GET['transaction_status_id'];
        $branch_id = $this->Auth->User('branch_id');

        $other_condition = '';
        if(!in_array($this->Auth->User('role'), ['super_admin','admin','manager'])){
            $user_id = $this->Auth->User('id');
            $other_condition = " AND (t.completed_by_id='$user_id' OR t.created_by_id='$user_id')";
        }

        if (!empty($_GET['customer_id'])) {
            $customer_id = $_GET['customer_id'];
            // Sort out for a specific customer
            $query = $this->Accounts->TransactionTypes->find()->select([
                'FromAccounts.name',
                'FromAccounts.currency_id',
                'FromAccounts.branch_id',
                'FromAccounts.id',
                'ToAccounts.name',
                'ToAccounts.currency_id',
                'ToAccounts.branch_id',
                'ToAccounts.id',
                'amount_from'=>'(SELECT SUM(t.value) as TransactionTypes__total_value FROM transactions t WHERE DATE(t.created) <= \''.$date_to.'\' ' . $other_condition . ' AND t.customer_id='.$customer_id.' AND t.transaction_type_id = TransactionTypes.id AND t.transaction_status_id='.$transaction_status_id.' AND TransactionTypes.branch_id='.$branch_id.')'
            ])->contain(['FromAccounts','ToAccounts'])
            ->limit(100);
        }else{
            $query = $this->Accounts->TransactionTypes->find()->select([
                'FromAccounts.id',
                'FromAccounts.name',
                'FromAccounts.currency_id',
                'FromAccounts.branch_id',
                'ToAccounts.name',
                'ToAccounts.currency_id',
                'ToAccounts.branch_id',
                'ToAccounts.id',
                'amount_from'=>'(SELECT SUM(t.value) as TransactionTypes__total_value FROM transactions t WHERE DATE(t.created) <= \''.$date_to.'\' ' . $other_condition . ' AND t.transaction_type_id = TransactionTypes.id AND t.transaction_status_id='.$transaction_status_id.' AND TransactionTypes.branch_id='.$branch_id.')'
            ])->contain(['FromAccounts','ToAccounts'])
            ->limit(100);
        }
        
        $accounts = $this->paginate($query);

        $this->set(compact('accounts','branch_id'));
        $this->set('_serialize', ['accounts']);
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $conditions = [];

        if (!empty($_GET['account_type_id'])) {
            $conditions['Accounts.account_type_id'] = $_GET['account_type_id'];
        }

        $this->paginate = [
            'contain' => ['AccountTypes','Branches'],
            'conditions'=>$conditions,
            'order'=>['Accounts.currency_id DESC, Accounts.branch_id ASC'],
            'limit'=>100
        ];

        $accounts = $this->paginate($this->Accounts);

        $this->set(compact('accounts'));
        $this->set('_serialize', ['accounts']);
    }

    /**
     * View method
     *
     * @param string|null $id Account id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $account = $this->Accounts->get($id, [
            'contain' => ['AccountTypes']
        ]);

        $transactionTypes = $this->Accounts->TransactionTypes->find('list')->where(['TransactionTypes.account_type_id'=>$account->account_type_id]);

        $this->set('account', $account);
        $this->set('transactionTypes', $transactionTypes);
        $this->set('_serialize', ['account'],'transactionTypes');
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {   
        $accountTypes = $this->Accounts->AccountTypes->find('list');
        $account = $this->Accounts->newEntity();
        if ($this->request->is('post')) {
            $account = $this->Accounts->patchEntity($account, $this->request->data);
            if ($this->Accounts->save($account)) {
                $this->Flash->success(__('The account has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The account could not be saved. Please, try again.'));
            }
        }

        $currencies = $this->Accounts->Currencies->find('list', ['limit' => 200]);
        $branches = $this->Accounts->Branches->find('list', ['limit' => 200]);
        $this->set(compact('account','accountTypes','currencies','branches'));
        $this->set('_serialize', ['account','accountTypes']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Account id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $accountTypes = $this->Accounts->AccountTypes->find('list');
        $account = $this->Accounts->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $account = $this->Accounts->patchEntity($account, $this->request->data);
            if ($this->Accounts->save($account)) {
                $this->Flash->success(__('The account has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The account could not be saved. Please, try again.'));
            }
        }

        $currencies = $this->Accounts->Currencies->find('list', ['limit' => 200]);
        $branches = $this->Accounts->Branches->find('list', ['limit' => 200]);
        $this->set(compact('account','accountTypes','currencies','branches'));
        $this->set('_serialize', ['account','accountTypes']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Account id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $account = $this->Accounts->get($id);
        if ($this->Accounts->delete($account)) {
            $this->Flash->success(__('The account has been deleted.'));
        } else {
            $this->Flash->error(__('The account could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}

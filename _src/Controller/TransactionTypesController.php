<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * TransactionTypes Controller
 *
 * @property \App\Model\Table\TransactionTypesTable $TransactionTypes
 */
class TransactionTypesController extends AppController
{
    public function flow(){
        $this->paginate = [
            'contain' => ['FromAccounts', 'ToAccounts'],
            'limit'=>200,
            'fields'=>[
                'TransactionTypes.id',
                'TransactionTypes.name',
                'TransactionTypes.linked_transaction_type_id',
                'TransactionTypes.commission_structure_id',
                'FromAccounts.name',
                'ToAccounts.name'
            ]
        ];

        $transactionTypes = $this->paginate($this->TransactionTypes);

        $this->set(compact('transactionTypes'));
        $this->set('_serialize', ['transactionTypes']);
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index($accountTypeId=null)
    {
        $conditions = [];

        if(!in_array($this->Auth->User('role'), ['super_admin'])){
            $conditions = ['TransactionTypes.from_branch_id'=>$this->Auth->User('branch_id')];
        }

        $order = ['TransactionTypes.currency_id DESC,TransactionTypes.priority ASC'];

        $this->paginate = [
            'contain' => ['FromAccounts', 'ToAccounts', 'CommissionStructures', 'LinkedTransactionTypes'],
            'conditions'=> $conditions,
            'order'=>$order
        ];

        if (!empty($accountTypeId)) {
            $conditions1 = [
                'OR'=>[
                    'FromAccounts.id'=>$accountTypeId,
                    'ToAccounts.id'=>$accountTypeId
                ]
            ];
            $this->paginate = [
                'contain' => ['FromAccounts', 'ToAccounts', 'CommissionStructures', 'LinkedTransactionTypes'],
                'conditions'=>array_merge($conditions,$conditions1),
                'order'=>$order
            ];
        }
        $transactionTypes = $this->paginate($this->TransactionTypes);

        $this->set(compact('transactionTypes','accountTypeId'));
        $this->set('_serialize', ['transactionTypes']);
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function listTypes($accountTypeId)
    {
        $this->paginate = [
            'contain' => ['FromAccounts', 'ToAccounts', 'CommissionStructures', 'LinkedTransactionTypes'],
            'conditions'=>[
                'FromAccounts.id'=>$accountTypeId
            ]
        ];
        $transactionTypes = $this->paginate($this->TransactionTypes);

        $this->set(compact('transactionTypes','accountTypeId'));
        $this->set('_serialize', ['transactionTypes']);
    }

    /**
     * View method
     *
     * @param string|null $id Transaction Type id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null,$accountTypeId=null)
    {
        /*$transactionType = $this->TransactionTypes->get($id, [
            'contain' => ['FromAccounts', 'ToAccounts', 'CommissionStructures', 'LinkedTransactionTypes', 'Transactions']
        ]);*/

        $transactionType = $this->TransactionTypes->get($id, [
            'contain' => ['FromAccounts','ToAccounts','CommissionStructures','LinkedTransactionTypes']
        ]);

        $this->set('transactionType', $transactionType);
        $this->set('accountTypeId', $accountTypeId);
        $this->set('_serialize', ['transactionType']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add($accountTypeId=null)
    {
        $transactionType = $this->TransactionTypes->newEntity();
        if ($this->request->is('post')) {
            
            $_break = false;

            $from_account = $this->TransactionTypes->FromAccounts
            ->find()
            ->where(['id'=>$this->request->data['from_account_id']])
            ->select(['currency_id','branch_id'])
            ->contain([])
            ->first();

            $to_account = $this->TransactionTypes->ToAccounts
            ->find()
            ->where(['id'=>$this->request->data['to_account_id']])
            ->select(['currency_id','branch_id'])
            ->contain([])
            ->first();

            if(!$_break && $from_account->currency_id!=$to_account->currency_id || $to_account->currency_id!=$this->request->data['currency_id']){
                $this->Flash->warning(__('Currency should much the AccountFrom and AccountTo selected'));
                $_break = true;
            }

            /*//Make sure the destination account belongs to the Branch
            if(!$_break && $to_account->branch_id!=$this->request->data['branch_id']){
                $this->Flash->warning(__('The Branch('.$to_account->branch_id.') of the Account-To should be similar to the Branch('.$this->request->data['branch_id'].') selected'));
                $_break = true;
            }*/

            if(!$_break){
                $this->request->data['custom_fields'] = 
                implode(',', $this->cleanCustomFields($this->request->data['custom_fields']));

                $transactionType = $this->TransactionTypes->patchEntity($transactionType, $this->request->data);
                if ($this->TransactionTypes->save($transactionType)) {
                    $this->request->session()->write('appTransactionTypes',null);
                    $this->Flash->success(__('The transaction type has been saved.'));
                    if (!empty($accountTypeId)) return $this->redirect(['action' => 'index',$accountTypeId]);
                    return $this->redirect(['action' => 'index']);
                } else {
                    $this->Flash->error(__('The transaction type could not be saved. Please, try again.'));
                }
            }
        }

        if (!empty($accountTypeId)) {
            $fromAccounts = $this->TransactionTypes->FromAccounts->find('list', ['limit' => 200,'conditions'=>[
                'FromAccounts.id'=>$accountTypeId
            ]]);
            $toAccounts = $this->TransactionTypes->ToAccounts->find('list', ['limit' => 200,'conditions'=>[
                'NOT'=>[
                    'ToAccounts.id'=>$accountTypeId
                ]
            ]]);
        }else{
            $fromAccounts = $toAccounts = $this->TransactionTypes->FromAccounts->find('list', ['limit' => 200]);
        }

        $fromBranches = $toBranches = $branches = $this->TransactionTypes->Branches->find('list', ['limit' => 200]);
        
        $commissionStructures = $this->TransactionTypes->CommissionStructures->find('list', ['limit' => 200]);
        $transactionStatuses = $this->TransactionTypes->TransactionStatuses->find('list', ['limit' => 200]);
        $currencies = $this->TransactionTypes->Currencies->find('list', ['limit' => 200]);
        $accountTypes = $this->TransactionTypes->AccountTypes->find('list', ['limit' => 200]);
        $linkedTransactionTypes = $this->TransactionTypes->LinkedTransactionTypes->find('list', ['limit' => 200]);
        $this->set(compact('transactionType', 'fromAccounts', 'toAccounts','branches','toBranches','fromBranches', 'commissionStructures', 'linkedTransactionTypes','accountTypeId','accountTypes','currencies','transactionStatuses'));
        $this->set('_serialize', ['transactionType']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Transaction Type id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null,$accountTypeId=null)
    {
        $transactionType = $this->TransactionTypes->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {

            $_break = false;

            $from_account = $this->TransactionTypes->FromAccounts
            ->find()
            ->where(['id'=>$this->request->data['from_account_id']])
            ->select(['currency_id','branch_id'])
            ->contain([])
            ->first();

            $to_account = $this->TransactionTypes->ToAccounts
            ->find()
            ->where(['id'=>$this->request->data['to_account_id']])
            ->select(['currency_id','branch_id'])
            ->contain([])
            ->first();

            if(!$_break && $from_account->currency_id!=$to_account->currency_id || $to_account->currency_id!=$this->request->data['currency_id']){
                $this->Flash->warning(__('Currency should much the AccountFrom and AccountTo selected'));
                $_break = true;
            }

            //Make sure the destination account belongs to the Branch
            /*if(!$_break && $to_account->branch_id!=$this->request->data['branch_id']){
                $this->Flash->warning(__('The Branch('.$to_account->branch_id.') of the Account-To should be similar to the Branch('.$this->request->data['branch_id'].') selected'));
                $_break = true;
            }*/

            if(!$_break){
                $this->request->data['custom_fields'] = 
                implode(',', $this->cleanCustomFields($this->request->data['custom_fields']));
                
                $oldCurrencyId = $transactionType->currency_id;
                $newCurrencyId = $this->request->data['currency_id'];

                $transactionType = $this->TransactionTypes->patchEntity($transactionType, $this->request->data);
                if ($this->TransactionTypes->save($transactionType)) {

                    // Make Sure the Transactions always match their transaction type currencies
                    if($oldCurrencyId!=$newCurrencyId){
                        $this->TransactionTypes->Transactions->query()
                        ->update()
                        ->set(['currency_id' => $newCurrencyId])
                        ->where(
                           [
                                'transaction_type_id' => $transactionType->id
                           ]
                        )
                        ->execute();
                    }
                    $this->request->session()->write('appTransactionTypes',null);
                    $this->Flash->success(__('The transaction type has been saved.'));
                    return $this->redirect(['action' => 'index']);
                } else {
                    $this->Flash->error(__('The transaction type could not be saved. Please, try again.'));
                }
            }
        }
        $fromAccounts = $toAccounts = $this->TransactionTypes->FromAccounts->find('list', ['limit' => 200]);
        $fromBranches = $toBranches = $branches = $this->TransactionTypes->Branches->find('list', ['limit' => 200]);

        $commissionStructures = $this->TransactionTypes->CommissionStructures->find('list', ['limit' => 200]);
        $transactionStatuses = $this->TransactionTypes->TransactionStatuses->find('list', ['limit' => 200]);
        $currencies = $this->TransactionTypes->Currencies->find('list', ['limit' => 200]);
        $accountTypes = $this->TransactionTypes->AccountTypes->find('list', ['limit' => 200]);
        $linkedTransactionTypes = $this->TransactionTypes->LinkedTransactionTypes->find('list', ['limit' => 200]);
        $this->set(compact('transactionType', 'fromAccounts', 'toAccounts','branches','fromBranches','toBranches', 'commissionStructures', 'linkedTransactionTypes','accountTypeId','accountTypes','currencies','transactionStatuses'));
        $this->set('_serialize', ['transactionType']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Transaction Type id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null,$accountTypeId=null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $transactionType = $this->TransactionTypes->get($id);
        if ($this->TransactionTypes->delete($transactionType)) {
            $this->request->session()->write('appTransactionTypes',null);
            $this->Flash->success(__('The transaction type has been deleted.'));
        } else {
            $this->Flash->error(__('The transaction type could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index',$accountTypeId]);
    }
}

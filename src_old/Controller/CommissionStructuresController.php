<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * CommissionStructures Controller
 *
 * @property \App\Model\Table\CommissionStructuresTable $CommissionStructures
 */
class CommissionStructuresController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {	
		$this->paginate = [
			'contain' => [
				'TransactionTypes',
				'TransactionTypes.Branches',
				'TransactionTypes.FromAccounts',
				'TransactionTypes.ToAccounts',
			],
			'order'=>['TransactionTypes.currency_id DESC'],
			'fields'=>[
				'CommissionStructures.id',
				'CommissionStructures.name',
				'CommissionStructures.description',
				'CommissionStructures.enabled',
				'CommissionStructures.pricing_structure',
				'CommissionStructures.transaction_type_id',
				'TransactionTypes.id',
				'TransactionTypes.name',
				'TransactionTypes.currency_id',
				'TransactionTypes.commission_structure_id',
				'TransactionTypes.branch_id',
				'Branches.id',
				'Branches.name',
				'FromAccounts.id',
				'FromAccounts.name',
				'FromAccounts.currency_id',
				'ToAccounts.id',
				'ToAccounts.name',
				'ToAccounts.currency_id'
			]
		];
        $commissionStructures = $this->paginate($this->CommissionStructures);

        //pr($commissionStructures);exit();
        $this->set(compact('commissionStructures'));
        $this->set('_serialize', ['commissionStructures']);
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
   /* public function index($transactionTypeId=null)
    {
        $this->paginate = [
            'contain' => ['TransactionTypes']
        ];
        if (!empty($transactionTypeId)) {
            $this->paginate = [
                'contain' => ['TransactionTypes'],
                'conditions'=>[
                    'TransactionTypes.id'=>$transactionTypeId
                ]
            ];
        }
        $commissionStructures = $this->paginate($this->Transactions);

        $this->set(compact('commissionStructures','transactionTypeId'));
        $this->set('_serialize', ['commissionStructures']);
    }*/

    /**
     * View method
     *
     * @param string|null $id Commission Structure id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        /*$commissionStructure = $this->CommissionStructures->get($id, [
            'contain' => ['TransactionTypes']
        ]);*/

        $commissionStructure = $this->CommissionStructures->get($id, [
            'contain' => ['TransactionTypes']
        ]);

        $this->set('commissionStructure', $commissionStructure);
        $this->set('_serialize', ['commissionStructure']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $commissionStructure = $this->CommissionStructures->newEntity();
        $transactionTypes = $this->CommissionStructures->TransactionTypes->find('list',[
            'conditions'=>[
                'TransactionTypes.from_branch_id'=>$this->Auth->User('branch_id'),
				'TransactionTypes.commission_structure_id IS'=>NULL
            ]
        ]);
        if ($this->request->is('post')) {
			
			if(empty($this->request->data['transaction_type_id'])){
				$this->Flash->error(__('A Transaction type is required.'));
                return $this->redirect(['action' => 'add']);
            }
			
            if(!isset($this->request->data['pricing_structure'])){
                $this->request->data['pricing_structure'] = json_encode($this->request->data['pricing_structure']);
            }
            
            $commissionStructure = $this->CommissionStructures->patchEntity($commissionStructure, $this->request->data);
            if ($this->CommissionStructures->save($commissionStructure)) {
                $this->Flash->success(__('The commission structure has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The commission structure could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('commissionStructure','transactionTypes'));
        $this->set('_serialize', ['commissionStructure']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Commission Structure id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $commissionStructure = $this->CommissionStructures->get($id, [
            'contain' => []
        ]);
        $transactionTypes = $this->CommissionStructures->TransactionTypes->find('list',[
            'conditions'=>[
                'TransactionTypes.from_branch_id'=>$this->Auth->User('branch_id'),
				'TransactionTypes.commission_structure_id IS'=>NULL
            ]
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            if(empty($this->request->data['transaction_type_id'])){
				$this->Flash->error(__('A Transaction type is required.'));
                return $this->redirect(['action' => 'edit',$id]);
            }
			
			if(!isset($this->request->data['pricing_structure'])){
                $this->request->data['pricing_structure'] = json_encode($this->request->data['pricing_structure']);
            }
            $commissionStructure = $this->CommissionStructures->patchEntity($commissionStructure, $this->request->data);
            if ($this->CommissionStructures->save($commissionStructure)) {
                $this->Flash->success(__('The commission structure has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The commission structure could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('commissionStructure','transactionTypes'));
        $this->set('_serialize', ['commissionStructure']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Commission Structure id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $commissionStructure = $this->CommissionStructures->get($id);
        if ($this->CommissionStructures->delete($commissionStructure)) {
            $this->Flash->success(__('The commission structure has been deleted.'));
        } else {
            $this->Flash->error(__('The commission structure could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}

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
        $commissionStructures = $this->paginate($this->CommissionStructures,[
            'contain' => ['TransactionTypes']
        ]);

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
                'TransactionTypes.from_branch_id'=>$this->Auth->User('branch_id')
            ]
        ]);
        if ($this->request->is('post')) {
			if(!isset($this->request->data['pricing_structure'])){
				$this->Flash->error(__('You need to add commission values in the Pricing Structure section'));
                return $this->redirect(['action' => 'index']);
			}
            $this->request->data['pricing_structure'] = json_encode($this->request->data['pricing_structure']);
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
                'TransactionTypes.from_branch_id'=>$this->Auth->User('branch_id')
            ]
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
			if(!isset($this->request->data['pricing_structure'])){
				$this->Flash->error(__('You need to add commission values in the Pricing Structure section'));
                return $this->redirect(['action' => 'edit',$id]);
			}
            $this->request->data['pricing_structure'] = json_encode($this->request->data['pricing_structure']);
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

<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * CustomerAccountTypes Controller
 *
 * @property \App\Model\Table\CustomerAccountTypesTable $CustomerAccountTypes
 */
class CustomerAccountTypesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->redirect(['controller'=>'AccountTypes','action'=>'index']);
        /*$customerAccountTypes = $this->paginate($this->CustomerAccountTypes);

        $this->set(compact('customerAccountTypes'));
        $this->set('_serialize', ['customerAccountTypes']);*/
    }

    /**
     * View method
     *
     * @param string|null $id Customer Account Type id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->redirect(['controller'=>'AccountTypes','action'=>'view',$id]);
        /*$customerAccountType = $this->CustomerAccountTypes->get($id, [
            'contain' => ['Accounts']
        ]);*/

       /* $customerAccountType = $this->CustomerAccountTypes->get($id, [
            'contain' => []
        ]);

        $this->set('customerAccountType', $customerAccountType);
        $this->set('_serialize', ['customerAccountType']);*/
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->redirect(['controller'=>'AccountTypes','action'=>'add']);

        $customerAccountType = $this->CustomerAccountTypes->newEntity();
        if ($this->request->is('post')) {
            $customerAccountType = $this->CustomerAccountTypes->patchEntity($customerAccountType, $this->request->data);
            if ($this->CustomerAccountTypes->save($customerAccountType)) {
                $this->Flash->success(__('The customer account type has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The customer account type could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('customerAccountType'));
        $this->set('_serialize', ['customerAccountType']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Customer Account Type id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->redirect(['controller'=>'AccountTypes','action'=>'edit']);
        
        $customerAccountType = $this->CustomerAccountTypes->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $customerAccountType = $this->CustomerAccountTypes->patchEntity($customerAccountType, $this->request->data);
            if ($this->CustomerAccountTypes->save($customerAccountType)) {
                $this->Flash->success(__('The customer account type has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The customer account type could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('customerAccountType'));
        $this->set('_serialize', ['customerAccountType']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Customer Account Type id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $customerAccountType = $this->CustomerAccountTypes->get($id);
        if ($this->CustomerAccountTypes->delete($customerAccountType)) {
            $this->Flash->success(__('The customer account type has been deleted.'));
        } else {
            $this->Flash->error(__('The customer account type could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}

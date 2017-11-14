<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * TransactionStatuses Controller
 *
 * @property \App\Model\Table\TransactionStatusesTable $TransactionStatuses
 */
class TransactionStatusesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $transactionStatuses = $this->paginate($this->TransactionStatuses);

        $this->set(compact('transactionStatuses'));
        $this->set('_serialize', ['transactionStatuses']);
    }

    /**
     * View method
     *
     * @param string|null $id Transaction Status id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        /*$transactionStatus = $this->TransactionStatuses->get($id, [
            'contain' => ['Transactions']
        ]);*/

        $transactionStatus = $this->TransactionStatuses->get($id, [
            'contain' => []
        ]);

        $this->set('transactionStatus', $transactionStatus);
        $this->set('_serialize', ['transactionStatus']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $transactionStatus = $this->TransactionStatuses->newEntity();
        if ($this->request->is('post')) {
            $transactionStatus = $this->TransactionStatuses->patchEntity($transactionStatus, $this->request->data);
            if ($this->TransactionStatuses->save($transactionStatus)) {
                $this->request->session()->write('appTransactionStatuses',null);
                $this->Flash->success(__('The transaction status has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The transaction status could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('transactionStatus'));
        $this->set('_serialize', ['transactionStatus']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Transaction Status id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $transactionStatus = $this->TransactionStatuses->get($id, [
            'contain' => []
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $transactionStatus = $this->TransactionStatuses->patchEntity($transactionStatus, $this->request->data);
            if ($this->TransactionStatuses->save($transactionStatus)) {
                
                $this->Flash->success(__('The transaction status has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The transaction status could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('transactionStatus'));
        $this->set('_serialize', ['transactionStatus']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Transaction Status id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $transactionStatus = $this->TransactionStatuses->get($id);
        if ($this->TransactionStatuses->delete($transactionStatus)) {
            $this->Flash->success(__('The transaction status has been deleted.'));
        } else {
            $this->Flash->error(__('The transaction status could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}

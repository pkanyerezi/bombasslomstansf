<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * AppSettings Controller
 *
 * @property \App\Model\Table\AppSettingsTable $AppSettings
 */
class AppSettingsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Branches', 'CustomerAccountTypes','FinalTransactionStatuses']
        ];
        $appSettings = $this->paginate($this->AppSettings);

        $this->set(compact('appSettings'));
        $this->set('_serialize', ['appSettings']);
    }

    /**
     * View method
     *
     * @param string|null $id App Setting id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        /*$appSetting = $this->AppSettings->get($id, [
            'contain' => ['Branches', 'CustomerAccountTypes']
        ]);*/

        $appSetting = $this->AppSettings->get($id, [
            'contain' => ['Branches', 'CustomerAccountTypes','FinalTransactionStatuses']
        ]);

        $this->set('appSetting', $appSetting);
        $this->set('_serialize', ['appSetting']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {

        if($this->Auth->User('role')!='super_admin'){
            $this->Flash->error(__('Access Denied. Contact Super Admin'));
            return $this->redirect(['action' => 'index']);
        }

        $appSetting = $this->AppSettings->newEntity();
        if ($this->request->is('post')) {
            
            $this->request->data['customer_custom_fields'] = 
            implode(',', $this->cleanCustomFields($this->request->data['customer_custom_fields']));

            $this->request->data['customer_identity_types'] = 
            implode(',', $this->cleanCustomFields($this->request->data['customer_identity_types']));

            $appSetting = $this->AppSettings->patchEntity($appSetting, $this->request->data);
            if ($this->AppSettings->save($appSetting)) {
                $this->request->session()->write('appSettings',null);
                $this->Flash->success(__('The app setting has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The app setting could not be saved. Please, try again.'));
            }
        }
        $branches = $this->AppSettings->Branches->find('list', ['limit' => 200]);
        $customerAccountTypes = $this->AppSettings->CustomerAccountTypes->find('list', ['limit' => 200]);
        $finalTransactionStatuses = $this->AppSettings->FinalTransactionStatuses->find('list',['limit' => 200]);
        $this->set(compact('appSetting', 'branches', 'customerAccountTypes','finalTransactionStatuses'));
        $this->set('_serialize', ['appSetting']);
    }

    /**
     * Edit method
     *
     * @param string|null $id App Setting id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $appSetting = $this->AppSettings->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {

            $this->request->data['customer_custom_fields'] = 
            implode(',', $this->cleanCustomFields($this->request->data['customer_custom_fields']));

            $this->request->data['customer_identity_types'] = 
            implode(',', $this->cleanCustomFields($this->request->data['customer_identity_types']));

            $appSetting = $this->AppSettings->patchEntity($appSetting, $this->request->data);
            if ($this->AppSettings->save($appSetting)) {
                $this->request->session()->write('appSettings',null);

                $this->Flash->success(__('The app setting has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The app setting could not be saved. Please, try again.'));
            }
        }
        $branches = $this->AppSettings->Branches->find('list', ['limit' => 200]);
        $customerAccountTypes = $this->AppSettings->CustomerAccountTypes->find('list', ['limit' => 200]);
        $finalTransactionStatuses = $this->AppSettings->FinalTransactionStatuses->find('list',['limit' => 200]);
        $this->set(compact('appSetting', 'branches', 'customerAccountTypes','finalTransactionStatuses'));
        $this->set('_serialize', ['appSetting']);
    }

    /**
     * Delete method
     *
     * @param string|null $id App Setting id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $appSetting = $this->AppSettings->get($id);
        if ($this->AppSettings->delete($appSetting)) {
            $this->Flash->success(__('The app setting has been deleted.'));
        } else {
            $this->Flash->error(__('The app setting could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}

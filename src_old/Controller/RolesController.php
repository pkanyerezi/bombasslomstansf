<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Roles Controller
 *
 * @property \App\Model\Table\RolesTable $Roles
 */
class RolesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $roles = $this->paginate($this->Roles);

        $this->set(compact('roles'));
        $this->set('_serialize', ['roles']);
    }

    /**
     * View method
     *
     * @param string|null $id Role id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        /*$role = $this->Roles->get($id, [
            'contain' => ['Users', 'TransactionTypes']
        ]);*/

        $role = $this->Roles->get($id, [
            'contain' => []
        ]);

        $this->set('role', $role);
        $this->set('_serialize', ['role']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $role = $this->Roles->newEntity();
        if ($this->request->is('post')) {
            $this->request->data['title'] = ucwords($this->request->data['title']);
            $alias = strtolower($this->request->data['title']);
            $alias = str_replace('   ', '_', $alias);
            $alias = str_replace('  ', '_', $alias);
            $alias = str_replace(' ', '_', $alias);
            $this->request->data['alias'] = $alias;

            //Check if the alias exists
            $roleRecord = $this->Roles->find()->where(['Roles.alias'=>$alias])->first();
            if(!empty($roleRecord->id)){
                $this->Flash->error(__('Role already exists as ' . $roleRecord->title));
                return $this->redirect(['action' => 'add']);
            }

            $role = $this->Roles->patchEntity($role, $this->request->data);
            if ($this->Roles->save($role)) {
                $this->Flash->success(__('The role has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The role could not be saved. Please, try again.'));
            }
        }
        // $users = $this->Roles->Users->find('list', ['limit' => 200]);
        $this->set(compact('role', 'users'));
        $this->set('_serialize', ['role']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Role id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $role = $this->Roles->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $role = $this->Roles->patchEntity($role, $this->request->data);
            if ($this->Roles->save($role)) {
                $this->Flash->success(__('The role has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The role could not be saved. Please, try again.'));
            }
        }
        // $users = $this->Roles->Users->find('list', ['limit' => 200]);
        $this->set(compact('role', 'users'));
        $this->set('_serialize', ['role']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Role id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $role = $this->Roles->get($id);
        if(in_array($role->alias, ['super_admin'])){
            $this->Flash->warning(__($role->alias . ' Should not be deleted!'));
            return $this->redirect(['action' => 'index']);
        }
        if ($this->Roles->delete($role)) {
            $this->Flash->success(__('The role has been deleted.'));
        } else {
            $this->Flash->error(__('The role could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}

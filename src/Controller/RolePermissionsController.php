<?php
namespace App\Controller;

use App\Controller\AppController;
use ReflectionClass;
use ReflectionMethod;

/**
 * RolePermissions Controller
 *
 * @property \App\Model\Table\RolePermissionsTable $RolePermissions
 */
class RolePermissionsController extends AppController
{
    public $ignoreListActions = ['beforeFilter', 'afterFilter', 'initialize','isAuthorized'];
    public $ignoreListControllers = [
        '.', 
        '..', 
        'Component', 
        'AppController.php',
        // 'RolePermissionsController.php',
        // 'PagesController.php',
        // 'DashboardsController.php',
        // 'AppSettingsController.php'
    ];

    // This returns permission status for each action as a JSON for a give controller
    public function getPermissionStatuses(){
        $controller = $_GET['controller'];
        $roles = $this->RolePermissions->Roles->find();
        foreach ($roles as $role) {
            if($role->alias=='super_admin') continue;
            $this->insertMissionRolePermissions($controller,$role->id);
        }

        $rolePermission = $this->RolePermissions
        ->find()
        ->where([
            'RolePermissions.controller'=>$controller
        ]);

        $response = [
            'status'=>true,
            'data'=>$rolePermission->toArray()
        ];

        echo json_encode($response);
        exit();
    }

    public function togglePermissionStatus(){
        $rolePermission = $this->RolePermissions->get($_GET['pkid']);
        $rolePermission = $this->RolePermissions->patchEntity($rolePermission, ['enabled'=>!$rolePermission->enabled]);
        if ($this->RolePermissions->save($rolePermission)) {
            $data = [];
            $data[] = $rolePermission->toArray();
            $response = [
                'status'=>true,
                'data'=>$data
            ];
        }else{
            $response = [
                'status'=>false,
                'data'=>[]
            ];
        }

        echo json_encode($response);
        exit();
    }

    // This will create missiong role-permissions in the database that are disabled by default.
    private function insertMissionRolePermissions($controller,$role_id){
        $actions = $this->getActions($controller);
        foreach ($actions[$controller] as $action) {
            // Check whether the permission for this action exists before creating it.
            
            $permissionExists = $this->RolePermissions
            ->find()
            ->where([
                'RolePermissions.role_id'=>$role_id,
                'RolePermissions.controller'=>$controller,
                'RolePermissions.action'=>$action,
            ])
            ->count();

            if(!$permissionExists){
                $rolePermissionRecord = [
                    'role_id'=>$role_id,
                    'controller'=>$controller,
                    'action'=>$action,
                    'enabled'=>false,
                    'description'=>"$action $controller"
                ];
                $rolePermission = $this->RolePermissions->newEntity();
                $rolePermission = $this->RolePermissions->patchEntity($rolePermission, $rolePermissionRecord);
                $this->RolePermissions->save($rolePermission);
            }
        }
    }

    public function viewPermisionsTable(){
        $this->set('resources',$this->getResources());
        $this->set('roles',$this->RolePermissions->Roles->find());
    }

    private function getResources(){
        $controllers = $this->getControllers();
        $resources = [];
        foreach($controllers as $controller){
            $actions = $this->getActions($controller);
            array_push($resources, $actions);
        }
        return $resources;
    }

    private function getControllers() {
        $files = scandir(APP . 'Controller');
        $results = [];
        foreach($files as $file){
            if(!in_array($file, $this->ignoreListControllers)) {
                $controller = explode('.', $file)[0];
                array_push($results, str_replace('Controller', '', $controller));
            }            
        }
        return $results;
    }

    private function getActions($controllerName) {
        $className = 'App\\Controller\\'.$controllerName.'Controller';
        $class = new ReflectionClass($className);
        $actions = $class->getMethods(ReflectionMethod::IS_PUBLIC);
        $results = [$controllerName => []];
        foreach($actions as $action){
            if($action->class == $className && !in_array($action->name, $this->ignoreListActions)){
                array_push($results[$controllerName], $action->name);
            }   
        }
        return $results;
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Roles']
        ];
        $rolePermissions = $this->paginate($this->RolePermissions);

        $this->set(compact('rolePermissions'));
        $this->set('_serialize', ['rolePermissions']);
    }

    /**
     * View method
     *
     * @param string|null $id Role Permission id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        /*$rolePermission = $this->RolePermissions->get($id, [
            'contain' => ['Roles']
        ]);*/

        $rolePermission = $this->RolePermissions->get($id, [
            'contain' => []
        ]);

        $this->set('rolePermission', $rolePermission);
        $this->set('_serialize', ['rolePermission']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $rolePermission = $this->RolePermissions->newEntity();
        if ($this->request->is('post')) {
            $rolePermission = $this->RolePermissions->patchEntity($rolePermission, $this->request->data);
            if ($this->RolePermissions->save($rolePermission)) {
                $this->Flash->success(__('The role permission has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The role permission could not be saved. Please, try again.'));
            }
        }
        $roles = $this->RolePermissions->Roles->find('list', ['limit' => 200]);
        $this->set(compact('rolePermission', 'roles'));
        $this->set('_serialize', ['rolePermission']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Role Permission id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $rolePermission = $this->RolePermissions->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $rolePermission = $this->RolePermissions->patchEntity($rolePermission, $this->request->data);
            if ($this->RolePermissions->save($rolePermission)) {
                $this->Flash->success(__('The role permission has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The role permission could not be saved. Please, try again.'));
            }
        }
        $roles = $this->RolePermissions->Roles->find('list', ['limit' => 200]);
        $this->set(compact('rolePermission', 'roles'));
        $this->set('_serialize', ['rolePermission']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Role Permission id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $rolePermission = $this->RolePermissions->get($id);
        if ($this->RolePermissions->delete($rolePermission)) {
            $this->Flash->success(__('The role permission has been deleted.'));
        } else {
            $this->Flash->error(__('The role permission could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}

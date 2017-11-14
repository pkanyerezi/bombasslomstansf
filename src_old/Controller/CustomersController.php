<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Customers Controller
 *
 * @property \App\Model\Table\CustomersTable $Customers
 */
class CustomersController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('ImageResize');
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $conditions = [];

        if (!empty($_GET['q'])) {
            $conditions['OR']['Customers.id'] = $_GET['q'];
            $conditions['OR']['Customers.name LIKE'] = '%' . $_GET['q'] . '%';
            $conditions['OR']['Customers.email LIKE'] = '%' . $_GET['q'] . '%';
            $conditions['OR']['Customers.identity LIKE'] = '%' . $_GET['q'] . '%';
        }

        $this->paginate = [
            'contain' => ['Branches'],
            'conditions' => $conditions
        ];
        $customers = $this->paginate($this->Customers);

        $appSettings = $this->appSettings();
        $this->loadModel('TransactionTypes');
        $transactionTypes = $this->TransactionTypes->find('list')->where(['TransactionTypes.account_type_id'=>$appSettings->customer_account_type_id]);

        $this->set(compact('customers','transactionTypes'));
        $this->set('_serialize', ['customers','transactionTypes']);
    }

    /**
     * View method
     *
     * @param string|null $id Customer id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        /*$customer = $this->Customers->get($id, [
            'contain' => ['Branches']
        ]);*/

        $customer = $this->Customers->get($id, [
            'contain' => ['Branches']
        ]);

        $appSettings = $this->appSettings();
        $this->loadModel('TransactionTypes');
        $transactionTypes = $this->TransactionTypes->find('list')->where(['TransactionTypes.account_type_id'=>$appSettings->customer_account_type_id]);

        $this->set(compact('customer','transactionTypes'));
        $this->set('_serialize', ['customer','transactionTypes']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $customer = $this->Customers->newEntity();
        if ($this->request->is('post')) {

            if (isset($this->request->data['custom_fields'])) {
                $this->request->data['custom_fields'] = json_encode($this->request->data['custom_fields']);;
            }else{
                $this->request->data['custom_fields'] = json_encode([]);
            }

            $customer = $this->Customers->patchEntity($customer, $this->request->data);
            if ($this->Customers->save($customer)) {
                $this->Flash->success(__('The customer has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The customer could not be saved. Please, try again.'));
            }
        }
        $branches = $this->Customers->Branches->find('list', ['limit' => 200]);
        $this->set(compact('customer', 'branches'));
        $this->set('_serialize', ['customer']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Customer id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $customer = $this->Customers->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {

            if (isset($this->request->data['custom_fields'])) {
                $this->request->data['custom_fields'] = json_encode($this->request->data['custom_fields']);;
            }else{
                $this->request->data['custom_fields'] = json_encode([]);
            }

            $customer = $this->Customers->patchEntity($customer, $this->request->data);
            if ($this->Customers->save($customer)) {
                $this->Flash->success(__('The customer has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The customer could not be saved. Please, try again.'));
            }
        }
        $branches = $this->Customers->Branches->find('list', ['limit' => 200]);
        $this->set(compact('customer', 'branches'));
        $this->set('_serialize', ['customer']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Customer id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $customer = $this->Customers->get($id);
        if ($this->Customers->delete($customer)) {
            $this->Flash->success(__('The customer has been deleted.'));
        } else {
            $this->Flash->error(__('The customer could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }

     public function editImages($id = null,$type="logos")
    {
        if (in_array($type, ['identification'])) {
            if (in_array($this->Auth->User('role'), ['super_admin'])) {
                $user = $this->Customers->get($id);
            }else{
                $user = $this->Customers->get($this->Auth->User('id'));
            }
        }elseif (in_array($type, ['post_images'])) {
            $post = $this->Customers->Posts->get($id,[
                'fields'=>['id','user_id','title']
            ]);
            if (in_array($this->Auth->User('role'), ['super_admin'])) {
                $user = $this->Customers->get($post->user_id);
            }else{
                if ($post->user_id!=$this->Auth->User('id')) {
                    $this->Flash->error(__('Access Denied!!'));
                    return $this->redirect(array('action' => 'view',$this->Auth->User('id')));
                }
                $user = $this->Customers->get($this->Auth->User('id'));

                // create an id to be set for the image
                $totalImages = $this->Customers->Posts->PostImages->find()->where(['post_id'=>$post->id])->count();
                $id = $totalImages + 1;
                $id = $post->id . '-' . $id;
            }
        }else{
            $this->Flash->error(__('Invalid location!!'));
            return $this->redirect(array('action' => 'view',$this->Auth->User('id')));
        }

        $max_file_size = 150;//kb
        $fileExtensionsSupported = ['PNG','JPEG','JPG'];
        $subfolder = '';
        switch ($type) {
            case 'identification':
                $title = 'ID/Passport Image';
                $data_img_field = 'identification_img';
                // $subfolder = DS . 'identification';
                $width = 480; $height = 480;
                break;
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            if(isset($_FILES[$type]) and !empty($_FILES[$type])){//getting the file extension and testing it

                $fileExtension=pathinfo($_FILES[$type]['name'], PATHINFO_EXTENSION);
                $FileExtensionAllowed=false;
                if (in_array(strtoupper($fileExtension), $fileExtensionsSupported)) {
                    $FileExtensionAllowed=true;
                }
                
                //redirect the user if the file is not accepted to be uploaded
                if(!$FileExtensionAllowed){
                    $this->Flash->error(__('File type not allowed...try png or jpg or jpeg images.Thanks'));
                    return $this->redirect(array('action' => 'editImages',$id,$type));                    
                }else{
                    //create filename if the image type is alowed                                       
                    $file_name=$id.'.'.(pathinfo($_FILES[$type]['name'], PATHINFO_EXTENSION));
                    if(file_exists(WWW_ROOT."img".DS."customers" . $subfolder .DS."$type".DS."$file_name")){
                        if($this->request->data[$type]!='default.png')
                            @unlink(WWW_ROOT."img".DS."customers" . $subfolder.DS."$type".DS."$file_name");
                    }
                    
                    if(move_uploaded_file($_FILES[$type]['tmp_name'],WWW_ROOT."img".DS."customers"  . $subfolder .DS."$type".DS."$file_name")){

                        //Validate the image size and pixels
                        $maxsize = 1024*$max_file_size;//150KB
                        $path = WWW_ROOT."img".DS."customers" . $subfolder .DS."$type".DS."$file_name";
                        $size = filesize($path);
                        if($size>$maxsize){
                            if(file_exists($path)){@unlink($path);}
                            $this->Flash->error(__('Image is too large. Maximum is '.($max_file_size).' KB'));
                            return $this->redirect(array('action' => 'editImages',$id,$type)); 
                        }

                        // resize img 
                        $this->ImageResize->resize($path, $width, $height);

                        $user = $this->Customers->patchEntity($user, [
                            ''.$data_img_field => $file_name
                        ]);
                        if ($this->Customers->save($user)) {
                            $this->Flash->success(__('Saved!!'));
                            return $this->redirect(array('action' => 'view',$id)); 
                        }

                        @unlink($path);
                        $this->Flash->error(__('Error saving image. Please try again.'));
                        return $this->redirect(array('action' => 'editImages',$id,$type));
                    }else{
                        $this->Flash->error(__('Failed to upload image. Try a smaller one.'));
                        return $this->redirect(array('action' => 'editImages',$id,$type));
                    }
                }
            }else{
                $this->Flash->error(__('No file posted!'));
                return $this->redirect(array('action' => 'editImages',$id,$type));
            }
        }
        $this->set(compact(['title','type','id','user','width','height','max_file_size','fileExtensionsSupported']));
    }
}

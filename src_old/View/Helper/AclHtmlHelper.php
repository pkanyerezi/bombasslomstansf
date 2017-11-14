<?php
namespace App\View\Helper;
use Cake\View\Helper;
use Cake\View\Helper\HtmlHelper;
use Cake\ORM\TableRegistry;

class AclHtmlHelper extends HtmlHelper {

    public function acl($url) {

    	$role_id = $this->request->session()->read('Auth.User.role_id');
    	$role = $this->request->session()->read('Auth.User.role');

    	if($role=='super_admin') return true;

    	return $this->actionAllowed($role_id, $url);
    }

    public function actionAllowed($user_role_id,$url){
        $action = $url['action'];
        if(in_array($action, ['login','logout','register'])){
            return true;
        }
        
        $controller = $url['controller'];
        $user_id = $this->request->session()->read('Auth.User.id');
        $sessionKey = "ACLHtml.User.$controller.$action.$user_id";
        $rolePermission = $this->request->session()->read($sessionKey);

        if(empty($rolePermission)){
            $RolePermissions = TableRegistry::get('RolePermissions');
            $rolePermission = $RolePermissions->find()
            ->select(['RolePermissions.id','RolePermissions.enabled'])
            ->where([
                'RolePermissions.controller'=>$controller,
                'RolePermissions.action'=>$action,
                'RolePermissions.role_id'=>$user_role_id
            ])->first();
            $this->request->session()->write($sessionKey,$rolePermission);
        }

        if(!empty($rolePermission->id) && $rolePermission->enabled){
            return true;
        }
        return false;
    }

    public function link($title , $url = null , array $options = []) {
    	if(!isset($url['controller'])){
    		$_url = [];
            $_url['controller'] = $this->request->params['controller'];
            $url = array_merge((is_array($url))?$url:[],$_url);
    	}
    	if(!isset($url['action'])){
            $_url = [];
            $_url['action'] = $this->request->params['action'];
            if(empty($_url['action'])) $_url['action'] = 'index';
            $url = array_merge((is_array($url))?$url:[],$_url);
    	}
    	return $this->acl($url) ? parent::link($title , $url , $options) : '';
	}

    public function formatTime($timeSetInit){
        $timeSet = explode(' PM ',$timeSetInit);
        $theTime = null;
        if(count($timeSet)>1) $theTime = $timeSet[0] . ' PM';
        
        if(empty($theTime)){
            $timeSet = explode(' AM ',$timeSetInit);
            
            if(count($timeSet)>1) $theTime = $timeSet[0] . ' AM';
            else $theTime = $timeSetInit;
        }
        return $theTime;
    }

}
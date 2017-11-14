<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Utility\Hash;
use Cake\Core\Configure;

/**
 * TransactionsApi Controller
 *
 * @property \App\Model\Table\TransactionsTable $Transactions
 */
class TransactionsApiController extends AppController
{
	public function beforeFilter(\Cake\Event\Event $event)
    {
        $this->Auth->allow(['add','getAccessToken','testSetToken','testGetTokenData','sendOfflineOnline','receiveOfflineOnline']);
    }

    public function isAuthorized($user)
    {
        $action = $this->request->params['action'];
        if(in_array($action,array('add'))){
            return true;
        }
        return parent::isAuthorized($user);
    }

    // Sends receipts/transactions created offline to the online server
    public function sendOfflineOnline(){

    	sleep(5);
    	set_time_limit(300);

    	if(!((int) Configure::read('offline'))){
            echo json_encode([
                'msg'=>'App Should Operate in offline mode. Check the config file.', 
                'status'=>false
            ]);
            exit();
        }

    	$this->loadModel('Transactions');
        $appSettings = $this->appSettings(Configure::read('branch_id'));
        
        $transactions = $this->Transactions->find()
        ->select([
            'Transactions.id',
            'Transactions.transaction_type_id',
            'Transactions.created_by_id',
            'Transactions.modified_by_id',
            'Transactions.offline',
            'Transactions.offline_form_data',
            'Transactions.from_branch_id',
            'Transactions.to_branch_id',
            'Transactions.reference_code',
            'Transactions.transaction_status_id',
            'Transactions.completed_by_id',
            'Transactions.completor_branch_id',
        ])
        ->contain([])
        ->where([
            'Transactions.offline'=>true,
            'Transactions.transaction_status_id'=>$appSettings->final_transaction_status_id,
            'Transactions.parent_transaction_id'=>0,
        ])
        ->order(['Transactions.created'=>'DESC'])
        ->limit(Configure::read('syncTransactionLimit'));

        $unSyncedCount = $transactions->count();

        if($unSyncedCount){

        	$url = Configure::read('remote_api_url') . 'transactions-api/getAccessToken';
	        $access = $this->httpPost($url,json_encode([
	        	'username'=>Configure::read('remote_api_username'),
	        	'password'=>Configure::read('remote_api_password'),
	        ]));
	        @$access = json_decode($access);

	     	if(empty($access) || empty($access->status) || empty($access->token)){
	        	echo json_encode([
	                'msg'=>'Api Access Denied.', 
	                'status'=>false
	            ]);
	            exit();
	        }

        	
            // Create Request Data for the transactions to be sent
            $data = json_encode([
            	'token'=>$access->token,
            	'transactions'=>$transactions->toArray()
            ]);
            $url = Configure::read('remote_api_url') . 'transactions-api/receiveOfflineOnline';
            $results = $this->httpPost($url,$data);
            @$results = json_decode($results);

            
            // exit();
            if($results && !empty($results) && !empty($results->status)){
            	$returnVal = $this->Transactions->query()
	            ->update()
	            ->set(['offline' => false])
	            ->where(['id IN' => $results->ids])
	            ->orwhere(['parent_transaction_id IN' => $results->ids])
	            ->execute();

	            if($returnVal->rowCount()){
	            	$status = true;
	            	$statusMsg = '';
	        	}else{
	        		$status = false;
	        		$statusMsg = 'Error Updating {offline} field for the transactions.';
	        	}

	        	echo json_encode([
	        		'msg'=>$statusMsg, 
	        		'unSyncedCount'=>$unSyncedCount  ,
	        		'syncedCount'=>count($results->ids), 
	        		'status'=>$status
	        	]);
            }else{
            	if(!empty($results->msg)){
            		@$msg = $results->msg;
            	}else{
            		$msg = "Request Failed.";
            	}
            	echo json_encode(['msg'=>$msg, 'status'=>false]);
            }
        }else{
        	echo json_encode(['msg'=>'No Transactions', 'status'=>false]);
        }

        exit();
    }

    #remote
    // Receive receipts/transactions created offline to the online system
    public function receiveOfflineOnline(){
    	if(((int) Configure::read('offline'))){
            echo json_encode([
                'msg'=>'App Should Operate in online mode. Check the config file.', 
                'status'=>false
            ]);
            exit();
        }

    	$input = file_get_contents('php://input');
    	@$input = json_decode($input);

        if(empty($input) || empty($input->token)){
    		echo json_encode(['msg'=>'access {token} required', 'status'=>false]);
    		exit();
    	}

    	if(!$this->getTokenData($input->token)){
    		echo json_encode(['msg'=>'access token is invalid', 'status'=>false]);
    		exit();
    	}

    	if(empty($input->transactions)){
    		echo json_encode(['msg'=>'at-least one transaction is requred!', 'status'=>false]);
    		exit();
    	}

    	$ids = [];
    	$this->loadComponent('TransactionLib');
    	foreach ($input->transactions as $transaction) {
    		# code...
    		$offline_form_data = $transaction->offline_form_data;
    		if(!empty($transaction->offline_form_data)){
    			$data = json_decode($transaction->offline_form_data,true);

    			$data['id'] = $transaction->id;
    			$data['transaction_type_id'] = $transaction->transaction_type_id;
    			$data['transaction_status_id'] = $transaction->transaction_status_id;
    			$data['completed_by_id'] = $transaction->completed_by_id;
    			$data['completor_branch_id'] = $transaction->completor_branch_id;
    			$data['created_by_id'] = $transaction->created_by_id;
    			$data['reference_code'] = $transaction->reference_code;

    			// check - if transaction with the reference_code provided exists, skip it
    			// At this point
    			if(empty($this->Transactions)){
    				$this->loadModel('Transactions');
    			}

    			$transactionExists = $this->Transactions->find()
    			->where(['Transactions.reference_code'=>$transaction->reference_code])
    			->count();

    			if($transactionExists){
    				$ids[] = $transaction->id;
    				continue;
    			}

				$this->appSettings($transaction->from_branch_id);
				$response = $this->TransactionLib->add($data);

				// pr($data);
				// exit();

    			if($this->TransactionLib->add($data)){
    				$ids[] = $transaction->id;
    			}
    		}
    	}

        echo json_encode(['ids'=>$ids, 'status'=>true]);
    	exit();
    }

    private function httpPost($url, $data)
    {

    	$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_TIMEOUT, (int)300);
		
		$resp = curl_exec($ch);
		$respData = null;
		if (curl_getinfo($ch, CURLINFO_HTTP_CODE)==200){
			$respData = $resp;
     	}

     	curl_close ($ch);
     	return $respData;
    }

    # remote
    public function getAccessToken(){
    	$input = file_get_contents('php://input');
    	@$input = json_decode($input);


    	if(empty($input) || empty($input->username) || empty($input->password)){
    		echo json_encode(['msg'=>'username and password is required', 'status'=>false, 'input'=>$input]);
    		exit();
    	}

    	$this->loadModel('Users');
    	$user = $this->Users->find()
    	->select(['id','username','role'])
    	->where([
    		'Users.username'=> $input->username,
    		'Users.password'=> $input->password,
    	])
    	->contain([])
    	->first();

    	if(empty($user->id)){
    		echo json_encode(['msg'=>'username or password is incorrect', 'status'=>false]);
    		exit();
    	}

    	$expiry = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' +6hours'));
    	$token = $this->setToken([
    		'id'=>$user->id,
    		'username'=>$user->username,
    		'role'=>$user->role,
    		'expiry'=>$expiry,
    	]);

    	echo json_encode(
    		['msg'=>'', 'status'=>true,'token'=>$token]
    	);
    	exit();
    }

    # remote
    private function setToken($tokenData){
    	$tokenDataJson = json_encode($tokenData);
    	$token = [];

    	$tokenId = hash('sha256', $tokenDataJson, Configure::consume('Security.salt'));

    	$token = $tokenData;
    	$token['tokenId'] = $tokenId;
    	$token = json_encode($token);
    	$token = base64_encode($token);

    	return $token;
    }

    # remote
    # if return value is empty. then validation failed
    private function getTokenData($token){

    	$token = base64_decode($token);
    	if(empty($token)) return [];

    	$token = json_decode($token, true);
    	if(empty($token)) return [];

    	$presetTokenId = $token['tokenId'];
    	unset($token['tokenId']);

    	$tokenDataJson = json_encode($token);
    	$tokenId = hash('sha256', $tokenDataJson, Configure::consume('Security.salt'));

    	if($presetTokenId!=$tokenId) return [];

    	return $token;
    }

    # remote
    public function testSetToken(){
    	pr($this->setToken([
    		'id'=>12,
    		'username'=>'api_kla',
    		'role'=>'api',
    	]));
    	exit();
    }

    # remote
    public function testGetTokenData(){
    	$token = $this->setToken([
    		'id'=>12,
    		'username'=>'api_kla',
    		'role'=>'api',
    	]);

    	if(empty($this->getTokenData($token))){
    		echo "TestFailed";
    	}else{
    		echo "TestPassed";
    	}

    	exit();
    }
}

<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Configure;

class TransactionLibComponent extends Component { 

	public $transaction = null; // Stores the transaction object
	public $transactionType = null; // Stores the transactionType object
	public $errorMsg = ''; // Stores the last error message
	public $Transactions = null; // Stores the Transactions Table Object

	public function initialize(array $config)
    {
        $this->Controller = $this->_registry->getController();

        if(empty($this->Controller->Transactions)){
        	$this->Controller->loadModel('Transactions');
    	}

    	$this->Transactions = $this->Controller->Transactions;
    }
	
	public function add($data, $transactionTypeId=null)
    {
    	$this->request->data = $data;

    	if(!empty($this->request->data['transaction_type_id'])){
    		$transactionTypeId = $this->request->data['transaction_type_id'];
    	}

    	if(empty($this->transaction)){
        	$transaction = $this->Transactions->newEntity();
    	}else{
    		$transaction = $this->transaction;
    	}

    	if(empty($this->transactionType)){
        	$transactionType = $this->Transactions->TransactionTypes
        		->find()
        		->where(['TransactionTypes.id'=>$transactionTypeId])
        		->contain(['CommissionStructures'])
        		->first();
    	}else{
    		$transactionType = $this->transactionType;
    	}

        $this->request->data['offline'] = (int) Configure::read('offline');

        if($this->request->data['offline']){
        	$this->request->data['offline_form_data'] = json_encode($this->request->data);
        }

        $this->request->data['transaction_type_id'] = $transactionTypeId;


        if (empty($this->request->data['created_by_id'])) {
            $this->request->data['created_by_id'] = $this->Controller->Auth->User('id');
        }

        if (empty($this->request->data['system_comment'])) {
            $this->request->data['system_comment'] = '';
        }

        if (empty($this->request->data['quantity'])) {
            $this->request->data['quantity'] = 1;
        }

        if (empty($this->request->data['amount'])) {
            $this->request->data['amount'] = $this->request->data['value'];
        }


        // Handle custome data fields
        $commission = null;
        if (isset($this->request->data['custom_fields'])) {
            if (isset($this->request->data['custom_fields']['commission']) && !empty($this->request->data['custom_fields']['commission'])) {
                $commission = $this->request->data['custom_fields']['commission'];

                if (empty($transactionType->commission_structure_id)) {
                    $this->errorMsg = 'Please create a transaction type to handle commissions.';
                    return false;
                }

            }

            if (isset($this->request->data['custom_fields']['reference_code']) && !empty($this->request->data['custom_fields']['reference_code'])) {
                $this->request->data['reference_code'] = $this->request->data['custom_fields']['reference_code'];
            }

            $custom_fields = $this->request->data['custom_fields'];
            $this->request->data['custom_fields'] = json_encode($this->request->data['custom_fields']);
        }else{
            $this->request->data['custom_fields'] = json_encode([]);
        }

        //Make sure that the commission is input before continuing if set as required
        if($this->Controller->appSettings->require_commission_input){
            if (!empty($transactionType->commission_structure_id) && empty($custom_fields['commission'])) {
                $this->errorMsg = 'Transaction requires commission';
                return false;
            }
        }

        $this->request->data['value'] = $this->request->data['quantity']*$this->request->data['amount'];

        if (empty($commission) && !empty($transactionType->commission_structure)) {
            if(!empty($this->request->data['commission_structure_id'])){
                
                // Make sure that when a commissionStructure is selected and its transactionType
                // is of a different curency compared to the default transactionType for the default
                // commissionStructure, A commission amount is required/provided by force
                if(!$this->Controller->appSettings->require_commission_input){
                    $commission_structure = $this->Transactions->TransactionTypes->CommissionStructures->get(
                        $this->request->data['commission_structure_id'],
                        [
                            'contain'=>['TransactionTypes'],
                            'fields'=>[
                                'CommissionStructures.id',
                                'CommissionStructures.name',
                                'CommissionStructures.transaction_type_id',
                                'CommissionStructures.pricing_structure',
                                'TransactionTypes.currency_id',
                            ]
                        ]
                    );
                    if($commission_structure->transaction_type->currency_id!=$transactionType->currency_id){
                        if (!empty($transactionType->commission_structure_id) && empty($custom_fields['commission'])) {
                            $this->errorMsg = 'Transaction requires commission';
                            return false;
                        }
                    }
                }else{
                    $commission_structure = $this->Transactions->TransactionTypes->CommissionStructures->get(
                        $this->request->data['commission_structure_id']
                    );
                }

                $commission = $this->Transactions->getCommission(
                    $this->request->data['value'],$commission_structure
                );
            }else{
                $commission = $this->Transactions->getCommission(
                    $this->request->data['value'],$transactionType->commission_structure
                );
            }
        }
        $this->request->data['commission'] = $commission;
		
		// In-case they are sending money, lets use an AutoIncremented transaction_reference
		// to_branch_id should not equal to from_branch_id and it should be a credit transaction
		$sending = false;
		if($transactionType->to_branch_id!=$transactionType->from_branch_id && $transactionType->balance_sheet_side){
			$sending = true;
			//transaction_reference
			if($transactionType->balance_sheet_side){
				$currentTransactionReference = $this->Transactions->TransactionTypes->Branches->get($transactionType->to_branch_id);
				$this->request->data['reference_code'] = $transactionType->from_branch_id . '-' . $transactionType->to_branch_id . '-' . ($currentTransactionReference->transaction_reference + 1);
							
			}else{
				$currentTransactionReference = $this->Transactions->TransactionTypes->Branches->get($transactionType->from_branch_id);
				$this->request->data['reference_code'] = $transactionType->from_branch_id . '-' . $transactionType->to_branch_id . '-' . ($currentTransactionReference->transaction_reference_for_receiving + 1);
							
			}

		}elseif(empty($this->request->data('reference_code'))){
			$this->request->data['reference_code'] = $transactionType->from_branch_id . '-' . $transactionType->to_branch_id . '-' . time();
		}

        if (!empty($_GET['customer_id'])) {
            $this->request->data['customer_id'] = $_GET['customer_id'];
        }

        
        if (empty($this->request->data['created_by_id'])) {
            $this->request->data['created_by_id'] = $this->Controller->Auth->User('id');
        }

        // Transactions in their final states at the time of creation need to indicate who completed the transaction.
        if($transactionType->transaction_status_id == $this->Controller->appSettings->final_transaction_status_id){
        	if(!empty($this->Controller->Auth->User('id'))){
            	$this->request->data['completed_by_id'] = $this->Controller->Auth->User('id');
        	}
            if(in_array($this->Controller->Auth->User('role'), ['super_admin','admin','manager'])){
                if(!empty($this->request->data('target_user_id'))){
                    $this->request->data['completed_by_id'] = $this->request->data['target_user_id'];
                }
            }
        }


        // Save transaction
        $transaction = $this->Transactions->patchEntity($transaction, $this->request->data);
        
        if(!empty($this->request->data['id'])){
        	if(!empty($this->request->data['completed_by_id'])){
        		$transaction->completed_by_id = $this->request->data['completed_by_id'];
        	}
        }

        if(!empty($this->request->data['commission_structure_id'])){
            $transaction->commission_structure_id = $this->request->data['commission_structure_id'];
        }

        $transaction->currency_id = $transactionType->currency_id;
        $transaction->branch_id = $transactionType->branch_id;
        $transaction->from_branch_id = $transactionType->from_branch_id;
        $transaction->to_branch_id = $transactionType->to_branch_id;

        if(empty($transaction->transaction_status_id)){
        	//Use the default set
        	$transaction->transaction_status_id = $transactionType->transaction_status_id;
    	}
        $transaction->balance_sheet_side = $transactionType->balance_sheet_side;
        $transaction->from_account_id = $transactionType->from_account_id;
        $transaction->to_account_id = $transactionType->to_account_id;

        if(empty($transaction->completor_branch_id)){
        	$transaction->completor_branch_id = $transactionType->to_branch_id;
    	}

        $transaction = $this->Transactions->save($transaction);
        $this->transaction = $transaction;


        if ($transaction) {
			
			if($transactionType->to_branch_id!=$transactionType->from_branch_id){
				if($transactionType->balance_sheet_side){
					$this->Transactions->TransactionTypes->Branches->query()
					->update()
					->set([
						'transaction_reference=transaction_reference+1'
					])
					->where([
						'Branches.id' => $transactionType->to_branch_id
					])
					->execute();					
				}else{
					$this->Transactions->TransactionTypes->Branches->query()
					->update()
					->set([
						'transaction_reference_for_receiving=transaction_reference_for_receiving+1'
					])
					->where([
						'Branches.id' => $transactionType->from_branch_id
					])
					->execute();					
				}

			}
			
			return true;
        } else {
        	$this->errorMsg = 'The transaction could not be saved. Please, try again.';
        	return false;
        }
    }

}
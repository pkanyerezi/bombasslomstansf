<?php
namespace App\Model\Table;

use App\Model\Entity\Transaction;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Transactions Model
 *
 * @property \Cake\ORM\Association\BelongsTo $TransactionTypes
 */
class TransactionsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('transactions');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('TransactionTypes', [
            'foreignKey' => 'transaction_type_id',
            // 'joinType' => 'INNER'
        ]);

        $this->belongsTo('TransactionStatuses', [
            'foreignKey' => 'transaction_status_id',
            // 'joinType' => 'INNER'
        ]);

        $this->belongsTo('Customers', [
            'foreignKey' => 'customer_id',
            // 'joinType' => 'INNER'
        ]);

        $this->belongsTo('Currencies', [
            'foreignKey' => 'currency_id',
            // 'joinType' => 'INNER'
        ]);

        $this->belongsTo('Branches', [
            'foreignKey' => 'branch_id',
            // 'joinType' => 'INNER'
        ]);

        $this->belongsTo('ToBranches', [
            'foreignKey' => 'to_branch_id',
            // 'joinType' => 'INNER'
        ]);

        $this->belongsTo('FromBranches', [
            'foreignKey' => 'from_branch_id',
            // 'joinType' => 'INNER'
        ]);

        $this->belongsTo('ToAccounts', [
            'foreignKey' => 'to_branch_id',
            // 'joinType' => 'INNER'
        ]);

        $this->belongsTo('FromAccounts', [
            'foreignKey' => 'from_branch_id',
            // 'joinType' => 'INNER'
        ]);

        $this->belongsTo('CreatedBy', [
            'foreignKey' => 'created_by_id',
            'joinType' => 'INNER',
        ]);

        $this->belongsTo('ModifiedBy', [
            'foreignKey' => 'modified_by_id',
            // 'joinType' => 'INNER'
        ]);

        $this->belongsTo('CompletedBy', [
            'foreignKey' => 'completed_by_id',
            // 'joinType' => 'INNER'
        ]);

        $this->belongsTo('TargetUser', [
            'foreignKey' => 'target_user_id',
            // 'joinType' => 'INNER'
        ]);

        $this->hasMany('ParentTransactions', [
            'foreignKey' => 'parent_transaction_id',
            'dependent' => true, 
            'cascadeCallbacks' => true,
            'joinType' => 'LEFT' // LEFT join will return even results withough parent Transaction IDs
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->integer('parent_transaction')
            ->allowEmpty('parent_transaction', 'create');

        $validator
            ->numeric('quantity')
            ->requirePresence('quantity', 'create')
            ->notEmpty('quantity');

        $validator
            ->numeric('amount')
            ->requirePresence('amount', 'create')
            ->notEmpty('amount');

        $validator
            ->numeric('value')
            ->requirePresence('value', 'create')
            ->notEmpty('value');

        $validator
            ->numeric('commission')
            ->allowEmpty('commission');

        $validator
            ->allowEmpty('system_comment');

        $validator
            ->allowEmpty('user_comment');

        $validator
            ->integer('created_by')
            ->allowEmpty('created_by');

        $validator
            ->integer('modified_by')
            ->allowEmpty('modified_by');

        $validator
            ->allowEmpty('custom_fields');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['transaction_type_id'], 'TransactionTypes'));
        return $rules;
    }

    public function afterSave($event, $entity, $options)
    {
        // Handle custome data fields
        @$custom_fields = json_decode($entity->custom_fields);

        // Get the transaction Type
        if (!empty($entity->transaction_type_id)) {
            $transactionType = $this->TransactionTypes->get($entity->transaction_type_id,[
                'fields'=>[
                    'TransactionTypes.commission_structure_id',
                    'TransactionTypes.name',
                    'TransactionTypes.linked_transaction_type_id',
                    'TransactionTypes.to_branch_id'
                ]
            ]);

            // This is set when the commission structure to be used 
            // is manually selected from the transaction/add form
            if(!empty($entity->commission_structure_id)){
                $transactionType->commission_structure_id = $entity->commission_structure_id;
            }

            if (!empty($transactionType->commission_structure_id)) {
                
                $commission_structure_id = $transactionType->commission_structure_id;
                if(!empty($entity->commission_structure_id)){
                    $commission_structure_id = $entity->commission_structure_id;
                }
                
                
                // TransactionTypes
                $commission_structure = $this->TransactionTypes->CommissionStructures->get($commission_structure_id,[
                    'fields'=>[
                        'CommissionStructures.id',
                        'CommissionStructures.pricing_structure',
                        'CommissionStructures.transaction_type_id',
                    ]
                ]);

                // Get the commission amount
                $commission = $this->getCommission($entity->value, $commission_structure, $custom_fields);
                $entity->commission = $commission;
				
				if($custom_fields->commission > 0){
            
					// Create the commission transaction
					$commissionTransactionData = [
						'parent_transaction_id'=>$entity->id,
						'transaction_type_id'=>$commission_structure->transaction_type_id,
						'transaction_status_id'=>$entity->transaction_status_id,
						'quantity'=>1,
						'amount'=>$entity->commission,
						'value'=>$entity->commission,
						'date'=>$entity->date,
						'created_by'=>$entity->created_by,
						'system_comment'=>'Commission for ' . $entity->value . ' on ' . $transactionType->name
					];

					if (isset($entity->customer_id) && !empty($entity->customer_id)) {
						$commissionTransactionData['customer_id'] = $entity->customer_id;
					}

					// Get the transaction Type that is affecting the Commission
					$commission_structure_ttype = $this->TransactionTypes->get($commission_structure->transaction_type_id);

					$commissionTransactionData['reference_code'] = $entity->reference_code;
					$commissionTransactionData['from_branch_id'] = $commission_structure_ttype->from_branch_id;
					$commissionTransactionData['to_branch_id'] = $commission_structure_ttype->to_branch_id;
					$commissionTransactionData['from_account_id'] = $commission_structure_ttype->from_account_id;
					$commissionTransactionData['to_account_id'] = $commission_structure_ttype->to_account_id;
					$commissionTransactionData['currency_id'] = $commission_structure_ttype->currency_id;
					$commissionTransactionData['branch_id'] = $commission_structure_ttype->branch_id;
					$commissionTransactionData['created_by_id'] = $entity->created_by_id;
					$commissionTransactionData['modified_by_id'] = $entity->modified_by_id;
					if(!empty($entity->completed_by_id)){
						 $commissionTransactionData['completed_by_id'] = $entity->completed_by_id;
					}               
					$commissionTransactionData['completor_branch_id'] = $transactionType->to_branch_id;

					// Save the commission transaction
					if($entity->isNew()){
						$commissionTransaction = $this->newEntity();
					}else{
						$commissionTransaction = $this->find()->where([
							'Transactions.parent_transaction_id'=>$entity->id,
							'Transactions.transaction_type_id'=>$commission_structure->transaction_type_id
						])->first();
					}

					// pr($commissionTransactionData);exit();

					$commissionTransaction = $this->patchEntity($commissionTransaction, $commissionTransactionData);

					if (!$this->save($commissionTransaction)) {
						if($entity->isNew()){
							echo "string1";
							$this->delete($entity);
							exit();
						}
						// echo "string2";
						// exit();
						return false;
					}
				}
            }

            if (!empty($transactionType->linked_transaction_type_id)) {
            // if (0) {
                // TransactionTypes
                $linkedTransactionType = $this->TransactionTypes->get($transactionType->linked_transaction_type_id);

                // Create the commission transaction
                $linkedTransactionData = [
                    'parent_transaction_id'=>$entity->id,
                    'transaction_type_id'=>$transactionType->linked_transaction_type_id,
                    'transaction_status_id'=>$entity->transaction_status_id,
                    'quantity'=>$entity->quantity,
                    'amount'=>$entity->amount,
                    'value'=>$entity->value,
                    'date'=>$entity->date,
                    'created_by'=>$entity->created_by,
                    'system_comment'=>'Linked transaction for ' . $entity->value . ' on ' . $transactionType->name
                ];

                if (isset($entity->customer_id) && !empty($entity->customer_id)) {
                    $linkedTransactionData['customer_id'] = $entity->customer_id;
                }

                $linkedTransactionData['from_branch_id'] = $linkedTransactionType->from_branch_id;
                $linkedTransactionData['to_branch_id'] = $linkedTransactionType->to_branch_id;
                $linkedTransactionData['branch_id'] = $linkedTransactionType->branch_id;
                $linkedTransactionData['currency_id'] = $linkedTransactionType->currency_id;
                $linkedTransactionData['from_account_id'] = $linkedTransactionType->from_account_id;
                $linkedTransactionData['to_account_id'] = $linkedTransactionType->to_account_id;
                $linkedTransactionData['balance_sheet_side'] = $linkedTransactionType->balance_sheet_side;
                
                $linkedTransactionData['transaction_status_id'] = $entity->transaction_status_id;
                $linkedTransactionData['reference_code'] = $entity->reference_code;
                $linkedTransactionData['created_by_id'] = $entity->created_by_id;
                $linkedTransactionData['modified_by_id'] = $entity->modified_by_id;
                $linkedTransactionData['completed_by_id'] = $entity->modified_by_id;
                if(!empty($entity->completed_by_id)){
                    $linkedTransactionData['completed_by_id'] = $entity->completed_by_id;
                }
                $linkedTransactionData['target_user_id'] = $entity->target_user_id;
                
                // Only the branch where the original transaction was intended to go should mark all the related transactions as complete
                $linkedTransactionData['completor_branch_id'] = $transactionType->to_branch_id;


                // Save the commission transaction
                if($entity->isNew()){
                    $linkedTransaction = $this->newEntity();
                }else{
                    // pr($entity);exit();
                    $linkedTransaction = $this->find()->where([
                        'Transactions.parent_transaction_id'=>$entity->id,
                        'Transactions.transaction_type_id'=>$transactionType->linked_transaction_type_id
                        // 'Transactions.transaction_type_id'=>$entity->linked_transaction_type_id
                    ])->first();
                }

                $linkedTransaction = $this->patchEntity($linkedTransaction, $linkedTransactionData);
                // $linkedTransaction->from_account_id = $linkedTransactionType->from_account_id;
                // $linkedTransaction->to_account_id = $linkedTransactionType->to_account_id;
                if ($this->save($linkedTransaction)) {
                    return true;
                }else{
                    if($entity->isNew()){
                        $this->delete($entity);
                    }
                    return false;
                }
            }
        }
    }

    // This simply returns the commission amount of the transaction amount given
    public function getCommission($amount,$commission_structure,$custom_fields=null){

        if(isset($custom_fields->commission) && !empty($custom_fields->commission)){
            return $custom_fields->commission;
        }

        if (empty($amount)) {
            return 0;
        }

        @$pricing_structure = json_decode($commission_structure->pricing_structure,true);
        if (isset($pricing_structure['min_price'])) {
            $counter=0;
            foreach($pricing_structure['min_price'] as $val){
                if ($amount>=$pricing_structure['min_price'][$counter] && $amount<=$pricing_structure['max_price'][$counter]) {
                    $ppCommAmount   = $pricing_structure['comm_amount'][$counter];
                    $ppCommPerc     = $pricing_structure['comm_perc'][$counter];
                    return ($ppCommAmount + round((($amount*$ppCommPerc)/100),4));
                }
                $counter++;
            }
            $amount = 0;
        }
        return $amount;
    }

    // This will update the status of the transactions related to this transaction
    public function completeRelatedTransactions($entity){
		return $this->changeRelatedTransactionsStatus($entity);
	}
	
	public function cancelRelatedTransactions($entity){
		return $this->changeRelatedTransactionsStatus($entity);
	}
	// update `transactions` set `transaction_status_id`=6
	public function changeRelatedTransactionsStatus($entity){
		$resp = $this->query()
        ->update()
        ->set([
            'transaction_status_id=' . $entity->transaction_status_id,
            'completed_by_id='.$entity->completed_by_id
        ])
        ->where([
            'OR'=>[
                'id'=>$entity->parent_transaction_id,
                'parent_transaction_id'=>$entity->parent_transaction_id
            ]
        ])
        ->execute();

        if(!$resp->rowCount()){
            return false;
        }

        return true;
	}
}

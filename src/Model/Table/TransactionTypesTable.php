<?php
namespace App\Model\Table;

use App\Model\Entity\TransactionType;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * TransactionTypes Model
 *
 * @property \Cake\ORM\Association\BelongsTo $FromAccounts
 * @property \Cake\ORM\Association\BelongsTo $ToAccounts
 * @property \Cake\ORM\Association\BelongsTo $CommissionStructures
 * @property \Cake\ORM\Association\BelongsTo $LinkedTransactionTypes
 * @property \Cake\ORM\Association\HasMany $CommissionStructures
 * @property \Cake\ORM\Association\HasMany $Transactions
 */
class TransactionTypesTable extends Table
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

        $this->table('transaction_types');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('FromAccounts', [
            'foreignKey' => 'from_account_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('ToAccounts', [
            'foreignKey' => 'to_account_id',
            'joinType' => 'INNER'
        ]);

        $this->belongsTo('FromBranches', [
            'foreignKey' => 'from_branch_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Branches', [
            'foreignKey' => 'branch_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('ToBranches', [
            'foreignKey' => 'to_branch_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Currencies', [
            'foreignKey' => 'currency_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('TransactionStatuses', [
            'foreignKey' => 'transaction_status_id',
            'joinType' => 'INNER'
        ]);            
        $this->belongsTo('AccountTypes', [
            'foreignKey' => 'account_type_id'
        ]);
        $this->belongsTo('CommissionStructures', [
            'foreignKey' => 'commission_structure_id'
        ]);
        $this->belongsTo('LinkedTransactionTypes', [
            'foreignKey' => 'linked_transaction_type_id'
        ]);
        /*$this->hasMany('CommissionStructures', [
            'foreignKey' => 'transaction_type_id'
        ]);*/
        $this->hasMany('Transactions', [
            'foreignKey' => 'transaction_type_id'
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
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->requirePresence('description', 'create')
            ->notEmpty('description');

        $validator
            ->allowEmpty('custom_fields');

        $validator
            ->boolean('add_to_menu')
            ->requirePresence('add_to_menu', 'create')
            ->notEmpty('add_to_menu');

        $validator
            ->allowEmpty('menu_link_title');

        $validator
            ->boolean('balance_sheet_side')
            ->requirePresence('balance_sheet_side', 'create')
            ->notEmpty('balance_sheet_side');

        $validator
            ->boolean('quantitized')
            ->requirePresence('quantitized', 'create')
            ->notEmpty('quantitized');

        $validator
            ->boolean('enabled')
            ->requirePresence('enabled', 'create')
            ->notEmpty('enabled');

        $validator
            ->integer('priority')
            ->requirePresence('priority', 'create')
            ->notEmpty('priority');

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
        $rules->add($rules->existsIn(['from_account_id'], 'FromAccounts'));
        $rules->add($rules->existsIn(['to_account_id'], 'ToAccounts'));
        $rules->add($rules->existsIn(['commission_structure_id'], 'CommissionStructures'));
        $rules->add($rules->existsIn(['linked_transaction_type_id'], 'LinkedTransactionTypes'));
        return $rules;
    }
}

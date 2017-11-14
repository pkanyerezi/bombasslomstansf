<?php
namespace App\Model\Table;

use App\Model\Entity\TransactionType;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * LinkedTransactionTypesTable Model
 *
 * @property \Cake\ORM\Association\BelongsTo $FromAccounts
 * @property \Cake\ORM\Association\BelongsTo $ToAccounts
 * @property \Cake\ORM\Association\BelongsTo $CommissionStructures
 * @property \Cake\ORM\Association\BelongsTo $Roles
 * @property \Cake\ORM\Association\BelongsTo $LinkedTransactionTypes
 * @property \Cake\ORM\Association\HasMany $Transactions
 */
class LinkedTransactionTypesTable extends Table
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
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('ToAccounts', [
            'foreignKey' => 'to_account_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('CommissionStructures', [
            'foreignKey' => 'commission_structure_id'
        ]);
        $this->belongsTo('LinkedTransactionTypes', [
            'foreignKey' => 'linked_transaction_type_id',
            'joinTable' => 'transaction_types'
        ]);
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

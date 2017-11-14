<?php
namespace App\Model\Table;

use App\Model\Entity\Account;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Accounts Model
 *
 */
class AccountsTable extends Table
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

        $this->table('accounts');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('AccountTypes', [
            'foreignKey' => 'account_type_id',
            'joinType' => 'INNER'
        ]);

        $this->belongsTo('Currencies', [
            'foreignKey' => 'currency_id',
            'joinType' => 'INNER'
        ]);

        $this->belongsTo('Branches', [
            'foreignKey' => 'branch_id',
            'joinType' => 'INNER'
        ]);

        $this->hasMany('TransactionTypes', [
            'foreignKey' => 'transaction_type_id',
            //'dependent' => true, 
            'joinType' => 'LEFT' // LEFT join will return even results without parent Transaction IDs
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
            ->boolean('enabled')
            ->requirePresence('enabled', 'create')
            ->notEmpty('enabled');

        return $validator;
    }
}

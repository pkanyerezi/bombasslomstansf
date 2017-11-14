<?php
namespace App\Model\Table;

use App\Model\Entity\AppSetting;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AppSettings Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Branches
 * @property \Cake\ORM\Association\BelongsTo $CustomerAccountTypes
 */
class AppSettingsTable extends Table
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

        $this->table('app_settings');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('Branches', [
            'foreignKey' => 'branch_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('CustomerAccountTypes', [
            'foreignKey' => 'customer_account_type_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('FinalTransactionStatuses', [
            'foreignKey' => 'final_transaction_status_id',
            'joinType' => 'INNER'
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
        $rules->add($rules->existsIn(['branch_id'], 'Branches'));
        $rules->add($rules->existsIn(['customer_account_type_id'], 'CustomerAccountTypes'));
        return $rules;
    }
}

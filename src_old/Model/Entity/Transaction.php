<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Transaction Entity.
 *
 * @property int $id
 * @property int $transaction_type_id
 * @property \App\Model\Entity\TransactionType $transaction_type
 * @property float $quantity
 * @property float $amount
 * @property float $value
 * @property float $commission
 * @property string $system_comment
 * @property string $user_comment
 * @property \Cake\I18n\Time $created
 * @property int $created_by
 * @property \Cake\I18n\Time $modified
 * @property int $modified_by
 * @property string $custom_fields
 */
class Transaction extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];
}

<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AppSetting Entity.
 *
 * @property int $id
 * @property int $branch_id
 * @property \App\Model\Entity\Branch $branch
 * @property int $customer_account_type_id
 * @property \App\Model\Entity\CustomerAccountType $customer_account_type
 */
class AppSetting extends Entity
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

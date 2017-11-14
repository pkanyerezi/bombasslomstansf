<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * TransactionType Entity.
 *
 * @property int $id
 * @property int $from_account_id
 * @property \App\Model\Entity\FromAccount $from_account
 * @property int $to_account_id
 * @property \App\Model\Entity\ToAccount $to_account
 * @property string $name
 * @property string $description
 * @property string $custom_fields
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 * @property bool $add_to_menu
 * @property string $menu_link_title
 * @property bool $balance_sheet_side
 * @property bool $quantitized
 * @property bool $enabled
 * @property int $commission_structure_id
 * @property int $linked_transaction_type_id
 * @property \App\Model\Entity\LinkedTransactionType $linked_transaction_type
 * @property int $priority
 * @property \App\Model\Entity\CommissionStructure[] $commission_structures
 * @property \App\Model\Entity\Transaction[] $transactions
 */
class TransactionType extends Entity
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

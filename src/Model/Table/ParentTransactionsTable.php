<?php
namespace App\Model\Table;

use App\Model\Entity\Transaction;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ParentTransactions Model
 *
 * @property \Cake\ORM\Association\BelongsTo $ParentTransactions
 */
class ParentTransactionsTable extends TransactionsTable
{

}

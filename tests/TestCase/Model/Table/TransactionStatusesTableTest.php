<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TransactionStatusesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TransactionStatusesTable Test Case
 */
class TransactionStatusesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\TransactionStatusesTable
     */
    public $TransactionStatuses;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.transaction_statuses',
        'app.transactions',
        'app.transaction_types',
        'app.from_accounts',
        'app.to_accounts',
        'app.commission_structures',
        'app.linked_transaction_types',
        'app.parent_transactions'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('TransactionStatuses') ? [] : ['className' => 'App\Model\Table\TransactionStatusesTable'];
        $this->TransactionStatuses = TableRegistry::get('TransactionStatuses', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->TransactionStatuses);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}

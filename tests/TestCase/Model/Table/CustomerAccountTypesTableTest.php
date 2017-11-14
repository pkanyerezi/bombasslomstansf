<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CustomerAccountTypesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CustomerAccountTypesTable Test Case
 */
class CustomerAccountTypesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CustomerAccountTypesTable
     */
    public $CustomerAccountTypes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.customer_account_types',
        'app.accounts',
        'app.account_types',
        'app.transaction_types',
        'app.from_accounts',
        'app.to_accounts',
        'app.from_branches',
        'app.users',
        'app.branches',
        'app.roles',
        'app.roles_users',
        'app.to_branches',
        'app.commission_structures',
        'app.linked_transaction_types',
        'app.transactions',
        'app.transaction_statuses',
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
        $config = TableRegistry::exists('CustomerAccountTypes') ? [] : ['className' => 'App\Model\Table\CustomerAccountTypesTable'];
        $this->CustomerAccountTypes = TableRegistry::get('CustomerAccountTypes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CustomerAccountTypes);

        parent::tearDown();
    }

    /**
     * Test initial setup
     *
     * @return void
     */
    public function testInitialization()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}

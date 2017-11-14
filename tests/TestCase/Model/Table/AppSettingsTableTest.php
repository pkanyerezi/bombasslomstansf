<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AppSettingsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AppSettingsTable Test Case
 */
class AppSettingsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\AppSettingsTable
     */
    public $AppSettings;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.app_settings',
        'app.branches',
        'app.users',
        'app.roles',
        'app.transaction_types',
        'app.from_accounts',
        'app.account_types',
        'app.accounts',
        'app.to_accounts',
        'app.from_branches',
        'app.to_branches',
        'app.commission_structures',
        'app.linked_transaction_types',
        'app.transactions',
        'app.transaction_statuses',
        'app.parent_transactions',
        'app.roles_users',
        'app.customer_account_types'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('AppSettings') ? [] : ['className' => 'App\Model\Table\AppSettingsTable'];
        $this->AppSettings = TableRegistry::get('AppSettings', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->AppSettings);

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

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}

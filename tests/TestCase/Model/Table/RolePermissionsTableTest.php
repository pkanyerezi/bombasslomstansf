<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\RolePermissionsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\RolePermissionsTable Test Case
 */
class RolePermissionsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\RolePermissionsTable
     */
    public $RolePermissions;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.role_permissions',
        'app.roles',
        'app.transaction_types',
        'app.from_accounts',
        'app.account_types',
        'app.accounts',
        'app.to_accounts',
        'app.from_branches',
        'app.users',
        'app.branches',
        'app.roles_users',
        'app.to_branches',
        'app.commission_structures',
        'app.linked_transaction_types',
        'app.transactions',
        'app.transaction_statuses',
        'app.customers',
        'app.created_by',
        'app.modified_by',
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
        $config = TableRegistry::exists('RolePermissions') ? [] : ['className' => 'App\Model\Table\RolePermissionsTable'];
        $this->RolePermissions = TableRegistry::get('RolePermissions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->RolePermissions);

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

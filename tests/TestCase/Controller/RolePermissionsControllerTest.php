<?php
namespace App\Test\TestCase\Controller;

use App\Controller\RolePermissionsController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\RolePermissionsController Test Case
 */
class RolePermissionsControllerTest extends IntegrationTestCase
{

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
        'app.created_by',
        'app.modified_by',
        'app.to_branches',
        'app.commission_structures',
        'app.linked_transaction_types',
        'app.transactions',
        'app.transaction_statuses',
        'app.customers',
        'app.parent_transactions'
    ];

    /**
     * Test index method
     *
     * @return void
     */
    public function testIndex()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test view method
     *
     * @return void
     */
    public function testView()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test add method
     *
     * @return void
     */
    public function testAdd()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test edit method
     *
     * @return void
     */
    public function testEdit()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete method
     *
     * @return void
     */
    public function testDelete()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}

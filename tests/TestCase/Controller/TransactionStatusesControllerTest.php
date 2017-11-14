<?php
namespace App\Test\TestCase\Controller;

use App\Controller\TransactionStatusesController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\TransactionStatusesController Test Case
 */
class TransactionStatusesControllerTest extends IntegrationTestCase
{

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

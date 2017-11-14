<?php
namespace App\Test\TestCase\Controller;

use App\Controller\ExpensesController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\ExpensesController Test Case
 */
class ExpensesControllerTest extends IntegrationTestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.expenses',
        'app.users',
        'app.deliveries',
        'app.drivers',
        'app.fuel_track_downs',
        'app.performances',
        'app.truck_expenses',
        'app.trucks',
        'app.petty_cash_transactions',
        'app.stock_pile_transactions',
        'app.items'
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

<?php
namespace App\Test\TestCase\Controller;

use App\Controller\EmployeeAllowancesController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\EmployeeAllowancesController Test Case
 */
class EmployeeAllowancesControllerTest extends IntegrationTestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.employee_allowances',
        'app.users',
        'app.deliveries',
        'app.fuel_track_downs',
        'app.trucks',
        'app.performances',
        'app.drivers',
        'app.truck_expenses',
        'app.petrol_stations',
        'app.expenses',
        'app.items',
        'app.petty_cash_transactions',
        'app.stock_pile_transactions',
        'app.employees'
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

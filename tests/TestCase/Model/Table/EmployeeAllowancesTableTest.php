<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\EmployeeAllowancesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\EmployeeAllowancesTable Test Case
 */
class EmployeeAllowancesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\EmployeeAllowancesTable
     */
    public $EmployeeAllowances;

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
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('EmployeeAllowances') ? [] : ['className' => 'App\Model\Table\EmployeeAllowancesTable'];
        $this->EmployeeAllowances = TableRegistry::get('EmployeeAllowances', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EmployeeAllowances);

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

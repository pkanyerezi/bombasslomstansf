<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TruckExpensesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TruckExpensesTable Test Case
 */
class TruckExpensesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\TruckExpensesTable
     */
    public $TruckExpenses;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.truck_expenses',
        'app.users',
        'app.deliveries',
        'app.drivers',
        'app.fuel_track_downs',
        'app.trucks',
        'app.petrol_stations',
        'app.performances',
        'app.expenses',
        'app.items',
        'app.petty_cash_transactions',
        'app.stock_pile_transactions'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('TruckExpenses') ? [] : ['className' => 'App\Model\Table\TruckExpensesTable'];
        $this->TruckExpenses = TableRegistry::get('TruckExpenses', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->TruckExpenses);

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

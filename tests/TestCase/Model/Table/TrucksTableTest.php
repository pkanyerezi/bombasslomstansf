<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TrucksTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TrucksTable Test Case
 */
class TrucksTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\TrucksTable
     */
    public $Trucks;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.trucks',
        'app.fuel_track_downs',
        'app.users',
        'app.deliveries',
        'app.drivers',
        'app.expenses',
        'app.items',
        'app.performances',
        'app.petty_cash_transactions',
        'app.stock_pile_transactions',
        'app.truck_expenses',
        'app.petrol_stations'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Trucks') ? [] : ['className' => 'App\Model\Table\TrucksTable'];
        $this->Trucks = TableRegistry::get('Trucks', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Trucks);

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

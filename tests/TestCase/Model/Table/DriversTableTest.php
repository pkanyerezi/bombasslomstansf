<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DriversTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DriversTable Test Case
 */
class DriversTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\DriversTable
     */
    public $Drivers;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.drivers',
        'app.fuel_track_downs',
        'app.users',
        'app.deliveries',
        'app.trucks',
        'app.performances',
        'app.truck_expenses',
        'app.expenses',
        'app.items',
        'app.petty_cash_transactions',
        'app.stock_pile_transactions',
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
        $config = TableRegistry::exists('Drivers') ? [] : ['className' => 'App\Model\Table\DriversTable'];
        $this->Drivers = TableRegistry::get('Drivers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Drivers);

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

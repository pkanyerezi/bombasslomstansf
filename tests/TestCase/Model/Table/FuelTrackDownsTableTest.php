<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FuelTrackDownsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FuelTrackDownsTable Test Case
 */
class FuelTrackDownsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\FuelTrackDownsTable
     */
    public $FuelTrackDowns;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.fuel_track_downs',
        'app.users',
        'app.deliveries',
        'app.drivers',
        'app.performances',
        'app.truck_expenses',
        'app.trucks',
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
        $config = TableRegistry::exists('FuelTrackDowns') ? [] : ['className' => 'App\Model\Table\FuelTrackDownsTable'];
        $this->FuelTrackDowns = TableRegistry::get('FuelTrackDowns', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->FuelTrackDowns);

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

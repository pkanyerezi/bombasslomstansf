<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PetrolStationsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PetrolStationsTable Test Case
 */
class PetrolStationsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\PetrolStationsTable
     */
    public $PetrolStations;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.petrol_stations',
        'app.fuel_track_downs',
        'app.users',
        'app.deliveries',
        'app.drivers',
        'app.performances',
        'app.trucks',
        'app.truck_expenses',
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
        $config = TableRegistry::exists('PetrolStations') ? [] : ['className' => 'App\Model\Table\PetrolStationsTable'];
        $this->PetrolStations = TableRegistry::get('PetrolStations', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->PetrolStations);

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

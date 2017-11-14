<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TyresTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TyresTable Test Case
 */
class TyresTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\TyresTable
     */
    public $Tyres;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.tyres',
        'app.trucks',
        'app.fuel_track_downs',
        'app.users',
        'app.deliveries',
        'app.expenses',
        'app.items',
        'app.performances',
        'app.drivers',
        'app.truck_expenses',
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
        $config = TableRegistry::exists('Tyres') ? [] : ['className' => 'App\Model\Table\TyresTable'];
        $this->Tyres = TableRegistry::get('Tyres', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Tyres);

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

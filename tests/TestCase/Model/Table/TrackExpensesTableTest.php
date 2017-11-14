<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TrackExpensesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TrackExpensesTable Test Case
 */
class TrackExpensesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\TrackExpensesTable
     */
    public $TrackExpenses;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.track_expenses',
        'app.users',
        'app.deliveries',
        'app.drivers',
        'app.fuel_track_downs',
        'app.tracks',
        'app.performances',
        'app.petrol_stations',
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
        $config = TableRegistry::exists('TrackExpenses') ? [] : ['className' => 'App\Model\Table\TrackExpensesTable'];
        $this->TrackExpenses = TableRegistry::get('TrackExpenses', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->TrackExpenses);

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

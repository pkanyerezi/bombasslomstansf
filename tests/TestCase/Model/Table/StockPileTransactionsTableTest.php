<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\StockPileTransactionsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\StockPileTransactionsTable Test Case
 */
class StockPileTransactionsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\StockPileTransactionsTable
     */
    public $StockPileTransactions;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.stock_pile_transactions',
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
        'app.petty_cash_transactions'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('StockPileTransactions') ? [] : ['className' => 'App\Model\Table\StockPileTransactionsTable'];
        $this->StockPileTransactions = TableRegistry::get('StockPileTransactions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->StockPileTransactions);

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

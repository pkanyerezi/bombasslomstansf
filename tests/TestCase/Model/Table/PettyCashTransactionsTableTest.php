<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PettyCashTransactionsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PettyCashTransactionsTable Test Case
 */
class PettyCashTransactionsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\PettyCashTransactionsTable
     */
    public $PettyCashTransactions;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.petty_cash_transactions',
        'app.users',
        'app.deliveries',
        'app.drivers',
        'app.fuel_track_downs',
        'app.trucks',
        'app.petrol_stations',
        'app.performances',
        'app.truck_expenses',
        'app.expenses',
        'app.items',
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
        $config = TableRegistry::exists('PettyCashTransactions') ? [] : ['className' => 'App\Model\Table\PettyCashTransactionsTable'];
        $this->PettyCashTransactions = TableRegistry::get('PettyCashTransactions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->PettyCashTransactions);

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

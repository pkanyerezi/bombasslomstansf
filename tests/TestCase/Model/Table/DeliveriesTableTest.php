<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DeliveriesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DeliveriesTable Test Case
 */
class DeliveriesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\DeliveriesTable
     */
    public $Deliveries;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.deliveries',
        'app.users',
        'app.expenses',
        'app.fuel_track_downs',
        'app.performances',
        'app.petty_cash_transactions',
        'app.stock_pile_transactions',
        'app.truck_expenses',
        'app.drivers',
        'app.trucks'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Deliveries') ? [] : ['className' => 'App\Model\Table\DeliveriesTable'];
        $this->Deliveries = TableRegistry::get('Deliveries', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Deliveries);

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

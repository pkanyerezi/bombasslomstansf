<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PurchaseOrdersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PurchaseOrdersTable Test Case
 */
class PurchaseOrdersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\PurchaseOrdersTable
     */
    public $PurchaseOrders;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.purchase_orders',
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
        $config = TableRegistry::exists('PurchaseOrders') ? [] : ['className' => 'App\Model\Table\PurchaseOrdersTable'];
        $this->PurchaseOrders = TableRegistry::get('PurchaseOrders', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->PurchaseOrders);

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

    /**
     * Test afterSave method
     *
     * @return void
     */
    public function testAfterSave()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test afterDelete method
     *
     * @return void
     */
    public function testAfterDelete()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}

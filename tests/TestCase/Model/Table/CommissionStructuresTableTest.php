<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CommissionStructuresTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CommissionStructuresTable Test Case
 */
class CommissionStructuresTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CommissionStructuresTable
     */
    public $CommissionStructures;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.commission_structures',
        'app.transaction_types',
        'app.from_accounts',
        'app.to_accounts',
        'app.linked_transaction_types',
        'app.transactions'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('CommissionStructures') ? [] : ['className' => 'App\Model\Table\CommissionStructuresTable'];
        $this->CommissionStructures = TableRegistry::get('CommissionStructures', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CommissionStructures);

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

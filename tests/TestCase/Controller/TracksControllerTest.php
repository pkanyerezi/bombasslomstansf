<?php
namespace App\Test\TestCase\Controller;

use App\Controller\TracksController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\TracksController Test Case
 */
class TracksControllerTest extends IntegrationTestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.tracks',
        'app.deliveries',
        'app.users',
        'app.expenses',
        'app.items',
        'app.fuel_track_downs',
        'app.drivers',
        'app.performances',
        'app.track_expenses',
        'app.petrol_stations',
        'app.petty_cash_transactions',
        'app.stock_pile_transactions'
    ];

    /**
     * Test index method
     *
     * @return void
     */
    public function testIndex()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test view method
     *
     * @return void
     */
    public function testView()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test add method
     *
     * @return void
     */
    public function testAdd()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test edit method
     *
     * @return void
     */
    public function testEdit()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete method
     *
     * @return void
     */
    public function testDelete()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}

<?php

namespace App\Test\Model;

use App\Model\Deck;

// Librairie de test
use \Mockery;
use PHPUnit\Framework\TestCase;

class DeckTest extends TestCase
{
    /** @test */
    public function testConstruct()
    {
        $deck = new Deck(30);
        $this->assertInstanceOf('App\Model\Deck', $deck);
        $this->assertEquals(count($deck->cards), 30);
    }

    // Le teardown est une fixture qui sera executée après chaque tests (setUp est executé avant chaques tests)
    public function tearDown()
    {
        // On appel la méthode statique qui signale à Mockery d'enclencher ses "expectations" (attentes), qui constituent des assertions
        Mockery::close();
    }
}

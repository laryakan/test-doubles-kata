<?php

namespace App\Test\Controller;

use App\Controller\Game;
use PHPUnit_Framework_TestCase;

class GameTest extends PHPUnit_Framework_TestCase
{

    // Au moins comme ça on aura toujours au moins un test
    // qui marche... en théorie
    /** @test
    *** @coversNothing
    **/
    public function assertTrueTest()
    {
        $this->assertTrue(true);
    }

    /** @test
    *** @covers ARandomController::_construct
    **/
    public function constructTest()
    {
        $arc = new Game();
        $this->assertInstanceOf('App\Controller\Game', $arc);
    }
}

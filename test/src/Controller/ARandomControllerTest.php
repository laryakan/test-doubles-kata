<?php

namespace App\Test\Controller;

use App\Controller\ARandomController;
use PHPUnit_Framework_TestCase;

class ARandomControllerTest extends PHPUnit_Framework_TestCase
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
    ***
    **/
    public function constructTest()
    {
        $arc = new ARandomController();
        $this->assertInstanceOf('App\Controller\ARandomController', $arc);
    }
}

<?php

namespace App\Test\Controller;

use App\Controller\Game;
use \Mockery;
use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
{

    // Au moins comme ça on aura toujours au moins un test
    // qui marche... en théorie
    /** @test */
    public function testAssertTrue()
    {
        $this->assertTrue(true);
    }

    /** @test */
    public function testConstruct()
    {
        $arc = new Game('Tester 1', 'Tester 2');
        $this->assertInstanceOf('App\Controller\Game', $arc);
    }

    /** @test */
    public function testSetPlayers()
    {
        $game = new Game();
        
        // Ceci permet de tester que la méthode d'injection
        // est efficace, au travers d'un Mock

        
        // Nottez que nous n'instancions pas vraiment le modèle
        // "Player". Nous allons juste le faire croire au
        // contrôleur.
        
        $player = Mockery::mock('App\Model\Player');

        
        // Il faut cependant spécifier l'ensemble des méthodes
        // qui vont être appellé et ce que nous attendons comme
        // retour de cet appel.
        
        $player->shouldReceive('setDeck')->andReturnNull();
        $player->shouldReceive('pickDeckCard')->andReturnNull();

        
        // Les méthodes appelé durant l'execution de la fonction
        // "setPlayers" étant identique sur les deux joueurs
        // nous pouvons nous contenter de ne le mocker qu'une fois
        
        $game->setPlayers($player, $player);
    }

    /** @test */
    public function testSetPlayersWithMoreExcpectation()
    {
        $game = new Game();
        $player = Mockery::mock('App\Model\Player');

        
        // Afin de préparer notre Deck, nous devons disposer
        // de cartes. Nous préparrons donc un Mock de carte
        
        $card = Mockery::mock('App\Model\Card');

        
        // Dans le cadre d'un test un peu plus complexe, nous
        // allons avoir besoin de préparer un mock
        // supplémentaire, en l'occurence un Deck.
        
        $deck = Mockery::mock('App\Model\Deck');
        $deck
        ->shouldReceive('pickCard') // L'objet va recevoir un appel
        ->atLeast()->times(6) // au moins 3 fois par joueur
        ->andReturn($card); // et retourner une carte à chaque fois

        
        // Le même test que ci-dessus mais un peut plus complexe
        // en terme d'attente. N'oubliez pas que nous allons
        // utiliser le même mock pour les deux joueurs et qu'il
        // vont commencer par tirer 3 carte de leur deck
        // nouvellement constitué.
        
        $player
        ->shouldReceive('setDeck') // L'objet va recevoir un appel
        ->twice() // deux fois
        ->andReturnNull();

        $player->shouldReceive('pickDeckCard')->andReturnNull();

        $game->setPlayers($player, $player, $deck);
    }

    // Le teardown est une fixture qui sera executée après chaque test
    public function tearDown()
    {
        // On appel la méthode statique qui signale à Mockery d'enclencher ses "expectation"
        Mockery::close();
    }
}

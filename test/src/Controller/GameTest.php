<?php

namespace App\Test\Controller;

// Controlleur d'entrée
use App\Controller\Game;

// Librairie de test
use \Mockery;
use PHPUnit\Framework\TestCase;

//Spy
use  App\Test\Spy\Player as PlayerSpy;

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

        // Afin de préparer notre Deck, nous devons disposer
        // de cartes. Nous préparrons donc un Mock de carte
        $card = Mockery::mock('App\Model\Card');

        // Dans le cadre d'un test un peu plus complexe, nous
        // allons avoir besoin de préparer un mock
        // supplémentaire, en l'occurence un Deck
        $deck = Mockery::mock('App\Model\Deck');
        $deck
        ->shouldReceive('pickCard') // L'objet va recevoir un appel
        ->atLeast()->times(6) // au moins 3 fois par joueur
        ->andReturn($card); // et retourner une carte à chaque fois

        // Le même test que ci-dessus mais un peut plus complexe
        // en terme d'attente. N'oubliez pas que nous allons
        // utiliser le même mock "Deck" pour les deux joueurs
        // et qu'ils vont commencer par tirer 3 carte de
        // leur deck nouvellement constitué.

        // Il ne sera cependant pas possible d'utiliser de Mock
        // pour "Player" car nous ne pourrons pas utiliser le
        // Mock "Deck" au travers du Mock "Player"

        // Nous allons donc en profiter pour créer un "Spy" du
        // Model "Player" qui va nous permettre de Mocker le
        // "Deck" en monitorant le joueur selon des règles métier
        // de l'application
        $playerSpyOne = new PlayerSpy('PlayerSpyOne');
        $playerSpyTwo = new PlayerSpy('PlayerSpyTwo');
        
        $game->setPlayers($playerSpyOne, $playerSpyTwo, $deck);

        // Nous pouvons maintenant vérifier que notre espions
        // a bien reçu ses appels, et dans l'ordre

        $expectedCalls = array(
            'setDeck'=> array($deck),
            'pickDeckCard'=> array(),
            'pickDeckCard'=> array(),
            'pickDeckCard'=> array(),
        );

        // Sur le premier joueur
        $this->assertEquals($expectedCalls, $playerSpyOne->calls);

        // Sur le second joueurs
        // Sur le premier joueur
        //$this->assertEquals($expectedCalls, $playerSpyTwo->calls);
    }

    // Le teardown est une fixture qui sera executée après chaque tests (setUp est executé avant chaques tests)
    public function tearDown()
    {
        // On appel la méthode statique qui signale à Mockery d'enclencher ses "expectations" (attentes), qui constituent des assertions
        Mockery::close();
    }
}

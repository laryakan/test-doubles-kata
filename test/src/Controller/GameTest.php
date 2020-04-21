<?php

namespace App\Test\Controller;

// Controlleur d'entrée
use App\Controller\Game;

// Librairie de test
use \Mockery;
use PHPUnit\Framework\TestCase;

//Spy
use App\Test\Spy\Player as PlayerSpy;

// Model
use App\Model\Card;

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
        
        $player1 = Mockery::mock('App\Model\Player');
        $player2 = Mockery::mock('App\Model\Player');

        
        // Il faut cependant spécifier l'ensemble des méthodes
        // qui vont être appellé et ce que nous attendons comme
        // retour de cet appel.
        
        $player1->shouldReceive('setDeck')->once()->andReturn();
        $player1->shouldReceive('pickDeckCard')->andReturnNull();

        $player2->shouldReceive('setDeck')->once()->andReturn();
        $player2->shouldReceive('pickDeckCard')->andReturnNull();
        
        // Les méthodes appelé durant l'execution de la fonction
        // "setPlayers" étant identique sur les deux joueurs
        // nous pouvons nous contenter de ne le mocker qu'une fois
        
        $game->setPlayers($player1, $player2);
    }

    /** @test */
    public function testConstructWithMoreExcpectations()
    {
        $game = new Game();
        // Afin de préparer notre Deck, nous devons disposer
        // de cartes. Nous préparrons donc un Mock de carte
        $card = Mockery::mock('App\Model\Card');

        // Dans le cadre d'un test un peu plus complexe, nous
        // allons avoir besoin de préparer un mock
        // supplémentaire, en l'occurence un Deck
        $deck1 = Mockery::mock('App\Model\Deck');
        $deck1
        ->shouldReceive('pickCard') // L'objet va recevoir un appel
        ->atLeast()->times(3) // au moins 3 fois par joueur
        ->andReturn($card); // et retourner une carte à chaque fois

        $deck2 = Mockery::mock('App\Model\Deck');
        $deck2
        ->shouldReceive('pickCard') // L'objet va recevoir un appel
        ->atLeast()->times(3) // au moins 3 fois par joueur
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
        
        $game->setPlayers($playerSpyOne, $playerSpyTwo, $deck1, $deck2);

        //var_dump($playerSpyOne->calls);


        // Nous pouvons maintenant vérifier que notre espions
        // a bien reçu ses appels, et dans l'ordre

        $expectedCallsPlayerOne = array(
            array('setDeck'=> array($deck1)),
            array('pickDeckCard'=> array()),
            array('pickDeckCard'=> array()),
            array('pickDeckCard'=> array()),
        );

        $expectedCallsPlayerTwo = array(
            array('setDeck'=> array($deck2)),
            array('pickDeckCard'=> array()),
            array('pickDeckCard'=> array()),
            array('pickDeckCard'=> array()),
        );

        // Sur le premier joueur
        $this->assertEquals($expectedCallsPlayerOne, $playerSpyOne->calls);

        // Sur le second joueur
        $this->assertEquals($expectedCallsPlayerTwo, $playerSpyTwo->calls);
    }

    public $player1HP = 30;
    public $player2HP = 30;

    /** @test */
    public function testStartGame()
    {
        $player1 = Mockery::mock('App\Model\Player');
        $player2 = Mockery::mock('App\Model\Player');

        $deckPlayer1 = self::mockNewDeck();
        $deckPlayer2 = self::mockNewDeck();

        $player1->shouldReceive('setDeck')->once()->set('health', $this->player1HP)->andSet('name', 'Sébastien');
        $player2->shouldReceive('setDeck')->once()->set('health', $this->player2HP)->andSet('name', 'Vincent');

        $player1->shouldReceive('pickDeckCard')->atLeast(3);
        $player2->shouldReceive('pickDeckCard')->atLeast(3);

        $player1->shouldReceive('playHighestValidManaCostingCard');
        $player2->shouldReceive('playHighestValidManaCostingCard');

        $player1->shouldReceive('removeHealth')->withArgs(function ($arg) {
            print($arg);
            return true;
        })->set('health', $this->player1HP);
        $player2->shouldReceive('removeHealth')->withArgs(function ($arg) {
            print($arg);
            return true;
        })->set('health', $this->player2HP);

        $player1->shouldReceive('increaseMaxMana');
        $player2->shouldReceive('increaseMaxMana');

        $player1->shouldReceive('resetCurrentMana');
        $player2->shouldReceive('resetCurrentMana');
        

        $game = new Game($player1, $player2, $deckPlayer1, $deckPlayer2);
        $game->startGame();
    }

    protected static function mockNewDeck()
    {
        $card = new Card('testCard', 1);
        $deck = Mockery::mock('App\Model\Deck');
        $deck->shouldReceive('pickCard')->andReturn($card);
        return $deck;
    }



    // Le teardown est une fixture qui sera executée après chaque tests (setUp est executé avant chaques tests)
    public function tearDown()
    {
        // On appel la méthode statique qui signale à Mockery d'enclencher ses "expectations" (attentes), qui constituent des assertions
        Mockery::close();
    }
}

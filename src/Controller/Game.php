<?php
/*
** Dans le cadre de ce Coding Dojo, le thème étant de
** une partie de "Jeu de cartes à collectionner" (TGC),
** le controlleur jouera le rôle d'une partie entre deux
** joueurs.
**
** Les règles du jeux sont inspirées de celles de Hearthstone
** (Simplifiées !)
**
** ###  REGLES DU JEU PAR DEFAUT ###
**
** Conditions de démarrage de la partie :
** - La partie se déroule au tour par tour
** - 2 joueurs par partie
** - Chaque joueur a 30 points de vie
** - Chaque joueur a 1 point de mana sur un maximum de 10
** - Chaque joueur possède un deck de 30 cartes de dégâts
** - Chaque carte coûte aléatoirement de 0 à 8 point(s) de mana
** - Chaque joueur reçoit 3 cartes initiales dans sa main
**
** Les règles en cours de partie sont :
** - Chaque joueur reçoit un point de mana par tour
** - Les points de mana sont rechargés à chaque tour
** - Chaque joueur tire une carte aléatoire de son deck, à chaque tour
** - Chaque joueur peut dépenser jusqu'au maximum de ses points de mana disponible en carte
** - Chaque carte inflige son montant de point de mana sous forme de point dégâts à l'adversaire, ce qui lui fait perdre un montant de point de vie équivalent
** - Si un joueur a son montant de point de vie <= 0, son adversaire gagne
** - Si un joueur ne peut pas jouer car il ne dispose pas d'assez de mana pour jouer une carte, son adversaire joue immédiatement
**
** Condition spéciale :
** - Si un joueur dont le deck est vide ne peut pas jouer, il perd 1 point de vie et son adversaire prend la main (pas encore implémenté)
** - La main d'un joueur ne peut pas dépasser 5 carte, toute les carte supplémentaire sont retirées du jeu (définitivement)
** - Les carte qui coûte 0 de mana n'inflige aucun dégât, elle ne servent à rien
**
**/


namespace App\Controller;

use App\Model\Player;
use App\Model\Deck;
use App\Model\Card;

class Game
{
    protected $players;
    protected $rules;

    /**
    * @param string $playerOneName
    *       Nom du premier joueur
    * @param string $playerTwoName
    *       Nom du second joueur
    **/
    public function __construct(string $playerOneName = 'Player1', string $playerTwoName = 'Player2')
    {
        // On initialise les joueurs
        $this->players = array(
        new Player($playerOneName),
        new Player($playerTwoName),
      );

        // On leur fournit leur Deck de départ
        foreach ($this->players as $player) {
            $player->setDeck(new Deck());
            //Et leur main initiale de 3 cartes
            for ($i=0; $i < 3; $i++) {
                $player->pickDeckCard();
            }
        }
    }

    /**
    * Fonction de démarrage de la partie
    */

    public function startGame()
    {
        // Granularité : Partie
        $gameOver = false;
        print("=== MOCKARD === \n Début de la partie...\n");
        print($this->players[0]->name ." VS ".$this->players[1]->name."\n");

        // Compteur de round
        $round = 0;

        // Boucle de la partie
        while (!$gameOver) {
            $round++;
            print("\n\n TOUR " . $round ."\n");

            // Granularité : Tour
            foreach ($this->players as $i => $player) {

              // Afin de déterminer l'opposant
                $opponent = ($i == 0) ? $this->players[1] : $this->players[0];

                // Puisqu'il s'agit d'un nouveua round, le joueur peut jouer
                $canPlay = true;
                print("---\nC'est au tour de ".$player->name."\n");
                print($player."\n");

                // Si on veut examiner le déroulement de la partie, décommenter
                //sleep(1);

                // Il commence son tour en tirant une carte
                $player->pickDeckCard();
                // Si le joueur à plus de 5 carte on lui retire la dernière
                if (sizeof($player->hand) > 5) {
                    array_pop($player->hand);
                }
                
                // Granularité : Joueur
                while ($canPlay) {
                    //sleep(1);
                    // Nous prennons le parti de lui faire jouer la plus grosse carte possible
                    $playedCard = $player->playHighestValidManaCostingCard();
                
                    // S'il ne peut pas jouer
                    if (is_null($playedCard)) {
                        $canPlay = false;
                        print($player->name." ne peut pas jouer\n");
                        // Et si en plus son deck est vide
                        if (empty($player->deck->cards)) {
                            print("Son Deck étant vide : -1PV\n");
                            $player->removeHealth();
                        }
                    } else {
                        // S'il peut jouer, il inflige des dégâts à son adversaire
                        $opponent->removeHealth($playedCard->manaCost);
                        // Et perd la mana associé à la carte jouée
                        $player->removeMana($playedCard->manaCost);
                        print($player->name .' joue "'. $playedCard->name . '"" et inflige '. $playedCard->manaCost . ' à ' . $opponent->name . "\n");

                        // Si en plus son adversaire passe à 0 PV ou moins, le joueur courant gagne la partie
                        if ($opponent->health <= 0) {
                            $gameOver = true;
                            $canPlay = false;
                            print($player->name .' a vaincu '. $opponent->name . ", partie terminée ! \n");
                            return;
                        }
                    }
                }

                // Fin du tour, on augmente le mana max du joueur
                $player->increaseMaxMana();
                // Et on réinitialise sa mana "courante"
                $player->resetCurrentMana();
            }
        }
    }

    /**
    * @param Player $playerOne
    *       Premier joueur
    * @param Player $playerTwo
    *       Second joueur
    *
    * Permet de redéfinir les joueurs, et nottament, via une
    * injection depuis les tests unitaires
    **/

    public function setPlayers(Player $playerOne, Player $playerTwo, $deck = null)
    {
        // On initialise les joueurs
        $this->players = array(
        $playerOne,
        $playerTwo,
      );

        // On leur fournit leur Deck de départ
        foreach ($this->players as $player) {
            $player->setDeck((is_null($deck) ? new Deck() : $deck));
            //Et leur main initiale de 3 cartes
            for ($i=0; $i < 3; $i++) {
                $player->pickDeckCard();
            }
        }
    }
}

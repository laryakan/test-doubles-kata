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
** - Si un joueur dont le deck est vide ne peut pas jouer, il perd 1 point de vie et son adversaire prend la main
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
    * @param sting $playerOneName
    *       Nom du premier joueur
    * @param sting $playerTwoName
    *       Nom du second joueur
    * @param array $rules
    *       Listes de règles du jeu afin de les modifiers
    **/
    public function __construct(string $playerOneName, string $playerTwoName, array $rules = array())
    {
        $this->players = array(
        new Player($playerOneName),
        new Player($playerTwoName),
      );

        foreach ($this->players as $player) {
            $player->setDeck(new Deck());
            for ($i=0; $i < 3; $i++) {
                $player->pickDeckCard();
            }
        }
    }

    public function startGame()
    {
        // Granularité : Partie
        $gameOver = false;
        print("=== MOCKARD === \n Début de la partie...\n");
        print($this->players[0]->name ." VS ".$this->players[1]->name."\n");
        while (!$gameOver) {
            // Granularité : Tour
            foreach ($this->players as $i => $player) {
                $opponent = ($i == 0) ? $this->players[1] : $this->players[0];

                $canPlay = true;
                print("C'est au tour de ".$player->name."\n");
                // Granularité : Joueur
                while ($canPlay) {
                    $playedCard = $player->playHighestValidManaCostingCard();
                
                    if (is_null($playedCard)) {
                        $canPlay = false;
                        print($player->name." ne peut pas jouer\n");
                    } else {
                        $opponent->removeHealth($playedCard->manaCost);
                        $player->removeMana($playedCard->manaCost);
                        print($player->name .' joue '. $playedCard->name . ' et inflige '. $playedCard->manaCost . ' à ' . $opponent->name . "\n");
                        if ($opponent->health <= 0) {
                            $gameOver = true;
                            print($player->name .' a vaincu '. $opponent->name . ", partie terminée ! \n");
                        }
                    }
                }
                $player->addMana();
            }
        }
    }
}

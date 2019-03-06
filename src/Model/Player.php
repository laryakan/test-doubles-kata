<?php
/*
** Dans le cadre de ce Coding Dojo, le thème étant de
** une partie de "Jeu de cartes à collectionner" (TGC),
** un joueur possède un deck de 5 cartes, chaque carte
** ayant un poid, compris entre 1 et 10,
** attribué aléatoirement, le joueur ayant le deck le
** plus lourd gagne.
**/


namespace App\Model;

use App\Model\Deck;

class Player
{
    public $name;
    public $deck;
    public $hand = array();
    public $mana;
    public $health;

    /**
    * @param sting $name
    *       Nom du joueur
    **/
    public function __construct(string $name)
    {
        $this->name = $name;
        $this->mana = array('current' => 0, 'max' => 10);
        $this->health = 30;
    }

    public function setDeck(Deck $deck)
    {
        $this->deck = $deck;
    }

    public function pickDeckCard()
    {
        array_push($hand, $this->deck->pickCard());
    }

    public function playHighestValidManaCostingCard()
    {
        $highestValidManaCostingCard = null;
        foreach ($this->hand as $card) {
            if ($card->manaCost <= $mana) {
                $highestValidManaCostingCard = $card;
            }
        }
        return $highestValidManaCostingCard;
    }

    public function removeHealth($point = 1)
    {
        $this->health -= $point;
    }

    public function addMana($point = 1)
    {
        $this->mana['current'] += $point;
    }

    public function removeMana($point = 1)
    {
        $this->mana['current'] -= $point;
    }
}

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
        $this->mana = array('current' => 0, 'max' => 1);
        $this->health = 30;
    }

    public function setDeck(Deck $deck)
    {
        $this->deck = $deck;
    }

    public function pickDeckCard()
    {
        $pickedCard = $this->deck->pickCard();
        if (!is_null($pickedCard)) {
            array_push($this->hand, $pickedCard);
        }
    }

    public function playHighestValidManaCostingCard()
    {
        $highestValidManaCostingCard = null;

        // Played card index
        $pci = null;
        foreach ($this->hand as $i => $card) {
            if ($card->manaCost <= $this->mana['current']) {
                $highestValidManaCostingCard = $card;
                $pci = $i;
            }
        }

        if (!is_null($pci)) {
            unset($this->hand[$pci]);
        }

        return $highestValidManaCostingCard;
    }

    public function removeHealth($point = 1)
    {
        $this->health -= $point;
    }

    public function addMana($point = 1)
    {
        if ($this->mana['current'] + $point > $this->mana['max']) {
            $this->mana['current'] = $this->mana['max'];
            return;
        }
        $this->mana['current'] += $point;
    }

    public function increaseMaxMana($point = 1)
    {
        if ($this->mana['max'] < 10) {
            $this->mana['max'] += $point;
        }
    }

    public function removeMana($point = 1)
    {
        if ($this->mana['current'] - $point < 0) {
            $this->mana['current'] = 0;
            return;
        }
        $this->mana['current'] -= $point;
    }

    public function resetCurrentMana()
    {
        $this->mana['current'] = $this->mana['max'];
    }

    public function __toString()
    {
        return $this->name . " dispose de " . sizeof($this->hand) . " cartes en main, " .sizeof($this->deck->cards) . " dans son deck, ". $this->health . "PV et " . $this->mana['current'] ."PM.";
    }
}

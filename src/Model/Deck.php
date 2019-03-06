<?php
/*
** Dans le cadre de ce Coding Dojo, le thème étant de
** une partie de "Jeu de cartes à collectionner" (TGC),
** un des modèles représente le deck d'un joueur, composé
** de 30 cartes (par défaut).
**/


namespace App\Model;

use App\Model\Card;

class Deck
{
    public $cards;

    public function __construct($numberOfCards = 30)
    {
        $this->cards = array();
        for ($i=0; $i < $numberOfCards; $i++) {
            array_push($this->cards, new Card());
        }
    }

    public function pickCard()
    {
        if (empty($this->cards)) {
            return null;
        }
        $rci = array_rand($this->cards);
        $rCard = $this->cards[$rci];
        unset($this->cards[$rci]);
        return $rCard;
    }
}

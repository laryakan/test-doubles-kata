<?php
/*
** Dans le cadre de ce Coding Dojo, le thème étant de
** une partie de "Jeu de cartes à collectionner" (TGC),
** un des modèles représente une carte dans le deck d'un
** joueur.
**/


namespace App\Model;

class Card
{
    protected $name;
    protected $manaCost;

    public function __construct($name = null, $manaCost = null)
    {
        if (is_null($name)) {
            $name = self::generateCardName();
        }
        if (is_null($manaCost)) {
            $manaCost = rand(0, 10);
        }

        $this->name = $name;
        $this->manaCost = $manaCost;
    }

    protected static function generateCardName()
    {
        $nameList = explode("\n", utf8_encode(str_replace("\r", '', file_get_contents($this->files['all_users']))));
        $rni = array_rand($nameList);
        return ucwords($nameList[$rni]);
    }
}

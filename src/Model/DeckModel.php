<?php
/*
** Dans le cadre de ce Coding Dojo, le thème étant de
** une partie de "Jeu de cartes à collectionner" (TGC),
** un des modèles représente le deck d'un joueur, composé
** de 5 cartes.
**/


namespace App\Model;

class DeckModel
{
    protected $id;
    protected $name;
    protected $age;
    protected $parentId;

    public function __construct($name, $age, $parentId)
    {
        $this->name = $name;
        $this->age = $age;
        $this->parentId = $parentId;
        //$constructArgs= func_get_args();
    }
}

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

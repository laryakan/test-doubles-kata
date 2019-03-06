<?php
/*
** Dans le cadre de ce Coding Dojo, le thème étant de
** une partie de "Jeu de cartes à collectionner" (TGC),
** un des modèles représente une carte dans le deck d'un
** joueur.
**/


namespace App\Model;

class CardModel
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

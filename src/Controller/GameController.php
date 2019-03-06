<?php
/*
** Dans le cadre de ce Coding Dojo, le thème étant de
** une partie de "Jeu de cartes à collectionner" (TGC),
** le controlleur jouera le rôle d'une partie entre deux
** joueurs.
**
** Les règles du jeux sont inspirées de celles de Hearthstone
** (Simplifié !)
**
** Conditions de démarrage de la partie :
** - La partie se déroule au tour par tour
** - 2 joueurs par partie
** - Chaque joueur a 30 points de vie
** - Chaque joueur a 1 point de mana sur un maximum de 10
** - Chaque joueur possède un deck de 30 cartes de dégâts
** - Chaque carte coûte aléatoirement de 0 à 8 point de mana
** - Chaque joueur reçoit 3 cartes initiales dans sa main
**
** Les règles en cours de partie sont :
** - Chaque joueur reçoit un point de mana par tour
** - Les points de mana sont rechargés à chaque tour
** - Chaque joueur tire une carte aléatoire de son deck, à chaque tour
** - Chaque joueur peut dépenser jusqu'au maximum de ses points de mana en carte
** - Chaque carte inflige son montant de point de mana sous forme de point dégâts à l'adversaire (ce qui lui fait perdre un montant de point de vie équivalent)
**
**/


namespace App\Controller;

class GameController
{
    public $constructArgs;

    public function __construct($arg1 = null, $arg2 = "foo", $arg3 = "bar")
    {
        $constructArgs= func_get_args();
    }
}

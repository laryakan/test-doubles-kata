<?php
/*
** Un Spy va permettre de simuler un objet en modifiant son
** comportement si besoin, mais surtout, il va permettre d'
** observer l'évolution du modèle "Player"
**
**/


namespace App\Test\Spy;

use App\Model\Player as OriginalPlayer;
use App\Model\Deck;

class Player extends OriginalPlayer
{
    public $calls = array();
    // A cet effet nous allons sucharger les méthodes que nous souhaitons surveiller

    // Si les méthodes à surveiller ne dispose pas d'une
    // visibilité public, il est possible d'utiliser __call
    public function setDeck(Deck $deck)
    {
        $function = __FUNCTION__;
        $this->spy($function, array($deck));
        parent::$function($deck);
    }

    public function pickDeckCard()
    {
        $function = __FUNCTION__;
        $this->spy($function, array());
        parent::$function();
    }

    public function playHighestValidManaCostingCard()
    {
        $function = __FUNCTION__;
        $this->spy($function, array());
        parent::$function();
    }

    public function removeHealth($point = 1)
    {
        $function = __FUNCTION__;
        $this->spy($function, func_get_args());
        parent::$function();
    }

    public function addMana($point = 1)
    {
        $function = __FUNCTION__;
        $this->spy($function, func_get_args());
        parent::$function();
    }

    public function increaseMaxMana($point = 1)
    {
        $function = __FUNCTION__;
        $this->spy($function, func_get_args());
        parent::$function();
    }

    public function removeMana($point = 1)
    {
        $function = __FUNCTION__;
        $this->spy($function, func_get_args());
        parent::$function();
    }

    public function resetCurrentMana()
    {
        $function = __FUNCTION__;
        $this->spy($function, array());
        parent::$function();
    }

    public function __toString()
    {
        return '';
    }

    // Une méthode qui permet de vérifier l'état des propriétés
    // de l'objet original
    public function status()
    {
        return get_object_vars($this);
    }

    //Cette fonction va servir de mouchard
    protected function spy($method, $args)
    {
        $this->calls[] = array( $method => $args );
    }
}

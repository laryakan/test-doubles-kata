<?php
/*
** Une classe quelconque qui fera notre sujet de test
**
**/


namespace App\Controller;

class ARandomController
{
    public $constructArgs;

    public function __construct($arg1 = null, $arg2 = "foo", $arg3 = "bar")
    {
        $constructArgs= func_get_args();
    }
}

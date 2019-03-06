<?php
require_once __DIR__ . '/bootstrap.php';

$game = new App\Controller\Game('Jean-Pierre', 'Corrine');
$game->startGame();

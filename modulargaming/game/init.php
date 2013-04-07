<?php defined('SYSPATH') OR die('No direct script access.');

Route::set('games', 'games')
  ->defaults(array(
    'directory'  => 'Game',
    'controller' => 'Index',
    'action'     => 'index',
  ));

Route::set('game', 'game(/<controller>(/<action>(/<id>)))')
  ->defaults(array(
    'directory'  => 'Game',
    'controller' => 'Index',
    'action'     => 'index',
));
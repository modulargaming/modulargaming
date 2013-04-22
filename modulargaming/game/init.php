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

Route::set('games.rock-paper-scissors', 'games/rock-paper-scissors')
	 ->defaults(array(
	'directory' => 'Game',
	'controller' => 'RockPaperScissors',
	'action' => 'index',
));

Route::set('games.lucky-wheel', 'games/lucky-wheel')
	 ->defaults(array(
	'directory' => 'Game',
	'controller' => 'LuckyWheel',
	'action' => 'index',
));

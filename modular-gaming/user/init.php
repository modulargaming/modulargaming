<?php defined('SYSPATH') OR die('No direct script access.');

Route::set('user', 'user(/<controller>(/<action>(/<id>)))')
->defaults(array(
'directory'  => 'user',
'controller' => 'dashboard',
'action'     => 'index',
));

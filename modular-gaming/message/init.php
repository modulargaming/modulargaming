<?php defined('SYSPATH') OR die('No direct script access.');

Route::set('message.view', 'message/view(/<id>)', array('id' => '[0-9]+'))
  ->defaults(array(  	
    'directory'  => 'message',	  	
    'controller' => 'view',	  	
    'action'     => 'index',	  	
  ));
  	
Route::set('message', 'message(/<controller>(/<action>(/<id>)))')
  ->defaults(array(
    'directory'  => 'message',
    'controller' => 'index',
    'action'     => 'index',
));


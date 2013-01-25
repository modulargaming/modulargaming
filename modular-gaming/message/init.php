<?php defined('SYSPATH') OR die('No direct script access.');

Route::set('messages', 'messages')
  ->defaults(array(  	
    'directory'  => 'Message',	  	
    'controller' => 'Index',	  	
    'action'     => 'index',	  	
  ));


Route::set('message.view', 'message/view(/<id>)', array('id' => '[0-9]+'))
  ->defaults(array(  	
    'directory'  => 'Message',	  	
    'controller' => 'View',	  	
    'action'     => 'index',	  	
  ));

Route::set('message.create', 'message/create(/<id>)', array('id' => '[0-9]+'))
  ->defaults(array(  	
    'directory'  => 'Message',	  	
    'controller' => 'Create',	  	
    'action'     => 'index',	  	
  ));
  	
Route::set('message', 'message(/<controller>(/<action>(/<id>)))')
  ->defaults(array(
    'directory'  => 'Message',
    'controller' => 'Index',
    'action'     => 'index',
));


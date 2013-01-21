<?php defined('SYSPATH') OR die('No direct script access.');

Route::set('messages', 'messages')
  ->defaults(array(  	
    'directory'  => 'Message',	  	
    'controller' => 'Index',	  	
    'action'     => 'Index',	  	
  ));


Route::set('message.view', 'message/view(/<id>)', array('id' => '[0-9]+'))
  ->defaults(array(  	
    'directory'  => 'Message',	  	
    'controller' => 'View',	  	
    'action'     => 'Index',	  	
  ));

Route::set('message.create', 'message/create(/<id>)', array('id' => '[0-9]+'))
  ->defaults(array(  	
    'directory'  => 'Message',	  	
    'controller' => 'Create',	  	
    'action'     => 'Index',	  	
  ));
  	
Route::set('message', 'message(/<controller>(/<action>(/<id>)))')
  ->defaults(array(
    'directory'  => 'Message',
    'controller' => 'Index',
    'action'     => 'Index',
));


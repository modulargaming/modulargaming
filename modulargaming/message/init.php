<?php defined('SYSPATH') OR die('No direct script access.');

Route::set('message.inbox', 'message/inbox')
  ->defaults(array(
    'directory'  => 'Message',
    'controller' => 'Index',
    'action'     => 'index',
  ));

Route::set('message.outbox', 'message/outbox')
  ->defaults(array(
    'directory'  => 'Message',
    'controller' => 'Outbox',
    'action'     => 'index',
  ));


Route::set('message.view', 'message/view(/<id>)', array('id' => '[0-9]+'))
  ->defaults(array(
    'directory'  => 'Message',
    'controller' => 'View',
    'action'     => 'index',
  ));

Route::set('message.create', 'message/create(/<username>)', array('username' => '[a-zA-Z0-9-_]+'))
  ->defaults(array(
    'directory'  => 'Message',
    'controller' => 'Create',
    'action'     => 'index',
  ));

/**
Route::set('messages', 'messages(/<controller>)')
  ->defaults(array(
    'directory'  => 'Message',
    'controller' => 'Index',
    'action'     => 'index',
  ));

Route::set('message', 'message(/<controller>(/<action>(/<id>)))')
  ->defaults(array(
    'directory'  => 'Message',
    'controller' => 'Index',
    'action'     => 'index',
));
**/

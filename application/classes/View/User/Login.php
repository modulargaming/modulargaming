<?php defined('SYSPATH') or die('No direct script access.');

class View_User_Login {

public $title = 'Log In';

public function base()
{
return URL::base(Request::$initial, TRUE);
}

public function links()
{
$route = Route::get('default');
return array(
'save' => $route->uri(array('controller'=>'user', 'action'=>'login')),
);
}


}




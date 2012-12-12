<?php defined('SYSPATH') or die('No direct script access.');

class View_User_Noaccess {

public $title = 'Unauthorized Access';
public $message = 'Only admininistrators may access the requested page. Please contact a site administrator to request access if needed.';

public function base()
{
return URL::base(Request::$initial, TRUE);
}

public function links()
{
$route = Route::get('default');
return array(
'dashboard' => $route->uri(array('controller'=>'dashboard')),
);
}

}

<?php defined('SYSPATH') OR die('No direct access allowed.');
class Controller_User extends Controller_Auth {

//Controls access for the whole controller, if not set to FALSE we will only allow user roles specified.
// Can be set to a string or an array, for example array('login', 'admin') or 'login'
   public $auth_required = FALSE; // this is TRUE only for the admin controller

    // Controls access for separate actions
public $secure_actions = array(
// user actions
'index' => 'login', // this is edit profile
'save' => 'login', // this is save profile
// the others are public (login, logout)
);

protected function login_required() {
$this->action_login();
}

public function action_login() {
// If user already signed-in
$message = NULL;
$auth = Auth::instance();
if($auth->logged_in() != 0){
$this->redirect_to_dashboard();
}
if (count($_POST) > 0)
{
$auth->login($this->request->post('username'),$this->request->post('password'));
if ($auth->logged_in())
{
$this->redirect_to_dashboard();
}
$message = 'Login failed. Password is case-sensitive. Is Caps Lock on?';
}
$this->show_login_form($message);
}

// This method can be called by action_edit
// or by the save action if validation fails.
private function show_login_form($message)
{
                $renderer = Kostache_Layout::factory();
		$renderer->set('message', $message);
		$this->response->body($renderer->render(new View_User_Login));
}

public function action_logout() {
// Sign out the user
Auth::instance()->logout();
$this->redirect_to_dashboard();
}

public function action_index()
{
// show the edit profile form
if ( Auth::instance()->logged_in() == false ){
// No user is currently logged in
$this->request->redirect('user/login');
}
$this->show_profile_form();
}

public function action_noaccess()
  {
                $renderer = Kostache_Layout::factory();
                $this->response->body($renderer->render(new View_User_Noaccess));

  }

public function action_save()
{
if (count($_POST) > 0)
{
$errors = NULL;
try
{
$user = Auth::instance()->get_user();
$user = $user->update_user($_POST, array('password', 'email'));
$user->save();
EmailHelper::notify($user, $this->request->post('password'));
$this->redirect_to_dashboard();
}
catch (ORM_Validation_Exception $e)
{
// todo: specify a real messages file here...
// external errors are still in a sub-array, so we have to flatten
// also the message is wrong - bug #3896
$errors = Arr::flatten($e->errors('hack'));
}
$this->show_profile_form($user, $errors);
}
else
{
$this->redirect_to_dashboard();
}
}

// This method can be called by action_edit
// or by the save action if validation fails.
private function show_profile_form($user = NULL, $errors = NULL)
{
if ($user === NULL)
{
$user = Auth::instance()->get_user();
}
$renderer = Kostache_Layout::factory();
$renderer->set('user', $user->as_array())
->set('errors',$errors);
$this->response->body($renderer->render(new View_User_Profile));

}

private function redirect_to_dashboard()
{
// Redirect the user to the list
// specify the controller in case later we change the default controller for the route.
$uri = Route::get('default')->uri(array('controller'=>'dashboard'));
$this->request->redirect($uri);
}

}



<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Account extends Controller {

public function action_index()
{
$this->request->redirect('');
}


// Create Account
public function action_create()
{
                $renderer = Kostache_Layout::factory();
                $this->response->body($renderer->render(new View_Account_Create));
}

// Login Account
public function action_login()
{
                $renderer = Kostache_Layout::factory();
                $this->response->body($renderer->render(new View_Account_Login));
}


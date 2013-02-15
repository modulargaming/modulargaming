<?php defined('SYSPATH') OR die('No direct script access.');

class Abstract_Controller_Admin extends Abstract_Controller_Frontend {

	protected $layout = 'Admin/layout';
	protected $protected = TRUE;
	
	
	public function before(){
		parent::before();
		
		$assets = Kohana::$config->load('assets.admin');
		$this->_load_assets($assets);
		$permission = $this->request->controller() . '_' . $this->request->action();
	}
	
	public function after() {
		//if no subnav has been defined search for one
		if($this->view !== null AND !$this->view->has_subnav())
		{
			$this->_nav(strtolower($this->request->controller()), $this->request->action());
		}
		
		parent::after();
	}
	
	protected function _nav($type, $action='index') {
		$nav = Kohana::$config->load('admin.'.$type.'.nav');
		
		if($nav != FALSE) 
		{
			$nav[$action]['active'] = true;
			$this->view->subnav = $nav;
		}
	}
	
}

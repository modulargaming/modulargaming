<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_Admin extends Controller_Frontend {

	protected $layout = 'Admin/layout';
	protected $protected = TRUE;
	
	protected $_nav = array();
	
	public function before(){
		parent::before();
		
		$permission = $this->request->controller() . '_' . $this->request->action();
	}
	
	public function after() {
		// TODO: This might fit better in the view class?
		if ( ! empty($this->_nav) AND $this->view !== null) {
			if (array_key_exists($this->request->action(), $this->_nav))
			{
				$this->_nav[$this->request->action()]['active'] = true;
			}

			$this->view->subnav = $this->_nav;
		}

		parent::after();
	}

}

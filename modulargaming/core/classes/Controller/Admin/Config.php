<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_Admin_Config extends Abstract_Controller_Admin {
	protected $_root_node = 'Game';
	protected $_node = 'Configuration';

	protected function _parse_set($def, &$cfg) {
		if(array_key_exists('html', $def)) {
			foreach($def['html'] as $k => $v) {
				$def['html'][$k] = $this->_parse_set($v, $cfg);
			}
		}
		else if(array_key_exists('name', $def)) {
			//echo $def['name'] . '----'.$_POST[$def['name']];
			$name = str_replace('.', '-', $def['name']);

			$input = (array_key_exists($name, $_POST)) ? $_POST[$name] : false;

			Arr::set_path($cfg, $def['name'], $input);
		}
		return $def;
	}

	public function action_index() {
		$cfg =  Kohana::$config->load('core')->as_array();

		$this->view = new View_Admin_Config;
		$this->view->currency = $cfg['currency'];
		$this->view->initial_points = $cfg['initial_points'];
		$this->view->site = $cfg['site'];
		$this->view->google_drive = $cfg['google_drive'] + array('url' => Route::url('core.google_drive.setup', array(), true));
		$this->view->link = Route::url('core.admin.modules.config.save', array(), true);

		Assets::factory('body_admin')->js('config', 'admin/config.js');
	}

	public function action_save() {
		$cfg =  Kohana::$config->load('core')->as_array();

		foreach($_POST as $key => $value)
		{
			$key = str_replace('-', '.', $key);
			if($key != 'csrf')
				Arr::set_path($cfg, $key, $value);
		}


		// strip whitespace and add tab
		$export = str_replace(array('  ', 'array (', "'true'", "'false'", "'".MGPATH), array("\t", "\tarray(", 'true', 'false', 'MGPATH.\''), var_export($cfg, true));
		$export = stripslashes($export);
		$export = str_replace("'".MGPATH, 'MGPATH.\'', $export);
		$export = str_replace("'".MODPATH, 'MODPATH.\'', $export);
		$content = "<?php defined('SYSPATH') OR die('No direct script access.');".PHP_EOL.PHP_EOL.'return '.$export.';';

		file_put_contents(MGPATH.'core'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'core.php', $content);

		Hint::success('Updated your game config successfully!');
		$this->redirect(Route::url('core.admin.modules.config', array(), true));
	}

	public function action_google(){
		$code = $this->request->query('code');

		$this->user->set_property('google_drive_token', $code);

		Hint::success('You\'ve successfully set up google drive backup!');
		$this->redirect(Route::url('core.admin.modules.data', array(), true));
	}
}

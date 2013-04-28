<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_Admin_Modules extends Abstract_Controller_Admin {
	protected $_root_node = 'Game';
	protected $_node = 'Modules';

	public function action_index()
	{
		//activated modules
		$config = Kohana::$config->load('core.modules');

		$this->view = new View_Admin_Modules;
		$this->view->activated = $config;

		//all modules
		$mg_modules = array();

		foreach($config['mg'] as $k => $p)
		{
			if($k != 'core')
			{
				$mod = MGPATH.$k.DIRECTORY_SEPARATOR.'module.php';
				$desc = (file_exists($mod)) ? include($mod) : array();
				$handle = $k;

				$mg_modules[$handle] = array('dir' => $k, 'description' => $desc);
			}
		}

		$dh  = opendir(MGPATH);
		while (false !== ($filename = readdir($dh))) {
			if(!array_key_exists($filename, $config['mg']) && is_dir(MGPATH.$filename) && !in_array($filename, array('.', '..', 'core')))
			{
				$mod = MGPATH.$filename.DIRECTORY_SEPARATOR.'module.php';
				$desc = (file_exists($mod)) ? include($mod) : array();
				$handle = (isset($desc['handle'])) ? $desc['handle'] : $filename;

				$mg_modules[$handle] = array('dir' => $filename, 'description' => $desc);
			}
		}
		closedir($dh);

		$modules = array();

		//Put activated kohana modules at the top of the table
		foreach($config['mod'] as $k => $p)
		{
			$modules[] = $k;
		}

		$dh  = opendir(MODPATH);
		while (false !== ($filename = readdir($dh))) {
			if(!array_key_exists($filename, $config['mod']) && is_dir(MODPATH.$filename) && !in_array($filename, array('.', '..', 'core')))
			{
				$modules[] = $filename;
			}
		}
		closedir($dh);

		Assets::factory('body_admin')->js('sortable', 'plugins/jquery.sortable.js');
		Assets::factory('body_admin')->js('modules', 'admin/modules.js');
		Assets::factory('head_admin')->css('sortable', 'sortable.css');
		$this->view->mg_modules = $mg_modules;
		$this->view->modules = $modules;
		$this->view->link_modules = Route::url('core.admin.modules.manage.save');
	}

	public function action_save() {
		$modules = array(
			'mg' => array(
				'core' => MGPATH.'core'
			)
		);

		foreach($_POST['modules'] as $v) {
			$type = substr($v, 0, 3);
			$name = substr($v, 3);

			$dir = ($type == 'mg-') ? MGPATH : MODPATH;
			$set = ($type == 'mg-') ? 'mg' : 'mod';
			if(is_dir($dir.$name))
			{
				$modules[$set][$name] = $dir.$name;
			}
		}

		$core = Kohana::$config->load('core')->as_array();
		$core['modules'] = $modules;

		// strip whitespace and add tab
		$export = str_replace(array('  ', 'array ('), array("\t", "\t array("), var_export($core, true));
		$export = stripslashes($export);
		$export = str_replace("'".MGPATH, "MGPATH.'", $export);
		$export = str_replace("'".MODPATH, "MODPATH.'", $export);
		$content = "<?php defined('SYSPATH') OR die('No direct script access.');".PHP_EOL.PHP_EOL.'return '.$export.';';

		file_put_contents(MGPATH.'core'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'core.php', $content);

		Hint::success('Updated active modules');
		$this->redirect(Route::url('core.admin.modules.manage', null, true));
	}

	public function action_config_manage() {
		$module = $this->request->param('module');
		$set = $this->request->param('set');

		$mod = MGPATH.$module.DIRECTORY_SEPARATOR.'module.php';
		$cfg = (file_exists($mod)) ? include($mod) : array();

		$this->view = new View_Admin_Module_Config;
		$this->view->definition = $cfg['config'][$set];
		$this->view->link = Route::url('core.admin.modules.config.edit', array('module' => $module, 'set' => $set), true);

		Assets::factory('body_admin')->js('dform', 'plugins/dform.js');
		Assets::factory('body_admin')->js('dform.core', 'plugins/dform.core.js');
		Assets::factory('body_admin')->js('dform.conv', 'plugins/dform.converters.js');
		Assets::factory('body_admin')->js('dform.ext', 'plugins/dform.extensions.js');
		Assets::factory('body_admin')->js('dform.mg', 'plugins/dform.mg.js');
		Assets::factory('body_admin')->js('config', 'admin/modules/config.js');
	}

	public function action_config_edit() {
		$module = $this->request->param('module');
		$set = $this->request->param('set');

		$mod = MGPATH.$module.DIRECTORY_SEPARATOR.'module.php';
		$cfg = (file_exists($mod)) ? include($mod) : array();

		//$this->view = new View_Admin_Config;
		$config[$set] = Kohana::$config->load($set)->as_array();

		$key = (array_key_exists('html', $cfg['config'][$set])) ? 'html': 'entries';

		foreach($cfg['config'][$set][$key] as $k => $tab) {
			$this->_parse_set($tab, $config);
		}


		// strip whitespace and add tab
		$export = str_replace(array('  ', 'array (', "'true'", "'false'"), array("\t", 'array(', 'true', 'false'), var_export($config[$set], true));

		$content = "<?php defined('SYSPATH') OR die('No direct script access.');".PHP_EOL.PHP_EOL.'return '.$export.';';

		file_put_contents(MGPATH.$module.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.$set.'.php', $content);

		Hint::success('Updated the config for '.$set.' successfully!');
		$this->redirect(Route::url('core.admin.modules.config.manage', array('module' => $module, 'set' => $set), true));
	}

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
}

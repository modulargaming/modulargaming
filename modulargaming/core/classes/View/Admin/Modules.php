<?php defined('SYSPATH') OR die('No direct script access.');

class View_Admin_Modules extends Abstract_View_Admin {

	public $title = 'Modules';

	public function mg_modules() {
		$list = array();

		$active_keys = array_keys($this->activated['mg']);

		foreach($this->mg_modules as $handle => $info)
		{
			$show_config = false;
			$config = false;
			if(isset($info['description']['config'])){
				$show_config = true;
				$config = array();
				$keys = array_keys($info['description']['config']);
				foreach($keys as $key)
				{
					$config[] = array('name' => $key, 'link' => Route::url('core.admin.modules.config.manage', array('module' => $handle, 'set' => $key)));
				}
			}
			$list[] = array(
				'active' => (in_array($handle, $active_keys)),
				'handle' => 'mg-'.$handle,
				'show_cfg' => $show_config,
				'config' => $config,
				'name' => (isset($info['description']['name'])) ? $info['description']['name'] : $handle,
				'description' => (isset($info['description']['description'])) ? $info['description']['description'] : $handle . ' module',
			);
		}

		return $list;
	}

	public function modules() {
		$list = array();

		$active_keys = array_keys($this->activated['mod']);

		foreach($this->modules as $module) {

			$list[] = array(
				'active' => (in_array($module, $active_keys)),
				'handle' => 'kh-'.  $module,
				'name' => ucfirst($module),
				'description' => ucfirst($module) . ' module',
			);
		}

		return $list;
	}
}

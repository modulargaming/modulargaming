<?php defined('SYSPATH') OR die('No direct script access.');

class View_Admin_Module_Config extends Abstract_View_Admin {

	public $title = 'Config';

	public function definition() {
		$key = (array_key_exists('html', $this->definition)) ? 'html': 'entries';

		foreach($this->definition[$key] as $k => $tab) {
			$this->definition[$key][$k] = $this->_parse_set($tab);
		}

		return json_encode($this->definition);
	}
	protected function _parse_set($def) {
		if(array_key_exists('html', $def)) {
			foreach($def['html'] as $k => $v) {
				$def['html'][$k] = $this->_parse_set($v);
			}
		}
		else if(array_key_exists('name', $def)) {
			$def['value'] = Kohana::$config->load($def['name']);
			$def['name'] = str_replace('.', '-', $def['name']);
		}
		return $def;
	}
}

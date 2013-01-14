<?php defined('SYSPATH') OR die('No direct script access.');

class Assets {
	static $_collection = array('head' => array(), 'body' => array());
	static $_files = array('css', 'js');
	
	static public function js($name, $file, $location='body', $possition='end', $relative=null) {
		self::add('js', $name, $file, $location, $possition, $relative);
	}
	
	static public function css($name, $file, $location='head', $possition='end', $relative=null) {
		self::add('css', $name, $file, $location, $possition, $relative);
	}
	
	static public function add($type, $name, $file, $location, $possition='end', $relative=null) {
		//assign the file
		self::$_files[$type][$name] = $file;
		
		//place the file
		switch($possition) {
			default:
			case'end':
				self::$_collection[$location][$type][] =  $name;
				
				break;
			case 'start':
				self::$_collection[$location][$type] = array_merge(array($name), self::$_collection[$location][$type]);
				break;
			case 'before':
			case 'after':
				$insertion = array_search($relative, self::$_collection[$location][$type]);
				
				if($possition == 'after')
					$insertion++;
		
				$before = array_slice(self::$_collection[$location][$type], 0, $insertion, true);
				$after  = array_slice(self::$_collection[$location][$type], $insertion, null, true);
		
				self::$_collection[$location][$type] = array_merge($before, array($name), $after);
			break;
		}
	}
	
	static public function head($type=null) {
		if($type = null)
			return array_merge(self::retrieve('css', 'head'), self::retrieve('js', 'head'));
		else
			self::retrieve($type, 'head');
	}
	
	static public function body($type=null) {
		if($type = null)
			return array_merge(self::retrieve('css', 'body'), self::retrieve('js', 'body'));
		else
			self::retrieve($type, 'body');
	}
	
	static public function retrieve($type, $location) {
		$collection = self::$_collection[$location];
		$list = array();
		
		foreach($collection as $type => $files) {
			foreach($files as $item)
				$list[] = self::$_files[$type][$item];
		}
		
		return $list;
	}
}
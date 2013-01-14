<?php defined('SYSPATH') OR die('No direct script access.');

class Assets {
	static $_collection = array('head' => array(), 'body' => array());
	static $_files = array('css', 'js');
	
	static public function js($name, $file, $location='body', $position ='end', $relative=null) {
		self::add('js', $name, $file, $location, $position, $relative);
	}
	
	static public function css($name, $file, $location='head', $position='end', $relative=null) {
		self::add('css', $name, $file, $location, $position, $relative);
	}
	
	static public function add($type, $name, $file, $location, $position='end', $relative=null) {
		//assign the file
		self::$_files[$type][$name] = 'assets/'.$type.'/'.$file;
		
		//place the file
		switch($position) {
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
				
				if($position == 'after')
					$insertion++;
		
				$before = array_slice(self::$_collection[$location][$type], 0, $insertion, true);
				$after  = array_slice(self::$_collection[$location][$type], $insertion, null, true);
		
				self::$_collection[$location][$type] = array_merge($before, array($name), $after);
			break;
		}
	}
	
	static public function head($type=null) {
		if($type == null){
			return array('css' => self::retrieve('css', 'head'), 'js' => self::retrieve('js', 'head'));
		}
		else
			return self::retrieve($type, 'head');
	}
	
	static public function body($type=null) {
		if($type == null)
			return array('css' => self::retrieve('css', 'body'), 'js' => self::retrieve('js', 'body'));
		else
			return self::retrieve($type, 'body');
	}
	
	static public function retrieve($type, $location) {
		$collection = self::$_collection[$location];
		$list = array();
		
		if(isset($collection[$type])) {
			foreach($collection as $ctype => $files) {
				if(count($files) > 0) {
					foreach($files as $item)
						$list[] = array('file' => self::$_files[$type][$item]);
				}
			}
		}
		
		return $list;
	}
}
<?php defined('SYSPATH') OR die('No direct script access.');

abstract class Paginate extends Kohana_Paginate {

	public $cfg = null;
	public $current_page = false;
	
	public static function factory($object, $config = 'default', $driver = NULL)
	{		
		$object = parent::factory($object, $driver);
		
		$object->set_config($config);
		
		//get the current page
		$page = Request::initial()->param($object->cfg['param']);
		
		if ( ! Valid::digit($page))
		{
			$page = 1;
		}
		
		$object->current_page = $page;
		
		//limit the pagination results
		$object->limit(($page - 1) * $object->cfg['total_items'], $object->cfg['total_items']);
		
		return $object;
	}
	
	public function set_config($config = 'default')
	{
		if ($config == 'default')
		{
			$this->cfg = Kohana::$config->load('pagination')->as_array();
		}
		elseif (is_array($config))
		{
			if (count($config) < 4)
			{
				$this->cfg = array_merge(Kohana::$config->load('pagination')->as_array(), $config);
			}
			else
			{
				$this->cfg = $config;
			}
		}
		else
		{
			$this->cfg = Kohana::$config->load($config)->as_array();
		}
	}
	
	public function pages()
	{
		return ceil($this->_count_total / $this->cfg['total_items']);
	}

	public function kostache()
	{
		//if we only have 1 page and autohide is on return nothing
		if ($this->_count == $this->_count_total AND $this->cfg['auto_hide'] == TRUE)
		{
			return false;
		}
		else
		{
			$page_count = $this->pages();
			$pages = array();
			
			for ($i = 1; $i <= $page_count; $i++)
			{
				$active = ($this->current_page == $i);
				
				$link = URL::site(Request::initial()->route()->uri(array($this->cfg['param'] => $i)));
				$pages[] = array('num' => $i, 'link' => $link, 'active' => $active);
			}
			
			return array('pages' => $pages);
		}
	}
}

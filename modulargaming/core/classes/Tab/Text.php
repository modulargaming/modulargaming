<?php defined('SYSPATH') OR die('No direct script access.');

class Tab_Text extends Abstract_Tab {

	public function __construct($text)
	{
		$this->view = new View_Tab_Text;
		$this->view->text = $text;
	}

}

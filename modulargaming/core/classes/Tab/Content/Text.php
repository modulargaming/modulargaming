<?php defined('SYSPATH') OR die('No direct script access.');

class Tab_Content_Text extends Abstract_Tab_Content {

	public function __construct($text)
	{
		$this->view = new View_Tab_Content_Text();
		$this->view->text = $text;
	}

}

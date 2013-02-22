<?php defined('SYSPATH') OR die('No direct script access.');

class Tab {

	/**
	 * @var String $name
	 */
	public $name;

	/**
	 * @var Abstract_Tab_Content[] $content
	 */
	private $contents;

	public function __construct($name)
	{
		$this->name = $name;
	}

	public function add_content(Abstract_Tab_Content $content)
	{
		$this->contents[] = $content;
	}

	public function render()
	{
		$content = "";
		foreach ($this->contents as $c)
		{
			$content .= $c->render();
		}
		return $content;
	}

}

<?php defined('SYSPATH') OR die('No direct script access.');
/**
 *
 *
 * @package    MG/Core
 * @category   Tab
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_Tab_Text extends Abstract_Tab {

	public function __construct($text)
	{
		$this->view = new View_Tab_Text;
		$this->view->text = $text;
	}

}

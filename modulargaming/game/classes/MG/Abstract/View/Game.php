<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Abstract base view for Games.
 *
 * @package    MG Game
 * @category   View
 * @author     Modular Gaming Team
 * @copyright  (c) 2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_Abstract_View_Game extends Abstract_View {

	protected function get_breadcrumb()
	{
		return array_merge(parent::get_breadcrumb(), array(
			array(
				'title' => 'Games',
				'href'  => Route::url('games')
			)
		));
	}

} // End Abstract_View_Game

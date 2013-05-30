<?php defined('SYSPATH') OR die('No direct script access.');
/**
 *
 *
 * @package    MG/Core
 * @category   View
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_View_Pagination {

	/**
	 * @var Paginate
	 */
	public $paginate;

	public function items()
	{
		$items = array();

		// Numbers.
		for ($i = 1; $i <= $this->paginate->pages(); $i++)
		{
			$items[] = array(
				'name' => $i,
				'current' => ($i === $this->paginate->current_page),
				'url' => $this->paginate->url($i)
			);
		}

		return $items;
	}

}

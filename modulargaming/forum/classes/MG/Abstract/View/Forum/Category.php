<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Abstract view for forum Category.
 *
 * @package    MG Forum
 * @category   View
 * @author     Modular Gaming Team
 * @copyright  (c) 2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_Abstract_View_Forum_Category extends Abstract_View_Forum {

	/**
	 * @var Model_Forum_Category Category
	 */
	public $category;

	protected function get_breadcrumb()
	{
		return array_merge(parent::get_breadcrumb(), array(
			array(
				'title' => $this->category->title,
				'href'  => Route::url('forum.category', array('id' => $this->category->id))
			)
		));
	}

}

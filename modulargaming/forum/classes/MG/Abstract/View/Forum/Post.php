<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Abstract view for forum Post.
 *
 * @package    MG Forum
 * @category   View
 * @author     Modular Gaming Team
 * @copyright  (c) 2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_Abstract_View_Forum_Post extends Abstract_View_Forum {

	/**
	 * @var Model_Forum_Post Post
	 */
	public $post;

	protected function get_breadcrumb()
	{
		$topic = $this->post->topic;
		$category = $topic->category;

		return array_merge(parent::get_breadcrumb(), array(
			array(
				'title' => $category->title,
				'href'  => Route::url('forum.category', array('id' => $category->id))
			),
			array(
				'title' => $topic->title,
				'href'  => Route::url('forum.topic', array('id' => $topic->id))
			)
		));
	}

}

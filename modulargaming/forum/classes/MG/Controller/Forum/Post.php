<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Forum post controller.
 *
 * @package    MG Forum
 * @category   Controller
 * @author     Modular Gaming Team
 * @copyright  (c) 2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_Controller_Forum_Post extends Abstract_Controller_Forum {

	protected $protected = TRUE;

	/**
	 * @var Model_Forum_Post
	 */
	private $post;

	/**
	 * @var Model_Forum_Topic
	 */
	private $topic;

	/**
	 * @var Model_Forum_Category
	 */
	private $category;

	public function before()
	{
		parent::before();

		$this->post = ORM::factory('Forum_Post', $this->request->param('id'));

		if ( ! $this->post->loaded())
		{
			throw HTTP_Exception::factory('404', 'Forum post not found');
		}

		$this->topic = $this->post->topic;
		$this->category = $this->topic->category;
	}

	public function action_edit()
	{
		$post = $this->post;

		if ( ! $this->user->can('Forum_Post_Edit', array('post' => $post)))
		{
			throw HTTP_Exception::factory('403', 'Permission denied to edit post');
		}

		if ($this->request->method() == HTTP_Request::POST)
		{
			try {
				$post->values($this->request->post(), array('content'))->save();
			}
			catch (ORM_Validation_Exception $e)
			{
				Hint::error($e->errors('models'));
			}

			Hint::success('Updated post');
			$this->redirect(Route::get('forum.topic')->uri(array('id' => $post->topic->id)));
		}

		$this->view = new View_Forum_Post_Edit;
		$this->view->post = $post;
	}


	public function action_delete()
	{
		$post = $this->post;

		if ( ! $this->user->can('Forum_Post_Delete', array('post' => $post)))
		{
			throw HTTP_Exception::factory('403', 'Permission denied to delete post');
		}

		if ($this->request->method() == HTTP_Request::POST)
		{
			$topic_redirect = Route::get('forum.topic')->uri(array('id' => $post->topic->id));
			try {

				// First post? delete the topic.
				if ($post->id == $post->topic->posts->limit(1)->find()->id)
				{
					$topic_redirect = Route::get('forum.category')->uri(array('id' => $post->topic->category));
					$post->topic->delete();
				}

				$post->delete();
			}
			catch (ORM_Validation_Exception $e)
			{
				Hint::error($e->errors('models'));
			}

			Hint::success('Deleted post');
			$this->redirect($topic_redirect);
		}

		$this->view = new View_Forum_Post_Delete;
		$this->view->post = $post;
	}

}

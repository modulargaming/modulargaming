<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Forum category controller.
 * Handles viewing categories AND creating new topics.
 *
 * @package    MG Forum
 * @category   Controller
 * @author     Modular Gaming Team
 * @copyright  (c) 2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_Controller_Forum_Category extends Abstract_Controller_Forum {

	/**
	 * @var Model_Forum_Category
	 */
	protected $category;

	/**
	 * Attempt to load the forum category using the id parameter.
	 * @throws HTTP_Exception
	 */
	public function before()
	{
		parent::before();

		$this->category = ORM::factory('Forum_Category', $this->request->param('id'));

		if ( ! $this->category->loaded())
		{
			throw HTTP_Exception::factory('404', 'Forum category not found');
		}
	}

	/**
	 * View topics in category.
	 */
	public function action_page()
	{
		$topics = $this->category->topics
			->with('last_post')
			->with('last_post:user')
			->order_by('sticky', 'DESC')
			->order_by('last_post.created', 'DESC');

		$paginate = Paginate::factory($topics)
			->execute();

		$this->view = new View_Forum_Category_View;

		$this->view->pagination = $paginate->render();
		$this->view->category = $this->category;
		$this->view->topics = $paginate->result();
	}

	/**
	 * Create new topic.
	 */
	public function action_create()
	{
		$this->logged_in_required();

		if ( ! $this->user->can('Forum_Topic_Create', array('category' => $this->category)))
		{
			throw HTTP_Exception::factory('403', 'Category is locked');
		}

		if ($this->request->method() == HTTP_Request::POST)
		{
			try
			{
				$topic = new Model_Forum_Topic;
				$topic->create_topic(array(
					'category_id' => $this->category->id,
					'user_id'     => $this->user->id,
					'title'       => $this->request->post('title'),
					'content'     => $this->request->post('content')
				), array(
					'category_id',
					'user_id',
					'title'
				));

				Hint::success('You have created a topic');
				$this->redirect(Route::get('forum.topic')->uri(array('id' => $topic->id)));
			}
			catch (ORM_Validation_Exception $e)
			{
				Hint::error($e->errors('models'));
			}
		}

		$this->view = new View_Forum_Topic_Create;
		$this->view->category = $this->category;
	}

}

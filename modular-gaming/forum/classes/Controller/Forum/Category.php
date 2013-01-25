<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Forum category controller.
 * Handles viewing categories and creating new topics.
 *
 * @package    MG Forum
 * @category   Controller
 * @author     Modular Gaming Team
 * @copyright  (c) 2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class Controller_Forum_Category extends Abstract_Controller_Forum {

	protected $category;

	/**
	 * Attempt to load the forum category using the id parameter from the url
	 * and throw an HTTP_Exception if it fails.
	 */
	public function before()
	{
		parent::before();

		$id = $this->request->param('id');

		$this->category = ORM::factory('Forum_Category', $id);

		if ( ! $this->category->loaded())
		{
			throw HTTP_Exception::factory('404', 'Forum category not found');
		}

		Breadcrumb::add($this->category->title, Route::url('forum.category', array('id' => $id)));
	}

	/**
	 * View topics in category.
	 */
	public function action_page()
	{
		$this->view = new View_Forum_Category_View;

		//$config = Kohana::$config->load('forum.topic');
		$count_topics = $this->category->topics
			->count_all();

		$topics = $this->category->topics
			->with('last_post')
			->order_by('sticky', 'DESC')
			->order_by('last_post.created', 'DESC');

		//$max_topics = $config['pagination'];
		//$paginate = Paginate::factory($topics, array('total_items' => $max_topics))->execute();
		$paginate = Paginate::factory($topics)
			->execute();

		// TODO: This belongs to the view class.
		$this->view->can_create = $this->user->can('Forum_Topic_Create', array('category' => $this->category));

		$this->view->pagination = $paginate->render();
		$this->view->category = $this->category;
		$this->view->topics = $paginate->result();

	}

	/**
	 * Create new topic.
	 */
	public function action_create()
	{
		if ( ! $this->user->can('Forum_Topic_Create', array('category' => $this->category)))
		{
			throw HTTP_Exception::factory('403', 'Category is locked');
		}

		Breadcrumb::add('Create', Route::url('forum.category', array(
			'action' => 'create',
			'id'     => $this->category->id,
		)));

		if ($this->request->method() == HTTP_Request::POST)
		{
			try
			{
				$topic_data = Arr::merge($this->request->post(), array(
					'category_id' => $this->category->id,
					'user_id'     => $this->user->id,
				));

				$topic = ORM::factory('Forum_Topic')
					->create_topic($topic_data, array(
						'category_id',
						'user_id',
						'title',
					));

				$post_data = Arr::merge($this->request->post(), array(
					'topic_id' => $topic->id,
					'user_id'  => $this->user->id,
				));

				$post = ORM::factory('Forum_Post')
					->create_post($post_data, array(
						'topic_id',
						'user_id',
						'content',
					));

				// Set the last post id.
				$topic->last_post_id = $post->id;
				$topic->save();

				$this->user->calculate_post_count();

				Hint::success('You have created a topic');
				$this->redirect(Route::get('forum.topic')->uri(array('id' => $topic->id)));
			}
			catch (ORM_Validation_Exception $e)
			{
				Hint::error($e->errors('models'));
			}
		}

		$this->view = new View_Forum_Topic_Create;
	}

}

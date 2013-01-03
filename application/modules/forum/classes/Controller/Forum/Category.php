<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_Forum_Category extends Controller_Frontend {

	protected $protected = TRUE;
	protected $category;

	public function before()
	{
		parent::before();

		$id = $this->request->param('id');

		$this->category = ORM::factory('Forum_Category', $id);

		if ( ! $this->category->loaded())
		{
			throw HTTP_Exception::factory('404', 'Forum category not found');
		}

		Breadcrumb::add('Forum', Route::url('forum'));
		Breadcrumb::add($this->category->title, Route::url('forum/category', array('id' => $id)));
	}

	public function action_view()
	{
		$topics = $this->category->topics
			->with('last_post')
			->order_by('last_post.created', 'DESC')
			->find_all();

		$this->view = new View_Forum_Category_Index;
		$this->view->category = $this->category;
		$this->view->topics = $topics;
	}

	public function action_create()
	{
		Breadcrumb::add('Create', Route::url('forum/category', array(
			'action' => 'create',
			'id'     => $this->category->id,
		)));

		if ($_POST)
		{
			try
			{
				$topic_data = Arr::merge($this->request->post(), array(
					'category_id' => $this->category->id,
					'user_id'     => $this->user->id,
					'last_post_time' => time(),
				));

				$topic = ORM::factory('Forum_Topic')
					->create_topic($topic_data, array(
						'category_id',
						'user_id',
						'title',
						'last_post_time',
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

				Hint::success('You have created a topic');
				$this->redirect(Route::get('forum/topic')->uri(array('id' => $topic->id)));
			}
			catch (ORM_Validation_Exception $e)
			{
				Hint::error($e->errors('models'));
			}
		}

		$this->view = new View_Forum_Topic_Create;
	}

}

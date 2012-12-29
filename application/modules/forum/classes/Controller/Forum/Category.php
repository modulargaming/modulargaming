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
		$topics = $this->category->topics->find_all();

		$this->view = new View_Forum_Category_Index;
		$this->view->category = $category;
		$this->view->topics = $topics;
	}

	public function action_create()
	{
		if ($_POST)
		{

			try
			{
				$array = Arr::merge($this->request->post(), array(
					'category_id' => $this->category->id,
					'user_id'	=> $this->user->id,
				));

				$topic = ORM::factory('Forum_Topic')
					->create_topic($array, array(
						'category_id',
						'user_id',
						'title',
					));

				$array = Arr::merge($array, array(
					'topic_id' => $topic->id,
					'user_id'	=> $this->user->id,
				));

				$post = ORM::factory('Forum_Post')
					->create_post($array, array(
						'topic_id',
						'user_id',
						'content',
					));

				Hint::success('You have created a topic');
				$this->redirect("forum/topic/$topic->id");
			}
			catch (ORM_Validation_Exception $e)
			{
				Hint::error($e->errors('models'));
			}

		}

		$this->view = new View_Forum_Topic_Create;


	}

}

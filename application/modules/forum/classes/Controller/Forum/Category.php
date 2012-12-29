<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_Forum_Category extends Controller_Frontend {

	public function action_view()
	{
		$id = $this->request->param('id');

		$category = ORM::factory('Forum_Category', $id);

		if ( ! $category->loaded())
		{
			throw HTTP_Exception::factory('404', 'Forum category not found');
		}

		$topics = $category->topics->find_all();

		Breadcrumb::add('Forum', Route::url('forum'));
		Breadcrumb::add($category->title, Route::url('forum/category', array('id' => $category->id)));

		$this->view = new View_Forum_Category_Index;
		$this->view->category = $category;
		$this->view->topics = $topics;
	}

	public function action_create()
	{
		$id = $this->request->param('id');

		$category = ORM::factory('Forum_Category', $id);

		if ( ! $category->loaded())
		{
			throw HTTP_Exception::factory('404', 'Forum category not found');
		}


		if ($_POST)
		{

			try
			{
				$array = Arr::merge($this->request->post(), array(
					'category_id' => $category->id,
					'created'      => time(),
					'user_id'	=> $this->user->id,
				));

				$topic = ORM::factory('Forum_Topic')
					->create_topic($array, array(
						'category_id',
						'user_id',
						'title',
						'created',
					));

				$array = Arr::merge($array, array(
					'topic_id' => $topic->id,
					'created'      => time(),
					'user_id'	=> $this->user->id,
				));

				$post = ORM::factory('Forum_Post')
					->create_post($array, array(
						'topic_id',
						'user_id',
						'content',
						'created',
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

<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_Forum_Topic extends Controller_Frontend {

	protected $protected = TRUE;

	public function action_view()
	{
		$id = $this->request->param('id');

		$topic = ORM::factory('Forum_Topic', $id);

		if ( ! $topic->loaded())
		{
			throw HTTP_Exception::factory('404', 'Forum topic not found');
		}

		$posts = $topic->posts->find_all();

		Breadcrumb::add('Forum', Route::url('forum'));
		Breadcrumb::add($topic->category->title, Route::url('forum/category', array('id' => $topic->category->id)));
		Breadcrumb::add($topic->title, Route::url('forum/topic', array('id' => $topic->id)));

		$this->view = new View_Forum_Topic_Index;
		$this->view->topic = $topic;
		$this->view->posts = $posts;
	}

	public function action_create()
	{
		$id = $this->request->param('id');

		$topic = ORM::factory('Forum_Topic', $id);

		if ( ! $topic->loaded())
		{
			throw HTTP_Exception::factory('404', 'Forum topic not found');
		}


		if ($_POST)
		{

			try
			{
				$array = Arr::merge($this->request->post(), array(
					'topic_id' => $topic->id,
					'user_id'	=> $this->user->id,
				));

				$topic = ORM::factory('Forum_Post')
					->create_post($array, array(
						'topic_id',
						'user_id',
						'content',
					));

				Hint::success('You have created a post!');
				$this->redirect("forum/topic/$topic->topic_id");
			}
			catch (ORM_Validation_Exception $e)
			{
				Hint::error($e->errors('models'));
			}

		}

		$this->view = new View_Forum_Post_Create;


	}

}


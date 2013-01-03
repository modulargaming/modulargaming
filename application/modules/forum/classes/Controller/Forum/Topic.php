<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_Forum_Topic extends Controller_Frontend {

	protected $protected = TRUE;
	protected $topic;

	public function before()
	{
		parent::before();

		$id = $this->request->param('id');
		$this->topic = ORM::Factory('Forum_Topic', $id);

		if ( ! $this->topic->loaded())
		{
			throw HTTP_Exception::factory('404', 'Forum topic not found');
		}

		Breadcrumb::add('Forum', Route::url('forum'));
		Breadcrumb::add($this->topic->category->title, Route::url('forum/category', array('id' => $this->topic->category->id)));
		Breadcrumb::add($this->topic->title, Route::url('forum/topic', array('id' => $this->topic->id)));
	}

	public function action_view()
	{
		$posts = $this->topic->posts->find_all();
		$this->view = new View_Forum_Topic_Index;
		$this->view->topic = $this->topic;
		$this->view->posts = $posts;
	}

	public function action_reply()
	{
		Breadcrumb::add('Reply', Route::url('forum/topic', array(
			'id'     => $this->topic->id,
			'action' => 'reply'
		)));

		if ($_POST)
		{

			try
			{
				$post_data = Arr::merge($this->request->post(), array(
					'topic_id' => $this->topic->id,
					'user_id'	=> $this->user->id,
				));
				$post_data['content'] = Security::xss_clean($post_data['content']);
				$topic = ORM::factory('Forum_Post')
					->create_post($post_data, array(
						'topic_id',
						'user_id',
						'content',
					));
				$this->topic->last_post_id = $topic->id;
				$this->topic->last_post_time = time();
				$this->topic->save();
				Hint::success('You have created a post!');
				$this->redirect("forum/topic/$topic->topic_id");
			}
			catch (ORM_Validation_Exception $e)
			{
				Hint::error($e->errors('models'));
			}

		}

		$this->view = new View_Forum_Post_Reply;

	}

}

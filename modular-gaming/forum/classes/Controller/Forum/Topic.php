<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Forum Topic controller.
 * Handles viewing a topic and replying to it.
 *
 * @package    MG Forum
 * @category   Controller
 * @author     Modular Gaming Team
 * @copyright  (c) 2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class Controller_Forum_Topic extends Controller_Abstract_Forum {

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

		Breadcrumb::add($this->topic->category->title, Route::url('forum.category', array('id' => $this->topic->category->id)));
		Breadcrumb::add($this->topic->title, Route::url('forum.topic', array('id' => $this->topic->id)));
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
		if ($this->topic->locked)
		{
			throw HTTP_Exception::factory('403', 'Topic is locked');
		}

		Breadcrumb::add('Reply', Route::url('forum.topic', array(
			'id'     => $this->topic->id,
			'action' => 'reply'
		)));

		if ($this->request->method() == HTTP_Request::POST)
		{

			try
			{
				$post_data = Arr::merge($this->request->post(), array(
					'topic_id' => $this->topic->id,
					'user_id'	=> $this->user->id,
				));

				$post = ORM::factory('Forum_Post')
					->create_post($post_data, array(
						'topic_id',
						'user_id',
						'content',
					));

				$this->topic->last_post_id = $post->id;
				$this->topic->save();

				$this->user->calculate_post_count();

				Hint::success('You have created a post!');
				$this->redirect(Route::get('forum.topic')->uri(array('id' => $this->topic->id)));
			}
			catch (ORM_Validation_Exception $e)
			{
				Hint::error($e->errors('models'));
			}

		}

		$this->view = new View_Forum_Post_Reply;

	}

	public function action_delete()
	{
		if ( ! $this->user->can('Forum_Topic_Delete', array('topic' => $this->topic)))
		{
			throw HTTP_Exception::factory('403', 'Permission denied to delete topic');
		}

		Breadcrumb::add('Delete', Route::url('forum.topic', array(
			'id'     => $this->topic->id,
			'action' => 'delete'
		)));

		if ($this->request->method() == HTTP_Request::POST)
		{

			try
			{
				$topic_redirect = $this->topic->category;
				$this->topic->delete();

				Hint::success('You have deleted a topic!');
				$this->redirect(Route::get('forum.category')->uri(array('id' => $topic_redirect)));
			}
			catch (ORM_Validation_Exception $e)
			{
				Hint::error($e->errors('models'));
			}

		}

		$this->view = new View_Forum_Topic_Delete;
	}

	public function action_sticky()
	{
		if ( ! $this->user->can('Forum_Topic_Sticky', array('topic' => $this->topic)))
		{
			throw HTTP_Exception::factory('403', 'Permission denied to sticky topic');
		}
		try
		{
			if ($this->topic->sticky)
			{
				$this->topic->sticky = 0;
				Hint::success('You have unstuck the topic!');
			}
			else
			{
				$this->topic->sticky = time();
				Hint::success('You have stuck the topic!');
			}
			$this->topic->save();
			$this->redirect(Route::get('forum.topic')->uri(array('id' => $this->topic->id)));
		}
		catch (ORM_Validation_Exception $e)
		{
			Hint::error($e->errors('models'));
		}
	}

	public function action_lock()
	{
		if ( ! $this->user->can('Forum_Topic_Lock', array('topic' => $this->topic)))
		{
			throw HTTP_Exception::factory('403', 'Permission denied to lock topic');
		}
		try
		{
			if ($this->topic->locked)
			{
				$this->topic->locked = 0;
				Hint::success('You have unlocked the topic!');
			}
			else
			{
				$this->topic->locked = time();
				Hint::success('You have locked the topic!');
			}

			$this->topic->save();
			$this->redirect(Route::get('forum.topic')->uri(array('id' => $this->topic->id)));
		}
		catch (ORM_Validation_Exception $e)
		{
			Hint::error($e->errors('models'));
		}
	}

}

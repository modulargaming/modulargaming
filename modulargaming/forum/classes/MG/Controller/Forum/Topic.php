<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Forum Topic controller.
 * Handles viewing a topic AND replying to it.
 *
 * @package    MG Forum
 * @category   Controller
 * @author     Modular Gaming Team
 * @copyright  (c) 2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_Controller_Forum_Topic extends Abstract_Controller_Forum {

	/**
	 * @var Model_Forum_Topic Topic
	 */
	private $topic;

	/**
	 * Attempt to load the Topic using the 'ID' parameter in the url.
	 *
	 * @throws HTTP_Exception_404 if topic is not found
	 */
	public function before()
	{
		parent::before();

		$this->topic = ORM::Factory('Forum_Topic', $this->request->param('id'));

		if ( ! $this->topic->loaded())
		{
			throw HTTP_Exception::factory('404', 'Forum topic not found');
		}
	}

	public function action_page()
	{
		// Increase the topic views
		$session = Session::instance();
		if ($session->get('forum.topic.last_visited_id') !== $this->topic->id)
		{
			$this->topic->views++;
			$this->topic->save();
		}
		$session->set('forum.topic.last_visited_id', $this->topic->id);

		// Display the topic.
		$posts = $this->topic->posts;

		$paginate = Paginate::factory($posts, array('total_items' => 10))
			->execute();

		$this->view = new View_Forum_Topic_Index;
		$this->view->pagination = $paginate->render();
		$this->view->topic = $this->topic;
		$this->view->posts = $paginate->result();

	}

	public function action_reply()
	{
		$this->logged_in_required();

		if ( ! $this->user->can('Forum_Topic_Reply', array('topic' => $this->topic)))
		{
			throw HTTP_Exception::factory('403', 'Topic is locked');
		}

		if ($this->request->method() == HTTP_Request::POST)
		{
			try
			{
				$post_data = Arr::merge($this->request->post(), array(
					'user_id'	=> $this->user->id,
				));

				$this->topic->create_reply($post_data, array(
					'topic_id',
					'user_id',
					'content',
				));

				Hint::success('You have created a post!');
				$this->redirect(Route::get('forum.topic')->uri(array('id' => $this->topic->id)));
			}
			catch (ORM_Validation_Exception $e)
			{
				Hint::error($e->errors('models'));
			}

		}

		$this->view = new View_Forum_Post_Reply;
		$this->view->topic = $this->topic;
	}

	public function action_delete()
	{
		if ( ! $this->user->can('Forum_Topic_Delete', array('topic' => $this->topic)))
		{
			throw HTTP_Exception::factory('403', 'Permission denied to delete topic');
		}

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
		$this->view->topic = $this->topic;
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

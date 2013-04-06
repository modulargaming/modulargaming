<?php defined('SYSPATH') OR die('No direct script access.');
 
class Controller_Forum_Poll extends Abstract_Controller_Forum {

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

	public function action_index()
	{
		if ( ! $this->topic->poll->loaded())
		{
			if ( ! $this->user->can('Forum_Poll_Create', array('topic' => $this->topic)))
			{
				throw HTTP_Exception::factory('403', 'Permission denied to create poll');
			}
	 	}
		else
		{
			if ( ! $this->user->can('Forum_Poll_Edit', array('poll' => $this->topic->poll)))
			{
				throw HTTP_Exception::factory('403', 'Permission denied to edit poll');
			}
		}

		$this->view = new View_Forum_Topic_Poll;
		$this->view->topic = $this->topic;

		if ($this->request->method() == HTTP_Request::POST)
		{
			// Include topic_id to post.
			$data = array(
				'topic_id' => $this->topic->id
			) + $this->request->post();

			/** @var Model_Forum_Poll $poll */
			$poll = $this->topic->poll;

			if ($poll->loaded()) // Poll was found, so update the title.
			{
				$poll->values($data, array('title'));
				$poll->save();
			}
			else // Poll was not found, so create it.
			{
				$poll = ORM::factory('Forum_Poll')
					->create_poll($data, array(
						'topic_id',
						'title',
					));
			}

			if ($poll->loaded())
			{
				Hint::success('You have updated the poll.');
			}
			else
			{
				Hint::success('You have created a poll for this topic.');
			}

			// Go back to topic.
			$this->redirect(Route::get('forum.topic')->uri(array('id' => $this->topic->id)));

		}
	}

	public function action_delete()
	{
		if ( ! $this->user->can('Forum_Poll_Delete', array('poll' => $this->topic->poll)))
		{
			throw HTTP_Exception::factory('403', 'Permission denied to delete poll');
		}

		if ($this->request->method() == HTTP_Request::POST)
		{
			$this->topic->poll->delete();

			Hint::success('You have deleted the poll.');

			$this->redirect(Route::get('forum.topic')->uri(array('id' => $this->topic->id)));
		}

		$this->view = new View_Forum_Poll_delete;
		$this->view->topic = $this->topic;
	}

	/*
		public function action_poll()
		{
			if ($this->request->method() == HTTP_Request::POST)
			{
				try
				{
					$post_data = Arr::merge($this->request->post(), array(
								'user_id'	=> $this->user->id,
							));
					if (isset($post_data['vote']) AND isset($post_data['option_id']))
					{
						if (ORM::factory('Forum_Poll_Vote')->where('poll_id', '=', $this->topic->poll->id)->where('user_id', '=', $this->user->id)->count_all())
						{
							Hint::error('You have already voted on the poll.');
						}
						else
						{
							$this->topic->poll->votes++;
							$this->topic->poll->save();
							$post_data['poll_id'] = $this->topic->poll->id;
							ORM::factory('Forum_Poll_Vote')
								->create_vote($post_data, array(
								'poll_id',
								'user_id',
								'option_id',
							));
							$option = ORM::factory('Forum_Poll_Option', $post_data['option_id']);
							$option->votes++;
							$option->save();
							Hint::success('You have voted on the poll.');
						}
					}
					if (isset($post_data['create']))
					{
						if ( ! $this->user->can('Forum_Poll_Create', array('topic' => $this->topic)))
						{
							throw HTTP_Exception::factory('403', 'Permission denied to create poll');
						}
						if ($this->topic->poll->loaded())
						{
							$this->redirect(Route::get('forum.topic')->uri(array('id' => $this->topic->id)));
						}
						$options = $options = array_slice($post_data['options'], 0, 5);
						foreach ($options as $key => $value)
						{
							if ( ! trim($value))
							{
								unset($options[$key]);
							}
						}
						if (count($options) < 2)
						{
							Hint::error('There must be at least 2 options.');
							$this->redirect(Route::url('forum.topic', array(
								'action' => 'poll',
								'id'     => $this->topic->id
							)));
						}
						$post_data['topic_id'] = $this->topic->id;
						$poll = ORM::factory('Forum_Poll')
								->create_poll($post_data, array(
								'topic_id',
								'title',
							));
						foreach ($options as $option)
						{
							ORM::factory('Forum_Poll_Option')->create_option(array('poll_id' => $poll->id, 'title' => $option), array('poll_id', 'title'));
						}
						Hint::success('You have created a poll for this topic.');
					}
					if (isset($post_data['edit']))
					{
						if ( ! $this->user->can('Forum_Poll_Edit', array('poll' => $this->topic->poll)))
						{
							throw HTTP_Exception::factory('403', 'Permission denied to edit poll');
						}
						$this->topic->poll->title = $post_data['title'];
						$this->topic->poll->save();
						foreach ($post_data['options'] as $key => $value)
						{
							$option = ORM::factory('Forum_Poll_Option')->where('id', '=', $key)->where('poll_id', '=', $this->topic->poll->id)->find();
							if ($option->loaded())
							{
								$option->title = $value;
								$option->save();
							}
							elseif (trim($value))
							{
								ORM::factory('Forum_Poll_Option')->create_option(array('poll_id' => $this->topic->poll->id, 'title' => $value), array('poll_id', 'title'));
							}
						}
						Hint::success('You have edited the poll.');
					}
					if (isset($post_data['delete']))
					{
						if ( ! $this->user->can('Forum_Poll_Delete', array('poll' => $this->topic->poll)))
						{
							throw HTTP_Exception::factory('403', 'Permission denied to delete poll');
						}
						$this->topic->poll->delete();
						Hint::success('You have deleted the poll.');
					}
					$this->redirect(Route::get('forum.topic')->uri(array('id' => $this->topic->id)));
				}
				catch (ORM_Validation_Exception $e)
				{
					Hint::error($e->errors('models'));
				}
			}

			$this->view = new View_Forum_Topic_Poll;
			$this->view->topic = $this->topic;
			if ($this->topic->poll->loaded())
			{
				if ( ! $this->user->can('Forum_Poll_Edit', array('poll' => $this->topic->poll)))
				{
					throw HTTP_Exception::factory('403', 'Permission denied to edit poll');
				}
				$this->view->poll = $this->topic->poll;
			}
			if ( ! $this->user->can('Forum_Poll_Create', array('topic' => $this->topic)))
			{
				throw HTTP_Exception::factory('403', 'Permission denied to edit poll');
			}
		}
		*/

}

<?php defined('SYSPATH') OR die('No direct script access.');

class MG_View_Forum_Topic_Index extends Abstract_View_Forum_Topic {

	public $posts;

	public function title()
	{
		return 'Forum - '.$this->topic->title;
	}

	/*
	public function topic()
	{
		$topic = array(
			'title'  => $this->topic->title,
			'locked' => $this->topic->locked
		);

		if ($this->topic->poll->loaded())
		{
			$topic['poll'] = $this->topic->poll->as_array();
			$options = array();
			$colours = array('info', 'success', 'warning', 'danger');
			foreach ($this->topic->poll->options->find_all() as $key => $value)
			{
				$colour = $key % 4;

				$options[] = array(
					'id' => $value->id,
					'title' => $value->title,
					'votes' => $value->votes,
					'colour' => $colours[$colour],
					'percent' => $topic['poll']['votes'] ? round($value->votes/$topic['poll']['votes']*100) : 0,
				);
			}
			$topic['poll']['options'] = $options;
			$topic['poll']['can_edit'] = Auth::instance()->get_user()->can('Forum_Poll_Edit', array('poll' => $this->topic->poll));
			$topic['poll']['can_delete'] = Auth::instance()->get_user()->can('Forum_Poll_Delete', array('poll' => $this->topic->poll));
			// TODO: We do not want database queries in the views, move to model?
			$topic['poll']['voted'] = ORM::factory('Forum_Poll_Vote')->where('poll_id', '=', $this->topic->poll->id)->where('user_id', '=', Auth::instance()->get_user()->id)->count_all();
		}
		else
		{
			$topic['poll'] = NULL;
		}

		return $topic;
	}
	*/

	public function posts()
	{
		$posts = array();

		foreach ($this->posts as $post)
		{
			$user = $post->user;
			$posts[] = array(
				'id'      => $post->id,
				'title'   => $this->topic->title,
				'content' => $post->content, // Escaped properly at create now
				'created' => Date::format($post->created),
				'user'    => array(
					'avatar'     => $user->avatar(),
					'username'   => $user->username,
					'title'      => $user->title->title,
					'signature'  => $user->get_property('signature'),
					'post_count' => number_format($user->get_property('forum.posts')),
					'created'    => Date::format($user->created),
					'href'       => Route::url('user.profile', array('id' => $user->id))
				),
				'links' => array(
					'edit' => Route::url('forum.post', array(
						'action' => 'edit',
						'id'     => $post->id,
					)),
					'delete' => Route::url('forum.post', array(
						'action' => 'delete',
						'id'     => $post->id,
					)),
				),
				'can_edit'   => $this->_user_can('Forum_Post_Edit', array('post' => $post)),
				'can_delete' => $this->_user_can('Forum_Post_Delete', array('post' => $post)),
			);
		}

		return $posts;
	}

	public function links()
	{
		return array(
			'reply' => Route::url('forum.topic', array(
				'action' => 'reply',
				'id'     => $this->topic->id
			))
		);
	}

	public function actions()
	{
		$actions = array();

		if ($this->_user_can('Forum_Topic_Delete'))
		{
			$actions[] = array(
				'title' => 'Delete',
				'icon'   => 'icon-remove',
				'href'  => Route::url('forum.topic', array(
					'action' => 'delete',
					'id'     => $this->topic->id,
				)),
			);
		}

		if ($this->_user_can('Forum_Topic_Sticky'))
		{
			$actions[] = array(
				'title' => $this->topic->sticky ? 'Unstick' : 'Stick',
				'icon'  => 'icon-star',
				'href'  => Route::url('forum.topic', array(
					'action' => 'sticky',
					'id'     => $this->topic->id,
				)),
			);
		}

		if ($this->_user_can('Forum_Topic_Lock'))
		{
			$actions[] = array(
				'title' => $this->topic->locked ? 'Unlock' : 'Lock',
				'icon'  => 'icon-lock',
				'href'  => Route::url('forum.topic', array(
					'action' => 'lock',
					'id'     => $this->topic->id,
				)),
			);
		}

		return $actions;
	}

	public function has_actions()
	{
		$actions = $this->actions();
		return ! empty($actions);
	}

	public function can_reply()
	{
		return $this->_user_can('Forum_Topic_Reply', array('topic' => $this->topic));
	}

}

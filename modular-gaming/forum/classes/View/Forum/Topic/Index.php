<?php defined('SYSPATH') OR die('No direct script access.');

class View_Forum_Topic_Index extends Abstract_View {

	public $topic;
	public $posts;

	public function title()
	{
		return 'Forum - '.$this->topic->title;
	}

	public function topic()
	{
		$topic = $this->topic->as_array();
		$topic['locked_date'] = Date::format($topic['locked']);
		if ($this->topic->poll->loaded())
		{
			$topic['poll'] = $this->topic->poll->as_array();
			$options = array();
			$colours = array('info', 'success', 'warning', 'danger');
			foreach ($this->topic->poll->options->find_all() as $key => $value)
			{
				$colour = $key;
				while ($colour >= 4)
				{
					$colour -= 4;
				}
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
			$topic['poll']['voted'] = ORM::factory('Forum_Poll_Vote')->where('poll_id', '=', $this->topic->poll->id)->where('user_id', '=', Auth::instance()->get_user()->id)->count_all();
		}
		else
		{
			$topic['poll'] = NULL;
		}
		return $topic;
	}

	public function can_create_poll()
	{
		return true;
		//return Auth::instance()->get_user()->can('Forum_Poll_Create', array('topic' => $this->topic));
	}

	public function posts()
	{
		$posts = array();

		foreach ($this->posts as $post)
		{
			$posts[] = array(
				'id' => $post->id,
				'title' => $this->topic->title,
				'content' => $post->content, // Escaped properly at create now
				'created' =>  Date::format($post->created),
				'user' => array(
					'avatar' => $post->user->avatar,
					'username'  => $post->user->username,
					'title'  => $post->user->title->title,
					'signature' => $post->user->signature,
					'post_count' => number_format($post->user->post_count),
					'created' => Date::format($post->user->created),
					'href'      => Route::url('user.view', array(
						'id'     => $post->user->id,
					)),
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
				'can_edit' => Auth::instance()->get_user()->can('Forum_Post_Edit', array('post' => $post)),
				'can_delete' => Auth::instance()->get_user()->can('Forum_Post_Delete', array('post' => $post)),
			);
		}

		return $posts;
	}

	public function links()
	{
		return array(
			'poll' => Route::url('forum.topic', array(
				'action' => 'poll',
				'id'     => $this->topic->id
			)),
			'reply' => Route::url('forum.topic', array(
				'action' => 'reply',
				'id'     => $this->topic->id
			)),
		);
	}

	public function actions()
	{
		$actions = array();
		$user = Auth::instance()->get_user();

		if ($user->can('Forum_Topic_Delete'))
		{
			$actions[] = array(
				'title' => 'Delete',
				'href'  => Route::url('forum.topic', array(
					'action' => 'delete',
					'id'     => $this->topic->id,
				)),
			);
		}

		if ($user->can('Forum_Topic_Sticky'))
		{
			$actions[] = array(
				'title' => $this->topic->sticky ? 'Unstick' : 'Stick',
				'href'  => Route::url('forum.topic', array(
					'action' => 'sticky',
					'id'     => $this->topic->id,
				)),
			);
		}

		if ($user->can('Forum_Topic_Lock'))
		{
			$actions[] = array(
				'title' => $this->topic->locked ? 'Unlock' : 'Lock',
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

}


<?php defined('SYSPATH') OR die('No direct script access.');

class View_Forum_Topic_Index extends View_Base {

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
				$options[] = array(
					'title' => $value->title,
					'votes' => $value->votes,
					'colour' => $colours[$key],
					'percent' => $topic['poll']['votes'] ? $value->votes/$topic['poll']['votes']*100 : 0,
				);
			}
			$topic['poll']['options'] = $options;
		}
		return $topic;
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
					'href'      => Route::url('user', array(
						'action' => 'view',
						'id'     => $post->user->id,
					)),
				),
				'links' => array(
					'edit' => Route::url('forum/post', array(
						'action' => 'edit',
						'id'     => $post->id,
					)),
					'delete' => Route::url('forum/post', array(
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
			'reply' => Route::url('forum/topic', array(
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
				'href'  => Route::url('forum/topic', array(
					'action' => 'delete',
					'id'     => $this->topic->id,
				)),
			);
		}

		if ($user->can('Forum_Topic_Sticky'))
		{
			$actions[] = array(
				'title' => $this->topic->sticky ? 'Unstick' : 'Stick',
				'href'  => Route::url('forum/topic', array(
					'action' => 'sticky',
					'id'     => $this->topic->id,
				)),
			);
		}

		if ($user->can('Forum_Topic_Lock'))
		{
			$actions[] = array(
				'title' => $this->topic->locked ? 'Unlock' : 'Lock',
				'href'  => Route::url('forum/topic', array(
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


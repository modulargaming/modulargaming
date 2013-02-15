<?php defined('SYSPATH') OR die('No direct script access.');

class View_Forum_Topic_Poll extends Abstract_View_Forum_Topic {

	public $title = 'Poll';

	public function options()
	{
		if (isset($this->poll))
		{
			$options = $this->poll->options->find_all()->as_array();
			foreach ($options as $key => $value) {
				$options[$key] = $value->as_array();
				$options[$key]['i'] = $key + 1;
			}
			$i = count($options);
			while ($i < 5)
			{
				$i ++;
				$options[] = array('title' => '', 'i' => $i);
			}
			return $options;
		}
	}

	protected function get_breadcrumb()
	{
		return array_merge(parent::get_breadcrumb(), array(
			array(
				'title' => 'Poll',
				'href' => Route::url('forum.topic', array(
					'id'     => $this->topic->id,
					'action' => 'poll'
				))
			)
		));
	}
}

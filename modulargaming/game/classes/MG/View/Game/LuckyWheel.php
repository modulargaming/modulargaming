<?php defined('SYSPATH') OR die('No direct script access.');

class MG_View_Game_LuckyWheel extends Abstract_View_Game {

	public $title = 'Lucky Wheel';

	public function get_breadcrumb()
	{
		return array_merge(parent::get_breadcrumb(), array(
			array(
				'title' => 'Lucky Wheel',
				'href'  => Route::url('games.lucky-wheel'
			))
		));
	}

	public function game()
	{
		return array(
			'plays'     => $this->game->plays,
			'last_play' => ($this->game->last_play ? Date::format($this->game->last_play) : 'Never'),
			'winnings'  => number_format($this->game->winnings)
		);
	}

}

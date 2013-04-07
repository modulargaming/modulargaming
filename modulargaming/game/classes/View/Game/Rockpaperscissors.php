<?php defined('SYSPATH') OR die('No direct script access.');

class View_Game_Rockpaperscissors extends Abstract_View_Game {

	public $title = 'Rock-paper-scissors';

	public function get_breadcrumb()
	{
		return array_merge(parent::get_breadcrumb(), array(
			array(
				'title' => 'Rock-paper-scissors',
				'href'  => Route::url('game', array(
					'controller' => 'rockpaperscissors',
				))
			)
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

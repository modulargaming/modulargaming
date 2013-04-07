<?php defined('SYSPATH') OR die('No direct script access.');

class View_Game_Index extends Abstract_View_Game {

	public $title = 'Games';

	public function links()
	{
		return array(
			'rockpaperscissors' => Route::url('game', array(
				'controller' => 'rockpaperscissors',
			)),
		);
	}

}

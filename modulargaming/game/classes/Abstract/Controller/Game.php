<?php defined('SYSPATH') OR die('No direct script access.');


class Abstract_Controller_Game extends Abstract_Controller_Frontend {

	protected $protected = TRUE;
	protected $game;
	protected $game_id;
	protected $max_plays;
	protected $play_timout;
	protected $win_points;
	protected $win_multiplier;

	public function before()
	{
		parent::before();
		$game = ORM::factory('Game')
			->where('game_id', '=', $this->game_id)
			->where('user_id', '=', $this->user->id)
			->find();
		if ( ! $game->loaded())
		{
			$game = ORM::factory('Game')
				->create_game(
					array(
						'game_id' => $this->game_id,
						'user_id' => $this->user->id
					),
					array(
						'game_id',
						'user_id'
					)
				);
		}
		$this->game = $game;
	}

	public function can_play()
	{
		return $this->game->can_play($this->max_plays, $this->play_timeout);
	}

}

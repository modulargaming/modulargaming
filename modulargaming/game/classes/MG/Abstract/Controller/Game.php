<?php defined('SYSPATH') OR die('No direct script access.');


class MG_Abstract_Controller_Game extends Abstract_Controller_Frontend {

	protected $protected = TRUE;
	protected $game;
	protected $game_id;
	protected $price;
	protected $max_plays;
	protected $play_timout;
	protected $win_points;
	protected $win_multiplier;

	public function before()
	{
		parent::before();
		if ($this->game_id)
		{
			$game = ORM::factory('User_Game')
				->where('game_id', '=', $this->game_id)
				->where('user_id', '=', $this->user->id)
				->find();
			if ( ! $game->loaded())
			{
				$game = ORM::factory('User_Game')
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
	}

	public function can_play()
	{
		$points = Kohana::$config->load('items.points');
		$initial_points = $points['initial'];
		if ($this->user->get_property('points', $initial_points) >= $this->price AND $this->game->can_play($this->max_plays, $this->play_timeout))
		{
			return TRUE;
		}
		return FALSE;
	}

	public function play_game($winnings)
	{
		if ( !$this->can_play())
		{
			return FALSE;
		}
		if ($this->price)
		{
			$points = Kohana::$config->load('items.points');
			$initial_points = $points['initial'];
			$this->user->set_property('points', $this->user->get_property('points', $initial_points) - $this->price);
		}
		$this->game->winnings = $winnings;
		$this->game->plays ++;
		$this->game->last_play = time();
		$this->game->save();
	}

}

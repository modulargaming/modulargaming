<?php defined('SYSPATH') OR die('No direct script access.');


class MG_Controller_Game_RockPaperScissors extends Abstract_Controller_Game {

	protected $game_id = 1;
	protected $max_plays = 5;
	protected $play_timeout = Date::DAY;
	protected $win_points = 10;
	protected $win_multiplier = 0.25;
	
	public function action_index()
	{
		$this->view = new View_Game_RockPaperScissors;
		$can_play = $this->can_play();
		$this->view->can_play = $can_play;
		if ($this->request->method() == HTTP_Request::POST AND $can_play)
		{
			try
			{
				$post = $this->request->post();
				if (isset($post['collect']) AND $this->game->winnings)
				{
					$this->game->collect_winnings(true);
					Hint::success('You have collected your winnings');
					$this->redirect(Route::url('games.rock-paper-scissors'));
				}
				$validation = Validation::factory($post)
   						 ->rule('move', 'not_empty')
   						 ->rule('move', 'in_array', array(':value', array('rock', 'paper', 'scissors')));

				if ($validation->check())
				{
					$play = $this->play($post['move'], $this->game);
					$this->view->play = $play;
				}
			}
			catch (ORM_Validation_Exception $e)
			{
				Hint::error($e->errors('models'));
			}
		}
		$this->view->game = $this->game;
	}

	private function play($choice, $game)
	{
		$win = 0;
    		$choices = array('rock', 'paper', 'scissors');
		$npc = $choices[mt_rand(0, 2)];
		if (($choice == 'rock' AND $npc == 'scissors') OR ($choice == 'paper' AND $npc == 'rock') OR ($choice == 'scissors' AND $npc == 'paper'))
		{
			$win = round($game->winnings * $this->win_multiplier + $this->win_points);
			$game->winnings = $game->winnings + $win;
		}
		else if ($choice != $npc)
		{
			$this->play_game(0);
		}
		$game->save();
		return array(
			'player' => $choice,
			'npc'    => $npc,
			'win'    => $win,
			'draw'   => $choice == $npc
		);
	}
}

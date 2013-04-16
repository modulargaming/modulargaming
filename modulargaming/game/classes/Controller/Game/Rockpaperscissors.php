<?php defined('SYSPATH') OR die('No direct script access.');


class Controller_Game_Rockpaperscissors extends Abstract_Controller_Game {

	public function action_index()
	{
		$this->view = new View_Game_Rockpaperscissors;
		$game = ORM::factory('Game')
			->where('game_id', '=', 1)
			->where('user_id', '=', $this->user->id)
			->find();
		if ( ! $game->loaded())
		{
			$game = ORM::factory('Game')
				->create_game(
					array(
						'game_id' => 1,
						'user_id' => $this->user->id
					),
					array(
						'game_id',
						'user_id'
					)
				);
		}
		$can_play = true;
		if ($game->plays >= 5)
		{
			if ($game->last_play > time() - 86400)
			{
				$can_play = false;
			}
			else
			{
				$game->plays = 0;
				$game->save();
			}
		}
		$this->view->can_play = $can_play;
		if ($this->request->method() == HTTP_Request::POST AND $can_play)
		{
			try
			{
				$post = $this->request->post();
				if (isset($post['collect']) AND $game->winnings)
				{
					$this->user->set_property('points', $this->user->get_property('points', 2000) + $game->winnings);
					$this->user->save();
					$game->winnings = 0;
					$game->plays ++;
					$game->last_play = time();
					$game->save();
					Hint::success('You have collected your winnings');
					$this->redirect(Route::url('game', array('controller' => 'rockpaperscissors')));
				}
				if (isset($post['move']) AND ($post['move'] == 'rock' OR $post['move'] == 'paper' OR $post['move'] == 'scissors'))
				{
					$player = $post['move'];
					$choices = array('rock', 'paper', 'scissors');
					$npc = $choices[mt_rand(0, 2)];
					$win = 0;
					if (($player == 'rock' AND $npc == 'scissors') OR ($player == 'paper' AND $npc == 'rock') OR ($player == 'scissors' AND $npc == 'paper'))
					{
						$win = round($game->winnings * 0.25 + 10);
						$game->winnings = $game->winnings + $win;
						$game->save();
					}
					else if ($player != $npc)
					{
						$game->winnings = 0;
						$game->plays ++;
						$game->last_play = time();
						$game->save();
					}
					$play = array(
						'player' => $player,
						'npc'    => $npc,
						'win'    => $win,
						'draw'   => $player == $npc
					);
					$this->view->play = $play;
				}
			}
			catch (ORM_Validation_Exception $e)
			{
				Hint::error($e->errors('models'));
			}
		}
		$this->view->game = $game;
	}

}

<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_Game extends ORM
{

	protected $_table_columns = array(
		'id'          => NULL,
		'game_id'     => NULL,
		'user_id'     => NULL,
		'plays'       => NULL,
		'last_play'   => NULL,
		'winnings'      => NULL
	);

	public function create_game($values, $expected)
	{
		return $this->values($values, $expected)
			->create();
	}



} // End Game Model

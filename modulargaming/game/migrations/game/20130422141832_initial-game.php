<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Initial game
 */
class Migration_Game_20130422141832 extends Minion_Migration_Base {

	/**
	 * Run queries needed to apply this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function up(Kohana_Database $db)
	{
		$db->query(NULL, "
			CREATE TABLE IF NOT EXISTS `user_games` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `game_id` int(11) NOT NULL,
			  `user_id` int(11) NOT NULL,
			  `plays` int(1) NOT NULL,
			  `last_play` int(10) NOT NULL,
			  `winnings` int(11) NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
		");
	}

	/**
	 * Run queries needed to remove this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function down(Kohana_Database $db)
	{
		$db->query(NULL, 'DROP TABLE user_games');
	}

}

<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Initial title
 */
class Migration_User_20130205225721 extends Minion_Migration_Base {

	/**
	 * Run queries needed to apply this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function up(Kohana_Database $db)
	{
		$db->query(NULL, "
			CREATE TABLE IF NOT EXISTS `user_titles` (
			  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `title` varchar(55) NOT NULL,
			  `description` varchar(255) NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
		");

		$db->query(NULL, "
			INSERT INTO `user_titles` (`id`, `title`, `description`) VALUES
			(1, 'User', 'Standard User'),
			(2, 'Administrator', 'Administrator user');
		");
	}

	/**
	 * Run queries needed to remove this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function down(Kohana_Database $db)
	{
		$db->query(NULL, 'DROP TABLE user_titles');
	}

}

<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Initial message
 */
class Migration_Message_20130206230208 extends Minion_Migration_Base {

	/**
	 * Run queries needed to apply this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function up(Kohana_Database $db)
	{
		$db->query(NULL, "
			CREATE TABLE IF NOT EXISTS `messages` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `sender_id` int(11) NOT NULL,
			  `receiver_id` int(11) NOT NULL,
			  `created` int(10) NOT NULL,
			  `subject` varchar(55) NOT NULL,
			  `content` mediumtext NOT NULL,
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
		$db->query(NULL, 'DROP TABLE messages');
	}

}

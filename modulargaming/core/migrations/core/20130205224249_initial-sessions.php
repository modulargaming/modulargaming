<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Initial sessions
 */
class Migration_Core_20130205224249 extends Minion_Migration_Base {

	/**
	 * Run queries needed to apply this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function up(Kohana_Database $db)
	{
		$db->query(NULL, "
			CREATE TABLE IF NOT EXISTS `sessions` (
			  `session_id` varchar(24) NOT NULL,
			  `last_active` int(10) unsigned NOT NULL,
			  `contents` text NOT NULL,
			  PRIMARY KEY (`session_id`),
			  KEY `last_active` (`last_active`)
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
		$db->query(NULL, 'DROP TABLE sessions');
	}

}

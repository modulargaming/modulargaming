<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * initial-properties
 */
class Migration_User_20130213231122 extends Minion_Migration_Base {

	/**
	 * Run queries needed to apply this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function up(Kohana_Database $db)
	{
		$db->query(NULL, "
			CREATE TABLE `user_properties` (
			  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `user_id` int(11) unsigned NOT NULL,
			  `key` varchar(255) NOT NULL DEFAULT '',
			  `value` text,
			  PRIMARY KEY (`id`),
			  UNIQUE KEY `uniq_user_id_key` (`user_id`,`key`),
			  KEY `fk_user_id` (`user_id`),
			  CONSTRAINT `user_properties_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		");
	}

	/**
	 * Run queries needed to remove this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function down(Kohana_Database $db)
	{
		$db->query(NULL, 'DROP TABLE user_properties');
	}

}

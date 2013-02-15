<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Initial avatar
 */
class Migration_User_20130206231226 extends Minion_Migration_Base {

	/**
	 * Run queries needed to apply this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function up(Kohana_Database $db)
	{
		$db->query(NULL, "
			CREATE TABLE IF NOT EXISTS `avatars` (
			  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `title` varchar(30) NOT NULL,
			  `img` varchar(120) NOT NULL,
			  `default` tinyint(1) NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;
		");

		$db->query(NULL, "
			CREATE TABLE `users_avatars` (
			  `user_id` int(11) unsigned NOT NULL,
			  `avatar_id` int(11) unsigned NOT NULL,
			  PRIMARY KEY (`user_id`,`avatar_id`),
			  KEY `k_user` (`user_id`),
			  KEY `k_avatar` (`avatar_id`),
			  CONSTRAINT `users_avatars_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
			  CONSTRAINT `users_avatars_ibfk_2` FOREIGN KEY (`avatar_id`) REFERENCES `avatars` (`id`) ON DELETE CASCADE
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;
		");
	}

	/**
	 * Run queries needed to remove this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function down(Kohana_Database $db)
	{
		$db->query(NULL, ' SET FOREIGN_KEY_CHECKS = 0');
		$db->query(NULL, 'DROP TABLE IF EXISTS `users_avatars`;');
		$db->query(NULL, ' SET FOREIGN_KEY_CHECKS = 1');
		$db->query(NULL, 'DROP TABLE `avatars`;');
	}

}

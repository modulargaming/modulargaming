<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Initial user scheme
 */
class Migration_User_20130205223355 extends Minion_Migration_Base {

	/**
	 * Run queries needed to apply this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function up(Kohana_Database $db)
	{
		$db->query(NULL, "
			CREATE TABLE `users` (
			  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `email` varchar(127) NOT NULL,
			  `username` varchar(32) NOT NULL DEFAULT '',
			  `password` char(64) NOT NULL,
			  `title_id` int(11) unsigned NOT NULL DEFAULT '1',
			  `logins` int(10) unsigned NOT NULL DEFAULT '0',
			  `created` int(10) unsigned DEFAULT NULL,
			  `last_login` int(10) unsigned DEFAULT NULL,
			  `timezone_id` int(11) unsigned DEFAULT NULL,
			  `signature` varchar(255) DEFAULT NULL,
			  `about` mediumtext,
			  `avatar` varchar(255) DEFAULT NULL,
			  `gravatar` int(1) DEFAULT NULL,
			  `post_count` int(10) unsigned DEFAULT NULL,
			  `points` int(10) unsigned DEFAULT NULL,
			  `avatar_id` int(11) unsigned DEFAULT NULL,
			  PRIMARY KEY (`id`),
			  UNIQUE KEY `uniq_username` (`username`),
			  UNIQUE KEY `uniq_email` (`email`)
			) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
		");

	}

	/**
	 * Run queries needed to remove this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function down(Kohana_Database $db)
	{
		$db->query('NULL', 'DROP TABLE users');
	}

}

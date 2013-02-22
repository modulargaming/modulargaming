<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Logs & notifications
 */
class Migration_User_20130210232021 extends Minion_Migration_Base {

	/**
	 * Run queries needed to apply this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function up(Kohana_Database $db)
	{
		 $db->query(NULL, "
		 	CREATE TABLE `user_notification_icons` (
			  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		 	  `name` varchar(35) DEFAULT NULL,
		 	  `image` varchar(90) DEFAULT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
		 $db->query(NULL, "
			CREATE TABLE `logs` (
			  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
			  `user_id` int(11) UNSIGNED DEFAULT NULL,
			  `location` varchar(255) DEFAULT NULL,
		 	  `alias` varchar(90) DEFAULT NULL,
		 	  `agent` varchar(80) DEFAULT NULL,
		 	  `ip` varchar(45) DEFAULT NULL,
		 	  `type` varchar(14) DEFAULT NULL,
			  `time` int(10) unsigned DEFAULT NULL,
			  `message` text,
			  `params` text,
			  PRIMARY KEY (`id`),
			  KEY `logs_user` (`user_id`),
		 	  CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
		 $db->query(NULL, "
		 	CREATE TABLE `user_notifications` (
			  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
			  `log_id` int(11) UNSIGNED DEFAULT NULL,
			  `user_id` int(11) UNSIGNED DEFAULT NULL,
			  `title` varchar(255) DEFAULT NULL,
		 	  `icon` varchar(35) DEFAULT NULL,
			  `message` text,
			  `type` enum('success','alert','warning', 'info') DEFAULT NULL,
		 	  `read` enum('0','1') DEFAULT NULL,
			  PRIMARY KEY (`id`),
			  KEY `notifications_user` (`user_id`),
			  KEY `notifications_log` (`log_id`),
		 	  CONSTRAINT `user_notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
		 	  CONSTRAINT `user_notifications_ibfk_2` FOREIGN KEY (`log_id`) REFERENCES `logs` (`id`) ON DELETE CASCADE
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
	}

	/**
	 * Run queries needed to remove this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function down(Kohana_Database $db)
	{
		 $db->query(NULL, ' SET FOREIGN_KEY_CHECKS = 0');
		 $db->query(NULL, 'DROP TABLE `user_notifications` ');
		 $db->query(NULL, ' SET FOREIGN_KEY_CHECKS = 1');
		 $db->query(NULL, 'DROP TABLE `user_notification_icons` ');
		 $db->query(NULL, 'DROP TABLE `logs` ');
	}

}

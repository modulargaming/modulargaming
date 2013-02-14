<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Initial roles scheme
 */
class Migration_User_20130205224618 extends Minion_Migration_Base {

	/**
	 * Run queries needed to apply this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function up(Kohana_Database $db)
	{
		$db->query(NULL, "
			CREATE TABLE IF NOT EXISTS `roles` (
			  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
			  `name` VARCHAR(32) NOT NULL,
			  `description` VARCHAR(255) NOT NULL,
			  PRIMARY KEY (`id`),
			  UNIQUE KEY `uniq_name` (`name`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
		");

		$db->query(NULL, "
			INSERT INTO `roles` (`id`, `name`, `description`) VALUES
			(1, 'login', 'Login privileges, granted after account confirmation'),
			(2, 'admin', 'Administrative user, has access to everything.');
		");

		$db->query(NULL, "
			CREATE TABLE IF NOT EXISTS `roles_users` (
			  `user_id` INT(10) UNSIGNED NOT NULL,
			  `role_id` INT(10) UNSIGNED NOT NULL,
			  PRIMARY KEY (`user_id`,`role_id`),
			  KEY `fk_role_id` (`role_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
		");

		$db->query(NULL, "
			ALTER TABLE `roles_users`
			  ADD CONSTRAINT `roles_users_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
			  ADD CONSTRAINT `roles_users_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
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
		$db->query(NULL, 'DROP TABLE roles_users');
		$db->query(NULL, ' SET FOREIGN_KEY_CHECKS = 1');
		$db->query(NULL, 'DROP TABLE roles');
	}

}

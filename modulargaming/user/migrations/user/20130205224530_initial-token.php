<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Initial token scheme
 */
class Migration_User_20130205224530 extends Minion_Migration_Base {

	/**
	 * Run queries needed to apply this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function up(Kohana_Database $db)
	{
		$db->query(NULL, "
			CREATE TABLE IF NOT EXISTS `user_tokens` (
			  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
			  `user_id` INT(11) UNSIGNED NOT NULL,
			  `user_agent` VARCHAR(40) NOT NULL,
			  `token` VARCHAR(40) NOT NULL,
			  `created` INT(10) UNSIGNED NOT NULL,
			  `expires` INT(10) UNSIGNED NOT NULL,
			  PRIMARY KEY (`id`),
			  UNIQUE KEY `uniq_token` (`token`),
			  KEY `fk_user_id` (`user_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
		");

		$db->query(NULL, "
			ALTER TABLE `user_tokens`
			  ADD CONSTRAINT `user_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
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
		$db->query(NULL, 'DROP TABLE user_tokens');
		$db->query(NULL, ' SET FOREIGN_KEY_CHECKS = 1');
	}

}

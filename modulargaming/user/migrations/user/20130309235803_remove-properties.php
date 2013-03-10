<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Remove properties
 */
class Migration_User_20130309235803 extends Minion_Migration_Base {

	/**
	 * Run queries needed to apply this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function up(Kohana_Database $db)
	{
		$db->query(NULL, "ALTER TABLE `users` DROP `avatar_id`");
		$db->query(NULL, "ALTER TABLE `users` DROP `points`");
		$db->query(NULL, "ALTER TABLE `users` DROP `post_count`");
		$db->query(NULL, "ALTER TABLE `users` DROP `avatar`");
		$db->query(NULL, "ALTER TABLE `users` DROP `signature`");
		$db->query(NULL, "ALTER TABLE `users` DROP `about`");
		$db->query(NULL, "ALTER TABLE `users` DROP `gravatar`");
		$db->query(NULL, "ALTER TABLE `users` MODIFY COLUMN `title_id` INT(11) UNSIGNED NOT NULL DEFAULT '1' AFTER `last_login`");
	}

	/**
	 * Run queries needed to remove this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function down(Kohana_Database $db)
	{
		// $db->query(NULL, 'DROP TABLE ... ');
	}

}

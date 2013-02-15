<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Alter user table for property
 */
class Migration_User_20130215192700 extends Minion_Migration_Base {

	/**
	 * Run queries needed to apply this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function up(Kohana_Database $db)
	{
		$db->query(NULL, '
			ALTER TABLE `users`
			ADD `cached_properties` TEXT  CHARACTER SET utf8  COLLATE utf8_general_ci  NULL  AFTER `avatar_id`;');
	}

	/**
	 * Run queries needed to remove this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function down(Kohana_Database $db)
	{
		$db->query(NULL, 'ALTER TABLE `users` DROP COLUMN cached_properties');
	}

}

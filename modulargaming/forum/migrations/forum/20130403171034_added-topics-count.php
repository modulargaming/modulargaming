<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * added topics count
 */
class Migration_Forum_20130403171034 extends Minion_Migration_Base {

	/**
	 * Run queries needed to apply this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function up(Kohana_Database $db)
	{
		$db->query(NULL, 'ALTER TABLE `forum_categories` ADD `topics_count` INT(10)  UNSIGNED  NOT NULL  AFTER `created`;');
	}


	/**
	 * Run queries needed to remove this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function down(Kohana_Database $db)
	{
		$db->query(NULL, 'ALTER TABLE `forum_categories` DROP `topics_count`;');
	}

}

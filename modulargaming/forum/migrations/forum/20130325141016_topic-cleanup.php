<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * topic-cleanup
 */
class Migration_Forum_20130325141016 extends Minion_Migration_Base {

	/**
	 * Run queries needed to apply this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function up(Kohana_Database $db)
	{
		$db->query(NULL, 'ALTER TABLE `forum_topics` DROP `total`;');
		$db->query(NULL, 'ALTER TABLE `forum_topics` DROP `status`;');
		$db->query(NULL, 'ALTER TABLE `forum_topics` ADD `replies` INT(10)  UNSIGNED  NOT NULL  AFTER `locked`;');
		$db->query(NULL, 'ALTER TABLE `forum_topics` ADD `views` INT(10)  UNSIGNED  NOT NULL  AFTER `replies`;');
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

<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Cleaned up forum structure
 */
class Migration_Forum_20130207151900 extends Minion_Migration_Base {

	/**
	 * Run queries needed to apply this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function up(Kohana_Database $db)
	{
		$db->query(NULL, "ALTER TABLE `forum_poll_options` CHANGE `votes` `votes` INT(11)  UNSIGNED  NOT NULL");
		$db->query(NULL, "ALTER TABLE `forum_polls` CHANGE `votes` `votes` INT(11)  UNSIGNED  NOT NULL;");

		$db->query(NULL, "ALTER TABLE `forum_posts` CHANGE `updated` `updated` INT(10)  UNSIGNED  NOT NULL;");
		$db->query(NULL, "ALTER TABLE `forum_posts` CHANGE `created` `created` INT(10)  UNSIGNED  NOT NULL;");

		$db->query(NULL, "ALTER TABLE `forum_topics` CHANGE `created` `created` INT(10)  UNSIGNED  NOT NULL;");
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

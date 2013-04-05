<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Read and sent messages
 */
class Migration_Message_20130404219220 extends Minion_Migration_Base {
	/**
	 * Run queries needed to apply this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function up(Kohana_Database $db)
	{
		$db->query(NULL, '
			ALTER TABLE `messages`
			ADD `read` int(1) NOT NULL,
			ADD `sent` int(1) NOT NULL');
	}

	/**
	 * Run queries needed to remove this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function down(Kohana_Database $db)
	{
		$db->query(NULL, 'ALTER TABLE `messages` DROP COLUMN `read`, DROP COLUMN `sent`');
	}

}

<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Initial user shop
 */
class Migration_Item_20130214154757 extends Minion_Migration_Base {

	/**
	 * Run queries needed to apply this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function up(Kohana_Database $db)
	{
		 $db->query(NULL, "CREATE TABLE IF NOT EXISTS `user_shops` (
		  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
		  `user_id` int(11) UNSIGNED NOT NULL,
		  `title` varchar(70) NOT NULL,
		  `description` text NOT NULL,
		  `size` int(11) NOT NULL DEFAULT '0',
		  `till` int(11) NOT NULL DEFAULT '0',
		  PRIMARY KEY (`id`),
		  KEY `user_shops_user` (`user_id`),
		  CONSTRAINT `user_shops_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
		) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");
	}

	/**
	 * Run queries needed to remove this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function down(Kohana_Database $db)
	{
		 $db->query(NULL, 'DROP TABLE `user_shops` ');
	}

}

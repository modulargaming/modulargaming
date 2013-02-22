<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Initial user trades
 */
class Migration_Item_20130214155035 extends Minion_Migration_Base {

	/**
	 * Run queries needed to apply this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function up(Kohana_Database $db)
	{
		 $db->query(NULL, "CREATE TABLE `user_trades` (
			  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
			  `user_id` int(11) UNSIGNED NOT NULL,
			  `created` int(11) NOT NULL,
			  `description` varchar(144) NOT NULL,
			  PRIMARY KEY (`id`),
		 	  KEY `user_trades_user` (`user_id`),
		  	  CONSTRAINT `user_trades_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
			) ENGINE=InnoDB  DEFAULT CHARSET=latin1;");

			$db->query(NULL, "CREATE TABLE `user_trade_bids` (
			  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
			  `trade_id` int(11) UNSIGNED NOT NULL,
			  `user_id` int(11) UNSIGNED NOT NULL,
			  `points` int(11) NOT NULL,
			  `created` int(11) NOT NULL,
			  PRIMARY KEY (`id`),
		 	  KEY `user_trades_bids_trade` (`trade_id`),
		 	  KEY `user_trades_bids_user` (`user_id`),
		  	  CONSTRAINT `user_trade_bids_ibfk_1` FOREIGN KEY (`trade_id`) REFERENCES `user_trades` (`id`) ON DELETE CASCADE,
		  	  CONSTRAINT `user_trade_bids_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
	}

	/**
	 * Run queries needed to remove this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function down(Kohana_Database $db)
	{
		$db->query(NULL, ' SET FOREIGN_KEY_CHECKS = 0');
		 $db->query(NULL, 'DROP TABLE `user_trades`, `user_trade_bids`;');
		 $db->query(NULL, ' SET FOREIGN_KEY_CHECKS = 1');
	}

}

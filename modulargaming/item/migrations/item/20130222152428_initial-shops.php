<?php defined('SYSPATH') OR die('No direct script access.');

	/**
	 * Initial DB dump for shops
	 */
class Migration_Item_20130222152428 extends Minion_Migration_Base {

	/**
	 * Run queries needed to apply this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function up(Kohana_Database $db)
	{
		$db->query(NULL, "CREATE TABLE IF NOT EXISTS `shops` (
		  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  `title` varchar(60) NOT NULL,
		  `npc_img` varchar(100) NOT NULL,
		  `npc_text` varchar(144) NOT NULL,
		  `stock_type` enum('restock','steady') NOT NULL,
		  `stock_cap` smallint(3) NOT NULL,
		  `status` enum('closed','open') NOT NULL DEFAULT 'closed',
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");

		$db->query(NULL, "CREATE TABLE IF NOT EXISTS `shop_inventories` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `shop_id` int(10) unsigned NOT NULL,
		  `item_id` int(10) unsigned NOT NULL,
		  `price` int(10) unsigned NOT NULL,
		  `stock` smallint(3) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");

		$db->query(NULL, "CREATE TABLE IF NOT EXISTS `shop_restocks` (
		  `id` int(11) NOT NULL,
		  `shop_id` int(10) unsigned NOT NULL,
		  `item_id` int(10) unsigned NOT NULL,
		  `frequency` int(10) unsigned NOT NULL,
		  `next_restock` int(10) unsigned NOT NULL,
		  `min_price` int(10) unsigned NOT NULL,
		  `max_price` int(10) unsigned NOT NULL,
		  `min_amount` smallint(3) unsigned NOT NULL COMMENT 'minimum amount to restock',
		  `max_amount` smallint(3) unsigned NOT NULL COMMENT 'maximum amount to restock',
		  `cap_amount` smallint(3) unsigned NOT NULL COMMENT 'the max amount of this item may be present in the shop'
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
	}

	/**
	 * Run queries needed to remove this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function down(Kohana_Database $db)
	{
		$db->query(NULL, 'DROP TABLE `shops` ');
		$db->query(NULL, 'DROP TABLE `shop_inventories` ');
		$db->query(NULL, 'DROP TABLE `shop_restocks` ');
	}

}

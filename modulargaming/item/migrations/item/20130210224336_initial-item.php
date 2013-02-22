<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Initial Item schema
 * @author happydemon
 *
 */
class Migration_Item_20130210224336 extends Minion_Migration_Base {

	/**
	 * Run queries needed to apply this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function up(Kohana_Database $db)
	{
		//set up items
		 $db->query(NULL, "CREATE TABLE IF NOT EXISTS `items` (
			  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `type_id` int(11) unsigned NOT NULL,
			  `name` varchar(50) NOT NULL,
			  `description` text NOT NULL,
			  `image` varchar(200) NOT NULL,
			  `status` enum('draft','released','retired') NOT NULL DEFAULT 'draft',
			  `unique` tinyint(1) NOT NULL,
			  `transferable` tinyint(1) NOT NULL,
			  `commands` text NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");

		 	$db->query(NULL, "CREATE TABLE IF NOT EXISTS `item_types` (
			  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `name` varchar(50) NOT NULL,
			  `action` varchar(200) NOT NULL,
			  `default_command` varchar(100) NOT NULL,
			  `img_dir` varchar(50) NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;	");

		 	$db->query(NULL, "CREATE TABLE IF NOT EXISTS `user_items` (
			  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `item_id` int(11) unsigned NOT NULL,
			  `user_id` int(11) unsigned NOT NULL,
			  `amount` int(11) NOT NULL,
			  `location` varchar(60) NOT NULL,
			  `parameter` varchar(255) NOT NULL COMMENT 'e.g. when location is usershop this would contain its price',
			  `parameter_id` int(11) NOT NULL,
			  PRIMARY KEY (`id`),
			  KEY `k_item` (`item_id`),
			  KEY `k_user` (`user_id`),
		 	  CONSTRAINT `user_items_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
		 	  CONSTRAINT `user_items_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
			) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
		 ");

		 //setup item recipes
		 $db->query(null, "
			CREATE TABLE IF NOT EXISTS `item_recipes` (
			  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `name` varchar(50) NOT NULL,
			  `description` varchar(200) NOT NULL,
			  `crafted_item_id` int(11) unsigned NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
			");

		 	$db->query(NULL, "
			CREATE TABLE IF NOT EXISTS `item_recipe_materials` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `item_recipe_id` int(11)unsigned  NOT NULL,
			  `item_id` int(11) unsigned NOT NULL,
			  `amount` int(11) NOT NULL,
			  PRIMARY KEY (`id`),
			  KEY `key_item` (`item_id`),
			  KEY `key_recipe` (`item_recipe_id`),
		 	  CONSTRAINT `item_recipe_materials_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
		 	  CONSTRAINT `item_recipe_materials_ibfk_2` FOREIGN KEY (`item_recipe_id`) REFERENCES `item_recipes` (`id`) ON DELETE CASCADE
			) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
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
		 $db->query(NULL, "DROP TABLE IF EXISTS `items`, `item_types`, `user_items`, `item_recipes`, `item_recipe_materials`");
		 $db->query(NULL, ' SET FOREIGN_KEY_CHECKS = 1');
	}

}

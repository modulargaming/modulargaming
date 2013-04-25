# Migrations

Migrations are a convenient way for you to alter your database in a structured and organized manner. You could edit fragments of SQL by hand but you would then be responsible for telling other developers that they need to go and run them. You’d also have to keep track of which changes need to be run against the production machines next time you deploy.


		<?php defined('SYSPATH') OR die('No direct script access.');
		
			/**
	 		* Initial database for Module
	 		*/
		class Migration_Module_20130222152428 extends Minion_Migration_Base {
		
			/**
	 		* Run queries needed to apply this migration
	 		*
	 		* @param Kohana_Database $db Database connection
	 		*/
			public function up(Kohana_Database $db)
			{
				$db->query(NULL, "CREATE TABLE IF NOT EXISTS `module` (
		  		`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		 		`user_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  		PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");

				 $db->query(NULL, "INSERT INTO `item_types` (`id`, `user_id`) VALUES
					(1, 1),
					(1, 2),
					(1, 3),
					(1, 4),
					(1, 5);");

			}

		
			/**
	 		* Run queries needed to remove this migration
	 		*
	 		* @param Kohana_Database $db Database connection
	 		*/
			public function down(Kohana_Database $db)
			{
				$db->query(NULL, 'DROP TABLE `module` ');
				$db->query(NULL, "DELETE FROM `module` WHERE id IN(1,2,3,4,5)");
			}
		
		}

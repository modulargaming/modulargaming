<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Initial pet schema (species, colours, user pets)
 * @author happydemon
 *
 */
class Migration_Pet_20130210230444 extends Minion_Migration_Base {

	/**
	 * Run queries needed to apply this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function up(Kohana_Database $db)
	{
		 $db->query(NULL, "
		 	CREATE TABLE IF NOT EXISTS `pet_species` (
			  `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
			  `name` varchar(35) NOT NULL,
			  `dir` varchar(40) NOT NULL,
			  `description` text NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");

		 $db->query(NULL, "
			INSERT INTO `pet_species` (`id`, `name`, `dir`, `description`) VALUES
				(1, 'Koorai', '1', 'The Koorai'),
				(2, 'Zedro', '2', 'The Zedro.');");

		 $db->query(NULL, "
		 	CREATE TABLE IF NOT EXISTS `pet_colours` (
			  `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
			  `locked` int(1) NOT NULL,
			  `name` varchar(35) NOT NULL,
			  `description` text NOT NULL,
			  `image` varchar(50) NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;");

		 $db->query(NULL, "
			INSERT INTO `pet_colours` (`id`, `locked`, `name`, `description`, `image`) VALUES
				(1, 0, 'Black', 'Black colour', 'black.png'),
				(2, 0, 'Blue', 'Blue colour', 'blue.png'),
				(3, 0, 'Green', 'Green colour', 'green.png'),
				(4, 0, 'Red', 'Red colour', 'red.png'),
				(5, 0, 'White', 'White colour', 'white.png'),
				(6, 0, 'Yellow', 'Yellow colour', 'yellow.png'),
				(7, 1, 'Outline', 'Special outline colour', 'outline.png');");



		 $db->query(NULL, "
		 	CREATE TABLE IF NOT EXISTS `pet_species_colours` (
			  `specie_id` int(11) UNSIGNED NOT NULL,
			  `colour_id` int(11) UNSIGNED NOT NULL,
			  KEY `pet_species_colours_specie` (`specie_id`),
			  KEY `pet_species_colours_colour` (`colour_id`),
		 	  CONSTRAINT `pet_species_colours_ibfk_1` FOREIGN KEY (`specie_id`) REFERENCES `pet_species` (`id`) ON DELETE CASCADE,
		 	  CONSTRAINT `pet_species_colours_ibfk_2` FOREIGN KEY (`colour_id`) REFERENCES `pet_colours` (`id`) ON DELETE CASCADE
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;
		 ");

		 $db->query(NULL, "
		 	INSERT INTO  `pet_species_colours` (`specie_id` , `colour_id`)
			VALUES ('1',  '1'), ('1',  '2'), ('1',  '3'),
				('1',  '4'), ('1',  '5'), ('1',  '6'), ('2',  '1'),
				('2',  '2'), ('2',  '3'), ('2',  '4'), ('2',  '5'), ('2',  '6');");

		 $db->query(NULL, "
		 	CREATE TABLE IF NOT EXISTS `user_pets` (
			  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
			  `user_id` int(11) UNSIGNED DEFAULT NULL,
			  `created` int(10) NOT NULL,
			  `abandoned` int(10) NOT NULL,
			  `active` int(10) NOT NULL,
			  `name` varchar(35) NOT NULL,
			  `gender` enum('male','female') NOT NULL,
			  `specie_id` int(6) UNSIGNED NOT NULL,
			  `colour_id` int(6) UNSIGNED NOT NULL,
			  `hunger` int(3) NOT NULL DEFAULT 100,
			  `happiness` int(3) NOT NULL DEFAULT 100,
			  PRIMARY KEY (`id`),
			  KEY `user_pets_user` (`user_id`),
		 	  KEY `user_pets_pet` (`specie_id`),
		 	  KEY `user_pets_colour` (`colour_id`),
		 	  CONSTRAINT `user_pets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
		 	  CONSTRAINT `user_pets_ibfk_2` FOREIGN KEY (`specie_id`) REFERENCES `pet_species` (`id`) ON DELETE CASCADE,
		 	  CONSTRAINT `user_pets_ibfk_3` FOREIGN KEY (`colour_id`) REFERENCES `pet_colours` (`id`) ON DELETE CASCADE
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");
	}

	/**
	 * Run queries needed to remove this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function down(Kohana_Database $db)
	{
		$db->query(NULL, "DROP TABLE IF EXISTS `pet_species`, `pet_colours`, `user_pets`, `pet_species_colours`;");
	}

}

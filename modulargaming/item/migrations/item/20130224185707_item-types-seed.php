<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * "item types seed"
 */
class Migration_Item_20130224185707 extends Minion_Migration_Base {

	/**
	 * Run queries needed to apply this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function up(Kohana_Database $db)
	{
		 $db->query(NULL, "INSERT INTO `item_types` (`id`, `name`, `action`, `default_command`, `img_dir`) VALUES
			(1, 'Food', 'feed to :pet_name', 'Item_Command_Pet_Feed', 'foods/'),
			(2, 'Toy', 'play with :pet_name', 'Item_Command_Pet_Play', 'toys/'),
			(3, 'Recipe', 'move to cookbook', 'Item_Command_General_Cook', 'recipes/'),
			(4, 'Paint', 'paint :pet_name', 'Item_Command_Pet_Paint', 'paint/'),
			(5, 'Transform', 'transform :pet_name', 'Item_Command_Pet_Transform', 'transform/');");
	}

	/**
	 * Run queries needed to remove this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function down(Kohana_Database $db)
	{
		 $db->query(NULL, "DELETE FROM `item_types` WHERE id IN(1,2,3,4,5)");
	}

}

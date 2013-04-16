<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * "item shops seed"
 */
class Migration_Item_20130416092400 extends Minion_Migration_Base {

	/**
	 * Run queries needed to apply this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function up(Kohana_Database $db)
	{
		 $db->query(NULL, 
"INSERT INTO `shops` (`id`, `title`, `npc_img`, `npc_text`, `stock_type`, `stock_cap`, `status`) VALUES
(1, 'Food Shop', 'food_NPC.png', 'Welcome to the food shop', 'restock', 25, 'open'),
(2, 'Paint Brush Shop', 'paintbrush_npc.png', 'Paint your Pet', 'restock', 25, 'open'),
(3, 'Recipe Shop', 'Recipe_NPC.png', 'Recipies', 'restock', 25, 'open'),
(4, 'Toy Shop', 'Toy_NPC.png', 'Toys for your Pet', 'restock', 25, 'open'),
(5, 'Transform Shop', 'Transform_NPC.png', 'Transform your Pet', 'restock', 25, 'open');
");
	}

	/**
	 * Run queries needed to remove this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function down(Kohana_Database $db)
	{
		 $db->query(NULL, "DELETE FROM `shops` WHERE id IN(1,2,3,4,5)");
	}

}

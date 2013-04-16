<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * "item shops inventory seed"
 */
class Migration_Item_201304161616 extends Minion_Migration_Base {

	/**
	 * Run queries needed to apply this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function up(Kohana_Database $db)
	{
		 $db->query(NULL, 
"INSERT INTO `shop_inventories` (`id`, `shop_id`, `item_id`, `price`, `stock`) VALUES

(1, 2, 1, 100, 25),
(2, 2, 2, 100, 25),
(3, 2, 3, 100, 25),
(4, 2, 4, 100, 25),
(5, 2, 5, 100, 25),
(6, 2, 6, 100, 25),
(7, 2, 7, 1000, 25),

(8, 1, 8, 100, 25),
(9, 1, 9, 100, 25),
(10, 1, 10, 100, 25),
(11, 1, 11, 100, 25),
(12, 1, 12, 100, 25),
(13, 1, 13, 100, 25),
(14, 1, 14, 100, 25),
(15, 1, 15, 100, 25),
(16, 1, 16, 100, 25),

(17, 3, 17, 100, 25),
(18, 3, 18, 100, 25),
(19, 3, 19, 100, 25),
(20, 3, 20, 100, 25),

(21, 4, 21, 100, 25),
(22, 4, 22, 100, 25),
(23, 4, 23, 100, 25),
(24, 4, 24, 100, 25),
(25, 4, 25, 100, 25),

(26, 5, 26, 500, 25),
(27, 5, 27, 500, 25);
");
	}

	/**
	 * Run queries needed to remove this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function down(Kohana_Database $db)
	{
		 $db->query(NULL, "DELETE FROM `shop_inventories` WHERE id IN(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27)");
	}

}

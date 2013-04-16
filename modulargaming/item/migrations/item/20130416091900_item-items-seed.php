<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * "items seed"
 */
class Migration_Item_20130416091900 extends Minion_Migration_Base {

	/**
	 * Run queries needed to apply this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function up(Kohana_Database $db)
	{
		 $db->query(NULL,"INSERT INTO `items` (`id`, `type_id`, `name`, `description`, `image`, `status`, `unique`, `transferable`, `commands`) VALUES
(1, 4, 'Black Paintbrush', 'Paint your pet Black', 'paintbrush black.png', 'released', 0, 1, '[{\"name\":\"Pet_Paint\",\"param\":\"Black\"}]'),
(2, 4, 'Blue Paintbrush', 'Paint your pet Blue', 'paintbrush blue.png', 'released', 0, 1, '[{\"name\":\"Pet_Paint\",\"param\":\"Blue\"}]'),
(3, 4, 'Green Paintbrush', 'Paint your pet Green', 'paintbrush green.png', 'released', 0, 1, '[{\"name\":\"Pet_Paint\",\"param\":\"Green\"}]'),
(4, 4, 'Outline Paintbrush', 'Paint your pet Outline', 'paintbrush outline.png', 'released', 0, 1, '[{\"name\":\"Pet_Paint\",\"param\":\"Outline\"}]'),
(5, 4, 'Red Paintbrush', 'Paint your pet Red', 'paintbrush red.png', 'released', 0, 1, '[{\"name\":\"Pet_Paint\",\"param\":\"Red\"}]'),
(6, 4, 'White Paintbrush', 'Paint your pet White', 'paintbrush white.png', 'released', 0, 1, '[{\"name\":\"Pet_Paint\",\"param\":\"White\"}]'),
(7, 4, 'Yellow Paintbrush', 'Paint your pet Yellow', 'paintbrush yellow.png', 'released', 0, 1, '[{\"name\":\"Pet_Paint\",\"param\":\"Yellow\"}]'),
(8, 1, 'Pinkberry Smoothie', 'Pinkberry Smoothie', 'pinkberry smoothie.png', 'released', 0, 1, '[{\"name\":\"Pet_Feed\",\"param\":\"10\"}]'),
(9, 1, 'Apple', 'An Apple', 'apple.png', 'released', 0, 1, '[{\"name\":\"Pet_Feed\",\"param\":\"10\"}]'),
(10, 1, 'Banana', 'A Banana', 'banana.png', 'released', 0, 1, '[{\"name\":\"Pet_Feed\",\"param\":\"10\"}]'),
(11, 1, 'Cake', 'A Cake', 'cake.png', 'released', 0, 1, '[{\"name\":\"Pet_Feed\",\"param\":\"10\"}]'),
(12, 1, 'Flank', 'A Meat', 'flank.png', 'released', 0, 1, '[{\"name\":\"Pet_Feed\",\"param\":\"25\"}]'),
(13, 1, 'Oreo', 'A Oreo', 'oreo.png', 'released', 0, 1, '[{\"name\":\"Pet_Feed\",\"param\":\"10\"}]'),
(14, 1, 'Oreo Pie', 'An Oreo Pie', 'oreo pie.png', 'released', 0, 1, '[{\"name\":\"Pet_Feed\",\"param\":\"25\"}]'),
(15, 1, 'Sandwich', 'A Sandwich', 'sandwich.png', 'released', 0, 1, '[{\"name\":\"Pet_Feed\",\"param\":\"15\"}]'),
(16, 1, 'Strawberry', 'A Strawberry', 'strawberry.png', 'released', 0, 1, '[{\"name\":\"Pet_Feed\",\"param\":\"10\"}]'),
(17, 1, 'Brownie Mix', 'A Brownie Mix. Mix this with Oreo to make a Oreo Pie.', 'brownie mix.png', 'released', 0, 1, '[{\"name\":\"Pet_Feed\",\"param\":\"10\"}]'),
(18, 1, 'Pink Lemonade Mix', 'A Pink Lemonade Mix', 'pink lemonade mix.png', 'released', 0, 1, '[{\"name\":\"Pet_Feed\",\"param\":\"5\"}]'),
(19, 1, 'Swirly Straw', 'A Swirly Straw. Mix this with Lemonade and a Whisk to make a Smoothie', 'swirly straw.png', 'released', 0, 1, '[{\"name\":\"Pet_Feed\",\"param\":\"1\"}]'),
(20, 1, 'Whisk', 'A Whisk. Mix this with Lemonade and a Swirly Straw to make a Smoothie', 'whisk.png', 'released', 0, 1, '[{\"name\":\"Pet_Feed\",\"param\":\"1\"}]'),
(21, 2, 'Blocks', 'Blocks to play with.', 'blocks.png', 'released', 0, 1, '[{\"name\":\"Pet_Play\",\"param\":\"10\"}]'),
(22, 2, 'Bouncy Ball', 'A Bouncy Ball to play with.', 'bouncy ball.png', 'released', 0, 1, '[{\"name\":\"Pet_Play\",\"param\":\"10\"}]'),
(23, 2, 'Cards', 'Cards to play with', 'cards.png', 'released', 0, 1, '[{\"name\":\"Pet_Play\",\"param\":\"10\"}]'),
(24, 2, 'Paddle Ball', 'A Paddle Ball to play with.', 'paddle ball.png', 'released', 0, 1, '[{\"name\":\"Pet_Play\",\"param\":\"10\"}]'),
(25, 2, 'Yo-Yo', 'A Yo-Yo to play with.', 'yo-yo.png', 'released', 0, 1, '[{\"name\":\"Pet_Play\",\"param\":\"10\"}]'),
(26, 5, 'Transform Koorai', 'Transform your pet to a Koorai', 'transmorg 1.png', 'released', 0, 1, '[{\"name\":\"Pet_Transform\",\"param\":\"Koorai\"}]'),
(27, 5, 'Transform Zedro', 'Transform your pet to a Zedro', 'transmorg 2.png', 'released', 0, 1, '[{\"name\":\"Pet_Transform\",\"param\":\"Zedro\"}]');");
	}

	/**
	 * Run queries needed to remove this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function down(Kohana_Database $db)
	{
		 $db->query(NULL, "DELETE FROM `items` WHERE id IN(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27)");
	}

}

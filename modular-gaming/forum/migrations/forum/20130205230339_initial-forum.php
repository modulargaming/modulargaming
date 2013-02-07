<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Initial forum
 */
class Migration_Forum_20130205230339 extends Minion_Migration_Base {

	/**
	 * Run queries needed to apply this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function up(Kohana_Database $db)
	{
		$db->query(NULL, "
			CREATE TABLE IF NOT EXISTS `forum_categories` (
			  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `title` varchar(30) NOT NULL,
			  `description` varchar(50) NOT NULL,
			  `locked` int(1) NOT NULL,
			  `created` int(10) unsigned DEFAULT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
		");

		$db->query(NULL, "
			INSERT INTO `forum_categories` (`id`, `title`, `description`, `locked`) VALUES
			(1, 'News', 'Only admins can create topics here', 1),
			(2, 'General', 'General discussions', 0),
			(3, 'Marketplace', 'Buy and sell items', 0);
		");

		$db->query(NULL, "
			CREATE TABLE IF NOT EXISTS `forum_polls` (
			  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `topic_id` int(11) unsigned NOT NULL,
			  `title` varchar(255) NOT NULL,
			  `votes` int(11) NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
		");

		$db->query(NULL, "
			CREATE TABLE IF NOT EXISTS `forum_poll_options` (
			  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `poll_id` int(11) unsigned NOT NULL,
			  `title` varchar(255) NOT NULL,
			  `votes` int(11) NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
		");

		$db->query(NULL, "
			CREATE TABLE IF NOT EXISTS `forum_poll_votes` (
			  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `poll_id` int(11) unsigned NOT NULL,
			  `option_id` int(11) unsigned NOT NULL,
			  `user_id` int(11) unsigned NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
		");

		$db->query(NULL, "
			CREATE TABLE IF NOT EXISTS `forum_topics` (
			  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `category_id` int(11) unsigned NOT NULL,
			  `user_id` int(11) unsigned NOT NULL,
			  `title` varchar(30) NOT NULL,
			  `status` varchar(12) NOT NULL,
			  `total` int(6) NOT NULL,
			  `created` int(10) NOT NULL,
			  `last_post_id` int(11) unsigned NOT NULL,
			  `sticky` int(10) NOT NULL,
			  `locked` int(10) NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
		");

		$db->query(NULL, "
			CREATE TABLE IF NOT EXISTS `forum_posts` (
			  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `topic_id` int(11) unsigned NOT NULL,
			  `user_id` int(11) unsigned NOT NULL,
			  `content` text NOT NULL,
			  `created` int(10) NOT NULL,
			  `updated` int(10) NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
		");
	}

	/**
	 * Run queries needed to remove this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function down(Kohana_Database $db)
	{
		$db->query(NULL, 'DROP TABLE forum_categories');
		$db->query(NULL, 'DROP TABLE forum_polls');
		$db->query(NULL, 'DROP TABLE forum_poll_options');
		$db->query(NULL, 'DROP TABLE forum_poll_votes');
		$db->query(NULL, 'DROP TABLE forum_topics');
		$db->query(NULL, 'DROP TABLE forum_posts');
	}

}

<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * constraints
 */
class Migration_Forum_20130324204704 extends Minion_Migration_Base {

	/**
	 * Run queries needed to apply this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function up(Kohana_Database $db)
	{
		$db->query(NULL, 'ALTER TABLE `forum_poll_options` ADD INDEX `fk_poll_id` (`poll_id`);');
		$db->query(NULL, 'ALTER TABLE `forum_poll_options` ADD CONSTRAINT `forum_poll_options_ibfk_1` FOREIGN KEY (`poll_id`) REFERENCES `forum_polls` (`id`) ON DELETE CASCADE;');

		$db->query(NULL, 'ALTER TABLE `forum_poll_votes` ADD INDEX `fk_poll_id` (`poll_id`);');
		$db->query(NULL, 'ALTER TABLE `forum_poll_votes` ADD INDEX `fk_option_id` (`option_id`);');
		$db->query(NULL, 'ALTER TABLE `forum_poll_votes` ADD INDEX `fk_user_id` (`user_id`);');
		$db->query(NULL, 'ALTER TABLE `forum_poll_votes` ADD CONSTRAINT `forum_poll_votes_ibfk_1` FOREIGN KEY (`poll_id`) REFERENCES `forum_polls` (`id`) ON DELETE CASCADE;');
		$db->query(NULL, 'ALTER TABLE `forum_poll_votes` ADD CONSTRAINT `forum_poll_votes_ibfk_2` FOREIGN KEY (`option_id`) REFERENCES `forum_poll_options` (`id`) ON DELETE CASCADE;');
		$db->query(NULL, 'ALTER TABLE `forum_poll_votes` ADD CONSTRAINT `forum_poll_votes_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;');

		$db->query(NULL, 'ALTER TABLE `forum_polls` ADD INDEX `fk_topic_id` (`topic_id`);');
		$db->query(NULL, 'ALTER TABLE `forum_polls` ADD CONSTRAINT `forum_polls_ibfk_1` FOREIGN KEY (`topic_id`) REFERENCES `forum_topics` (`id`);');

		$db->query(NULL, 'ALTER TABLE `forum_posts` ADD INDEX `fk_topic_id` (`topic_id`);');
		$db->query(NULL, 'ALTER TABLE `forum_posts` ADD INDEX `fk_user_id` (`user_id`);');
		$db->query(NULL, 'ALTER TABLE `forum_posts` ADD CONSTRAINT `forum_posts_ibfk_1` FOREIGN KEY (`topic_id`) REFERENCES `forum_topics` (`id`) ON DELETE CASCADE;');
		$db->query(NULL, 'ALTER TABLE `forum_posts` ADD CONSTRAINT `forum_posts_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;');

		$db->query(NULL, 'ALTER TABLE `forum_topics` ADD INDEX `fk_category_id` (`category_id`);');
		$db->query(NULL, 'ALTER TABLE `forum_topics` ADD INDEX `fk_user_id` (`user_id`);');
		$db->query(NULL, 'ALTER TABLE `forum_topics` ADD CONSTRAINT `forum_topics_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `forum_categories` (`id`) ON DELETE CASCADE;');
		$db->query(NULL, 'ALTER TABLE `forum_topics` ADD CONSTRAINT `forum_topics_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;');
	}

	/**
	 * Run queries needed to remove this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function down(Kohana_Database $db)
	{
		// $db->query(NULL, 'DROP TABLE ... ');
	}

}

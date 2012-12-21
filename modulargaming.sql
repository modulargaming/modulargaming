CREATE TABLE IF NOT EXISTS `sessions` (
  `session_id` varchar(24) NOT NULL,
  `last_active` int(10) unsigned NOT NULL,
  `contents` text NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_active` (`last_active`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `roles` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(32) NOT NULL,
  `description` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_name` (`name`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `roles` (`id`, `name`, `description`) VALUES
(1, 'login', 'Login privileges, granted after account confirmation'),
(2, 'admin', 'Administrative user, has access to everything.');

CREATE TABLE IF NOT EXISTS `roles_users` (
  `user_id` INT(10) UNSIGNED NOT NULL,
  `role_id` INT(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `fk_role_id` (`role_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


CREATE TABLE IF NOT EXISTS `users` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(127) NOT NULL,
  `username` VARCHAR(32) NOT NULL DEFAULT '',
  `password` CHAR(64) NOT NULL,
  `logins` INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `last_login` INT(10) UNSIGNED DEFAULT NULL,
  `timezone_id` INT(11) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_username` (`username`),
  UNIQUE KEY `uniq_email` (`email`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


CREATE TABLE IF NOT EXISTS `user_tokens` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) UNSIGNED NOT NULL,
  `user_agent` VARCHAR(40) NOT NULL,
  `token` VARCHAR(32) NOT NULL,
  `created` INT(10) UNSIGNED NOT NULL,
  `expires` INT(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_token` (`token`),
  KEY `fk_user_id` (`user_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


ALTER TABLE `roles_users`
  ADD CONSTRAINT `roles_users_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `roles_users_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

ALTER TABLE `user_tokens`
  ADD CONSTRAINT `user_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

CREATE TABLE IF NOT EXISTS `forum_categories` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `title` varchar(30) NOT NULL,
  `description` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `forum_categories`
--

INSERT INTO `forum_categories` (`id`, `title`, `description`) VALUES
(1, 'General', 'General Discussions'),
(2, 'Marketplace', 'Buy and sell items.');


CREATE TABLE IF NOT EXISTS `forum_posts` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `topic_id` int(6) NOT NULL,
  `user_id` int(6) NOT NULL,
  `title` varchar(25) NOT NULL,
  `content` varchar(500) NOT NULL,
  `created` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `forum_topics` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `category_id` int(6) NOT NULL,
  `user_id` int(6) NOT NULL,
  `title` varchar(30) NOT NULL,
  `status` varchar(12) NOT NULL,
  `posts` int(6) NOT NULL,
  `created` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `time` int(10) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `text` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ;


CREATE TABLE `user_timezones` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `timezone` varchar(50) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO `user_timezones` (`id`, `timezone`, `name`) VALUES
(1, 'Europe/Stockholm', '(UTC+01:00) Stockholm'),
(2, 'Pacific/Midway', '(UTC-11:00) Midway Island');

CREATE TABLE IF NOT EXISTS `pets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `gender` enum('male','female') NOT NULL,
  `race_id` int(6) NOT NULL,
  `colour_id` int(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

INSERT INTO `pets` (`id`, `name`, `gender`, `race_id`, `colour_id`) VALUES
(1, 'Test', 'male', 1, 3),
(2, 'Test2', 'male', 2, 3),
(3, 'Test3', 'male', 2, 4);

CREATE TABLE IF NOT EXISTS `pet_colours` (
  `id` int(6) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` longtext NOT NULL,
  `image` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `pet_colours` (`id`, `name`, `description`, `image`) VALUES
(1, 'Black', 'Black colour', 'black.png'),
(2, 'Blue', 'Blue colour', 'blue.png'),
(3, 'Green', 'Green colour', 'green.png'),
(4, 'Red', 'Red colour', 'red.png'),
(5, 'White', 'White colour', 'white.png'),
(6, 'Yellow', 'Yellow colour', 'yellow.png');

CREATE TABLE IF NOT EXISTS `pet_races` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

INSERT INTO `pet_races` (`id`, `name`, `description`) VALUES
(1, 'Koorai', 'The Koorai'),
(2, 'Zedro', 'The Zedro.');

CREATE TABLE IF NOT EXISTS `user_pets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `pet_id` int(11) NOT NULL,
  `active` int(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pet_id` (`pet_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4;

INSERT INTO `user_pets` (`id`, `user_id`, `pet_id`, `active`) VALUES
(1, 4, 1, 0),
(2, 4, 2, 1),
(3, 4, 3, 0);


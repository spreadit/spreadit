SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(12) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(12) unsigned NOT NULL,
  `created_at` int(12) unsigned NOT NULL,
  `post_id` int(12) unsigned NOT NULL,
  `data` text NOT NULL,
  `updated_at` int(12) unsigned NOT NULL,
  `parent_id` int(12) unsigned NOT NULL,
  `upvotes` int(12) NOT NULL,
  `downvotes` int(12) NOT NULL,
  `markdown` text NOT NULL,
  `deleted_at` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `data` text NOT NULL,
  `markdown` text NOT NULL,
  `updated_at` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(12) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(12) unsigned NOT NULL,
  `type` tinyint(3) unsigned NOT NULL,
  `item_id` int(12) unsigned NOT NULL,
  `created_at` int(12) unsigned NOT NULL,
  `read` tinyint(1) NOT NULL DEFAULT '0',
  `updated_at` int(12) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `password_reminders` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  KEY `password_reminders_email_index` (`email`),
  KEY `password_reminders_token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(12) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(12) unsigned NOT NULL,
  `created_at` int(12) NOT NULL,
  `type` int(2) unsigned NOT NULL,
  `data` text NOT NULL,
  `updated_at` int(11) NOT NULL,
  `section_id` int(12) unsigned NOT NULL,
  `title` varchar(128) NOT NULL,
  `upvotes` int(12) NOT NULL,
  `downvotes` int(12) NOT NULL,
  `url` varchar(256) NOT NULL,
  `comment_count` int(10) unsigned NOT NULL,
  `markdown` text NOT NULL,
  `thumbnail` varchar(32) NOT NULL,
  `deleted_at` int(11) NOT NULL DEFAULT '0',
  `nsfw` int(11) NOT NULL,
  `nsfl` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `post_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `type` int(11) NOT NULL COMMENT '0=nsfw, 1=nsfl',
  `updown` int(11) NOT NULL COMMENT '-1, 1',
  PRIMARY KEY (`id`),
  KEY `post_id` (`post_id`,`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `sections` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `title` varchar(24) NOT NULL,
  `created_at` int(12) NOT NULL,
  `updated_at` int(12) NOT NULL,
  `data` text NOT NULL,
  `upvotes` int(12) NOT NULL,
  `downvotes` int(12) NOT NULL,
  `markdown` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

INSERT INTO `sections` (`id`, `title`, `created_at`, `updated_at`, `data`, `upvotes`, `downvotes`, `markdown`) VALUES
(0, 'all', 1404501194, 1404501194, 'everything!', 0, 0, 0);
INSERT INTO `sections` (`id`, `title`, `created_at`, `updated_at`, `data`, `upvotes`, `downvotes`, `markdown`) VALUES
(0, 'news', 1404501194, 1404501194, 'everything!', 0, 0, 0);

CREATE TABLE IF NOT EXISTS `ta_auth_tokens` (
  `auth_identifier` int(11) NOT NULL,
  `public_key` varchar(96) COLLATE utf8_unicode_ci NOT NULL,
  `private_key` varchar(96) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`auth_identifier`,`public_key`,`private_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(12) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(24) NOT NULL,
  `password` varchar(64) NOT NULL,
  `created_at` int(12) unsigned NOT NULL,
  `updated_at` int(12) unsigned NOT NULL,
  `remember_token` varchar(100) NOT NULL,
  `points` int(11) NOT NULL,
  `upvotes` int(12) unsigned NOT NULL,
  `downvotes` int(12) unsigned NOT NULL,
  `anonymous` tinyint(1) NOT NULL DEFAULT '0',
  `votes` int(11) NOT NULL DEFAULT '0',
  `show_nsfw` binary(1) NOT NULL DEFAULT '0',
  `show_nsfl` binary(1) NOT NULL DEFAULT '0',
  `frontpage_ignore_sections` text NOT NULL,
  `frontpage_show_sections` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `votes` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `user_id` int(12) unsigned NOT NULL,
  `type` int(2) unsigned NOT NULL COMMENT '0=comment, 1=post, 2=section',
  `item_id` int(12) unsigned NOT NULL,
  `updown` int(1) NOT NULL,
  `created_at` int(12) unsigned NOT NULL,
  `updated_at` int(12) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `item_id` (`item_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

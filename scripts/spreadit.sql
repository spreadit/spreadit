-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 22, 2014 at 02:59 PM
-- Server version: 5.5.38-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `spreadit`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

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
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=75 ;

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=108 ;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(12) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(12) unsigned NOT NULL,
  `type` tinyint(3) unsigned NOT NULL,
  `item_id` int(12) unsigned NOT NULL,
  `created_at` int(12) unsigned NOT NULL,
  `read` tinyint(1) NOT NULL DEFAULT '0',
  `updated_at` int(12) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=64 ;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(12) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(12) unsigned NOT NULL,
  `created_at` int(12) unsigned NOT NULL,
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=140 ;

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE IF NOT EXISTS `sections` (
  `id` int(12) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(24) NOT NULL,
  `created_at` int(12) unsigned NOT NULL,
  `updated_at` int(12) unsigned NOT NULL,
  `data` text NOT NULL,
  `upvotes` int(12) unsigned NOT NULL,
  `downvotes` int(12) unsigned NOT NULL,
  `markdown` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=53 ;

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=172 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

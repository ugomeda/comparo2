-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Client: 127.0.0.1
-- Généré le : Lun 10 Décembre 2012 à 23:58
-- Version du serveur: 5.5.16
-- Version de PHP: 5.3.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `comparo`
--

-- --------------------------------------------------------

--
-- Structure de la table `commentaires`
--

CREATE TABLE IF NOT EXISTS `commentaires` (
  `comparo` varchar(10) NOT NULL,
  `index` int(11) NOT NULL,
  `text` tinytext NOT NULL,
  `heure` datetime NOT NULL,
  PRIMARY KEY (`comparo`,`index`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `compare`
--

CREATE TABLE IF NOT EXISTS `compare` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idnonrelu` varchar(10) NOT NULL,
  `idrelu` varchar(10) NOT NULL,
  `idvo` varchar(10) NOT NULL,
  `idsc` varchar(10) NOT NULL,
  `file` varchar(10) NOT NULL,
  `charset` tinyint(4) NOT NULL DEFAULT '0',
  `keep` enum('1','0') NOT NULL DEFAULT '0',
  `tags` enum('1','0') NOT NULL DEFAULT '1',
  `highTolerance` enum('0','1') NOT NULL DEFAULT '0',
  `stats_total` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `comparos`
--

CREATE TABLE IF NOT EXISTS `comparos` (
  `id` varchar(10) NOT NULL,
  `comparatif` int(11) NOT NULL,
  `nom_st1` varchar(128) NOT NULL,
  `nom_st2` varchar(128) NOT NULL,
  `nom_vo` varchar(128) NOT NULL,
  `nom_sc` varchar(128) NOT NULL,
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_view` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `code` varchar(10) NOT NULL,
  `discuss` enum('1','0') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `discuss`
--

CREATE TABLE IF NOT EXISTS `discuss` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comparo` varchar(10) NOT NULL,
  `pseudo` varchar(32) NOT NULL,
  `value` varchar(256) NOT NULL,
  `ind` int(11) NOT NULL,
  `heure` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `dispatch`
--

CREATE TABLE IF NOT EXISTS `dispatch` (
  `userid` int(11) NOT NULL,
  `subid` int(11) NOT NULL,
  `index` tinyint(4) NOT NULL,
  `from` tinyint(4) NOT NULL,
  `to` tinyint(4) NOT NULL,
  `step` tinyint(4) NOT NULL,
  PRIMARY KEY (`userid`,`subid`,`index`,`step`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `erreurs`
--

CREATE TABLE IF NOT EXISTS `erreurs` (
  `error` varchar(10) NOT NULL,
  `heure` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `files`
--

CREATE TABLE IF NOT EXISTS `files` (
  `id` varchar(10) NOT NULL,
  `sha1` varchar(40) NOT NULL,
  `size` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` varchar(10) NOT NULL,
  `name` varchar(128) NOT NULL,
  `public_key` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `groups_comparos`
--

CREATE TABLE IF NOT EXISTS `groups_comparos` (
  `group` varchar(10) NOT NULL,
  `comparo` varchar(10) NOT NULL,
  `added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nom` varchar(128) NOT NULL,
  PRIMARY KEY (`group`,`comparo`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `modified_lines`
--

CREATE TABLE IF NOT EXISTS `modified_lines` (
  `comparoid` varchar(10) NOT NULL,
  `idline` mediumint(9) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`comparoid`,`idline`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
  `postid` int(11) NOT NULL AUTO_INCREMENT,
  `subid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `post_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_text` text NOT NULL,
  PRIMARY KEY (`postid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `stats`
--

CREATE TABLE IF NOT EXISTS `stats` (
  `text` varchar(512) NOT NULL,
  `count` tinyint(4) NOT NULL,
  PRIMARY KEY (`text`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `subtitles`
--

CREATE TABLE IF NOT EXISTS `subtitles` (
  `subid` int(11) NOT NULL AUTO_INCREMENT,
  `teamid` int(11) NOT NULL,
  `episode` smallint(6) NOT NULL,
  `saison` smallint(6) NOT NULL,
  `episodename` varchar(128) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  PRIMARY KEY (`subid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `teams`
--

CREATE TABLE IF NOT EXISTS `teams` (
  `teamid` int(3) NOT NULL AUTO_INCREMENT,
  `userid` int(3) NOT NULL,
  `name` varchar(128) NOT NULL,
  PRIMARY KEY (`teamid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `teams_steps`
--

CREATE TABLE IF NOT EXISTS `teams_steps` (
  `teamid` int(3) NOT NULL,
  `index` int(1) NOT NULL,
  `name` varchar(64) NOT NULL,
  `color` varchar(7) NOT NULL,
  PRIMARY KEY (`teamid`,`index`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `userkeys`
--

CREATE TABLE IF NOT EXISTS `userkeys` (
  `public` varchar(10) NOT NULL,
  `private` varchar(16) NOT NULL,
  PRIMARY KEY (`public`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `uid` int(4) NOT NULL AUTO_INCREMENT,
  `username` varchar(128) NOT NULL,
  `password` varchar(40) NOT NULL,
  `email` varchar(128) NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `users_teams`
--

CREATE TABLE IF NOT EXISTS `users_teams` (
  `teamid` int(3) NOT NULL,
  `userid` int(3) NOT NULL,
  `status` enum('0','1') NOT NULL,
  PRIMARY KEY (`teamid`,`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

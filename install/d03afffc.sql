-- phpMyAdmin SQL Dump
-- version 4.9.11
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Erstellungszeit: 18. Apr 2025 um 20:51
-- Server-Version: 10.6.21-MariaDB-0ubuntu0.22.04.2-log
-- PHP-Version: 7.4.33-nmm7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `d03afffc`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `backups`
--

CREATE TABLE `backups` (
  `id` int(11) NOT NULL,
  `filename` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `createdby` int(11) NOT NULL DEFAULT 0,
  `createdate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `backups`
--

INSERT INTO `backups` (`id`, `filename`, `description`, `createdby`, `createdate`) VALUES
(2, 'myphp-backup-d03afffc-20250330_213610.sql.gz', 'myphp-backup-d03afffc-20250330_213610.sql.gz', 1, '2025-03-30 19:36:10'),
(3, 'myphp-backup-d03afffc-20250330_213808.sql.gz', 'myphp-backup-d03afffc-20250330_213808.sql.gz', 1, '2025-03-30 19:38:08');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `backup_theme`
--

CREATE TABLE `backup_theme` (
  `id` int(11) NOT NULL,
  `theme_name` varchar(255) NOT NULL,
  `backup_data` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `backup_theme`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `banned_ips`
--

CREATE TABLE `banned_ips` (
  `ip` varchar(64) NOT NULL,
  `deltime` int(11) NOT NULL,
  `reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `captcha`
--

CREATE TABLE `captcha` (
  `hash` varchar(255) NOT NULL DEFAULT '',
  `captcha` int(11) NOT NULL DEFAULT 0,
  `deltime` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Daten für Tabelle `captcha`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `contact`
--

CREATE TABLE `contact` (
  `contactID` int(11) NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `contact`
--

INSERT INTO `contact` (`contactID`, `name`, `email`, `sort`) VALUES
(1, 'Administrator', 'info@webspell-rm.de', 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cookies`
--

CREATE TABLE `cookies` (
  `userID` int(11) NOT NULL,
  `cookie` binary(64) NOT NULL,
  `expiration` int(14) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `cookies`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `counter`
--

CREATE TABLE `counter` (
  `hits` int(20) NOT NULL DEFAULT 0,
  `online` int(14) NOT NULL DEFAULT 0,
  `maxonline` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `counter`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `counter_iplist`
--

CREATE TABLE `counter_iplist` (
  `dates` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `del` int(20) NOT NULL DEFAULT 0,
  `ip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `counter_iplist`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `counter_stats`
--

CREATE TABLE `counter_stats` (
  `dates` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `count` int(20) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `counter_stats`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `email`
--

CREATE TABLE `email` (
  `emailID` int(1) NOT NULL,
  `user` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `host` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `port` int(5) NOT NULL,
  `debug` int(1) NOT NULL,
  `auth` int(1) NOT NULL,
  `html` int(1) NOT NULL,
  `smtp` int(1) NOT NULL,
  `secure` int(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `email`
--

INSERT INTO `email` (`emailID`, `user`, `password`, `host`, `port`, `debug`, `auth`, `html`, `smtp`, `secure`) VALUES
(1, '', '', '', 25, 0, 0, 1, 0, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `failed_login_attempts`
--

CREATE TABLE `failed_login_attempts` (
  `ip` varchar(64) NOT NULL,
  `wrong` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lock`
--

CREATE TABLE `lock` (
  `time` int(11) NOT NULL,
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `lock`
--

INSERT INTO `lock` (`time`, `reason`) VALUES
(1743856280, 'egegege');

-- --------------------------------------------------------

















--
-- Tabellenstruktur für Tabelle `modrewrite`
--

CREATE TABLE `modrewrite` (
  `ruleID` int(11) NOT NULL,
  `regex` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fields` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `replace_regex` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `replace_result` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rebuild_regex` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rebuild_result` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `modrewrite`
--

INSERT INTO `modrewrite` (`ruleID`, `regex`, `link`, `fields`, `replace_regex`, `replace_result`, `rebuild_regex`, `rebuild_result`) VALUES
(1, 'about.html', 'index.php?site=about_us', 'a:0:{}', 'index\\.php\\?site=about_us', 'about.html', 'about\\.html', 'index.php?site=about_us'),
(2, 'clan_rules.html', 'index.php?site=clan_rules', 'a:0:{}', 'index\\.php\\?site=clan_rules', 'clan_rules.html', 'clan_rules\\.html', 'index.php?site=clan_rules'),
(3, 'clanwars.html', 'index.php?site=clanwars', 'a:0:{}', 'index\\.php\\?site=clanwars', 'clanwars.html', 'clanwars\\.html', 'index.php?site=clanwars'),
(4, 'contact.html', 'index.php?site=contact', 'a:0:{}', 'index\\.php\\?site=contact', 'contact.html', 'contact\\.html', 'index.php?site=contact'),
(5, 'counter.html', 'index.php?site=counter', 'a:0:{}', 'index\\.php\\?site=counter', 'counter.html', 'counter\\.html', 'index.php?site=counter'),
(6, 'discord.html', 'index.php?site=discord', 'a:0:{}', 'index\\.php\\?site=discord', 'discord.html', 'discord\\.html', 'index.php?site=discord'),
(7, 'faq.html', 'index.php?site=faq', 'a:0:{}', 'index\\.php\\?site=faq', 'faq.html', 'faq\\.html', 'index.php?site=faq'),
(8, 'faq/{catID}.html', 'index.php?site=faq&action=faqcat&faqcatID={catID}', 'a:1:{s:5:\"catID\";s:7:\"integer\";}', 'index\\.php\\?site=faq[&|&amp;]*action=faqcat[&|&amp;]*faqcatID=([0-9]+)', 'faq/$3.html', 'faq\\/([0-9]+?)\\.html', 'index.php?site=faq&action=faqcat&faqcatID=$1'),
(9, 'faq/{catID}/{faqID}.html', 'index.php?site=faq&action=faq&faqID={faqID}&faqcatID={catID}', 'a:2:{s:5:\"faqID\";s:7:\"integer\";s:5:\"catID\";s:7:\"integer\";}', 'index\\.php\\?site=faq[&|&amp;]*action=faq[&|&amp;]*faqID=([0-9]+)[&|&amp;]*faqcatID=([0-9]+)', 'faq/$4/$3.html', 'faq\\/([0-9]+?)\\/([0-9]+?)\\.html', 'index.php?site=faq&action=faq&faqID=$2&faqcatID=$1'),
(10, 'files.html', 'index.php?site=files', 'a:0:{}', 'index\\.php\\?site=files', 'files.html', 'files\\.html', 'index.php?site=files'),
(11, 'files/category/{catID}', 'index.php?site=files&cat={catID}', 'a:1:{s:5:\"catID\";s:7:\"integer\";}', 'index\\.php\\?site=files[&|&amp;]*cat=([0-9]+)', 'files/category/$3', 'files\\/category\\/([0-9]+?)', 'index.php?site=files&cat=$1'),
(12, 'files/file/{fileID}', 'index.php?site=files&file={fileID}', 'a:1:{s:6:\"fileID\";s:7:\"integer\";}', 'index\\.php\\?site=files[&|&amp;]*file=([0-9]+)', 'files/file/$3', 'files\\/file\\/([0-9]+?)', 'index.php?site=files&file=$1'),
(13, 'files/report/{fileID}', 'index.php?site=files&action=report&link={fileID}', 'a:1:{s:6:\"fileID\";s:7:\"integer\";}', 'index\\.php\\?site=files[&|&amp;]*action=report[&|&amp;]*link=([0-9]+)', 'files/report/$3', 'files\\/report\\/([0-9]+?)', 'index.php?site=files&action=report&link=$1'),
(14, 'forum.html', 'index.php?site=forum', 'a:0:{}', 'index\\.php\\?site=forum', 'forum.html', 'forum\\.html', 'index.php?site=forum'),
(15, 'forum/{action}/board/{board}.html', 'index.php?site=forum&board={board}&action={action}', 'a:2:{s:5:\"board\";s:7:\"integer\";s:6:\"action\";s:6:\"string\";}', 'index\\.php\\?site=forum[&|&amp;]*board=([0-9]+)[&|&amp;]*action=(\\w*?)', 'forum/$4/board/$3.html', 'forum\\/(\\w*?)\\/board\\/([0-9]+?)\\.html', 'index.php?site=forum&board=$2&action=$1'),
(16, 'forum/action.html', 'forum.php', 'a:0:{}', 'forum\\.php', 'forum/action.html', 'forum\\/action\\.html', 'forum.php'),
(17, 'forum/actions/markall.html', 'index.php?site=forum&action=markall', 'a:0:{}', 'index\\.php\\?site=forum[&|&amp;]*action=markall', 'forum/actions/markall.html', 'forum\\/actions\\/markall\\.html', 'index.php?site=forum&action=markall'),
(18, 'forum/board/{board}.html', 'index.php?site=forum&board={board}', 'a:1:{s:5:\"board\";s:7:\"integer\";}', 'index\\.php\\?site=forum[&|&amp;]*board=([0-9]+)', 'forum/board/$3.html', 'forum\\/board\\/([0-9]+?)\\.html', 'index.php?site=forum&board=$1'),
(19, 'forum/board/{board}/addtopic.html', 'index.php?site=forum&addtopic=true&board={board}', 'a:1:{s:5:\"board\";s:7:\"integer\";}', 'index\\.php\\?site=forum[&|&amp;]*addtopic=true[&|&amp;]*board=([0-9]+)', 'forum/board/$3/addtopic.html', 'forum\\/board\\/([0-9]+?)\\/addtopic\\.html', 'index.php?site=forum&addtopic=true&board=$1'),
(20, 'forum/cat/{cat}.html', 'index.php?site=forum&cat={cat}', 'a:1:{s:3:\"cat\";s:7:\"integer\";}', 'index\\.php\\?site=forum[&|&amp;]*cat=([0-9]+)', 'forum/cat/$3.html', 'forum\\/cat\\/([0-9]+?)\\.html', 'index.php?site=forum&cat=$1'),
(21, 'gallery.html', 'index.php?site=gallery', 'a:0:{}', 'index\\.php\\?site=gallery', 'gallery.html', 'gallery\\.html', 'index.php?site=gallery'),
(23, 'history.html', 'index.php?site=history', 'a:0:{}', 'index\\.php\\?site=history', 'history.html', 'history\\.html', 'index.php?site=history'),
(24, 'imprint.html', 'index.php?site=imprint', 'a:0:{}', 'index\\.php\\?site=imprint', 'imprint.html', 'imprint\\.html', 'index.php?site=imprint'),
(25, 'joinus.html', 'index.php?site=joinus', 'a:0:{}', 'index\\.php\\?site=joinus', 'joinus.html', 'joinus\\.html', 'index.php?site=joinus'),
(26, 'joinus/save.html', 'index.php?site=joinus&action=save', 'a:0:{}', 'index\\.php\\?site=joinus[&|&amp;]*action=save', 'joinus/save.html', 'joinus\\/save\\.html', 'index.php?site=joinus&action=save'),
(27, 'links.html', 'index.php?site=links', 'a:0:{}', 'index\\.php\\?site=links', 'links.html', 'links\\.html', 'index.php?site=links'),
(28, 'links/category/{catID}.html', 'index.php?site=links&action=show&linkcatID={catID}', 'a:1:{s:5:\"catID\";s:7:\"integer\";}', 'index\\.php\\?site=links[&|&amp;]*action=show[&|&amp;]*linkcatID=([0-9]+)', 'links/category/$3.html', 'links\\/category\\/([0-9]+?)\\.html', 'index.php?site=links&action=show&linkcatID=$1'),
(29, 'linkus.html', 'index.php?site=linkus', 'a:0:{}', 'index\\.php\\?site=linkus', 'linkus.html', 'linkus\\.html', 'index.php?site=linkus'),
(30, 'login.html', 'index.php?site=login', 'a:0:{}', 'index\\.php\\?site=login', 'login.html', 'login\\.html', 'index.php?site=login'),
(31, 'logout.html', 'index.php?site=logout', 'a:0:{}', 'index\\.php\\?site=logout', 'logout.html', 'logout\\.html', 'index.php?site=logout'),
(32, 'lostpassword.html', 'index.php?site=lostpassword', 'a:0:{}', 'index\\.php\\?site=lostpassword', 'lostpassword.html', 'lostpassword\\.html', 'index.php?site=lostpassword'),
(33, 'members.html', 'index.php?site=members', 'a:0:{}', 'index\\.php\\?site=members', 'members.html', 'members\\.html', 'index.php?site=members'),
(34, 'members/{squadID}.html', 'index.php?site=members&action=show&squadID={squadID}', 'a:1:{s:7:\"squadID\";s:7:\"integer\";}', 'index\\.php\\?site=members[&|&amp;]*action=show[&|&amp;]*squadID=([0-9]+)', 'members/$3.html', 'members\\/([0-9]+?)\\.html', 'index.php?site=members&action=show&squadID=$1'),
(35, 'messenger.html', 'index.php?site=messenger', 'a:0:{}', 'index\\.php\\?site=messenger', 'messenger.html', 'messenger\\.html', 'index.php?site=messenger'),
(36, 'messenger/{messageID}/read.html', 'index.php?site=messenger&action=show&id={messageID}', 'a:1:{s:9:\"messageID\";s:7:\"integer\";}', 'index\\.php\\?site=messenger[&|&amp;]*action=show[&|&amp;]*id=([0-9]+)', 'messenger/$3/read.html', 'messenger\\/([0-9]+?)\\/read\\.html', 'index.php?site=messenger&action=show&id=$1'),
(37, 'messenger/{messageID}/reply.html', 'index.php?site=messenger&action=reply&id={messageID}', 'a:1:{s:9:\"messageID\";s:7:\"integer\";}', 'index\\.php\\?site=messenger[&|&amp;]*action=reply[&|&amp;]*id=([0-9]+)', 'messenger/$3/reply.html', 'messenger\\/([0-9]+?)\\/reply\\.html', 'index.php?site=messenger&action=reply&id=$1'),
(38, 'messenger/action.html', 'messenger.php', 'a:0:{}', 'messenger\\.php', 'messenger/action.html', 'messenger\\/action\\.html', 'messenger.php'),
(39, 'messenger/incoming.html', 'index.php?site=messenger&action=incoming', 'a:0:{}', 'index\\.php\\?site=messenger[&|&amp;]*action=incoming', 'messenger/incoming.html', 'messenger\\/incoming\\.html', 'index.php?site=messenger&action=incoming'),
(40, 'messenger/new.html', 'index.php?site=messenger&action=newmessage', 'a:0:{}', 'index\\.php\\?site=messenger[&|&amp;]*action=newmessage', 'messenger/new.html', 'messenger\\/new\\.html', 'index.php?site=messenger&action=newmessage'),
(41, 'messenger/outgoing.html', 'index.php?site=messenger&action=outgoing', 'a:0:{}', 'index\\.php\\?site=messenger[&|&amp;]*action=outgoing', 'messenger/outgoing.html', 'messenger\\/outgoing\\.html', 'index.php?site=messenger&action=outgoing'),
(42, 'news.html', 'index.php?site=news_manager', 'a:0:{}', 'index\\.php\\?site=news_manager', 'news.html', 'news\\.html', 'index.php?site=news_manager'),
(43, 'news_contents/{newsID}.html', 'index.php?site=news&action=news_contents&newsID={newsID}', 'a:1:{s:6:\"newsID\";s:7:\"integer\";}', 'index\\.php\\?site=news[&|&amp;]*action=news_contents[&|&amp;]*newsID=([0-9]+)', 'news_contents/$3.html', 'news_contents\\/([0-9]+?)\\.html', 'index.php?site=news&action=news_contents&newsID=$1'),
(44, 'news/archive.html', 'index.php?site=news_manager&action=news_archive', 'a:0:{}', 'index\\.php\\?site=news_manager[&|&amp;]*action=news_archive', 'news/archive.html', 'news\\/archive\\.html', 'index.php?site=news_manager&action=news_archive'),
(45, 'portfolio.html', 'index.php?site=portfolio', 'a:0:{}', 'index\\.php\\?site=portfolio', 'portfolio.html', 'portfolio\\.html', 'index.php?site=portfolio'),
(46, 'privacy_policy.html', 'index.php?site=privacy_policy', 'a:0:{}', 'index\\.php\\?site=privacy_policy', 'privacy_policy.html', 'privacy_policy\\.html', 'index.php?site=privacy_policy'),
(47, 'profile/{action}/{id}.html', 'index.php?site=profile&id={id}&action={action}', 'a:2:{s:2:\"id\";s:7:\"integer\";s:6:\"action\";s:6:\"string\";}', 'index\\.php\\?site=profile[&|&amp;]*id=([0-9]+)[&|&amp;]*action=(\\w*?)', 'profile/$4/$3.html', 'profile\\/(\\w*?)\\/([0-9]+?)\\.html', 'index.php?site=profile&id=$2&action=$1'),
(48, 'profile/{action}/{id}.html', 'index.php?site=pr1ofile&action={action}&id={id}', 'a:2:{s:2:\"id\";s:7:\"integer\";s:6:\"action\";s:6:\"string\";}', 'index\\.php\\?site=pr1ofile[&|&amp;]*action=(\\w*?)[&|&amp;]*id=([0-9]+)', 'profile/$3/$4.html', 'profile\\/(\\w*?)\\/([0-9]+?)\\.html', 'index.php?site=pr1ofile&action=$1&id=$2'),
(49, 'profile/{id}.html', 'index.php?site=profile&id={id}', 'a:1:{s:2:\"id\";s:7:\"integer\";}', 'index\\.php\\?site=profile[&|&amp;]*id=([0-9]+)', 'profile/$3.html', 'profile\\/([0-9]+?)\\.html', 'index.php?site=profile&id=$1'),
(50, 'profile/edit.html', 'index.php?site=myprofile', 'a:0:{}', 'index\\.php\\?site=myprofile', 'profile/edit.html', 'profile\\/edit\\.html', 'index.php?site=myprofile'),
(51, 'profile/mail.html', 'index.php?site=myprofile&action=editmail', 'a:0:{}', 'index\\.php\\?site=myprofile[&|&amp;]*action=editmail', 'profile/mail.html', 'profile\\/mail\\.html', 'index.php?site=myprofile&action=editmail'),
(52, 'profile/password.html', 'index.php?site=myprofile&action=editpwd', 'a:0:{}', 'index\\.php\\?site=myprofile[&|&amp;]*action=editpwd', 'profile/password.html', 'profile\\/password\\.html', 'index.php?site=myprofile&action=editpwd'),
(53, 'register.html', 'index.php?site=register', 'a:0:{}', 'index\\.php\\?site=register', 'register.html', 'register\\.html', 'index.php?site=register'),
(54, 'search.html', 'index.php?site=search', 'a:0:{}', 'index\\.php\\?site=search', 'search.html', 'search\\.html', 'index.php?site=search'),
(55, 'search/results.html', 'index.php?site=search&action=search', 'a:0:{}', 'index\\.php\\?site=search[&|&amp;]*action=search', 'search/results.html', 'search\\/results\\.html', 'index.php?site=search&action=search'),
(56, 'search/submit.html', 'search.php', 'a:0:{}', 'search\\.php', 'search/submit.html', 'search\\/submit\\.html', 'search.php'),
(57, 'server_rules.html', 'index.php?site=server_rules', 'a:0:{}', 'index\\.php\\?site=server_rules', 'server_rules.html', 'server_rules\\.html', 'index.php?site=server_rules'),
(58, 'server.html', 'index.php?site=servers', 'a:0:{}', 'index\\.php\\?site=servers', 'server.html', 'server\\.html', 'index.php?site=servers'),
(59, 'sponsors.html', 'index.php?site=sponsors', 'a:0:{}', 'index\\.php\\?site=sponsors', 'sponsors.html', 'sponsors\\.html', 'index.php?site=sponsors'),
(60, 'squads.html', 'index.php?site=squads', 'a:0:{}', 'index\\.php\\?site=squads', 'squads.html', 'squads\\.html', 'index.php?site=squads'),
(61, 'squads/{squadID}.html', 'index.php?site=squads&action=show&squadID={squadID}', 'a:1:{s:7:\"squadID\";s:7:\"integer\";}', 'index\\.php\\?site=squads[&|&amp;]*action=show[&|&amp;]*squadID=([0-9]+)', 'squads/$3.html', 'squads\\/([0-9]+?)\\.html', 'index.php?site=squads&action=show&squadID=$1'),
(62, 'static/{staticID}.html', 'index.php?site=static&staticID={staticID}', 'a:1:{s:8:\"staticID\";s:7:\"integer\";}', 'index\\.php\\?site=static[&|&amp;]*staticID=([0-9]+)', 'static/$3.html', 'static\\/([0-9]+?)\\.html', 'index.php?site=static&staticID=$1'),
(63, 'todo.html', 'index.php?site=todo', 'a:0:{}', 'index\\.php\\?site=todo', 'todo.html', 'todo\\.html', 'index.php?site=todo'),
(64, 'twitter.html', 'index.php?site=twitter', 'a:0:{}', 'index\\.php\\?site=twitter', 'twitter.html', 'twitter\\.html', 'index.php?site=twitter'),
(65, 'userlist.html', 'index.php?site=userlist', 'a:0:{}', 'index\\.php\\?site=userlist', 'userlist.html', 'userlist\\.html', 'index.php?site=userlist'),
(66, 'videos.html', 'index.php?site=videos', 'a:0:{}', 'index\\.php\\?site=videos', 'videos.html', 'videos\\.html', 'index.php?site=videos'),
(67, 'videos/{videosID}.html', 'index.php?site=videos&action=watch&videosID={videosID}', 'a:1:{s:8:\"videosID\";s:7:\"integer\";}', 'index\\.php\\?site=videos[&|&amp;]*action=watch[&|&amp;]*videosID=([0-9]+)', 'videos/$3.html', 'videos\\/([0-9]+?)\\.html', 'index.php?site=videos&action=watch&videosID=$1'),
(68, 'whoisonline.html', 'index.php?site=whoisonline', 'a:0:{}', 'index\\.php\\?site=whoisonline', 'whoisonline.html', 'whoisonline\\.html', 'index.php?site=whoisonline'),
(69, 'whoisonline.html#was', 'index.php?site=whoisonline#was', 'a:0:{}', 'index\\.php\\?site=whoisonline#was', 'whoisonline.html#was', 'whoisonline\\.html#was', 'index.php?site=whoisonline#was'),
(70, 'whoisonline/{sort}/{type}.html', 'index.php?site=whoisonline&sort={sort}&type={type}', 'a:2:{s:4:\"sort\";s:6:\"string\";s:4:\"type\";s:6:\"string\";}', 'index\\.php\\?site=whoisonline[&|&amp;]*sort=(\\w*?)[&|&amp;]*type=(\\w*?)', 'whoisonline/$3/$4.html', 'whoisonline\\/(\\w*?)\\/(\\w*?)\\.html', 'index.php?site=whoisonline&sort=$1&type=$2'),
(71, 'forum/topic/{topicID}.html', 'index.php?site=forum_topic&topic={topicID}', 'a:1:{s:7:\"topicID\";s:7:\"integer\";}', 'index\\.php\\?site=forum_topic[&|&amp;]*topic=([0-9]+)', 'forum/topic/$3.html', 'forum\\/topic\\/([0-9]+?)\\.html', 'index.php?site=forum_topic&topic=$1'),
(72, 'myprofile/deleteaccount.html', 'index.php?site=myprofile&action=deleteaccount', 'a:0:{}', 'index\\.php\\?site=myprofile[&|&amp;]*action=deleteaccount', 'myprofile/deleteaccount.html', 'myprofile\\/deleteaccount\\.html', 'index.php?site=myprofile&action=deleteaccount'),
(78, 'news/{page}.html', 'index.php?site=news_manager&action=news_contents&newsID={page}', 'a:1:{s:4:\"page\";s:7:\"integer\";}', 'index\\.php\\?site=news_manager[&|&amp;]*action=news_contents[&|&amp;]*newsID=([0-9]+)', 'news/$3.html', 'news\\/([0-9]+?)\\.html', 'index.php?site=news_manager&action=news_contents&newsID=$1'),
(79, 'shoutbox.html', 'index.php?site=shoutbox_content&action=showall', 'a:0:{}', 'index\\.php\\?site=shoutbox_content[&|&amp;]*action=showall', 'shoutbox.html', 'shoutbox\\.html', 'index.php?site=shoutbox_content&action=showall'),
(74, 'partners.html', 'index.php?site=partners', 'a:0:{}', 'index\\.php\\?site=partners', 'partners.html', 'partners\\.html', 'index.php?site=partners'),
(75, 'streams.html', 'index.php?site=streams', 'a:0:{}', 'index\\.php\\?site=streams', 'streams.html', 'streams\\.html', 'index.php?site=streams'),
(81, 'streams/{streamID}.html', 'index.php?site=streams&id={streamID}', 'a:1:{s:8:\"streamID\";s:7:\"integer\";}', 'index\\.php\\?site=streams[&|&amp;]*id=([0-9]+)', 'streams/$3.html', 'streams\\/([0-9]+?)\\.html', 'index.php?site=streams&id=$1'),
(77, 'forum_topic/{topicID}/{type}/{page}.html', 'index.php?site=forum_topic&topic={topicID}&type={type}&page={page}', 'a:3:{s:7:\"topicID\";s:6:\"string\";s:4:\"type\";s:6:\"string\";s:4:\"page\";s:7:\"integer\";}', 'index\\.php\\?site=forum_topic[&|&amp;]*topic=(\\w*?)[&|&amp;]*type=(\\w*?)[&|&amp;]*page=([0-9]+)', 'forum_topic/$3/$4/$5.html', 'forum_topic\\/(\\w*?)\\/(\\w*?)\\/([0-9]+?)\\.html', 'index.php?site=forum_topic&topic=$1&type=$2&page=$3'),
(80, 'calendar.html', 'index.php?site=calendar', 'a:0:{}', 'index\\.php\\?site=calendar', 'calendar.html', 'calendar\\.html', 'index.php?site=calendar'),
(82, 'shoutbox.html', 'index.php?site=shoutbox_content', 'a:0:{}', 'index\\.php\\?site=shoutbox_content', 'shoutbox.html', 'shoutbox\\.html', 'index.php?site=shoutbox_content'),
(83, 'candidature.html', 'index.php?site=candidature', 'a:0:{}', 'index\\.php\\?site=candidature', 'candidature.html', 'candidature\\.html', 'index.php?site=candidature'),
(84, 'candidature/new.html', 'index.php?site=candidature&action=new', 'a:0:{}', 'index\\.php\\?site=candidature[&|&amp;]*action=new', 'candidature/new.html', 'candidature\\/new\\.html', 'index.php?site=candidature&action=new'),
(85, 'guestbook.html', 'index.php?site=guestbook', 'a:0:{}', 'index\\.php\\?site=guestbook', 'guestbook.html', 'guestbook\\.html', 'index.php?site=guestbook'),
(86, 'news_contents/{newsID}.html', 'index.php?site=news_contents&newsID={newsID}', 'a:1:{s:6:\"newsID\";s:7:\"integer\";}', 'index\\.php\\?site=news_contents[&|&amp;]*newsID=([0-9]+)', 'news_contents/$3.html', 'news_contents\\/([0-9]+?)\\.html', 'index.php?site=news_contents&newsID=$1'),
(87, 'clanwars/result.html', 'index.php?site=clanwars&action=clanwar_result', 'a:0:{}', 'index\\.php\\?site=clanwars[&|&amp;]*action=clanwar_result', 'clanwars/result.html', 'clanwars\\/result\\.html', 'index.php?site=clanwars&action=clanwar_result'),
(88, 'facebook.html', 'index.php?site=facebook', 'a:0:{}', 'index\\.php\\?site=facebook', 'facebook.html', 'facebook\\.html', 'index.php?site=facebook'),
(89, 'reddit.html', 'index.php?site=reddit', 'a:0:{}', 'index\\.php\\?site=reddit', 'reddit.html', 'reddit\\.html', 'index.php?site=reddit'),
(90, 'instagram.html', 'index.php?site=instagram', 'a:0:{}', 'index\\.php\\?site=instagram', 'instagram.html', 'instagram\\.html', 'index.php?site=instagram'),
(91, 'tiktok.html', 'index.php?site=tiktok', 'a:0:{}', 'index\\.php\\?site=tiktok', 'tiktok.html', 'tiktok\\.html', 'index.php?site=tiktok'),
(92, 'rss.html', 'index.php?site=rss', 'a:0:{}', 'index\\.php\\?site=rss', 'rss.html', 'rss\\.html', 'index.php?site=rss'),
(93, 'projectlist.html', 'index.php?site=projectlist', 'a:0:{}', 'index\\.php\\?site=projectlist', 'projectlist.html', 'projectlist\\.html', 'index.php?site=projectlist'),
(94, 'projectlist/{catID}.html', 'index.php?site=projectlist&action=show&projectlistcatID={catID}', 'a:1:{s:5:\"catID\";s:7:\"integer\";}', 'index\\.php\\?site=projectlist[&|&amp;]*action=show[&|&amp;]*projectlistcatID=([0-9]+)', 'projectlist/$3.html', 'projectlist\\/([0-9]+?)\\.html', 'index.php?site=projectlist&action=show&projectlistcatID=$1'),
(95, 'logout.html', 'index.php?site=logout', 'a:0:{}', 'index\\.php\\?site=logout', 'logout.html', 'logout\\.html', 'index.php?site=logout'),
(96, 'usergallery.html', 'index.php?site=usergallery', 'a:0:{}', 'index\\.php\\?site=usergallery', 'usergallery.html', 'usergallery\\.html', 'index.php?site=usergallery'),
(97, 'usergallery/add.html', 'index.php?site=usergallery&action=add', 'a:0:{}', 'index\\.php\\?site=usergallery[&|&amp;]*action=add', 'usergallery/add.html', 'usergallery\\/add\\.html', 'index.php?site=usergallery&action=add'),
(98, 'cashbox.html', 'index.php?site=cashbox', 'a:0:{}', 'index\\.php\\?site=cashbox', 'cashbox.html', 'cashbox\\.html', 'index.php?site=cashbox'),
(99, 'calendar/event.html', 'index.php?site=calendar#event', 'a:0:{}', 'index\\.php\\?site=calendar#event', 'calendar/event.html', 'calendar\\/event\\.html', 'index.php?site=calendar#event'),
(100, 'awaylist.html', 'index.php?site=awaylist', 'a:0:{}', 'index\\.php\\?site=awaylist', 'awaylist.html', 'awaylist\\.html', 'index.php?site=awaylist'),
(101, 'awaylist/add.html', 'index.php?site=awaylist&action=add', 'a:0:{}', 'index\\.php\\?site=awaylist[&|&amp;]*action=add', 'awaylist/add.html', 'awaylist\\/add\\.html', 'index.php?site=awaylist&action=add'),
(102, 'awaylist/edit/{id}.html', 'index.php?site=awaylist&action=edit&id={id}', 'a:1:{s:2:\"id\";s:7:\"integer\";}', 'index\\.php\\?site=awaylist[&|&amp;]*action=edit[&|&amp;]*id=([0-9]+)', 'awaylist/edit/$3.html', 'awaylist\\/edit\\/([0-9]+?)\\.html', 'index.php?site=awaylist&action=edit&id=$1'),
(103, 'awaylist/show/{id}.html', 'index.php?site=awaylist&action=show&id={id}', 'a:1:{s:2:\"id\";s:7:\"integer\";}', 'index\\.php\\?site=awaylist[&|&amp;]*action=show[&|&amp;]*id=([0-9]+)', 'awaylist/show/$3.html', 'awaylist\\/show\\/([0-9]+?)\\.html', 'index.php?site=awaylist&action=show&id=$1'),
(104, 'mc_status.html', 'index.php?site=mc_status', 'a:0:{}', 'index\\.php\\?site=mc_status', 'mc_status.html', 'mc_status\\.html', 'index.php?site=mc_status'),
(105, 'planning.html', 'index.php?site=planning', 'a:0:{}', 'index\\.php\\?site=planning', 'planning.html', 'planning\\.html', 'index.php?site=planning'),
(106, 'memberslist.html', 'index.php?site=memberslist', 'a:0:{}', 'index\\.php\\?site=memberslist', 'memberslist.html', 'memberslist\\.html', 'index.php?site=memberslist'),
(107, 'cup.html', 'index.php?site=cup', 'a:0:{}', 'index\\.php\\?site=cup', 'cup.html', 'cup\\.html', 'index.php?site=cup'),
(108, 'cup/teams.html', 'index.php?site=cup&action=teams', 'a:0:{}', 'index\\.php\\?site=cup[&|&amp;]*action=teams', 'cup/teams.html', 'cup\\/teams\\.html', 'index.php?site=cup&action=teams'),
(109, 'newsletter.html', 'index.php?site=newsletter', 'a:0:{}', 'index\\.php\\?site=newsletter', 'newsletter.html', 'newsletter\\.html', 'index.php?site=newsletter'),
(110, 'tsviewer.html', 'index.php?site=tsviewer', 'a:0:{}', 'index\\.php\\?site=tsviewer', 'tsviewer.html', 'tsviewer\\.html', 'index.php?site=tsviewer'),
(111, 'gallery/category{groupID}.html', 'index.php?site=gallery&groupID={groupID}', 'a:1:{s:7:\"groupID\";s:7:\"integer\";}', 'index\\.php\\?site=gallery[&|&amp;]*groupID=([0-9]+)', 'gallery/category$3.html', 'gallery\\/category([0-9]+?)\\.html', 'index.php?site=gallery&groupID=$1'),
(112, 'blog.html', 'index.php?site=blog', 'a:0:{}', 'index\\.php\\?site=blog', 'blog.html', 'blog\\.html', 'index.php?site=blog'),
(113, 'blog/archiv.html', 'index.php?site=blog&action=archiv', 'a:0:{}', 'index\\.php\\?site=blog[&|&amp;]*action=archiv', 'blog/archiv.html', 'blog\\/archiv\\.html', 'index.php?site=blog&action=archiv'),
(114, 'gallery/gallery{galleryID}.html', 'index.php?site=gallery&galleryID={galleryID}', 'a:1:{s:9:\"galleryID\";s:7:\"integer\";}', 'index\\.php\\?site=gallery[&|&amp;]*galleryID=([0-9]+)', 'gallery/gallery$3.html', 'gallery\\/gallery([0-9]+?)\\.html', 'index.php?site=gallery&galleryID=$1'),
(115, 'blog/show/{blogID}.html', 'index.php?site=blog&action=show&blogID={blogID}', 'a:1:{s:6:\"blogID\";s:7:\"integer\";}', 'index\\.php\\?site=blog[&|&amp;]*action=show[&|&amp;]*blogID=([0-9]+)', 'blog/show/$3.html', 'blog\\/show\\/([0-9]+?)\\.html', 'index.php?site=blog&action=show&blogID=$1'),
(116, 'blog/detail/{id}.html', 'index.php?site=blog&action=detail&user={id}', 'a:1:{s:2:\"id\";s:7:\"integer\";}', 'index\\.php\\?site=blog[&|&amp;]*action=detail[&|&amp;]*user=([0-9]+)', 'blog/detail/$3.html', 'blog\\/detail\\/([0-9]+?)\\.html', 'index.php?site=blog&action=detail&user=$1'),
(117, 'blog/add.html', 'index.php?site=blog&action=new', 'a:0:{}', 'index\\.php\\?site=blog[&|&amp;]*action=new', 'blog/add.html', 'blog\\/add\\.html', 'index.php?site=blog&action=new'),
(118, 'blog/edit/{blogID}.html', 'index.php?site=blog&action=edit&blogID={blogID}', 'a:1:{s:6:\"blogID\";s:7:\"integer\";}', 'index\\.php\\?site=blog[&|&amp;]*action=edit[&|&amp;]*blogID=([0-9]+)', 'blog/edit/$3.html', 'blog\\/edit\\/([0-9]+?)\\.html', 'index.php?site=blog&action=edit&blogID=$1');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `navigation_dashboard_categories`
--

CREATE TABLE `navigation_dashboard_categories` (
  `catID` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `modulname` varchar(255) NOT NULL,
  `fa_name` varchar(255) NOT NULL DEFAULT '',
  `sort` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `navigation_dashboard_categories`
--

INSERT INTO `navigation_dashboard_categories` (`catID`, `name`, `modulname`, `fa_name`, `sort`) VALUES
(1, '{[de]}Webseiten Info - Einstellungen{[en]}Website Info - Settings{[it]}Informazioni-Impostazioni Sito', 'cat_web_info', 'bi bi-gear', 1),
(2, '{[de]}Spam{[en]}Spam{[it]}Spam', 'cat_spam', 'bi bi-exclamation-triangle', 2),
(3, '{[de]}Benutzer Administration{[en]}User Administration{[it]}Amministrazione Utenti', 'cat_user', 'bi bi-person', 3),
(4, '{[de]}Team Verwaltung{[en]}Team Administration{[it]}Amministrazione della squadra', 'cat_team', 'bi bi-people', 4),
(5, '{[de]}Template - Layout{[en]}Template - Layout{[it]}Template - Disposizione', 'cat_temp', 'bi bi-layout-text-window-reverse', 5),
(6, '{[de]}Plugin & Widget Verwaltung{[en]}Plugin and Widget Management{[it]}Gestione plugin e widget', 'cat_pwv', 'bi bi-puzzle', 6),
(7, '{[de]}Webseiteninhalte{[en]}Website Content{[it]}Contenuto del sito web', 'cat_web_content', 'bi bi-card-checklist', 7),
(8, '{[de]}Grafik - Video - Projekte{[en]}Grafik - Video - Projekte{[it]}Grafica - Video - Progetti', 'cat_grafik', 'bi bi-image ', 8),
(9, '{[de]}Header - Slider{[en]}Header - Slider{[it]}Slider-Header', 'cat_header', 'bi bi-fast-forward-btn', 9),
(10, '{[de]}Game - Voice Server Tools{[en]}Game - Voice Server Tools{[it]}Voice Server Tools', 'cat_game', 'bi bi-controller', 10),
(11, '{[de]}Social Media{[en]}Social Media{[it]}Social Media', 'cat_social', 'bi bi-steam', 11),
(12, '{[de]}Links - Download - Sponsoren{[en]}Links - Download - Sponsore{[it]}Link - Download - Sponsor', 'cat_links', 'bi bi-link', 12);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `navigation_dashboard_links`
--

CREATE TABLE `navigation_dashboard_links` (
  `modulname` varchar(255) NOT NULL DEFAULT '',
  `linkID` int(11) NOT NULL,
  `catID` int(11) NOT NULL DEFAULT 0,
  `name` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `sort` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `navigation_dashboard_links`
--

INSERT INTO `navigation_dashboard_links` (`modulname`, `linkID`, `catID`, `name`, `url`, `sort`) VALUES
('ac_overview', 1, 1, '{[de]}Webserver-Info{[en]}Webserver-Info{[it]}Informazioni Sul Sito', 'admincenter.php?site=overview', 1),
('ac_page_statistic', 2, 1, '{[de]}Seiten Statistiken{[en]}Page Statistics{[it]}Pagina delle Statistiche', 'admincenter.php?site=page_statistic', 2),
('ac_visitor_statistic', 3, 1, '{[de]}Besucher Statistiken{[en]}Visitor Statistics{[it]}Statistiche Visitatori', 'admincenter.php?site=visitor_statistic', 3),
('ac_settings', 4, 1, '{[de]}Allgemeine Einstellungen{[en]}General Settings{[it]}Impostazioni Generali', 'admincenter.php?site=settings', 4),
('ac_dashboard_navigation', 5, 1, '{[de]}Admincenter Navigation{[en]}Admincenter Navigation{[it]}Menu Navigazione Admin', 'admincenter.php?site=dashboard_navigation', 5),
('ac_email', 6, 1, '{[de]}E-Mail{[en]}E-Mail{[it]}E-Mail', 'admincenter.php?site=email', 6),
('ac_contact', 7, 1, '{[de]}Kontakte{[en]}Contacts{[it]}Contatti', 'admincenter.php?site=contact', 7),
('ac_modrewrite', 8, 1, '{[de]}Mod-Rewrite{[en]}Mod-Rewrite{[it]}Mod-Rewrite', 'admincenter.php?site=modrewrite', 8),
('ac_database', 9, 1, '{[de]}Datenbank{[en]}Database{[it]}Database', 'admincenter.php?site=database', 9),
('ac_update', 10, 1, '{[de]}Webspell-RM Update{[en]}Webspell-RM Update{[it]}Aggiornamento Webspell-RM', 'admincenter.php?site=update', 10),
('ac_users', 11, 3, '{[de]}Registrierte Benutzer{[en]}Registered Users{[it]}Utenti Registrati', 'admincenter.php?site=users', 1),
('ac_spam_forum', 12, 2, '{[de]}Geblockte Inhalte{[en]}Blocked Content{[it]}Contenuti Bloccati', 'admincenter.php?site=spam&amp;action=forum_spam', 1),
('ac_spam_user', 13, 2, '{[de]}Nutzer l&ouml;schen{[en]}Remove User{[it]}Banna Utente', 'admincenter.php?site=spam&amp;action=user', 2),
('ac_spam_multi', 14, 2, '{[de]}Multi-Accounts{[en]}Multi-Accounts{[it]}Multi-Account', 'admincenter.php?site=spam&amp;action=multi', 3),
('ac_spam_banned_ips', 15, 2, '{[de]}gebannte IPs{[en]}banned IPs{[it]}IP bannati', 'admincenter.php?site=banned_ips', 4),
('ac_webside_navigation', 16, 5, '{[de]}Webseiten Navigation{[en]}Webside Navigation{[it]}Menu Navigazione Web', 'admincenter.php?site=webside_navigation', 1),
('ac_themes_installer', 17, 5, '{[de]}Themes Installer{[en]}Themes Installer{[it]}Installazione Themes', 'admincenter.php?site=themes_installer', 2),
('ac_themes', 18, 5, '{[de]}Themes - Style{[en]}Themes - Style{[it]}Themes Grafici', 'admincenter.php?site=settings_themes', 3),
('ac_startpage', 20, 5, '{[de]}Startseite{[en]}Start Page{[it]}Pagina Principale', 'admincenter.php?site=settings_startpage', 5),
('ac_static', 21, 5, '{[de]}Statische Seiten{[en]}Static Pages{[it]}Pagine Statiche', 'admincenter.php?site=settings_static', 6),
('ac_imprint', 22, 5, '{[de]}Impressum{[en]}Imprint{[it]}Impronta Editoriale', 'admincenter.php?site=settings_imprint', 7),
('ac_privacy_policy', 23, 5, '{[de]}Datenschutz-Bestimmungen{[en]}Privacy Policy{[it]}Informativa sulla Privacy', 'admincenter.php?site=settings_privacy_policy', 8),
('ac_plugin_manager', 24, 6, '{[de]}Plugin & Widget Manager{[en]}Plugin & Widget Manager{[it]}Gestore di plugin e widget', 'admincenter.php?site=plugin_manager', 1),
('ac_plugin_installer', 25, 6, '{[de]}Plugin Installer{[en]}Plugin Installer{[it]}Installazione Plugin', 'admincenter.php?site=plugin_installer', 2),
('ac_editlang', 26, 1, '{[de]}Spracheditor{[en]}Language Editor{[it]}Editor di Linguaggi', 'admincenter.php?site=editlang', 11),
('footer', 69, 7, '{[de]}Footer{[en]}Footer{[it]}Piè di pagina', 'admincenter.php?site=fotter', 0),
('ac_admin_security', 70, 3, 'admin_security', 'admincenter.php?site=admin_security', 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `navigation_website_main`
--

CREATE TABLE `navigation_website_main` (
  `mnavID` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `default` int(11) NOT NULL DEFAULT 1,
  `sort` int(2) NOT NULL DEFAULT 0,
  `isdropdown` int(1) NOT NULL DEFAULT 1,
  `windows` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `navigation_website_main`
--

INSERT INTO `navigation_website_main` (`mnavID`, `name`, `url`, `default`, `sort`, `isdropdown`, `windows`) VALUES
(1, '{[de]}HAUPT{[en]}MAIN{[it]}PRINCIPALE', '#', 1, 1, 1, 1),
(2, '{[de]}TEAM{[en]}TEAM{[it]}TEAM', '#', 1, 2, 1, 1),
(3, '{[de]}GEMEINSCHAFT{[en]}COMMUNITY{[it]}COMMUNITY', '#', 1, 3, 1, 1),
(4, '{[de]}MEDIEN{[en]}MEDIA{[it]}MEDIA', '#', 1, 4, 1, 1),
(5, '{[de]}SOCIAL{[en]}SOCIAL{[it]}SOCIAL', '#', 1, 5, 1, 1),
(6, '{[de]}SONSTIGES{[en]}MISCELLANEOUS{[it]}VARIE', '#', 1, 6, 1, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `navigation_website_sub`
--

CREATE TABLE `navigation_website_sub` (
  `snavID` int(11) NOT NULL,
  `mnavID` int(11) NOT NULL DEFAULT 0,
  `name` varchar(255) NOT NULL DEFAULT '',
  `modulname` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `sort` int(2) NOT NULL DEFAULT 0,
  `indropdown` int(1) NOT NULL DEFAULT 1,
  `themes_modulname` varchar(255) DEFAULT 'default'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `navigation_website_sub`
--

INSERT INTO `navigation_website_sub` (`snavID`, `mnavID`, `name`, `modulname`, `url`, `sort`, `indropdown`, `themes_modulname`) VALUES
(1, 6, '{[de]}Kontakt{[en]}Contact{[it]}Contatti', 'contact', 'index.php?site=contact', 1, 1, 'default'),
(2, 6, '{[de]}Datenschutz-Bestimmungen{[en]}Privacy Policy{[it]}Informativa sulla Privacy', 'privacy_policy', 'index.php?site=privacy_policy', 2, 1, 'default'),
(3, 6, '{[de]}Impressum{[en]}Imprint{[it]}Impronta Editoriale', 'imprint', 'index.php?site=imprint', 3, 1, 'default'),
(59, 3, '{[de]}Server Regeln{[en]}Server Rules{[it]}Regole Server', 'server_rules', 'index.php?site=server_rules', 1, 1, 'default');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `plugins_clan_rules`
--

CREATE TABLE `plugins_clan_rules` (
  `clan_rulesID` int(11) NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `text` text NOT NULL,
  `poster` int(11) NOT NULL,
  `date` int(14) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT 0,
  `displayed` varchar(255) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Daten für Tabelle `plugins_clan_rules`
--

INSERT INTO `plugins_clan_rules` (`clan_rulesID`, `title`, `text`, `poster`, `date`, `sort`, `displayed`) VALUES
(1, 'Wir haben für Euch eine tolle Neuigkeit!', 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.', 1, 1743982805, 1, '1'),
(2, '2', '2', 1, 1743982857, 1, '1');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `plugins_clan_rules_settings_widgets`
--

CREATE TABLE `plugins_clan_rules_settings_widgets` (
  `id` int(11) NOT NULL,
  `position` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `modulname` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `themes_modulname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `widgetname` varchar(255) NOT NULL DEFAULT '',
  `widgetdatei` varchar(255) NOT NULL DEFAULT '',
  `activated` int(1) DEFAULT 1,
  `sort` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Daten für Tabelle `plugins_clan_rules_settings_widgets`
--

INSERT INTO `plugins_clan_rules_settings_widgets` (`id`, `position`, `modulname`, `themes_modulname`, `widgetname`, `widgetdatei`, `activated`, `sort`) VALUES
(1, 'navigation_widget', 'navigation', 'default', 'Navigation', 'widget_navigation', 1, 1),
(2, 'footer_widget', 'footer', 'default', 'Footer Easy', 'widget_footer_easy', 1, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `plugins_counter_settings_widgets`
--

CREATE TABLE `plugins_counter_settings_widgets` (
  `id` int(11) NOT NULL,
  `position` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `modulname` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `themes_modulname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `widgetname` varchar(255) NOT NULL DEFAULT '',
  `widgetdatei` varchar(255) NOT NULL DEFAULT '',
  `activated` int(1) DEFAULT 1,
  `sort` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Daten für Tabelle `plugins_counter_settings_widgets`
--

INSERT INTO `plugins_counter_settings_widgets` (`id`, `position`, `modulname`, `themes_modulname`, `widgetname`, `widgetdatei`, `activated`, `sort`) VALUES
(1, 'navigation_widget', 'navigation', 'default', 'Navigation', 'widget_navigation', 1, 1),
(2, 'footer_widget', 'footer', 'default', 'Footer Easy', 'widget_footer_easy', 1, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `plugins_footer`
--

CREATE TABLE `plugins_footer` (
  `footID` int(11) NOT NULL,
  `banner` varchar(255) NOT NULL DEFAULT '',
  `about` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `strasse` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telefon` varchar(255) NOT NULL,
  `since` varchar(255) NOT NULL DEFAULT '',
  `linkname1` varchar(255) NOT NULL,
  `navilink1` varchar(255) NOT NULL,
  `linkname2` varchar(255) NOT NULL,
  `navilink2` varchar(255) NOT NULL,
  `linkname3` varchar(255) NOT NULL,
  `navilink3` varchar(255) NOT NULL,
  `linkname4` varchar(255) NOT NULL,
  `navilink4` varchar(255) NOT NULL,
  `linkname5` varchar(255) NOT NULL,
  `navilink5` varchar(255) NOT NULL,
  `linkname6` varchar(255) NOT NULL,
  `navilink6` varchar(255) NOT NULL,
  `linkname7` varchar(255) NOT NULL,
  `navilink7` varchar(255) NOT NULL,
  `linkname8` varchar(255) NOT NULL,
  `navilink8` varchar(255) NOT NULL,
  `linkname9` varchar(255) NOT NULL,
  `navilink9` varchar(255) NOT NULL,
  `linkname10` varchar(255) NOT NULL,
  `navilink10` varchar(255) NOT NULL,
  `social_text` varchar(255) NOT NULL,
  `social_link_name1` text NOT NULL,
  `social_link1` varchar(255) NOT NULL,
  `social_link_name2` varchar(255) NOT NULL,
  `social_link2` varchar(255) NOT NULL,
  `social_link_name3` varchar(255) NOT NULL,
  `social_link3` varchar(255) NOT NULL,
  `copyright_link_name1` varchar(255) NOT NULL,
  `copyright_link1` varchar(255) NOT NULL,
  `copyright_link_name2` varchar(255) NOT NULL,
  `copyright_link2` varchar(255) NOT NULL,
  `copyright_link_name3` varchar(255) NOT NULL,
  `copyright_link3` varchar(255) NOT NULL,
  `copyright_link_name4` varchar(255) NOT NULL,
  `copyright_link4` varchar(255) NOT NULL,
  `copyright_link_name5` varchar(255) NOT NULL,
  `copyright_link5` varchar(255) NOT NULL,
  `widget_left` varchar(255) NOT NULL,
  `widget_center` varchar(255) NOT NULL,
  `widget_right` varchar(255) NOT NULL,
  `widgetdatei_left` varchar(255) NOT NULL,
  `widgetdatei_center` varchar(255) NOT NULL,
  `widgetdatei_right` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Daten für Tabelle `plugins_footer`
--

INSERT INTO `plugins_footer` (`footID`, `banner`, `about`, `name`, `strasse`, `email`, `telefon`, `since`, `linkname1`, `navilink1`, `linkname2`, `navilink2`, `linkname3`, `navilink3`, `linkname4`, `navilink4`, `linkname5`, `navilink5`, `linkname6`, `navilink6`, `linkname7`, `navilink7`, `linkname8`, `navilink8`, `linkname9`, `navilink9`, `linkname10`, `navilink10`, `social_text`, `social_link_name1`, `social_link1`, `social_link_name2`, `social_link2`, `social_link_name3`, `social_link3`, `copyright_link_name1`, `copyright_link1`, `copyright_link_name2`, `copyright_link2`, `copyright_link_name3`, `copyright_link3`, `copyright_link_name4`, `copyright_link4`, `copyright_link_name5`, `copyright_link5`, `widget_left`, `widget_center`, `widget_right`, `widgetdatei_left`, `widgetdatei_center`, `widgetdatei_right`) VALUES
(1, '', 'Team Clanname ist eine 1999 gegründete deutsche E-Sport-Organisation, welche über professionelle Spieler in unterschiedlichen Disziplinen verfügt...', 'Hans Mustermann', 'Musterhausen 11, Germany', 'mail@Clanname-esport.de', '(123) 456-7890', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'Impressum', 'index.php?site=imprint', 'Datenschutz', 'index.php?site=privacy_policy', 'Kontakt', 'index.php?site=contact', 'Counter', 'index.php?site=counter', '', '', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `plugins_footer_target`
--

CREATE TABLE `plugins_footer_target` (
  `targetID` int(11) NOT NULL,
  `windows1` int(1) NOT NULL DEFAULT 1,
  `windows2` int(1) NOT NULL DEFAULT 1,
  `windows3` int(1) NOT NULL DEFAULT 1,
  `windows4` int(1) NOT NULL DEFAULT 1,
  `windows5` int(1) NOT NULL DEFAULT 1,
  `windows6` int(1) NOT NULL DEFAULT 1,
  `windows7` int(1) NOT NULL DEFAULT 1,
  `windows8` int(1) NOT NULL DEFAULT 1,
  `windows9` int(1) NOT NULL DEFAULT 1,
  `windows10` int(1) NOT NULL DEFAULT 1,
  `windows11` int(1) NOT NULL DEFAULT 1,
  `windows12` int(1) NOT NULL DEFAULT 1,
  `windows13` int(1) NOT NULL DEFAULT 1,
  `windows14` int(1) NOT NULL DEFAULT 1,
  `windows15` int(1) NOT NULL DEFAULT 1,
  `windows16` int(1) NOT NULL DEFAULT 1,
  `windows17` int(1) NOT NULL DEFAULT 1,
  `windows18` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Daten für Tabelle `plugins_footer_target`
--

INSERT INTO `plugins_footer_target` (`targetID`, `windows1`, `windows2`, `windows3`, `windows4`, `windows5`, `windows6`, `windows7`, `windows8`, `windows9`, `windows10`, `windows11`, `windows12`, `windows13`, `windows14`, `windows15`, `windows16`, `windows17`, `windows18`) VALUES
(1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `plugins_startpage_settings_widgets`
--

CREATE TABLE `plugins_startpage_settings_widgets` (
  `id` int(11) NOT NULL,
  `position` varchar(255) NOT NULL,
  `modulname` varchar(255) NOT NULL,
  `themes_modulname` varchar(255) NOT NULL,
  `widgetname` varchar(255) NOT NULL,
  `widgetdatei` varchar(255) NOT NULL,
  `activated` int(1) DEFAULT 1,
  `sort` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `plugins_startpage_settings_widgets`
--

INSERT INTO `plugins_startpage_settings_widgets` (`id`, `position`, `modulname`, `themes_modulname`, `widgetname`, `widgetdatei`, `activated`, `sort`) VALUES
(2, 'footer_widget', 'footer', 'default', 'Footer Easy', 'widget_footer_easy', 1, 2),
(23, 'navigation_widget', 'navigation', 'default', 'Navigation', 'widget_navigation', 1, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sessions`
--

CREATE TABLE `sessions` (
  `sessionID` varchar(255) NOT NULL,
  `userID` int(11) NOT NULL,
  `ip` varchar(64) DEFAULT NULL,
  `lastaction` int(11) DEFAULT NULL,
  `browser` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `settings`
--

CREATE TABLE `settings` (
  `settingID` int(11) NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `hpurl` varchar(255) NOT NULL DEFAULT '',
  `clanname` varchar(255) NOT NULL DEFAULT '',
  `clantag` varchar(255) NOT NULL DEFAULT '',
  `adminname` varchar(255) NOT NULL DEFAULT '',
  `adminemail` varchar(255) NOT NULL DEFAULT '',
  `sball` int(11) NOT NULL DEFAULT 0,
  `topics` int(11) NOT NULL DEFAULT 0,
  `posts` int(11) NOT NULL DEFAULT 0,
  `latesttopics` int(11) NOT NULL,
  `latesttopicchars` int(11) NOT NULL DEFAULT 0,
  `messages` int(11) NOT NULL DEFAULT 0,
  `register_per_ip` int(1) NOT NULL DEFAULT 1,
  `sessionduration` int(3) NOT NULL,
  `closed` int(1) NOT NULL DEFAULT 0,
  `imprint` int(1) NOT NULL DEFAULT 0,
  `default_language` varchar(2) NOT NULL DEFAULT 'en',
  `insertlinks` int(1) NOT NULL DEFAULT 1,
  `search_min_len` int(3) NOT NULL DEFAULT 3,
  `max_wrong_pw` int(2) NOT NULL DEFAULT 10,
  `captcha_math` int(1) NOT NULL DEFAULT 2,
  `captcha_bgcol` varchar(7) NOT NULL DEFAULT '#FFFFFF',
  `captcha_fontcol` varchar(7) NOT NULL DEFAULT '#000000',
  `captcha_type` int(1) NOT NULL DEFAULT 2,
  `captcha_noise` int(3) NOT NULL DEFAULT 100,
  `captcha_linenoise` int(2) NOT NULL DEFAULT 10,
  `bancheck` int(13) NOT NULL,
  `spam_check` int(1) NOT NULL DEFAULT 0,
  `detect_language` int(1) NOT NULL DEFAULT 0,
  `spammaxposts` int(11) NOT NULL DEFAULT 0,
  `spamapiblockerror` int(1) NOT NULL DEFAULT 0,
  `date_format` varchar(255) NOT NULL DEFAULT 'd.m.Y',
  `time_format` varchar(255) NOT NULL DEFAULT 'H:i',
  `modRewrite` int(1) NOT NULL DEFAULT 0,
  `startpage` varchar(255) NOT NULL,
  `forum_double` int(1) NOT NULL DEFAULT 1,
  `profilelast` int(11) NOT NULL DEFAULT 10,
  `de_lang` int(1) DEFAULT 1,
  `en_lang` int(1) DEFAULT 1,
  `it_lang` int(1) DEFAULT 1,
  `birthday` int(11) NOT NULL DEFAULT 0,
  `keywords` text NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `settings`
--

INSERT INTO `settings` (`settingID`, `title`, `hpurl`, `clanname`, `clantag`, `adminname`, `adminemail`, `sball`, `topics`, `posts`, `latesttopics`, `latesttopicchars`, `messages`, `register_per_ip`, `sessionduration`, `closed`, `imprint`, `default_language`, `insertlinks`, `search_min_len`, `max_wrong_pw`, `captcha_math`, `captcha_bgcol`, `captcha_fontcol`, `captcha_type`, `captcha_noise`, `captcha_linenoise`, `bancheck`, `spam_check`, `detect_language`, `spammaxposts`, `spamapiblockerror`, `date_format`, `time_format`, `modRewrite`, `startpage`, `forum_double`, `profilelast`, `de_lang`, `en_lang`, `it_lang`, `birthday`, `keywords`, `description`) VALUES
(1, 'Webspell-RM', 'https://next-version.webspell-rm.de', 'Clan Name', 'MyClan', 'T-Seven', 'info@webspell-rm.de', 30, 20, 10, 10, 18, 20, 1, 0, 0, 1, 'de', 1, 3, 10, 2, '#FFFFFF', '#000000', 2, 100, 10, 1744975095, 0, 0, 0, 0, 'd.m.Y', 'H:i', 0, 'startpage', 1, 10, 1, 1, 1, 0, 'Clandesign, Webspell, Webspell-RM, Wespellanpassungen, Webdesign, Tutorials, Downloads, Webspell-rm, rm, addon, plugin, Templates Webspell Addons, plungin, mods, Webspellanpassungen, Modifikationen und Anpassungen und mehr!', 'Kostenlose Homepage erstellen mit Webspell-RM CMS: Einfach, schnell & kostenlos! In wenigen Minuten mit der eigenen Website online gehen.');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `settings_buttons`
--

CREATE TABLE `settings_buttons` (
  `buttonID` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `modulname` varchar(255) NOT NULL DEFAULT '',
  `active` int(11) DEFAULT NULL,
  `version` varchar(11) NOT NULL,
  `button1` varchar(255) NOT NULL DEFAULT '',
  `button2` varchar(255) NOT NULL DEFAULT '',
  `button3` varchar(255) NOT NULL DEFAULT '',
  `button4` varchar(255) NOT NULL DEFAULT '',
  `button5` varchar(255) NOT NULL DEFAULT '',
  `button6` varchar(255) NOT NULL DEFAULT '',
  `button7` varchar(255) NOT NULL DEFAULT '',
  `button8` varchar(255) NOT NULL DEFAULT '',
  `button9` varchar(255) NOT NULL DEFAULT '',
  `button10` varchar(255) NOT NULL DEFAULT '',
  `button11` varchar(255) NOT NULL DEFAULT '',
  `button12` varchar(255) NOT NULL DEFAULT '',
  `button13` varchar(255) NOT NULL DEFAULT '',
  `button14` varchar(255) NOT NULL DEFAULT '',
  `button15` varchar(255) NOT NULL DEFAULT '',
  `button16` varchar(255) NOT NULL DEFAULT '',
  `button17` varchar(255) NOT NULL DEFAULT '',
  `button18` varchar(255) NOT NULL DEFAULT '',
  `button19` varchar(255) NOT NULL DEFAULT '',
  `button20` varchar(255) NOT NULL DEFAULT '',
  `button21` varchar(255) NOT NULL DEFAULT '',
  `button22` varchar(255) NOT NULL DEFAULT '',
  `button23` varchar(255) NOT NULL DEFAULT '',
  `button24` varchar(255) NOT NULL DEFAULT '',
  `button25` varchar(255) NOT NULL DEFAULT '',
  `button26` varchar(255) NOT NULL DEFAULT '',
  `button27` varchar(255) NOT NULL DEFAULT '',
  `button28` varchar(255) NOT NULL DEFAULT '',
  `button29` varchar(255) NOT NULL DEFAULT '',
  `button30` varchar(255) NOT NULL DEFAULT '',
  `button31` varchar(255) NOT NULL DEFAULT '',
  `button32` varchar(255) NOT NULL DEFAULT '',
  `button33` varchar(255) NOT NULL DEFAULT '',
  `button34` varchar(255) NOT NULL DEFAULT '',
  `button35` varchar(255) NOT NULL DEFAULT '',
  `button36` varchar(255) NOT NULL DEFAULT '',
  `button37` varchar(255) NOT NULL DEFAULT '',
  `button38` varchar(255) NOT NULL DEFAULT '',
  `button39` varchar(255) NOT NULL DEFAULT '',
  `button40` varchar(255) NOT NULL DEFAULT '',
  `button41` varchar(255) NOT NULL DEFAULT '',
  `button42` varchar(255) NOT NULL DEFAULT '',
  `btn_border_radius` varchar(255) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `settings_buttons`
--

INSERT INTO `settings_buttons` (`buttonID`, `name`, `modulname`, `active`, `version`, `button1`, `button2`, `button3`, `button4`, `button5`, `button6`, `button7`, `button8`, `button9`, `button10`, `button11`, `button12`, `button13`, `button14`, `button15`, `button16`, `button17`, `button18`, `button19`, `button20`, `button21`, `button22`, `button23`, `button24`, `button25`, `button26`, `button27`, `button28`, `button29`, `button30`, `button31`, `button32`, `button33`, `button34`, `button35`, `button36`, `button37`, `button38`, `button39`, `button40`, `button41`, `button42`, `btn_border_radius`) VALUES
(81, 'Default', 'default', 0, '0.3', 'rgb(254,130,29)', 'rgb(196,89,1)', 'rgb(255,255,255)', 'rgb(254,130,29)', 'rgb(196,89,1)', 'rgb(108,117,125)', 'rgb(90,98,104)', 'rgb(255,255,255)', 'rgb(108,117,125)', 'rgb(84,91,98)', 'rgb(40,167,69)', 'rgb(33,136,56)', 'rgb(255,255,255)', 'rgb(40,167,69)', 'rgb(30,126,52)', 'rgb(220,53,69)', 'rgb(200,35,51)', 'rgb(255,255,255)', 'rgb(220,53,69)', 'rgb(189,33,48)', 'rgb(255,193,7)', 'rgb(224,168,0)', 'rgb(33,37,41)', 'rgb(255,193,7)', 'rgb(211,158,0)', 'rgb(23,162,184)', 'rgb(19,132,150)', 'rgb(255,255,255)', 'rgb(23,162,184)', 'rgb(17,122,139)', 'rgb(248,249,250)', 'rgb(226,230,234)', 'rgb(33,37,41)', 'rgb(248,249,250)', 'rgb(218,224,229)', 'rgb(52,58,64)', 'rgb(35,39,43)', 'rgb(255,255,255)', 'rgb(52,58,64)', 'rgb(29,33,36)', 'rgb(254,130,29)', 'rgb(196,89,1)', '0px'),
(84, 'Cyborg', 'cyborg', 0, '0.3', 'rgb(255,193,7)', 'rgb(225,171,6)', 'rgb(0,0,0)', 'rgb(87,71,71)', 'rgb(57,47,47)', 'rgb(85,85,85)', 'rgb(149,148,148)', 'rgb(255,255,255)', 'rgb(87,71,71)', 'rgb(57,47,47)', 'rgb(119,179,0)', 'rgb(94,140,1)', 'rgb(255,255,255)', 'rgb(87,71,71)', 'rgb(57,47,47)', 'rgb(204,0,0)', 'rgb(173,2,2)', 'rgb(245,245,245)', 'rgb(87,71,71)', 'rgb(57,47,47)', 'rgb(255,136,0)', 'rgb(203,109,2)', 'rgb(0,0,0)', 'rgb(87,71,71)', 'rgb(57,47,47)', 'rgb(42,159,214)', 'rgb(19,132,150)', 'rgb(245,245,245)', 'rgb(87,71,71)', 'rgb(57,47,47)', 'rgb(173,175,174)', 'rgb(237,237,237)', 'rgb(0,0,0)', 'rgb(87,71,71)', 'rgb(57,47,47)', 'rgb(34,34,34)', 'rgb(152,151,151)', 'rgb(245,245,245)', 'rgb(87,71,71)', 'rgb(57,47,47)', 'rgb(255,193,7)', 'rgb(225,171,6)', '0px'),
(85, 'Slate', 'slate', 1, '0.3', 'rgb(58,63,68)', 'rgb(49,54,58)', 'rgb(245,245,245)', 'rgb(170,170,170)', 'rgb(84,91,98)', 'rgb(122,130,136)', 'rgb(104,111,116)', 'rgb(245,245,245)', 'rgb(170,170,170)', 'rgb(84,91,98)', 'rgb(98,196,98)', 'rgb(83,167,83)', 'rgb(245,245,245)', 'rgb(170,170,170)', 'rgb(84,91,98)', 'rgb(238,95,91)', 'rgb(202,81,77)', 'rgb(245,245,245)', 'rgb(170,170,170)', 'rgb(84,91,98)', 'rgb(248,148,6)', 'rgb(211,126,5)', 'rgb(0,0,0)', 'rgb(170,170,170)', 'rgb(84,91,98)', 'rgb(91,192,222)', 'rgb(77,163,189)', 'rgb(245,245,245)', 'rgb(170,170,170)', 'rgb(84,91,98)', 'rgb(233,236,239)', 'rgb(198,201,203)', 'rgb(0,0,0)', 'rgb(170,170,170)', 'rgb(84,91,98)', 'rgb(39,43,48)', 'rgb(71,75,79)', 'rgb(245,245,245)', 'rgb(170,170,170)', 'rgb(84,91,98)', 'rgb(254,130,29)', 'rgb(196,89,1)', '0px'),
(86, 'Cyborg', 'cyborg', 0, '0.3', 'rgb(255,193,7)', 'rgb(225,171,6)', 'rgb(0,0,0)', 'rgb(87,71,71)', 'rgb(57,47,47)', 'rgb(85,85,85)', 'rgb(149,148,148)', 'rgb(255,255,255)', 'rgb(87,71,71)', 'rgb(57,47,47)', 'rgb(119,179,0)', 'rgb(94,140,1)', 'rgb(255,255,255)', 'rgb(87,71,71)', 'rgb(57,47,47)', 'rgb(204,0,0)', 'rgb(173,2,2)', 'rgb(245,245,245)', 'rgb(87,71,71)', 'rgb(57,47,47)', 'rgb(255,136,0)', 'rgb(203,109,2)', 'rgb(0,0,0)', 'rgb(87,71,71)', 'rgb(57,47,47)', 'rgb(42,159,214)', 'rgb(19,132,150)', 'rgb(245,245,245)', 'rgb(87,71,71)', 'rgb(57,47,47)', 'rgb(173,175,174)', 'rgb(237,237,237)', 'rgb(0,0,0)', 'rgb(87,71,71)', 'rgb(57,47,47)', 'rgb(34,34,34)', 'rgb(152,151,151)', 'rgb(245,245,245)', 'rgb(87,71,71)', 'rgb(57,47,47)', 'rgb(255,193,7)', 'rgb(225,171,6)', '0px');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `settings_expansion`
--

CREATE TABLE `settings_expansion` (
  `themeID` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `modulname` varchar(100) NOT NULL,
  `pfad` varchar(255) NOT NULL,
  `version` varchar(11) NOT NULL,
  `active` int(11) DEFAULT NULL,
  `express_active` int(11) NOT NULL DEFAULT 0,
  `nav1` varchar(255) NOT NULL,
  `nav2` varchar(255) NOT NULL,
  `nav3` varchar(255) NOT NULL,
  `nav4` varchar(255) NOT NULL,
  `nav5` varchar(255) NOT NULL,
  `nav6` varchar(255) NOT NULL,
  `nav7` varchar(255) NOT NULL,
  `nav8` varchar(255) NOT NULL,
  `nav9` varchar(255) NOT NULL,
  `nav10` varchar(255) NOT NULL,
  `nav11` varchar(255) NOT NULL,
  `nav12` varchar(255) NOT NULL,
  `nav_text_alignment` varchar(255) DEFAULT '0',
  `body1` text NOT NULL,
  `body2` varchar(255) NOT NULL,
  `body3` varchar(255) NOT NULL,
  `body4` varchar(255) NOT NULL,
  `body5` varchar(255) NOT NULL,
  `background_pic` varchar(255) DEFAULT '0',
  `border_radius` varchar(255) DEFAULT '0',
  `typo1` varchar(255) NOT NULL,
  `typo2` varchar(255) NOT NULL,
  `typo3` varchar(255) NOT NULL,
  `typo4` varchar(255) NOT NULL,
  `typo5` varchar(255) NOT NULL,
  `typo6` varchar(255) NOT NULL,
  `typo7` varchar(255) NOT NULL,
  `typo8` varchar(255) NOT NULL,
  `card1` varchar(255) NOT NULL,
  `card2` varchar(255) NOT NULL,
  `foot1` varchar(255) NOT NULL,
  `foot2` varchar(255) NOT NULL,
  `foot3` varchar(255) NOT NULL,
  `foot4` varchar(255) NOT NULL,
  `foot5` varchar(255) NOT NULL,
  `foot6` varchar(255) NOT NULL,
  `calendar1` varchar(255) NOT NULL,
  `calendar2` varchar(255) NOT NULL,
  `carousel1` varchar(255) NOT NULL,
  `carousel2` varchar(255) NOT NULL,
  `carousel3` varchar(255) NOT NULL,
  `carousel4` varchar(255) NOT NULL,
  `logo_pic` varchar(255) DEFAULT '0',
  `logotext1` varchar(255) NOT NULL,
  `logotext2` varchar(255) NOT NULL,
  `reg_pic` varchar(255) NOT NULL,
  `reg1` varchar(255) NOT NULL,
  `reg2` varchar(255) NOT NULL,
  `headlines` varchar(255) DEFAULT '0',
  `sort` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Daten für Tabelle `settings_expansion`
--

INSERT INTO `settings_expansion` (`themeID`, `name`, `modulname`, `pfad`, `version`, `active`, `express_active`, `nav1`, `nav2`, `nav3`, `nav4`, `nav5`, `nav6`, `nav7`, `nav8`, `nav9`, `nav10`, `nav11`, `nav12`, `nav_text_alignment`, `body1`, `body2`, `body3`, `body4`, `body5`, `background_pic`, `border_radius`, `typo1`, `typo2`, `typo3`, `typo4`, `typo5`, `typo6`, `typo7`, `typo8`, `card1`, `card2`, `foot1`, `foot2`, `foot3`, `foot4`, `foot5`, `foot6`, `calendar1`, `calendar2`, `carousel1`, `carousel2`, `carousel3`, `carousel4`, `logo_pic`, `logotext1`, `logotext2`, `reg_pic`, `reg1`, `reg2`, `headlines`, `sort`) VALUES
(1, 'Default', 'default', 'default', '0.3', 0, 0, 'rgb(51,51,51)', '16px', 'rgb(221,221,221)', 'rgb(254,130,29)', 'rgb(254,130,29)', '2px', 'rgb(221,221,221)', 'rgb(196,89,1)', '', 'rgb(51,51,51)', 'rgb(221,221,221)', 'rgb(101,100,100)', 'ms-auto', 'Roboto', '13px', 'rgb(255,255,255)', 'rgb(85,85,85)', 'rgb(236,236,236)', '', '0px', '', '', '', 'rgb(254,130,29)', '', '', '', 'rgb(196,89,1)', 'rgb(255,255,255)', 'rgb(221,221,221)', 'rgb(51,51,51)', '', 'rgb(255,255,255)', 'rgb(254,130,29)', 'rgb(255,255,255)', 'rgb(255,255,255)', '', '', 'rgb(255,255,255)', 'rgb(254,130,29)', 'rgb(255,255,255)', 'rgb(254,130,29)', 'default_logo.png', '', '', 'default_login_bg.jpg', 'rgb(254,130,29)', 'rgb(255,255,255)', 'headlines_03.css', 1),
(2, 'Cyborg', 'cyborg', 'cyborg', '0.3', 1, 0, 'rgb(6,6,6)', '16px', 'rgb(255,193,7)', 'rgb(6,6,6)', 'rgb(115,87,2)', '3px', 'rgb(173,175,174)', 'rgb(6,6,6)', 'rgb(255,193,7)', 'rgb(6,6,6)', 'rgb(255,193,7)', 'rgb(24,24,24)', 'ms-auto', 'Roboto', '13px', 'rgb(6,6,6)', 'rgb(173,175,174)', 'rgb(14,14,14)', '', '0px', '', '', '', 'rgb(255,193,7)', '', '', '', 'rgb(225,171,6)', 'rgb(6,6,6)', 'rgb(115,87,2)', 'rgb(14,14,14)', 'rgb(255,255,255)', 'rgb(255,255,255)', 'rgb(255,193,7)', 'rgb(255,193,7)', 'rgb(255,255,255)', '', '', 'rgb(255,255,255)', 'rgb(255,193,7)', 'rgb(255,255,255)', 'rgb(255,193,7)', 'cyborg_logo.png', '', '', 'cyborg_login_bg.jpg', 'rgb(255,193,7)', 'rgb(255,255,255)', 'headlines_02.css', 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `settings_imprint`
--

CREATE TABLE `settings_imprint` (
  `imprintID` int(11) NOT NULL,
  `imprint` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `disclaimer_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `settings_imprint`
--

INSERT INTO `settings_imprint` (`imprintID`, `imprint`, `disclaimer_text`) VALUES
(1, '{[de]} Impressum in deutscher Sprache.<br />\r\n<span style=\"color:#c0392b\"><strong>Konfigurieren Sie bitte Ihr Impressum!</strong></span><br />\r\n{[en]} Imprint in English.<br />\r\n<span style=\"color:#c0392b\"><strong>Please configure your imprint!</strong></span>{[it]} Impronta Editoriale in Italiano.<br />\r\n<span style=\"color:#c0392b\"><strong>Si prega di configurare l&rsquo;impronta!</strong></span>', '{[de]} Haftungsausschluss in deutscher Sprache.<br />\r\n<span style=\"color:#c0392b\"><strong>Konfigurieren Sie bitte Ihr Haftungsausschluss! </strong></span><br />\r\n{[en]} Disclaimer in English.<br />\r\n<span style=\"color:#c0392b\"><strong>Please configure your disclaimer!</strong></span>{[it]} Dichiarazione di non Responsabilit&Atilde;&nbsp; in Italiano.<br />\r\n<span style=\"color:#c0392b\"><strong>Si prega di configurare la Dichiarazione di non Responsabilit&Atilde;&nbsp;!</strong></span>');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `settings_languages`
--

CREATE TABLE `settings_languages` (
  `langID` int(11) NOT NULL,
  `language` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `lang` char(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `alt` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `settings_languages`
--

INSERT INTO `settings_languages` (`langID`, `language`, `lang`, `alt`) VALUES
(1, 'danish', 'da', 'danish'),
(2, 'dutch', 'nl', 'dutch'),
(3, 'english', 'en', 'english'),
(4, 'finnish', 'fi', 'finnish'),
(5, 'french', 'fr', 'french'),
(6, 'german', 'de', 'german'),
(7, 'hungarian', 'hu', 'hungarian'),
(8, 'italian', 'it', 'italian'),
(9, 'norwegian', 'no', 'norwegian'),
(10, 'spanish', 'es', 'spanish'),
(11, 'swedish', 'sv', 'swedish'),
(12, 'czech', 'cs', 'czech'),
(13, 'croatian', 'hr', 'croatian'),
(14, 'lithuanian', 'lt', 'lithuanian'),
(15, 'polish', 'pl', 'polish'),
(16, 'portuguese', 'pt', 'portuguese'),
(17, 'slovak', 'sk', 'slovak'),
(18, 'arabic', 'ar', 'arabic'),
(19, 'bosnian', 'bs', 'bosnian'),
(20, 'estonian', 'et', 'estonian'),
(21, 'georgian', 'ka', 'georgian'),
(22, 'macedonian', 'mk', 'macedonian'),
(23, 'persian', 'fa', 'persian'),
(24, 'romanian', 'ro', 'romanian'),
(25, 'russian', 'ru', 'russian'),
(26, 'serbian', 'sr', 'serbian'),
(27, 'slovenian', 'sl', 'slovenian'),
(28, 'latvian', 'lv', 'latvian'),
(29, 'turkish', 'tr', 'turkish'),
(30, 'albanian', 'sq', 'albanian'),
(31, 'bulgarian', 'bg', 'bulgarian'),
(32, 'greek', 'el', 'greek'),
(33, 'ukrainian', 'uk', 'ukrainian'),
(34, 'luxembourgish', 'lb', 'luxembourgish'),
(35, 'afrikaans', 'af', 'afrikaans'),
(36, 'acholi', 'ac', 'acholi');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `settings_plugins`
--

CREATE TABLE `settings_plugins` (
  `pluginID` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `modulname` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `info` text NOT NULL,
  `admin_file` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `activate` int(1) NOT NULL DEFAULT 1,
  `author` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `website` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `index_link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `hiddenfiles` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `version` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_display` int(1) NOT NULL DEFAULT 1,
  `plugin_display` int(11) NOT NULL DEFAULT 1,
  `widget_display` int(11) NOT NULL DEFAULT 1,
  `delete_display` int(1) NOT NULL DEFAULT 1,
  `sidebar` varchar(255) NOT NULL DEFAULT 'deactivated'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `settings_plugins`
--

INSERT INTO `settings_plugins` (`pluginID`, `name`, `modulname`, `info`, `admin_file`, `activate`, `author`, `website`, `index_link`, `hiddenfiles`, `version`, `path`, `status_display`, `plugin_display`, `widget_display`, `delete_display`, `sidebar`) VALUES
(2, 'Privacy Policy', 'privacy_policy', '{[de]}Kein Plugin. Bestandteil vom System!!!{[en]}No plugin. Part of the system!!!{[it]}Nessun plug-in. Parte del sistema!!!', '', 1, '', '', 'privacy_policy', '', '', '', 0, 0, 1, 0, 'deactivated'),
(3, 'Imprint', 'imprint', '{[de]}Kein Plugin. Bestandteil vom System!!!{[en]}No plugin. Part of the system!!!{[it]}Nessun plug-in. Parte del sistema!!!', '', 1, '', '', 'imprint', '', '', '', 0, 0, 1, 0, 'deactivated'),
(4, 'Static', 'static', '{[de]}Kein Plugin. Bestandteil vom System!!!{[en]}No plugin. Part of the system!!!{[it]}Nessun plug-in. Parte del sistema!!!', '', 1, '', '', 'static', '', '', '', 1, 0, 1, 0, 'deactivated'),
(5, 'Error_404', 'error_404', '{[de]}Kein Plugin. Bestandteil vom System!!!{[en]}No plugin. Part of the system!!!{[it]}Nessun plug-in. Parte del sistema!!!', '', 1, '', '', 'error_404', '', '', '', 1, 0, 1, 0, 'deactivated'),
(6, 'Profile', 'profile', '{[de]}Kein Plugin. Bestandteil vom System!!!{[en]}No plugin. Part of the system!!!{[it]}Nessun plug-in. Parte del sistema!!!', '', 1, '', '', 'profile', '', '', '', 1, 0, 1, 0, 'deactivated'),
(7, 'Login', 'login', '{[de]}Kein Plugin. Bestandteil vom System!!!{[en]}No plugin. Part of the system!!!{[it]}Nessun plug-in. Parte del sistema!!!', '', 1, '', '', 'login', '', '', '', 1, 0, 1, 0, 'deactivated'),
(8, 'Lost Password', 'lostpassword', '{[de]}Kein Plugin. Bestandteil vom System!!!{[en]}No plugin. Part of the system!!!{[it]}Nessun plug-in. Parte del sistema!!!', '', 1, '', '', 'lostpassword', '', '', '', 1, 0, 1, 0, 'deactivated'),
(9, 'Contact', 'contact', '{[de]}Kein Plugin. Bestandteil vom System!!!{[en]}No plugin. Part of the system!!!{[it]}Nessun plug-in. Parte del sistema!!!', '', 1, '', '', 'contact', '', '', '', 1, 0, 1, 0, 'deactivated'),
(10, 'Register', 'register', '{[de]}Kein Plugin. Bestandteil vom System!!!{[en]}No plugin. Part of the system!!!{[it]}Nessun plug-in. Parte del sistema!!!', '', 1, '', '', 'register', '', '', '', 1, 0, 1, 0, 'deactivated'),
(11, 'My Profile', 'myprofile', '{[de]}Kein Plugin. Bestandteil vom System!!!{[en]}No plugin. Part of the system!!!{[it]}Nessun plug-in. Parte del sistema!!!', '', 1, '', '', 'myprofile', '', '', '', 1, 0, 1, 0, 'deactivated'),
(12, 'Report', 'report', '{[de]}Kein Plugin. Bestandteil vom System!!!{[en]}No plugin. Part of the system!!!{[it]}Nessun plug-in. Parte del sistema!!!', '', 1, '', '', 'report', '', '', '', 1, 0, 1, 0, 'deactivated'),
(61, 'Navigation', 'navigation', '{[de]}Mit diesem Plugin könnt ihr euch die Navigation anzeigen lassen.{[en]}With this plugin you can display navigation.{[it]}Con questo plugin puoi visualizzare la Barra di navigazione predefinita.', '', 1, 'T-Seven', 'https://webspell-rm.de', '', '', '0.3', 'includes/plugins/navigation/', 1, 1, 0, 0, 'deactivated'),
(83, 'Footer', 'footer', '{[de]}Mit diesem Plugin könnt ihr einen neuen Footer anzeigen lassen.{[en]}With this plugin you can have a new Footer displayed.{[it]}Con questo plugin puoi visualizzare un nuovo piè di pagina.', 'admin_footer', 1, 'T-Seven', 'https://webspell-rm.de', '', '', '0.1', 'includes/plugins/footer/', 1, 1, 0, 0, 'deactivated'),
(65, 'Clan Rules', 'clan_rules', '{[de]}Mit diesem Plugin könnt ihr eure Clan Regeln anzeigen lassen.{[en]}With this plugin it is possible to show the Clan Rules on the website.{[it]}Con questo plugin è possibile mostrare le Regole del Clan sul sito web', 'admin_clan_rules', 1, 'T-Seven', 'https://webspell-rm.de', 'clan_rules', '', '0.1', 'includes/plugins/clan_rules/', 1, 1, 1, 1, 'deactivated'),
(1, 'Startpage', 'startpage', '{[de]}Kein Plugin. Bestandteil vom System!!!{[en]}No plugin. Part of the system!!!{[it]}Nessun plug-in. Parte del sistema!!!', '', 1, '', '', '', '', '', '', 0, 0, 1, 0, 'full_activated'),
(79, 'Access Rights', 'access_rights', '', '', 1, '', '', '', '', '', '', 1, 1, 1, 1, 'deactivated'),
(77, 'Who is online', 'whoisonline', '{[de]}Mit diesem Plugin können Sie anzeigen, wie viele Benutzer auf Ihrer Webspell-RM-Seite online sind, mit detaillierten Statistiken darüber, wo sie sich befinden und wer sie sind (Mitglieder/Gäste).{[en]}This plugin enables you to display how many users are online on your Webspell-RM-Site, with detailed statistics of where they are and who they are (Members/Guests).{[it]}Questo plugin ti consente di visualizzare quanti utenti sono online sul tuo sito Webspell-RM, con statistiche dettagliate su dove si trovano e chi sono (Membri/Ospiti).', '', 1, 'T-Seven', 'https://webspell-rm.de', 'whoisonline', '', '0.2', 'includes/plugins/counter/', 1, 1, 1, 1, 'deactivated'),
(75, 'Who is online', 'whoisonline', '{[de]}Mit diesem Plugin können Sie anzeigen, wie viele Benutzer auf Ihrer Webspell-RM-Seite online sind, mit detaillierten Statistiken darüber, wo sie sich befinden und wer sie sind (Mitglieder/Gäste).{[en]}This plugin enables you to display how many users are online on your Webspell-RM-Site, with detailed statistics of where they are and who they are (Members/Guests).{[it]}Questo plugin ti consente di visualizzare quanti utenti sono online sul tuo sito Webspell-RM, con statistiche dettagliate su dove si trovano e chi sono (Membri/Ospiti).', '', 1, 'T-Seven', 'https://webspell-rm.de', 'whoisonline', '', '0.2', 'includes/plugins/counter/', 1, 1, 1, 1, 'deactivated');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `settings_plugins_widget`
--

CREATE TABLE `settings_plugins_widget` (
  `id` int(11) NOT NULL,
  `modulname` varchar(100) NOT NULL,
  `widgetname` varchar(255) NOT NULL,
  `widgetdatei` varchar(255) NOT NULL,
  `area` int(11) NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `settings_plugins_widget`
--

INSERT INTO `settings_plugins_widget` (`id`, `modulname`, `widgetname`, `widgetdatei`, `area`) VALUES
(74, 'navigation', 'Navigation', 'widget_navigation', 2),
(85, 'footer', 'Footer Box', 'widget_footer_box', 6),
(84, 'footer', 'Footer Plugin', 'widget_footer_plugin', 6),
(83, 'footer', 'Footer Default', 'widget_footer_default', 6),
(82, 'footer', 'Footer Easy', 'widget_footer_easy', 6);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `settings_plugins_widget_settings`
--

CREATE TABLE `settings_plugins_widget_settings` (
  `id` int(11) NOT NULL,
  `side` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `modulname` varchar(100) NOT NULL,
  `themes_modulname` varchar(255) NOT NULL,
  `widgetname` varchar(255) NOT NULL,
  `widgetdatei` varchar(255) NOT NULL,
  `activated` int(1) DEFAULT 1,
  `sort` int(11) DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `settings_plugins_widget_settings`
--

INSERT INTO `settings_plugins_widget_settings` (`id`, `side`, `position`, `modulname`, `themes_modulname`, `widgetname`, `widgetdatei`, `activated`, `sort`) VALUES
(1, '', 'navigation_widget', 'navigation', 'default', 'Navigation', 'widget_navigation', 1, 0),
(2, '', 'footer_widget', 'footer', 'default', 'Footer Easy', 'widget_footer_easy', 1, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `settings_privacy_policy`
--

CREATE TABLE `settings_privacy_policy` (
  `privacy_policyID` int(11) NOT NULL,
  `date` int(14) NOT NULL,
  `privacy_policy_text` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `settings_privacy_policy`
--

INSERT INTO `settings_privacy_policy` (`privacy_policyID`, `date`, `privacy_policy_text`) VALUES
(1, 1576689811, '{[de]} Datenschutz-Bestimmungen in deutscher Sprache.<br /><span style=\"color:#c0392b\"><strong>Konfigurieren Sie bitte Ihre Datenschutz-Bestimmungen!</strong></span><br />{[en]} Privacy Policy in English.<br /><span style=\"color:#c0392b\"><strong>Please configure your Privacy Policy!</strong></span>{[it]} Informativa sulla Privacy in Italiano.<br /><span style=\"color:#c0392b\"><strong>Si prega di configurare l&rsquo;Informativa sulla Privacy!</strong></span>');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `settings_recaptcha`
--

CREATE TABLE `settings_recaptcha` (
  `activated` int(11) NOT NULL DEFAULT 0,
  `webkey` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `seckey` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `settings_recaptcha`
--

INSERT INTO `settings_recaptcha` (`activated`, `webkey`, `seckey`) VALUES
(0, 'Web-Key', 'Sec-Key');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `settings_social_media`
--

CREATE TABLE `settings_social_media` (
  `socialID` int(11) NOT NULL,
  `twitch` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `facebook` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `twitter` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `youtube` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rss` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `vine` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `flickr` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `linkedin` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `instagram` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `since` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `gametracker` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `discord` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `steam` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `settings_social_media`
--

INSERT INTO `settings_social_media` (`socialID`, `twitch`, `facebook`, `twitter`, `youtube`, `rss`, `vine`, `flickr`, `linkedin`, `instagram`, `since`, `gametracker`, `discord`, `steam`) VALUES
(1, 'https://www.twitch.tv/pulsradiocom', 'https://www.facebook.com/WebspellRM', 'https://twitter.com/webspell_rm', 'https://www.youtube.com/channel/UCE5yTn9ljzSnC_oMp9Jnckg', '-', '-', '-', '-', '-', '2025', '85.14.228.228:28960', 'https://www.discord.gg/kErxPxb', '-');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `settings_startpage`
--

CREATE TABLE `settings_startpage` (
  `pageID` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `startpage_text` longtext NOT NULL,
  `date` int(14) NOT NULL,
  `displayed` varchar(255) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `settings_startpage`
--

INSERT INTO `settings_startpage` (`pageID`, `title`, `startpage_text`, `date`, `displayed`) VALUES
(1, '{[de]}Willkommen zu Webspell | RM{[en]}Welcome to Webspell | RM{[it]}Benvenuti su Webspell | RM', '<!-- Page Content -->\r\n<div class=\"container\"><!-- Jumbotron Header -->\r\n<h1>Webspell RM!</h1>\r\n\r\n<p>{[de]}</p>\r\n\r\n<p><strong><u>Was ist Webspell RM?</u></strong><br />\r\n<br />\r\nWebspell RM ist ein Clan &amp; Gamer CMS (<em>Content Management System</em>). Es basiert auf PHP, MySQL und der letzten webSPELL.org GitHub Version (4.3.0). Webspell RM l&auml;uft unter der General Public License. Siehe auch <a href=\"http://wiki.webspell-rm.de/index.php?site=static&amp;staticID=4\" target=\"_blank\">Lizenzvereinbarung</a>.</p>\r\n\r\n<p style=\"text-align:center\"><a class=\"btn btn-info\" href=\"http://demo.webspell-rm.de/\" rel=\"noopener\" role=\"button\" target=\"_blank\"><strong><u>WEBSPELL RM DEMO</u></strong></a> <a class=\"btn btn-success\" href=\"https://webspell-rm.de/index.php?site=forum\" rel=\"noopener\" role=\"button\" target=\"_blank\"><strong><u>WEBSPELL RM SUPPORT</u></strong></a></p>\r\n\r\n<p><strong><u>Was bietet Webspell | RM?</u></strong><br />\r\n<br />\r\nWebspell RM basiert auf Bootstrap und ist einfach anzupassen via Dashboard. Theoretisch sind alle Bootstrap Templates verwendbar. Als Editor wir der CKEditor verwendet. Das CMS ist Multi-Language f&auml;hig und liefert von Haus aus viele Sprachen mit. Das beliebte reCAPTCHA wurde als Spam Schutz integriert. Alle Plugins sind via Webspell RM Installer einfach und problemlos zu installieren.</p>\r\n<!-- Page Features -->\r\n\r\n<div class=\"row text-center\">\r\n<div class=\"col mb-4\">\r\n<div class=\"card h-100\" style=\"width:15rem\"><img alt=\"\" class=\"card-img-top img-fluid\" src=\"https://www.webspell-rm.de/includes/plugins/pic_update/images/173.jpg\" />\r\n<div class=\"card-body\">\r\n<h4>Webside</h4>\r\n\r\n</div>\r\n\r\n<div class=\"card-footer\"><a class=\"btn btn-primary\" href=\"#\">Find Out More!</a></div>\r\n</div>\r\n</div>\r\n\r\n<div class=\"col mb-4\">\r\n<div class=\"card h-100\" style=\"width:15rem\"><img alt=\"\" class=\"card-img-top\" src=\"https://www.webspell-rm.de//includes/plugins/pic_update/images/170.jpg\" />\r\n<div class=\"card-body\">\r\n<h4>Dashboard</h4>\r\n\r\n</div>\r\n\r\n<div class=\"card-footer\"><a class=\"btn btn-primary\" href=\"/admin/admincenter.php\" target=\"_blank\">Find Out More!</a></div>\r\n</div>\r\n</div>\r\n\r\n<div class=\"col mb-4\">\r\n<div class=\"card h-100\" style=\"width:15rem\"><img alt=\"\" class=\"card-img-top img-fluid\" src=\"https://www.webspell-rm.de/includes/plugins/pic_update/images/171.jpg\" />\r\n<div class=\"card-body\">\r\n<h4>Layout</h4>\r\n\r\n</div>\r\n\r\n<div class=\"card-footer\"><a class=\"btn btn-primary\" href=\"/admin/admincenter.php?site=settings_templates\" target=\"_blank\">Find Out More!</a></div>\r\n</div>\r\n</div>\r\n\r\n<div class=\"col mb-4\">\r\n<div class=\"card h-100\" style=\"width:15rem\"><img alt=\"\" class=\"card-img-top img-fluid\" src=\"https://www.webspell-rm.de/includes/plugins/pic_update/images/172.jpg\" />\r\n<div class=\"card-body\">\r\n<h4>Plugin-Installer</h4>\r\n\r\n</div>\r\n\r\n<div class=\"card-footer\"><a class=\"btn btn-primary\" href=\"/admin/admincenter.php?site=plugin_installer\" target=\"_blank\">Find Out More!</a></div>\r\n</div>\r\n</div>\r\n<!-- zweite Reihe -->\r\n\r\n<div class=\"col mb-4\">\r\n<div class=\"card h-100\" style=\"width:15rem\"><img alt=\"\" class=\"card-img-top img-fluid\" src=\"https://www.webspell-rm.de/includes/plugins/pic_update/images/174.jpg\" />\r\n<div class=\"card-body\">\r\n<h4>Theme-Installer</h4>\r\n\r\n</div>\r\n\r\n<div class=\"card-footer\"><a class=\"btn btn-primary\" href=\"/admin/admincenter.php?site=template_installer\" target=\"_blank\">Find Out More!</a></div>\r\n</div>\r\n</div>\r\n\r\n<div class=\"col mb-4\">\r\n<div class=\"card h-100\" style=\"width:15rem\"><img alt=\"\" class=\"card-img-top img-fluid\" src=\"https://www.webspell-rm.de/includes/plugins/pic_update/images/175.jpg\" />\r\n<div class=\"card-body\">\r\n<h4>Updater</h4>\r\n\r\n</div>\r\n\r\n<div class=\"card-footer\"><a class=\"btn btn-primary\" href=\"/admin/admincenter.php?site=update\" target=\"_blank\">Find Out More!</a></div>\r\n</div>\r\n</div>\r\n\r\n<div class=\"col mb-4\">\r\n<div class=\"card h-100\" style=\"width:15rem\"><img alt=\"\" class=\"card-img-top img-fluid\" src=\"https://www.webspell-rm.de/includes/plugins/pic_update/images/176.jpg\" />\r\n<div class=\"card-body\">\r\n<h4>Startpage</h4>\r\n\r\n</div>\r\n\r\n<div class=\"card-footer\"><a class=\"btn btn-primary\" href=\"/admin/admincenter.php?site=settings_startpage\" target=\"_blank\">Find Out More!</a></div>\r\n</div>\r\n</div>\r\n\r\n<div class=\"col mb-4\">\r\n<div class=\"card h-100\" style=\"width:15rem\"><img alt=\"\" class=\"card-img-top img-fluid\" src=\"https://www.webspell-rm.de/includes/plugins/pic_update/images/177.jpg\" />\r\n<div class=\"card-body\">\r\n<h4>Webspell-RM</h4>\r\n\r\n</div>\r\n\r\n<div class=\"card-footer\"><a class=\"btn btn-primary\" href=\"https://www.webspell-rm.de/forum.html\" target=\"_blank\">Support</a> <a class=\"btn btn-primary\" href=\"https://www.webspell-rm.de/wiki.html\" target=\"_blank\">WIKI</a></div>\r\n</div>\r\n</div>\r\n</div>\r\n<!-- /.row --></div>\r\n<!-- /.container -->\r\n\r\n<p>{[en]}</p>\r\n\r\n<p><strong><u>What is Webspell RM?</u></strong><br />\r\n<br />\r\nWebspell RM is a Clan &amp; Gamer CMS (Content Management System). It is based on PHP, MySQL and the latest webSPELL.org GitHub version (4.3.0). Webspell RM runs under the General Public License. See also license agreement <a href=\"http://wiki.webspell-rm.de/index.php?site=static&amp;staticID=4\" target=\"_blank\">license agreement</a>.</p>\r\n\r\n<p style=\"text-align:center\"><a class=\"btn btn-info\" href=\"http://demo.webspell-rm.de/\" rel=\"noopener\" role=\"button\" target=\"_blank\"><strong><u>WEBSPELL RM DEMO</u></strong></a> <a class=\"btn btn-success\" href=\"https://webspell-rm.de/index.php?site=forum\" rel=\"noopener\" role=\"button\" target=\"_blank\"><strong><u>WEBSPELL RM SUPPORT</u></strong></a></p>\r\n\r\n<p><strong><u>What does Webspell | RM offer?</u></strong><br />\r\n<br />\r\nWebspell RM is based on bootstrap and it is easy to customize via dashboard. Theoretically, all bootstrap templates can be used. As editor we use the CKEditor. The CMS is multi-language capable and comes with many native languages. The popular reCAPTCHA was integrated as spam protection. All plugins are easy to install via Webspell RM Installer.</p>\r\n<!-- Page Features -->\r\n\r\n<div class=\"row text-center\">\r\n<div class=\"col mb-4\">\r\n<div class=\"card h-100\" style=\"width:15rem\"><img alt=\"\" class=\"card-img-top img-fluid\" src=\"https://www.webspell-rm.de/includes/plugins/pic_update/images/173.jpg\" />\r\n<div class=\"card-body\">\r\n<h4>Webside</h4>\r\n\r\n</div>\r\n\r\n<div class=\"card-footer\"><a class=\"btn btn-primary\" href=\"#\">Find Out More!</a></div>\r\n</div>\r\n</div>\r\n\r\n<div class=\"col mb-4\">\r\n<div class=\"card h-100\" style=\"width:15rem\"><img alt=\"\" class=\"card-img-top\" src=\"https://www.webspell-rm.de//includes/plugins/pic_update/images/170.jpg\" />\r\n<div class=\"card-body\">\r\n<h4>Dashboard</h4>\r\n\r\n</div>\r\n\r\n<div class=\"card-footer\"><a class=\"btn btn-primary\" href=\"/admin/admincenter.php\" target=\"_blank\">Find Out More!</a></div>\r\n</div>\r\n</div>\r\n\r\n<div class=\"col mb-4\">\r\n<div class=\"card h-100\" style=\"width:15rem\"><img alt=\"\" class=\"card-img-top img-fluid\" src=\"https://www.webspell-rm.de/includes/plugins/pic_update/images/171.jpg\" />\r\n<div class=\"card-body\">\r\n<h4>Layout</h4>\r\n\r\n</div>\r\n\r\n<div class=\"card-footer\"><a class=\"btn btn-primary\" href=\"/admin/admincenter.php?site=settings_templates\" target=\"_blank\">Find Out More!</a></div>\r\n</div>\r\n</div>\r\n\r\n<div class=\"col mb-4\">\r\n<div class=\"card h-100\" style=\"width:15rem\"><img alt=\"\" class=\"card-img-top img-fluid\" src=\"https://www.webspell-rm.de/includes/plugins/pic_update/images/172.jpg\" />\r\n<div class=\"card-body\">\r\n<h4>Plugin-Installer</h4>\r\n\r\n</div>\r\n\r\n<div class=\"card-footer\"><a class=\"btn btn-primary\" href=\"/admin/admincenter.php?site=plugin_installer\" target=\"_blank\">Find Out More!</a></div>\r\n</div>\r\n</div>\r\n<!-- zweite Reihe -->\r\n\r\n<div class=\"col mb-4\">\r\n<div class=\"card h-100\" style=\"width:15rem\"><img alt=\"\" class=\"card-img-top img-fluid\" src=\"https://www.webspell-rm.de/includes/plugins/pic_update/images/174.jpg\" />\r\n<div class=\"card-body\">\r\n<h4>Theme-Installer</h4>\r\n\r\n</div>\r\n\r\n<div class=\"card-footer\"><a class=\"btn btn-primary\" href=\"/admin/admincenter.php?site=template_installer\" target=\"_blank\">Find Out More!</a></div>\r\n</div>\r\n</div>\r\n\r\n<div class=\"col mb-4\">\r\n<div class=\"card h-100\" style=\"width:15rem\"><img alt=\"\" class=\"card-img-top img-fluid\" src=\"https://www.webspell-rm.de/includes/plugins/pic_update/images/175.jpg\" />\r\n<div class=\"card-body\">\r\n<h4>Updater</h4>\r\n\r\n</div>\r\n\r\n<div class=\"card-footer\"><a class=\"btn btn-primary\" href=\"/admin/admincenter.php?site=update\" target=\"_blank\">Find Out More!</a></div>\r\n</div>\r\n</div>\r\n\r\n<div class=\"col mb-4\">\r\n<div class=\"card h-100\" style=\"width:15rem\"><img alt=\"\" class=\"card-img-top img-fluid\" src=\"https://www.webspell-rm.de/includes/plugins/pic_update/images/176.jpg\" />\r\n<div class=\"card-body\">\r\n<h4>Startpage</h4>\r\n\r\n</div>\r\n\r\n<div class=\"card-footer\"><a class=\"btn btn-primary\" href=\"/admin/admincenter.php?site=settings_startpage\" target=\"_blank\">Find Out More!</a></div>\r\n</div>\r\n</div>\r\n\r\n<div class=\"col mb-4\">\r\n<div class=\"card h-100\" style=\"width:15rem\"><img alt=\"\" class=\"card-img-top img-fluid\" src=\"https://www.webspell-rm.de/includes/plugins/pic_update/images/177.jpg\" />\r\n<div class=\"card-body\">\r\n<h4>Webspell-RM</h4>\r\n\r\n</div>\r\n\r\n<div class=\"card-footer\"><a class=\"btn btn-primary\" href=\"https://www.webspell-rm.de/forum.html\" target=\"_blank\">Support</a> <a class=\"btn btn-primary\" href=\"https://www.webspell-rm.de/wiki.html\" target=\"_blank\">WIKI</a></div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n<!-- /.row --></div><!-- /.container -->\r\n\r\n<p>{[it]}</p>\r\n\r\n<p><strong><u>Che cosa &egrave; Webspell RM? </u> </strong><br />\r\n<br />\r\nWebspell RM &egrave; un Clan Gamer CMS (Content Management System). Basato su PHP, MySQL ultima versione di webSPELL.org GitHub (4.3.0). Webspell RM funziona con la General Public License. Vedi anche il contratto di licenza <a href=\"http://wiki.webspell-rm.de/index.php?site=static&amp;staticID=4\" target=\"_blank\"> contratto di licenza </a>.</p>\r\n\r\n<p style=\"text-align:center\"><a class=\"btn btn-info\" href=\"http://demo.webspell-rm.de/\" rel=\"noopener\" role=\"button\" target=\"_blank\"><strong><u>DEMO WEBSPELL RM </u> </strong> </a> <a class=\"btn btn-success\" href=\"https://webspell-rm.de/index. php? site = forum \" rel=\" noopener \" role=\" button \" target=\" _ blank \"> <strong> <u> SUPPORTO RM WEBSPELL </u> </strong> </a></p>\r\n\r\n<p><strong><u>Cosa fa Webspell RM? </u> </strong><br />\r\n<br />\r\nWebspell RM &egrave; basato su bootstrap ed &egrave; facile da personalizzare tramite dashboard. Teoricamente, possono essere utilizzati tutti i modelli di bootstrap. Come editor usiamo CKEditor. Il CMS &egrave; multilingue e viene fornito con molte lingue native. Il popolare reCAPTCHA &egrave; stato integrato come protezione antispam. Tutti i plugin sono facili da installare tramite Webspell RM Installer.</p>\r\n<!-- Page Features -->\r\n\r\n<div class=\"row text-center\">\r\n<div class=\"col mb-4\">\r\n<div class=\"card h-100\" style=\"width:15rem\"><img alt=\"\" class=\"card-img-top img-fluid\" src=\"https://www.webspell-rm.de/includes/plugins/pic_update/images/173.jpg\" />\r\n<div class=\"card-body\">\r\n<h4>Sito Web</h4>\r\n\r\n</div>\r\n\r\n<div class=\"card-footer\"><a class=\"btn btn-primary\" href=\"#\">Find Out More!</a></div>\r\n</div>\r\n</div>\r\n\r\n<div class=\"col mb-4\">\r\n<div class=\"card h-100\" style=\"width:15rem\"><img alt=\"\" class=\"card-img-top\" src=\"https://www.webspell-rm.de//includes/plugins/pic_update/images/170.jpg\" />\r\n<div class=\"card-body\">\r\n<h4>Dashboard</h4>\r\n\r\n</div>\r\n\r\n<div class=\"card-footer\"><a class=\"btn btn-primary\" href=\"/admin/admincenter.php\" target=\"_blank\">Find Out More!</a></div>\r\n</div>\r\n</div>\r\n\r\n<div class=\"col mb-4\">\r\n<div class=\"card h-100\" style=\"width:15rem\"><img alt=\"\" class=\"card-img-top img-fluid\" src=\"https://www.webspell-rm.de/includes/plugins/pic_update/images/171.jpg\" />\r\n<div class=\"card-body\">\r\n<h4>Layout</h4>\r\n\r\n</div>\r\n\r\n<div class=\"card-footer\"><a class=\"btn btn-primary\" href=\"/admin/admincenter.php?site=settings_templates\" target=\"_blank\">Find Out More!</a></div>\r\n</div>\r\n</div>\r\n\r\n<div class=\"col mb-4\">\r\n<div class=\"card h-100\" style=\"width:15rem\"><img alt=\"\" class=\"card-img-top img-fluid\" src=\"https://www.webspell-rm.de/includes/plugins/pic_update/images/172.jpg\" />\r\n<div class=\"card-body\">\r\n<h4>Plugin-Installer</h4>\r\n\r\n</div>\r\n\r\n<div class=\"card-footer\"><a class=\"btn btn-primary\" href=\"/admin/admincenter.php?site=plugin_installer\" target=\"_blank\">Find Out More!</a></div>\r\n</div>\r\n</div>\r\n<!-- zweite Reihe -->\r\n\r\n<div class=\"col mb-4\">\r\n<div class=\"card h-100\" style=\"width:15rem\"><img alt=\"\" class=\"card-img-top img-fluid\" src=\"https://www.webspell-rm.de/includes/plugins/pic_update/images/174.jpg\" />\r\n<div class=\"card-body\">\r\n<h4>Theme-Installer</h4>\r\n\r\n</div>\r\n\r\n<div class=\"card-footer\"><a class=\"btn btn-primary\" href=\"/admin/admincenter.php?site=template_installer\" target=\"_blank\">Find Out More!</a></div>\r\n</div>\r\n</div>\r\n\r\n<div class=\"col mb-4\">\r\n<div class=\"card h-100\" style=\"width:15rem\"><img alt=\"\" class=\"card-img-top img-fluid\" src=\"https://www.webspell-rm.de/includes/plugins/pic_update/images/175.jpg\" />\r\n<div class=\"card-body\">\r\n<h4>Updater</h4>\r\n\r\n</div>\r\n\r\n<div class=\"card-footer\"><a class=\"btn btn-primary\" href=\"/admin/admincenter.php?site=update\" target=\"_blank\">Find Out More!</a></div>\r\n</div>\r\n</div>\r\n\r\n<div class=\"col mb-4\">\r\n<div class=\"card h-100\" style=\"width:15rem\"><img alt=\"\" class=\"card-img-top img-fluid\" src=\"https://www.webspell-rm.de/includes/plugins/pic_update/images/176.jpg\" />\r\n<div class=\"card-body\">\r\n<h4>Startpage</h4>\r\n\r\n</div>\r\n\r\n<div class=\"card-footer\"><a class=\"btn btn-primary\" href=\"/admin/admincenter.php?site=settings_startpage\" target=\"_blank\">Find Out More!</a></div>\r\n</div>\r\n</div>\r\n\r\n<div class=\"col mb-4\">\r\n<div class=\"card h-100\" style=\"width:15rem\"><img alt=\"\" class=\"card-img-top img-fluid\" src=\"https://www.webspell-rm.de/includes/plugins/pic_update/images/177.jpg\" />\r\n<div class=\"card-body\">\r\n<h4>Webspell-RM</h4>\r\n\r\n</div>\r\n\r\n<div class=\"card-footer\"><a class=\"btn btn-primary\" href=\"https://www.webspell-rm.de/forum.html\" target=\"_blank\">Support</a> <a class=\"btn btn-primary\" href=\"https://www.webspell-rm.de/wiki.html\" target=\"_blank\">WIKI</a></div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n<!-- /.row --></div><!-- /.container -->', 1616526018, '1');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `settings_static`
--

CREATE TABLE `settings_static` (
  `staticID` int(11) NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `accesslevel` int(1) NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` int(14) NOT NULL,
  `displayed` int(1) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `settings_static`
--

INSERT INTO `settings_static` (`staticID`, `title`, `accesslevel`, `content`, `date`, `displayed`) VALUES
(1, 'new', 0, '<strong>dbdbdbdb</strong>', 1743938016, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `settings_themes`
--

CREATE TABLE `settings_themes` (
  `themeID` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `modulname` varchar(100) NOT NULL,
  `pfad` varchar(255) DEFAULT '0',
  `version` varchar(11) DEFAULT NULL,
  `active` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Daten für Tabelle `settings_themes`
--

INSERT INTO `settings_themes` (`themeID`, `name`, `modulname`, `pfad`, `version`, `active`) VALUES
(1, 'Default', 'default', 'default', '0.3', 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tags`
--

CREATE TABLE `tags` (
  `rel` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ID` int(11) NOT NULL,
  `tag` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `tags`
--

INSERT INTO `tags` (`rel`, `ID`, `tag`) VALUES
('news', 2, 'Artikel'),
('news', 2, 'Plugin');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE `users` (
  `userID` int(11) NOT NULL,
  `registerdate` int(14) NOT NULL DEFAULT 0,
  `lastlogin` int(14) NOT NULL DEFAULT 0,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_pepper` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_hide` int(1) NOT NULL DEFAULT 1,
  `email_change` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_activate` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `firstname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'select_gender',
  `town` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `birthday` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `facebook` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `twitter` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `twitch` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `steam` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `instagram` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `youtube` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `discord` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `usertext` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `userpic` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `homepage` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `about` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pmgot` int(11) NOT NULL DEFAULT 0,
  `pmsent` int(11) NOT NULL DEFAULT 0,
  `visits` int(11) NOT NULL DEFAULT 0,
  `_banned` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `_ban_reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `topics` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `articles` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `demos` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `files` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `gallery_pictures` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `special_rank` int(11) DEFAULT 0,
  `mailonpm` int(1) NOT NULL DEFAULT 0,
  `userdescription` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `activated` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `language` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_format` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'd.m.Y',
  `time_format` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'H:i',
  `newsletter` int(1) DEFAULT 1,
  `links` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `videos` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `games` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `projectlist` text NOT NULL,
  `profile_visibility` int(1) NOT NULL DEFAULT 1,
  `_is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `_rights` text NOT NULL,
  `country_code` varchar(2) DEFAULT NULL,
  `banned` tinyint(1) DEFAULT 0,
  `ban_reason` text DEFAULT NULL,
  `ban_until` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `users`
--

INSERT INTO `users` (`userID`, `registerdate`, `lastlogin`, `password`, `password_hash`, `password_pepper`, `username`, `email`, `email_hide`, `email_change`, `email_activate`, `firstname`, `lastname`, `gender`, `town`, `birthday`, `facebook`, `twitter`, `twitch`, `steam`, `instagram`, `youtube`, `discord`, `avatar`, `usertext`, `userpic`, `homepage`, `about`, `pmgot`, `pmsent`, `visits`, `_banned`, `_ban_reason`, `ip`, `topics`, `articles`, `demos`, `files`, `gallery_pictures`, `special_rank`, `mailonpm`, `userdescription`, `activated`, `language`, `date_format`, `time_format`, `newsletter`, `links`, `videos`, `games`, `projectlist`, `profile_visibility`, `_is_admin`, `created_at`, `_rights`, `country_code`, `banned`, `ban_reason`, `ban_until`) VALUES
(1, 1742759061, 1744916443, '', '$2y$12$44PYe9Ke7dGaUTFULuPENOuRXIxI4yt.d6Vb58PhGlKjk9uNZXOfS', '$2y$12$d9l7QFQIT0ogyv0J18//XOB8kggmWYxDs0nlrH9uUzGawi2FkwP3O', 'T-Seven&#039;', 'info@webspell-rm.de', 1, '', '', 'T', '', 'male', '', '1980.01.02', '', '', '', '', '', '', '', '', '', '', 'https://www.webspell-rm.de', '', 1, 1, 0, NULL, '', '94.31.74.47', '|', '', '', '', '', 0, 0, '', '1', 'de', 'd.m.Y', 'H:i', 0, '', '', 'a:4:{i:0;s:2:\"ac\";i:1;s:4:\"bf_1\";i:2;s:4:\"bf_4\";i:3;s:4:\"bf_5\";}', '', 1, 1, '2025-04-10 19:51:22', '', NULL, NULL, '', '0000-00-00 00:00:00'),
(3, 1743347695, 0, '868682c4a575f5da080728aa7cb42213917f7b7150beb161e8342d7557f7528037e44139fa7be2043640131f466a422cde17fe96a36449cd920174bf39aab08b', '', '', 'Demo 3', 'admin@example.com', 1, '', '', '', '', 'select_gender', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 2, NULL, '', '', '|1|', '', '', '', '', 0, 0, '', '0', '', 'd.m.Y', 'H:i', 1, '', '', '', '', 1, 0, '2025-04-10 19:51:22', '', NULL, NULL, NULL, NULL),
(15, 1743619781, 1744014495, '', '$2y$12$h0oJ3PEsY7K5QO.Fb1m5fOCfm0gw4xfzA/AluliERwWlqyGIKGoYi', '$2y$12$oUitLjUGLpwuztPMMAia9OZOt9hA6g8kFu1bhd8idy7MPVTDbawiK', 'test neu', 'thomas.heipke@webspell-rm.de', 1, '', '', 'Test', '', 'male', '', '2025-04-01', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, NULL, '', '94.31.74.47', '|', '', '', '', '', 0, 0, '', '1', '', 'd.m.Y', 'H:i', 1, '', '', '', '', 1, 0, '2025-04-10 19:51:22', '', NULL, NULL, NULL, NULL),
(17, 1744054229, 1744922622, '', '$2y$12$qJ/Q0VrdUucJ50GRS8wGUOnn4YhYlQ2M2wS4tRmj4dims8r/nNqWS', '$2y$12$/hGrT.N.a0mmAzUMRMWo1ee6IaqXB/3fDvt/0NCOVVGCuUv90NGd.', 'test Demo', 't-seven@webspell-rm.de', 1, '', '', '', '', 'select_gender', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, NULL, '', '', '|', '', '', '', '', 0, 0, '', '1', 'de', 'd.m.Y', 'H:i', 1, '', '', '', '', 1, 1, '2025-04-10 19:51:22', '', NULL, NULL, NULL, NULL),
(18, 1744489716, 0, '', '$2y$12$sM42/4IRxC82cXXQhokrLe0j1XeufHVr0fZqmM3.1.eU9EDQoNh7u', '', 'Demo 5', 'admin@example.com', 1, '', '', '', '', 'select_gender', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 1, NULL, '', '', '', '', '', '', '', 0, 0, '', '1', '', 'd.m.Y', 'H:i', 1, '', '', '', '', 1, 0, '2025-04-12 20:28:36', '', NULL, NULL, NULL, NULL),
(19, 1744489837, 0, '', '$2y$12$2eY7Ioia4y64.BGSeVR/IeEbyVYjkj2IgKeDgmiZdq9cexzr9F/0q', 'dein_geheimer_pepper_string', 'Demo_2', 'admin@gmail.com', 1, '', '', '', '', 'select_gender', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, NULL, '', '', '', '', '', '', '', 0, 0, '', '1', '', 'd.m.Y', 'H:i', 1, '', '', '', '', 1, 0, '2025-04-12 20:30:37', '', NULL, NULL, NULL, NULL),
(20, 1744493520, 0, '6ad29b10881eee6e07f9b86d92acaae9f174babcfea5010ce6cd1eeedfaf2c303ce0be6f9c30fa6cf664757f4790ff9d5951a39ac6ed989c6d4410a8a9b975a7', '', '', 'Demo', 'guenther@webspell-rm.de', 1, '', '', '', '', 'select_gender', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, NULL, '', '', '|', '', '', '', '', 0, 0, '', '1', '', 'd.m.Y', 'H:i', 1, '', '', '', '', 1, 0, '2025-04-12 21:32:00', '', NULL, NULL, NULL, NULL),
(21, 1744494237, 0, '', '$2y$12$mjscYUPepm19s241xi78NOqj38pdOM9InaE4Cl6Bkhhv8nGg43Smm', '$2y$12$ge5NaONAK/4xuxCC.ssFmu/TkawT22aSr4sKPRGvboRVRvFCZkQbi', 'xxx', 'deyneeraj666@gmail.com', 1, '', '', '', '', 'select_gender', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 5, NULL, '', '', '', '', '', '', '', 0, 0, '', '1', '', 'd.m.Y', 'H:i', 1, '', '', '', '', 1, 0, '2025-04-12 21:43:57', '', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user_forum_groups`
--

CREATE TABLE `user_forum_groups` (
  `usfgID` int(11) NOT NULL,
  `userID` int(11) NOT NULL DEFAULT 0,
  `1` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `user_forum_groups`
--

INSERT INTO `user_forum_groups` (`usfgID`, `userID`, `1`) VALUES
(1, 1, 1),
(2, 6, 0),
(3, 17, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user_roles`
--

CREATE TABLE `user_roles` (
  `roleID` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `user_roles`
--

INSERT INTO `user_roles` (`roleID`, `role_name`, `description`, `is_default`) VALUES
(1, 'Admin', 'Vollzugriff auf alle Funktionen', 0),
(2, 'Co-Admin', 'Unterstützt Admin bei Verwaltungsaufgaben', 0),
(3, 'Leader', 'Clan-Leiter, strategische Entscheidungen', 0),
(4, 'Co-Leader', 'Vertretung des Leaders', 0),
(5, 'Squad-Leader', 'Leitet eine eigene Squad-Gruppe', 0),
(6, 'War-Organisator', 'Organisiert Clanwars und Turniere', 0),
(7, 'Moderator', 'Betreut Forum und Community-Bereiche', 0),
(8, 'Redakteur', 'Schreibt News und verwaltet Inhalte', 0),
(9, 'Member', 'Vollwertiges Clan-Mitglied', 1),
(10, 'Trial-Member', 'Mitglied auf Probe', 0),
(11, 'Gast', 'Öffentlicher Besucher ohne Login', 0),
(12, 'Registrierter Benutzer', 'Angemeldet, aber nicht im Clan', 0),
(13, 'Ehrenmitglied', 'Ehemalige Mitglieder mit besonderem Status', 0),
(14, 'Streamer', 'Darf Stream-Ankündigungen posten', 0),
(15, 'Designer', 'Erstellt oder pflegt Grafiken und Layouts', 0),
(16, 'Techniker', 'Hat Zugriff auf technische Einstellungen', 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user_role_admin_navi_rights`
--

CREATE TABLE `user_role_admin_navi_rights` (
  `id` int(11) NOT NULL,
  `roleID` int(11) NOT NULL,
  `type` enum('link','category') NOT NULL,
  `modulname` varchar(255) NOT NULL,
  `accessID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `user_role_admin_navi_rights`
--

INSERT INTO `user_role_admin_navi_rights` (`id`, `roleID`, `type`, `modulname`, `accessID`) VALUES
(109, 1, 'link', 'edit_role_rights', 67),
(305, 1, 'link', 'ac_overview', 1),
(306, 1, 'link', 'ac_users', 11),
(307, 1, 'link', 'ac_spam_forum', 12),
(308, 1, 'link', 'ac_webside_navigation', 16),
(309, 1, 'link', 'ac_plugin_manager', 24),
(310, 1, 'link', 'footer', 69),
(311, 1, 'link', 'cup', 32),
(312, 1, 'link', 'fightus', 33),
(313, 1, 'link', 'games_pic', 35),
(314, 1, 'link', 'news_manager', 36),
(315, 1, 'link', 'forum', 37),
(316, 1, 'link', 'files', 38),
(317, 1, 'link', 'carousel', 39),
(318, 1, 'link', 'gallery', 40),
(319, 1, 'link', 'shoutbox', 42),
(320, 1, 'link', 'about_us', 43),
(321, 1, 'link', 'sponsors', 44),
(322, 1, 'link', 'servers', 46),
(323, 1, 'link', 'streams', 52),
(324, 1, 'link', 'socialmedia', 53),
(325, 1, 'link', 'userlist', 54),
(326, 1, 'link', 'squads', 56),
(327, 1, 'link', 'clan_rules', 61),
(328, 1, 'link', 'server_rules', 62),
(329, 1, 'link', 'ac_page_statistic', 2),
(330, 1, 'link', 'ac_spam_user', 13),
(331, 1, 'link', 'ac_themes_installer', 17),
(332, 1, 'link', 'ac_plugin_installer', 25),
(334, 1, 'link', 'ac_visitor_statistic', 3),
(335, 1, 'link', 'ac_spam_multi', 14),
(336, 1, 'link', 'ac_themes', 18),
(337, 1, 'link', 'ac_settings', 4),
(338, 1, 'link', 'ac_spam_banned_ips', 15),
(339, 1, 'link', 'ac_dashboard_navigation', 5),
(340, 1, 'link', 'ac_startpage', 20),
(341, 1, 'link', 'ac_user_roles', 64),
(343, 1, 'link', 'ac_email', 6),
(344, 1, 'link', 'ac_static', 21),
(345, 1, 'link', 'ac_contact', 7),
(346, 1, 'link', 'ac_imprint', 22),
(347, 1, 'link', 'ac_modrewrite', 8),
(348, 1, 'link', 'ac_privacy_policy', 23),
(349, 1, 'link', 'ac_database', 9),
(350, 1, 'link', 'ac_update', 10),
(351, 1, 'link', 'ac_editlang', 26),
(352, 1, 'link', 'ac_access_rights', 63),
(353, 1, 'category', 'cat_web_info', 1),
(354, 1, 'category', 'cat_spam', 2),
(355, 1, 'category', 'cat_user', 3),
(356, 1, 'category', 'cat_team', 4),
(357, 1, 'category', 'cat_temp', 5),
(358, 1, 'category', 'cat_pwv', 6),
(359, 1, 'category', 'cat_web_content', 7),
(360, 1, 'category', 'cat_grafik', 8),
(361, 1, 'category', 'cat_header', 9),
(362, 1, 'category', 'cat_game', 10),
(363, 1, 'category', 'cat_social', 11),
(364, 1, 'category', 'cat_links', 12),
(391, 3, 'link', 'ac_modrewrite', 8),
(392, 3, 'link', 'ac_privacy_policy', 23),
(393, 3, 'link', 'ac_database', 9),
(394, 3, 'link', 'ac_update', 10),
(395, 3, 'link', 'ac_editlang', 26),
(396, 3, 'category', 'cat_web_info', 1),
(397, 3, 'link', 'ac_imprint', 22),
(404, 1, 'link', 'ac_edit_role_rights', 67),
(405, 1, 'link', 'ac_user_role_details', 68),
(526, 7, 'link', 'ac_overview', 1),
(527, 7, 'category', 'cat_web_info', 1),
(532, 9, 'link', 'ac_overview', 1),
(533, 9, 'category', 'cat_web_info', 1),
(539, 1, 'link', 'ac_admin_security', 70);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user_role_assignments`
--

CREATE TABLE `user_role_assignments` (
  `adminID` int(11) NOT NULL,
  `roleID` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `assignmentID` int(11) NOT NULL,
  `assigned_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `user_role_assignments`
--

INSERT INTO `user_role_assignments` (`adminID`, `roleID`, `created_at`, `assignmentID`, `assigned_at`) VALUES
(1, 1, '2025-04-11 19:10:00', 15, '2025-04-11 19:10:00'),
(3, 1, '2025-04-12 08:56:50', 37, '2025-04-12 08:56:50'),
(17, 9, '2025-04-14 15:48:28', 50, '2025-04-14 15:48:28');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user_username`
--

CREATE TABLE `user_username` (
  `userID` int(11) NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `user_username`
--

INSERT INTO `user_username` (`userID`, `username`) VALUES
(1, 'T-Seven&#039;'),
(2, 'Demo_2'),
(3, 'Demo 3'),
(4, 'Demo 4'),
(5, 'Demo 5'),
(6, 'test'),
(7, 'neu'),
(8, 'test neu'),
(9, 'test neu'),
(10, 'test neu'),
(11, 'test neu'),
(12, 'test neu'),
(13, 'test neu'),
(14, 'neu'),
(15, 'test neu'),
(16, 'Demo 5'),
(17, 'test Demo'),
(20, 'Demo');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user_visitors`
--

CREATE TABLE `user_visitors` (
  `visitID` int(11) NOT NULL,
  `userID` int(11) NOT NULL DEFAULT 0,
  `visitor` int(11) NOT NULL DEFAULT 0,
  `date` int(14) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `user_visitors`
--

INSERT INTO `user_visitors` (`visitID`, `userID`, `visitor`, `date`) VALUES
(1, 3, 1, 1743348557),
(2, 4, 1, 1743622272),
(3, 21, 1, 1744831457),
(4, 18, 1, 1744831444);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `whoisonline`
--

CREATE TABLE `whoisonline` (
  `time` int(14) NOT NULL DEFAULT 0,
  `ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `userID` int(11) NOT NULL DEFAULT 0,
  `site` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `whoisonline`
--

INSERT INTO `whoisonline` (`time`, `ip`, `userID`, `site`) VALUES
(1744970742, '94.31.74.47', 0, 'login');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `whowasonline`
--

CREATE TABLE `whowasonline` (
  `time` int(14) NOT NULL DEFAULT 0,
  `ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `userID` int(11) NOT NULL DEFAULT 0,
  `site` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `whowasonline`
--

INSERT INTO `whowasonline` (`time`, `ip`, `userID`, `site`) VALUES
(1744903723, '', 17, 'login'),
(1744916443, '', 1, 'imprint');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `_banned_ips`
--

CREATE TABLE `_banned_ips` (
  `banID` int(11) NOT NULL,
  `ip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deltime` int(15) NOT NULL,
  `reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `_failed_login_attempts`
--

CREATE TABLE `_failed_login_attempts` (
  `id` int(11) NOT NULL,
  `ip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `wrong` int(2) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `_role_access_rights`
--

CREATE TABLE `_role_access_rights` (
  `id` int(11) NOT NULL,
  `roleID` int(11) NOT NULL,
  `modulname` varchar(100) NOT NULL,
  `type` enum('link','category') NOT NULL DEFAULT 'link',
  `accessID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `_role_permissions`
--

CREATE TABLE `_role_permissions` (
  `roleID` int(11) NOT NULL,
  `type` enum('link','category') NOT NULL,
  `accessID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `_settings_theme_css`
--

CREATE TABLE `_settings_theme_css` (
  `id` int(11) NOT NULL,
  `setting_name` varchar(50) NOT NULL,
  `setting_value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `_settings_theme_css`
--

INSERT INTO `_settings_theme_css` (`id`, `setting_name`, `setting_value`) VALUES
(1, 'primary_color', '#ff5733'),
(2, 'secondary_color', '#333333'),
(3, 'text_color', '#ffffff'),
(4, 'font_family', 'Arial, sans-serif'),
(5, 'font_size', '26px'),
(6, 'background_color', '#f5f5f5');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `_theme_settings`
--

CREATE TABLE `_theme_settings` (
  `id` int(11) NOT NULL,
  `theme_name` varchar(255) NOT NULL,
  `primary_color` varchar(7) NOT NULL,
  `secondary_color` varchar(7) NOT NULL,
  `background_color` varchar(255) NOT NULL,
  `font_size` varchar(255) NOT NULL,
  `font_family` varchar(100) NOT NULL,
  `text_color` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `_theme_settings`
--

INSERT INTO `_theme_settings` (`id`, `theme_name`, `primary_color`, `secondary_color`, `background_color`, `font_size`, `font_family`, `text_color`, `created_at`) VALUES
(1, 'Default', '#000000', '#000000', '#36dad6', '23', 'Arial, sans-serif', '#555555', '2025-03-28 15:50:17');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `_user_groups`
--

CREATE TABLE `_user_groups` (
  `usgID` int(11) NOT NULL,
  `userID` int(11) NOT NULL DEFAULT 0,
  `news` int(1) NOT NULL DEFAULT 0,
  `news_writer` int(1) NOT NULL,
  `newsletter` int(1) NOT NULL DEFAULT 0,
  `polls` int(1) NOT NULL DEFAULT 0,
  `forum` int(1) NOT NULL DEFAULT 0,
  `moderator` int(1) NOT NULL DEFAULT 0,
  `clanwars` int(1) NOT NULL DEFAULT 0,
  `feedback` int(1) NOT NULL DEFAULT 0,
  `user` int(1) NOT NULL DEFAULT 0,
  `page` int(1) NOT NULL DEFAULT 0,
  `files` int(1) NOT NULL DEFAULT 0,
  `cash` int(1) NOT NULL DEFAULT 0,
  `gallery` int(1) NOT NULL,
  `super` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `_user_groups`
--

INSERT INTO `_user_groups` (`usgID`, `userID`, `news`, `news_writer`, `newsletter`, `polls`, `forum`, `moderator`, `clanwars`, `feedback`, `user`, `page`, `files`, `cash`, `gallery`, `super`) VALUES
(1, 1, 1, 1, 1, 0, 1, 1, 0, 1, 1, 1, 1, 0, 1, 1),
(3, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(4, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(6, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0),
(7, 7, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(8, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(9, 9, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(10, 10, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(11, 11, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(12, 12, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(13, 13, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(14, 14, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(15, 15, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(16, 16, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(17, 17, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 1, 0),
(18, 20, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `_user_sessions`
--

CREATE TABLE `_user_sessions` (
  `session_id` varchar(255) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `last_activity` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `_user_sessions`
--

INSERT INTO `_user_sessions` (`session_id`, `user_id`, `ip_address`, `user_agent`, `last_activity`) VALUES
('265890f2c0840320e8b769d2c32ee45b', 1, '94.31.74.47', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:137.0) Gecko/20100101 Firefox/137.0', '2025-04-15 14:57:47'),
('3c397e1a7035ac026d683c7419649f52', 17, '94.31.74.47', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-15 15:04:00'),
('c61ec131d89ee0f932ee03188372ad51', 1, '94.31.74.47', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:137.0) Gecko/20100101 Firefox/137.0', '2025-04-15 15:02:35');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `backups`
--
ALTER TABLE `backups`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `backup_theme`
--
ALTER TABLE `backup_theme`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `banned_ips`
--
ALTER TABLE `banned_ips`
  ADD PRIMARY KEY (`ip`);

--
-- Indizes für die Tabelle `captcha`
--
ALTER TABLE `captcha`
  ADD PRIMARY KEY (`hash`);

--
-- Indizes für die Tabelle `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`contactID`);

--
-- Indizes für die Tabelle `cookies`
--
ALTER TABLE `cookies`
  ADD PRIMARY KEY (`userID`,`cookie`),
  ADD KEY `expiration` (`expiration`);

--
-- Indizes für die Tabelle `failed_login_attempts`
--
ALTER TABLE `failed_login_attempts`
  ADD PRIMARY KEY (`ip`);

























--
-- Indizes für die Tabelle `modrewrite`
--
ALTER TABLE `modrewrite`
  ADD PRIMARY KEY (`ruleID`);

--
-- Indizes für die Tabelle `navigation_dashboard_categories`
--
ALTER TABLE `navigation_dashboard_categories`
  ADD PRIMARY KEY (`catID`),
  ADD UNIQUE KEY `modulname` (`modulname`,`catID`);

--
-- Indizes für die Tabelle `navigation_dashboard_links`
--
ALTER TABLE `navigation_dashboard_links`
  ADD PRIMARY KEY (`linkID`),
  ADD UNIQUE KEY `modulname` (`modulname`,`linkID`);

--
-- Indizes für die Tabelle `navigation_website_main`
--
ALTER TABLE `navigation_website_main`
  ADD PRIMARY KEY (`mnavID`);

--
-- Indizes für die Tabelle `navigation_website_sub`
--
ALTER TABLE `navigation_website_sub`
  ADD PRIMARY KEY (`snavID`);

--
-- Indizes für die Tabelle `plugins_clan_rules`
--
ALTER TABLE `plugins_clan_rules`
  ADD PRIMARY KEY (`clan_rulesID`);

--
-- Indizes für die Tabelle `plugins_clan_rules_settings_widgets`
--
ALTER TABLE `plugins_clan_rules_settings_widgets`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `plugins_footer`
--
ALTER TABLE `plugins_footer`
  ADD PRIMARY KEY (`footID`);

--
-- Indizes für die Tabelle `plugins_footer_target`
--
ALTER TABLE `plugins_footer_target`
  ADD PRIMARY KEY (`targetID`);

--
-- Indizes für die Tabelle `plugins_startpage_settings_widgets`
--
ALTER TABLE `plugins_startpage_settings_widgets`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`sessionID`);

--
-- Indizes für die Tabelle `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`settingID`);

--
-- Indizes für die Tabelle `settings_buttons`
--
ALTER TABLE `settings_buttons`
  ADD PRIMARY KEY (`buttonID`);

--
-- Indizes für die Tabelle `settings_expansion`
--
ALTER TABLE `settings_expansion`
  ADD PRIMARY KEY (`themeID`);

--
-- Indizes für die Tabelle `settings_imprint`
--
ALTER TABLE `settings_imprint`
  ADD PRIMARY KEY (`imprintID`);

--
-- Indizes für die Tabelle `settings_languages`
--
ALTER TABLE `settings_languages`
  ADD PRIMARY KEY (`langID`);

--
-- Indizes für die Tabelle `settings_plugins`
--
ALTER TABLE `settings_plugins`
  ADD PRIMARY KEY (`pluginID`);

--
-- Indizes für die Tabelle `settings_plugins_widget`
--
ALTER TABLE `settings_plugins_widget`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `settings_plugins_widget_settings`
--
ALTER TABLE `settings_plugins_widget_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `settings_privacy_policy`
--
ALTER TABLE `settings_privacy_policy`
  ADD PRIMARY KEY (`privacy_policyID`);

--
-- Indizes für die Tabelle `settings_social_media`
--
ALTER TABLE `settings_social_media`
  ADD PRIMARY KEY (`socialID`);

--
-- Indizes für die Tabelle `settings_startpage`
--
ALTER TABLE `settings_startpage`
  ADD PRIMARY KEY (`pageID`);

--
-- Indizes für die Tabelle `settings_static`
--
ALTER TABLE `settings_static`
  ADD PRIMARY KEY (`staticID`);

--
-- Indizes für die Tabelle `settings_themes`
--
ALTER TABLE `settings_themes`
  ADD PRIMARY KEY (`themeID`);

--
-- Indizes für die Tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`);

--
-- Indizes für die Tabelle `user_forum_groups`
--
ALTER TABLE `user_forum_groups`
  ADD PRIMARY KEY (`usfgID`);

--
-- Indizes für die Tabelle `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`roleID`);

--
-- Indizes für die Tabelle `user_role_admin_navi_rights`
--
ALTER TABLE `user_role_admin_navi_rights`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_access` (`roleID`,`type`,`modulname`);

--
-- Indizes für die Tabelle `user_role_assignments`
--
ALTER TABLE `user_role_assignments`
  ADD PRIMARY KEY (`assignmentID`),
  ADD KEY `roleID` (`roleID`),
  ADD KEY `user_role_assignments_ibfk_1` (`adminID`);

--
-- Indizes für die Tabelle `user_username`
--
ALTER TABLE `user_username`
  ADD PRIMARY KEY (`userID`);

--
-- Indizes für die Tabelle `user_visitors`
--
ALTER TABLE `user_visitors`
  ADD PRIMARY KEY (`visitID`);

--
-- Indizes für die Tabelle `_banned_ips`
--
ALTER TABLE `_banned_ips`
  ADD PRIMARY KEY (`banID`);

--
-- Indizes für die Tabelle `_failed_login_attempts`
--
ALTER TABLE `_failed_login_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `_role_access_rights`
--
ALTER TABLE `_role_access_rights`
  ADD PRIMARY KEY (`id`),
  ADD KEY `roleID` (`roleID`);

--
-- Indizes für die Tabelle `_role_permissions`
--
ALTER TABLE `_role_permissions`
  ADD PRIMARY KEY (`roleID`,`type`,`accessID`);

--
-- Indizes für die Tabelle `_settings_theme_css`
--
ALTER TABLE `_settings_theme_css`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_name` (`setting_name`);

--
-- Indizes für die Tabelle `_theme_settings`
--
ALTER TABLE `_theme_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `_user_groups`
--
ALTER TABLE `_user_groups`
  ADD PRIMARY KEY (`usgID`);

--
-- Indizes für die Tabelle `_user_sessions`
--
ALTER TABLE `_user_sessions`
  ADD PRIMARY KEY (`session_id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `backups`
--
ALTER TABLE `backups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT für Tabelle `backup_theme`
--
ALTER TABLE `backup_theme`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT für Tabelle `contact`
--
ALTER TABLE `contact`
  MODIFY `contactID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;












--
-- AUTO_INCREMENT für Tabelle `modrewrite`
--
ALTER TABLE `modrewrite`
  MODIFY `ruleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- AUTO_INCREMENT für Tabelle `navigation_dashboard_categories`
--
ALTER TABLE `navigation_dashboard_categories`
  MODIFY `catID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT für Tabelle `navigation_dashboard_links`
--
ALTER TABLE `navigation_dashboard_links`
  MODIFY `linkID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT für Tabelle `navigation_website_main`
--
ALTER TABLE `navigation_website_main`
  MODIFY `mnavID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT für Tabelle `navigation_website_sub`
--
ALTER TABLE `navigation_website_sub`
  MODIFY `snavID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT für Tabelle `plugins_clan_rules`
--
ALTER TABLE `plugins_clan_rules`
  MODIFY `clan_rulesID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT für Tabelle `plugins_footer`
--
ALTER TABLE `plugins_footer`
  MODIFY `footID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `plugins_footer_target`
--
ALTER TABLE `plugins_footer_target`
  MODIFY `targetID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `plugins_startpage_settings_widgets`
--
ALTER TABLE `plugins_startpage_settings_widgets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT für Tabelle `settings`
--
ALTER TABLE `settings`
  MODIFY `settingID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `settings_buttons`
--
ALTER TABLE `settings_buttons`
  MODIFY `buttonID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT für Tabelle `settings_expansion`
--
ALTER TABLE `settings_expansion`
  MODIFY `themeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT für Tabelle `settings_imprint`
--
ALTER TABLE `settings_imprint`
  MODIFY `imprintID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `settings_languages`
--
ALTER TABLE `settings_languages`
  MODIFY `langID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT für Tabelle `settings_plugins`
--
ALTER TABLE `settings_plugins`
  MODIFY `pluginID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT für Tabelle `settings_plugins_widget`
--
ALTER TABLE `settings_plugins_widget`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT für Tabelle `settings_plugins_widget_settings`
--
ALTER TABLE `settings_plugins_widget_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT für Tabelle `settings_privacy_policy`
--
ALTER TABLE `settings_privacy_policy`
  MODIFY `privacy_policyID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `settings_social_media`
--
ALTER TABLE `settings_social_media`
  MODIFY `socialID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `settings_startpage`
--
ALTER TABLE `settings_startpage`
  MODIFY `pageID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `settings_static`
--
ALTER TABLE `settings_static`
  MODIFY `staticID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `settings_themes`
--
ALTER TABLE `settings_themes`
  MODIFY `themeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT für Tabelle `user_forum_groups`
--
ALTER TABLE `user_forum_groups`
  MODIFY `usfgID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT für Tabelle `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `roleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT für Tabelle `user_role_admin_navi_rights`
--
ALTER TABLE `user_role_admin_navi_rights`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=597;

--
-- AUTO_INCREMENT für Tabelle `user_role_assignments`
--
ALTER TABLE `user_role_assignments`
  MODIFY `assignmentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT für Tabelle `user_username`
--
ALTER TABLE `user_username`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT für Tabelle `user_visitors`
--
ALTER TABLE `user_visitors`
  MODIFY `visitID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT für Tabelle `_banned_ips`
--
ALTER TABLE `_banned_ips`
  MODIFY `banID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT für Tabelle `_failed_login_attempts`
--
ALTER TABLE `_failed_login_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT für Tabelle `_role_access_rights`
--
ALTER TABLE `_role_access_rights`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `_settings_theme_css`
--
ALTER TABLE `_settings_theme_css`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT für Tabelle `_theme_settings`
--
ALTER TABLE `_theme_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `_user_groups`
--
ALTER TABLE `_user_groups`
  MODIFY `usgID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `user_role_assignments`
--
ALTER TABLE `user_role_assignments`
  ADD CONSTRAINT `fk_user_role_assignments_admin` FOREIGN KEY (`adminID`) REFERENCES `users` (`userID`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user_role_assignments_role` FOREIGN KEY (`roleID`) REFERENCES `user_roles` (`roleID`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `_role_access_rights`
--
ALTER TABLE `_role_access_rights`
  ADD CONSTRAINT `_role_access_rights_ibfk_1` FOREIGN KEY (`roleID`) REFERENCES `user_roles` (`roleID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

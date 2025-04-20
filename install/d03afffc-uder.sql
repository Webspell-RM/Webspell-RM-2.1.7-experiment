-- phpMyAdmin SQL Dump
-- version 4.9.11
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Erstellungszeit: 19. Apr 2025 um 16:19
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
  `linkID` int(11) NOT NULL,
  `catID` int(11) NOT NULL DEFAULT 0,
  `modulname` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `sort` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `navigation_dashboard_links`
--

INSERT INTO `navigation_dashboard_links` (`linkID`, `catID`, `modulname`, `name`, `url`, `sort`) VALUES
(1, 1, 'ac_overview', '{[de]}Webserver-Info{[en]}Webserver-Info{[it]}Informazioni Sul Sito', 'admincenter.php?site=overview', 1),
(2, 1, 'ac_page_statistic', '{[de]}Seiten Statistiken{[en]}Page Statistics{[it]}Pagina delle Statistiche', 'admincenter.php?site=page_statistic', 2),
(3, 1, 'ac_visitor_statistic', '{[de]}Besucher Statistiken{[en]}Visitor Statistics{[it]}Statistiche Visitatori', 'admincenter.php?site=visitor_statistic', 3),
(4, 1, 'ac_settings', '{[de]}Allgemeine Einstellungen{[en]}General Settings{[it]}Impostazioni Generali', 'admincenter.php?site=settings', 4),
(5, 1, 'ac_dashboard_navigation', '{[de]}Admincenter Navigation{[en]}Admincenter Navigation{[it]}Menu Navigazione Admin', 'admincenter.php?site=dashboard_navigation', 5),
(6, 1, 'ac_email', '{[de]}E-Mail{[en]}E-Mail{[it]}E-Mail', 'admincenter.php?site=email', 6),
(7, 1, 'ac_contact', '{[de]}Kontakte{[en]}Contacts{[it]}Contatti', 'admincenter.php?site=contact', 7),
(8, 1, 'ac_modrewrite', '{[de]}Mod-Rewrite{[en]}Mod-Rewrite{[it]}Mod-Rewrite', 'admincenter.php?site=modrewrite', 8),
(9, 1, 'ac_database', '{[de]}Datenbank{[en]}Database{[it]}Database', 'admincenter.php?site=database', 9),
(10, 1, 'ac_update', '{[de]}Webspell-RM Update{[en]}Webspell-RM Update{[it]}Aggiornamento Webspell-RM', 'admincenter.php?site=update', 10),
(11, 3, 'ac_users', '{[de]}Registrierte Benutzer{[en]}Registered Users{[it]}Utenti Registrati', 'admincenter.php?site=users', 1),
(12, 2, 'ac_spam_forum', '{[de]}Geblockte Inhalte{[en]}Blocked Content{[it]}Contenuti Bloccati', 'admincenter.php?site=spam&amp;action=forum_spam', 1),
(13, 2, 'ac_spam_user', '{[de]}Nutzer l&ouml;schen{[en]}Remove User{[it]}Banna Utente', 'admincenter.php?site=spam&amp;action=user', 2),
(14, 2, 'ac_spam_multi', '{[de]}Multi-Accounts{[en]}Multi-Accounts{[it]}Multi-Account', 'admincenter.php?site=spam&amp;action=multi', 3),
(15, 2, 'ac_spam_banned_ips', '{[de]}gebannte IPs{[en]}banned IPs{[it]}IP bannati', 'admincenter.php?site=banned_ips', 4),
(16, 5, 'ac_webside_navigation', '{[de]}Webseiten Navigation{[en]}Webside Navigation{[it]}Menu Navigazione Web', 'admincenter.php?site=webside_navigation', 1),
(17, 5, 'ac_themes_installer', '{[de]}Themes Installer{[en]}Themes Installer{[it]}Installazione Themes', 'admincenter.php?site=themes_installer', 2),
(18, 5, 'ac_themes', '{[de]}Themes - Style{[en]}Themes - Style{[it]}Themes Grafici', 'admincenter.php?site=settings_themes', 3),
(20, 5, 'ac_startpage', '{[de]}Startseite{[en]}Start Page{[it]}Pagina Principale', 'admincenter.php?site=settings_startpage', 5),
(21, 5, 'ac_static', '{[de]}Statische Seiten{[en]}Static Pages{[it]}Pagine Statiche', 'admincenter.php?site=settings_static', 6),
(22, 5, 'ac_imprint', '{[de]}Impressum{[en]}Imprint{[it]}Impronta Editoriale', 'admincenter.php?site=settings_imprint', 7),
(23, 5, 'ac_privacy_policy', '{[de]}Datenschutz-Bestimmungen{[en]}Privacy Policy{[it]}Informativa sulla Privacy', 'admincenter.php?site=settings_privacy_policy', 8),
(24, 6, 'ac_plugin_manager', '{[de]}Plugin & Widget Manager{[en]}Plugin & Widget Manager{[it]}Gestore di plugin e widget', 'admincenter.php?site=plugin_manager', 1),
(25, 6, 'ac_plugin_installer', '{[de]}Plugin Installer{[en]}Plugin Installer{[it]}Installazione Plugin', 'admincenter.php?site=plugin_installer', 2),
(26, 1, 'ac_editlang', '{[de]}Spracheditor{[en]}Language Editor{[it]}Editor di Linguaggi', 'admincenter.php?site=editlang', 11),
(69, 7, 'footer', '{[de]}Footer{[en]}Footer{[it]}Piè di pagina', 'admincenter.php?site=fotter', 0),
(70, 3, 'ac_admin_security', 'admin_security', 'admincenter.php?site=admin_security', 0);

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
(1, 1742759061, 1745072216, '', '$2y$12$44PYe9Ke7dGaUTFULuPENOuRXIxI4yt.d6Vb58PhGlKjk9uNZXOfS', '$2y$12$d9l7QFQIT0ogyv0J18//XOB8kggmWYxDs0nlrH9uUzGawi2FkwP3O', 'T-Seven&#039;', 'info@webspell-rm.de', 1, '', '', 'T', '', 'male', '', '1980.01.02', '', '', '', '', '', '', '', '', '', '', 'https://www.webspell-rm.de', '', 1, 1, 0, NULL, '', '94.31.74.47', '|', '', '', '', '', 0, 0, '', '1', 'de', 'd.m.Y', 'H:i', 0, '', '', 'a:4:{i:0;s:2:\"ac\";i:1;s:4:\"bf_1\";i:2;s:4:\"bf_4\";i:3;s:4:\"bf_5\";}', '', 1, 1, '2025-04-10 19:51:22', '', NULL, NULL, '', '0000-00-00 00:00:00'),
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
  `roleID` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`roleID`)
) ENGINE=InnoDB;


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
) ENGINE=InnoDB;

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

--
-- Indizes der exportierten Tabellen
--

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
  ADD KEY `user_role_assignments` (`adminID`) USING BTREE;

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
-- AUTO_INCREMENT für exportierte Tabellen
--

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
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `user_role_assignments`
--
ALTER TABLE `user_role_assignments`
  ADD CONSTRAINT `fk_user_role_assignments_admin` FOREIGN KEY (`adminID`) REFERENCES `users` (`userID`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user_role_assignments_role` FOREIGN KEY (`roleID`) REFERENCES `user_roles` (`roleID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

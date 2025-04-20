-- Webspell-RM 2.1.7 - Datenbankbasis

-- Tabellen für Benutzer und Admin-Zuweisungen
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `userID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `registerdate` int(14) NOT NULL DEFAULT 0,
  `lastlogin` int(14) NOT NULL DEFAULT 0,
  `password` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `password_pepper` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`registerdate`, `lastlogin`, `password`, `password_pepper`, `username`, `email`) 
VALUES
(UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), '{{adminpass}}', '', '{{adminuser}}', '{{adminmail}}');

-- Ende der Tabelle 'users'

CREATE TABLE IF NOT EXISTS `contact` (
  `contactID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(100) NOT NULL,
  `email` varchar(200) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `contact` (`name`, `email`, `sort`) VALUES
('Administrator', '{{adminmail}}', 1);


-- Ende der Tabelle 'contact'

-- Tabelle für Benutzerrollen
CREATE TABLE `user_roles` (
  `roleID` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`roleID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


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


-- Ende der Tabelle 'user_roles'

-- Tabelle für Benutzerrollen-Zuweisungen
CREATE TABLE `user_role_assignments` (
  `assignmentID` int(11) NOT NULL AUTO_INCREMENT,
  `adminID` int(11) NOT NULL,
  `roleID` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `assigned_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`assignmentID`),
  KEY `roleID` (`roleID`),
  KEY `user_role_assignments` (`adminID`) USING BTREE,
  CONSTRAINT `user_role_assignments_admin` FOREIGN KEY (`adminID`) REFERENCES `users` (`userID`) ON DELETE CASCADE,
  CONSTRAINT `user_role_assignments_role` FOREIGN KEY (`roleID`) REFERENCES `user_roles` (`roleID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



INSERT INTO `user_role_assignments` (`adminID`, `roleID`, `created_at`, `assigned_at`) 
VALUES (1, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);
-- Ende der Tabelle 'user_role_assignments'


-- Daten für Tabelle `user_role_admin_navi_rights`

CREATE TABLE `user_role_admin_navi_rights` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `roleID` int(11) NOT NULL,
  `type` enum('link','category') NOT NULL,
  `modulname` varchar(255) NOT NULL,
  `accessID` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_access` (`roleID`,`type`,`modulname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;




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
-- Ende der Tabelle 'user_role_admin_navi_rights'

-- Tabellenstruktur für Tabelle `navigation_dashboard_categories`
CREATE TABLE `navigation_dashboard_categories` (
  `catID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `modulname` varchar(255) NOT NULL,
  `fa_name` varchar(255) NOT NULL DEFAULT '',
  `sort` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`catID`),
  UNIQUE KEY `modulname` (`modulname`, `catID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


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

-- Ende der Tabelle 'navigation_dashboard_categories'

-- Tabellenstruktur für Tabelle `navigation_dashboard_links`
CREATE TABLE `navigation_dashboard_links` (
  `linkID` int(11) NOT NULL AUTO_INCREMENT,
  `catID` int(11) NOT NULL DEFAULT 0,
  `modulname` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `sort` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`linkID`),
  UNIQUE KEY `modulname` (`modulname`, `linkID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



INSERT INTO `navigation_dashboard_links` (`linkID`, `catID`, `modulname`, `name`, `url`, `sort`) 
VALUES
(1, 1, 'ac_overview', 'Webserver-Info', 'admincenter.php?site=overview', 1),
(2, 1, 'ac_page_statistic', 'Seiten Statistiken', 'admincenter.php?site=page_statistic', 2),
(3, 1, 'ac_visitor_statistic', 'Besucher Statistiken', 'admincenter.php?site=visitor_statistic', 3),
(4, 1, 'ac_settings', 'Allgemeine Einstellungen', 'admincenter.php?site=settings', 4),
(5, 1, 'ac_dashboard_navigation', 'Admincenter Navigation', 'admincenter.php?site=dashboard_navigation', 5),
(6, 1, 'ac_email', 'E-Mail', 'admincenter.php?site=email', 6),
(7, 1, 'ac_contact', 'Kontakte', 'admincenter.php?site=contact', 7),
(8, 1, 'ac_modrewrite', 'Mod-Rewrite', 'admincenter.php?site=modrewrite', 8),
(9, 1, 'ac_database', 'Datenbank', 'admincenter.php?site=database', 9),
(10, 1, 'ac_update', 'Webspell-RM Update', 'admincenter.php?site=update', 10),
(11, 3, 'ac_users', 'Registrierte Benutzer', 'admincenter.php?site=users', 1),
(12, 2, 'ac_spam_forum', 'Geblockte Inhalte', 'admincenter.php?site=spam&action=forum_spam', 1),
(13, 2, 'ac_spam_user', 'Nutzer löschen', 'admincenter.php?site=spam&action=user', 2),
(14, 2, 'ac_spam_multi', 'Multi-Accounts', 'admincenter.php?site=spam&action=multi', 3),
(15, 2, 'ac_spam_banned_ips', 'gebannte IPs', 'admincenter.php?site=banned_ips', 4),
(16, 5, 'ac_webside_navigation', 'Webseiten Navigation', 'admincenter.php?site=webside_navigation', 1),
(17, 5, 'ac_themes_installer', 'Themes Installer', 'admincenter.php?site=themes_installer', 2),
(18, 5, 'ac_themes', 'Themes - Style', 'admincenter.php?site=settings_themes', 3),
(20, 5, 'ac_startpage', 'Startseite', 'admincenter.php?site=settings_startpage', 5),
(21, 5, 'ac_static', 'Statische Seiten', 'admincenter.php?site=settings_static', 6),
(22, 5, 'ac_imprint', 'Impressum', 'admincenter.php?site=settings_imprint', 7),
(23, 5, 'ac_privacy_policy', 'Datenschutz-Bestimmungen', 'admincenter.php?site=settings_privacy_policy', 8),
(24, 6, 'ac_plugin_manager', 'Plugin & Widget Manager', 'admincenter.php?site=plugin_manager', 1),
(25, 6, 'ac_plugin_installer', 'Plugin Installer', 'admincenter.php?site=plugin_installer', 2),
(26, 1, 'ac_editlang', 'Spracheditor', 'admincenter.php?site=editlang', 11),
(69, 7, 'footer', 'Footer', 'admincenter.php?site=fotter', 0),
(70, 3, 'ac_admin_security', 'admin_security', 'admincenter.php?site=admin_security', 0);

-- Ende der Tabelle 'navigation_dashboard_links'


-- Tabellenstruktur für Tabelle `navigation_website_main`
CREATE TABLE `navigation_website_main` (
  `mnavID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `default` int(11) NOT NULL DEFAULT 1,
  `sort` int(2) NOT NULL DEFAULT 0,
  `isdropdown` int(1) NOT NULL DEFAULT 1,
  `windows` int(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`mnavID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


INSERT INTO `navigation_website_main` (`mnavID`, `name`, `url`, `default`, `sort`, `isdropdown`, `windows`) VALUES
(1, '{[de]}HAUPT{[en]}MAIN{[it]}PRINCIPALE', '#', 1, 1, 1, 1),
(2, '{[de]}TEAM{[en]}TEAM{[it]}TEAM', '#', 1, 2, 1, 1),
(3, '{[de]}GEMEINSCHAFT{[en]}COMMUNITY{[it]}COMMUNITY', '#', 1, 3, 1, 1),
(4, '{[de]}MEDIEN{[en]}MEDIA{[it]}MEDIA', '#', 1, 4, 1, 1),
(5, '{[de]}SOCIAL{[en]}SOCIAL{[it]}SOCIAL', '#', 1, 5, 1, 1),
(6, '{[de]}SONSTIGES{[en]}MISCELLANEOUS{[it]}VARIE', '#', 1, 6, 1, 1);
-- Ende der Tabelle 'navigation_website_main'


-- Tabellenstruktur für Tabelle `navigation_website_sub`
CREATE TABLE `navigation_website_sub` (
  `snavID` int(11) NOT NULL AUTO_INCREMENT,
  `mnavID` int(11) NOT NULL DEFAULT 0,
  `name` varchar(255) NOT NULL DEFAULT '',
  `modulname` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `sort` int(2) NOT NULL DEFAULT 0,
  `indropdown` int(1) NOT NULL DEFAULT 1,
  `themes_modulname` varchar(255) DEFAULT 'default',
  PRIMARY KEY (`snavID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `navigation_website_sub` (`snavID`, `mnavID`, `name`, `modulname`, `url`, `sort`, `indropdown`, `themes_modulname`) VALUES
(1, 6, '{[de]}Kontakt{[en]}Contact{[it]}Contatti', 'contact', 'index.php?site=contact', 1, 1, 'default'),
(2, 6, '{[de]}Datenschutz-Bestimmungen{[en]}Privacy Policy{[it]}Informativa sulla Privacy', 'privacy_policy', 'index.php?site=privacy_policy', 2, 1, 'default'),
(3, 6, '{[de]}Impressum{[en]}Imprint{[it]}Impronta Editoriale', 'imprint', 'index.php?site=imprint', 3, 1, 'default'),
(59, 3, '{[de]}Server Regeln{[en]}Server Rules{[it]}Regole Server', 'server_rules', 'index.php?site=server_rules', 1, 1, 'default');
-- Ende der Tabelle 'navigation_website_sub'

-- Tabellenstruktur für Tabelle `user_username`
CREATE TABLE `user_username` (
  `userID` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `user_username` (`userID`, `username`) VALUES
(1, '{{adminuser}}');
-- Ende der Tabelle 'user_username'

-- Tabellenstruktur für Tabelle `user_visitors`
CREATE TABLE IF NOT EXISTS `user_visitors` (
  `visitID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `userID` int(11) NOT NULL DEFAULT '0',
  `visitor` int(11) NOT NULL DEFAULT '0',
  `date` int(14) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- Ende der Tabelle 'user_visitors'

-- Tabellenstruktur für Tabelle `whoisonline`
CREATE TABLE IF NOT EXISTS `whoisonline` (
  `time` int(14) NOT NULL DEFAULT '0',
  `ip` varchar(20) NOT NULL,
  `userID` int(11) NOT NULL DEFAULT '0',
  `site` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- Ende der Tabelle 'whoisonline'

-- Tabellenstruktur für Tabelle `whowasonline`
CREATE TABLE IF NOT EXISTS `whowasonline` (
  `time` int(14) NOT NULL DEFAULT '0',
  `ip` varchar(20) NOT NULL,
  `userID` int(11) NOT NULL DEFAULT '0',
  `site` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- Ende der Tabelle 'whowasonline'

-- Tabellenstruktur für Tabelle `settings`
CREATE TABLE IF NOT EXISTS `settings` (
  `settingID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` varchar(255) NOT NULL,
  `hpurl` varchar(255) NOT NULL,
  `clanname` varchar(255) NOT NULL,
  `clantag` varchar(255) NOT NULL,
  `adminname` varchar(255) NOT NULL,
  `adminemail` varchar(255) NOT NULL,
  `sball` int(11) NOT NULL DEFAULT '0',
  `topics` int(11) NOT NULL DEFAULT '0',
  `posts` int(11) NOT NULL DEFAULT '0',
  `latesttopics` int(11) NOT NULL,
  `latesttopicchars` int(11) NOT NULL DEFAULT '0',
  `messages` int(11) NOT NULL DEFAULT '0',
  `register_per_ip` int(1) NOT NULL DEFAULT 1,
  `sessionduration` int(3) NOT NULL,
  `closed` int(1) NOT NULL DEFAULT '0',
  `imprint` int(1) NOT NULL DEFAULT '0',
  `default_language` varchar(2) NOT NULL DEFAULT 'en',
  `insertlinks` int(1) NOT NULL DEFAULT '1',
  `search_min_len` int(3) NOT NULL DEFAULT '3',
  `max_wrong_pw` int(2) NOT NULL DEFAULT '10',
  `captcha_math` int(1) NOT NULL DEFAULT '2',
  `captcha_bgcol` varchar(7) NOT NULL DEFAULT '#FFFFFF',
  `captcha_fontcol` varchar(7) NOT NULL DEFAULT '#000000',
  `captcha_type` int(1) NOT NULL DEFAULT '2',
  `captcha_noise` int(3) NOT NULL DEFAULT '100',
  `captcha_linenoise` int(2) NOT NULL DEFAULT '10',
  `bancheck` int(13) NOT NULL,
  `spam_check` int(1) NOT NULL DEFAULT '0',
  `detect_language` int(1) NOT NULL DEFAULT '0',
  `spammaxposts` int(11) NOT NULL DEFAULT '0',
  `spamapiblockerror` int(1) NOT NULL DEFAULT '0',
  `date_format` varchar(255) NOT NULL DEFAULT 'd.m.Y',
  `time_format` varchar(255) NOT NULL DEFAULT 'H:i',
  `modRewrite` int(1) NOT NULL DEFAULT '0',
  `startpage` varchar(255) NOT NULL,
  `forum_double` int(1) NOT NULL DEFAULT '1',
  `profilelast` int(11) NOT NULL DEFAULT '10',
  `de_lang` int(1) DEFAULT '1',
  `en_lang` int(1) DEFAULT '1',
  `it_lang` int(1) DEFAULT '1',
  `birthday` int(11) NOT NULL DEFAULT '0',
  `keywords` text NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `settings` (`settingID`, `title`, `hpurl`, `clanname`, `clantag`, `adminname`, `adminemail`, `sball`, `topics`, `posts`, `latesttopics`, `latesttopicchars`, `messages`, `register_per_ip`, `sessionduration`, `closed`, `imprint`, `default_language`, `insertlinks`, `search_min_len`, `max_wrong_pw`, `captcha_math`, `captcha_bgcol`, `captcha_fontcol`, `captcha_type`, `captcha_noise`, `captcha_linenoise`, `bancheck`, `spam_check`, `detect_language`, `spammaxposts`, `spamapiblockerror`, `date_format`, `time_format`, `modRewrite`, `startpage`, `forum_double`, `profilelast`, `de_lang`, `en_lang`, `it_lang`, `birthday`, `keywords`, `description`) VALUES
(1, 'Webspell-RM', '{{adminweburl}}', 'Clan Name', 'MyClan', '{{adminuser}}', '{{adminmail}}', 30, 20, 10, 10, 18, 20, 1, 0, 0, 1, 'de', 1, 3, 10, 2, '#FFFFFF', '#000000', 2, 100, 10, 1564938159, 0, 0, 0, 0, 'd.m.Y', 'H:i', 0, 'startpage', 1, 10, 1, 1, 1, 0, 'Clandesign, Webspell, Webspell-RM, Wespellanpassungen, Webdesign, Tutorials, Downloads, Webspell-rm, rm, addon, plugin, Templates Webspell Addons, plungin, mods, Webspellanpassungen, Modifikationen und Anpassungen und mehr!', 'Kostenlose Homepage erstellen mit Webspell-RM CMS: Einfach, schnell & kostenlos! In wenigen Minuten mit der eigenen Website online gehen.');
-- Ende der Tabelle 'settings'

-- Tabellenstruktur für Tabelle `settings_expansion`
CREATE TABLE IF NOT EXISTS `settings_expansion` (
  `themeID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(255) NOT NULL,
  `modulname` varchar(100) NOT NULL,
  `pfad` varchar(255) NOT NULL,
  `version` varchar(11) NOT NULL,
  `active` int(11) DEFAULT NULL,
  `express_active` int(11) NOT NULL DEFAULT '0',
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
  `sort` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `settings_expansion` (`themeID`, `name`, `modulname`, `pfad`, `version`, `active`, `express_active`, `nav1`, `nav2`, `nav3`, `nav4`, `nav5`, `nav6`, `nav7`, `nav8`, `nav9`, `nav10`, `nav11`, `nav12`, `nav_text_alignment`, `body1`, `body2`, `body3`, `body4`, `body5`, `background_pic`, `border_radius`, `typo1`, `typo2`, `typo3`, `typo4`, `typo5`, `typo6`, `typo7`, `typo8`, `card1`, `card2`, `foot1`, `foot2`, `foot3`, `foot4`, `foot5`, `foot6`, `calendar1`, `calendar2`, `carousel1`, `carousel2`, `carousel3`, `carousel4`, `logo_pic`, `logotext1`, `logotext2`, `reg_pic`, `reg1`, `reg2`, `headlines`, `sort`) VALUES
(1, 'Default', 'default', 'default', '0.3', 1, 0, 'rgb(51,51,51)', '16px', 'rgb(221,221,221)', 'rgb(254,130,29)', 'rgb(254,130,29)', '2px', 'rgb(221,221,221)', 'rgb(196,89,1)', '#1bdf1b', 'rgb(51,51,51)', 'rgb(221,221,221)', 'rgb(101,100,100)', 'ms-auto', 'Roboto', '13px', 'rgb(255,255,255)', 'rgb(85,85,85)', 'rgb(236,236,236)', '', '0px', '', '', '', 'rgb(254,130,29)', '', '', '', 'rgb(196,89,1)', 'rgb(255,255,255)', 'rgb(221,221,221)', 'rgb(85,85,85)', 'rgb(255,255,255)', 'rgb(255,255,255)', 'rgb(181,179,179)', 'rgb(254,130,29)', 'rgb(255,255,255)', '', '', 'rgb(255,255,255)', 'rgb(254,130,29)', 'rgb(255,255,255)', 'rgb(254,130,29)', 'default_logo.png', '', '', 'default_login_bg.jpg', 'rgb(254,130,29)', 'rgb(255,255,255)', 'headlines_01.css', 1);
-- Ende der Tabelle 'settings_expansion'

-- Tabellenstruktur für Tabelle `settings_themes`
CREATE TABLE IF NOT EXISTS `settings_themes` (
  `themeID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(255) NOT NULL,
  `modulname` varchar(100) NOT NULL,
  `pfad` varchar(255) DEFAULT '0',
  `version` varchar(11) DEFAULT NULL,
  `active` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


INSERT IGNORE INTO `settings_themes` (`themeID`, `name`, `modulname`, `pfad`, `version`, `active`) VALUES
(1, 'Default', 'default', 'default', '0.3', 1);
-- Ende der Tabelle 'settings_themes'

-- Tabellenstruktur für Tabelle `backups`
CREATE TABLE IF NOT EXISTS `backups` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `filename` text NOT NULL,
  `description` text,
  `createdby` int(11) NOT NULL DEFAULT '0',
  `createdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- Ende der Tabelle 'backups'

-- Tabellenstruktur für Tabelle `backups`
CREATE TABLE IF NOT EXISTS `backups` (
  `banID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `ip` varchar(255) NOT NULL,
  `deltime` int(15) NOT NULL,
  `reason` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- Ende der Tabelle 'backups'

-- Tabellenstruktur für Tabelle `captcha`
CREATE TABLE IF NOT EXISTS `captcha` (
  `hash` VARCHAR(255) NOT NULL,
  `captcha` INT(11) NOT NULL DEFAULT '0',
  `deltime` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- Ende der Tabelle 'captcha'

-- Tabellenstruktur für Tabelle `cookies`
CREATE TABLE IF NOT EXISTS `cookies` (
  `userID` int(11) NOT NULL,
  `cookie` binary(64) NOT NULL,
  `expiration` int(14) NOT NULL,
  PRIMARY KEY (`userID`,`cookie`),
  KEY `expiration` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- Ende der Tabelle 'cookies'

-- Tabellenstruktur für Tabelle `counter`
CREATE TABLE IF NOT EXISTS `counter` (
  `hits` int(20) NOT NULL DEFAULT '0',
  `online` int(14) NOT NULL DEFAULT '0',
  `maxonline` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `counter` (`hits`, `online`, `maxonline`) 
VALUES (1, UNIX_TIMESTAMP(), 1);
-- Ende der Tabelle 'counter'

-- Tabellenstruktur für Tabelle `counter_iplist`
CREATE TABLE IF NOT EXISTS `counter_iplist` (
  `date` DATE NOT NULL,
  `ip` VARCHAR(45) NOT NULL,
  `deleted` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`date`, `ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- Ende der Tabelle 'counter_iplist'

-- Tabellenstruktur für Tabelle `counter_stats`
CREATE TABLE IF NOT EXISTS `counter_stats` (
  `date` DATE NOT NULL,
  `count` INT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- Ende der Tabelle 'counter_stats'

-- Tabellenstruktur für Tabelle `email`  
CREATE TABLE IF NOT EXISTS `email` (
  `emailID` int(1) NOT NULL,
  `user` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `host` varchar(255) NOT NULL,
  `port` int(5) NOT NULL,
  `debug` int(1) NOT NULL,
  `auth` int(1) NOT NULL,
  `html` int(1) NOT NULL,
  `smtp` int(1) NOT NULL,
  `secure` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `email` (`emailID`, `user`, `password`, `host`, `port`, `debug`, `auth`, `html`, `smtp`, `secure`) 
VALUES (1, '', '', '', 25, 0, 0, 1, 0, 0);
-- Ende der Tabelle 'email'

-- Tabellenstruktur für Tabelle `failed_login_attempts`  
CREATE TABLE IF NOT EXISTS `failed_login_attempts` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `ip` varchar(255) NOT NULL,
  `wrong` int(2) default '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- Ende der Tabelle 'failed_login_attempts'

-- Tabellenstruktur für Tabelle `lock`  
CREATE TABLE IF NOT EXISTS `lock` (
  `time` int(11) NOT NULL,
  `reason` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- Ende der Tabelle 'lock'



-- Tabellenstruktur für Tabelle `plugins_footer_target`
CREATE TABLE IF NOT EXISTS `plugins_footer_target` (
  `targetID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `windows1` int(1) NOT NULL DEFAULT '1',
  `windows2` int(1) NOT NULL DEFAULT '1',
  `windows3` int(1) NOT NULL DEFAULT '1',
  `windows4` int(1) NOT NULL DEFAULT '1',
  `windows5` int(1) NOT NULL DEFAULT '1',
  `windows6` int(1) NOT NULL DEFAULT '1',
  `windows7` int(1) NOT NULL DEFAULT '1',
  `windows8` int(1) NOT NULL DEFAULT '1',
  `windows9` int(1) NOT NULL DEFAULT '1',
  `windows10` int(1) NOT NULL DEFAULT '1',
  `windows11` int(1) NOT NULL DEFAULT '1',
  `windows12` int(1) NOT NULL DEFAULT '1',
  `windows13` int(1) NOT NULL DEFAULT '1',
  `windows14` int(1) NOT NULL DEFAULT '1',
  `windows15` int(1) NOT NULL DEFAULT '1',
  `windows16` int(1) NOT NULL DEFAULT '1',
  `windows17` int(1) NOT NULL DEFAULT '1',
  `windows18` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `plugins_footer_target` (`targetID`, `windows1`, `windows2`, `windows3`, `windows4`, `windows5`, `windows6`, `windows7`, `windows8`, `windows9`, `windows10`, `windows11`, `windows12`, `windows13`, `windows14`, `windows15`, `windows16`, `windows17`, `windows18`) VALUES
(1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);
-- Ende der Tabelle 'plugins_footer_target'

-- Tabellenstruktur für Tabelle `plugins_startpage_settings_widgets`
CREATE TABLE IF NOT EXISTS `plugins_startpage_settings_widgets` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `position` varchar(255) NOT NULL,
  `modulname` varchar(255) NOT NULL,
  `themes_modulname` varchar(255) NOT NULL,
  `widgetname` varchar(255) NOT NULL,
  `widgetdatei` varchar(255) NOT NULL,
  `activated` int(1) DEFAULT '1',
  `sort` int(11) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `plugins_startpage_settings_widgets` (`id`, `position`, `modulname`, `themes_modulname`, `widgetname`, `widgetdatei`, `activated`, `sort`) VALUES
(1, 'navigation_widget', 'navigation', 'default', 'Navigation', 'widget_navigation', 1, 1),
(2, 'footer_widget', 'footer', 'default', 'Footer Easy', 'widget_footer_easy', 1, 2);
-- Ende der Tabelle 'plugins_startpage_settings_widgets'

-- Tabellenstruktur für Tabelle `settings_buttons`
CREATE TABLE IF NOT EXISTS `settings_buttons` (
  `buttonID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(255) NOT NULL,
  `modulname` varchar(255) NOT NULL,
  `active` int(11) DEFAULT NULL,
  `version` varchar(11) NOT NULL,
  `button1` varchar(255) NOT NULL,
  `button2` varchar(255) NOT NULL,
  `button3` varchar(255) NOT NULL,
  `button4` varchar(255) NOT NULL,
  `button5` varchar(255) NOT NULL,
  `button6` varchar(255) NOT NULL,
  `button7` varchar(255) NOT NULL,
  `button8` varchar(255) NOT NULL,
  `button9` varchar(255) NOT NULL,
  `button10` varchar(255) NOT NULL,
  `button11` varchar(255) NOT NULL,
  `button12` varchar(255) NOT NULL,
  `button13` varchar(255) NOT NULL,
  `button14` varchar(255) NOT NULL,
  `button15` varchar(255) NOT NULL,
  `button16` varchar(255) NOT NULL,
  `button17` varchar(255) NOT NULL,
  `button18` varchar(255) NOT NULL,
  `button19` varchar(255) NOT NULL,
  `button20` varchar(255) NOT NULL,
  `button21` varchar(255) NOT NULL,
  `button22` varchar(255) NOT NULL,
  `button23` varchar(255) NOT NULL,
  `button24` varchar(255) NOT NULL,
  `button25` varchar(255) NOT NULL,
  `button26` varchar(255) NOT NULL,
  `button27` varchar(255) NOT NULL,
  `button28` varchar(255) NOT NULL,
  `button29` varchar(255) NOT NULL,
  `button30` varchar(255) NOT NULL,
  `button31` varchar(255) NOT NULL,
  `button32` varchar(255) NOT NULL,
  `button33` varchar(255) NOT NULL,
  `button34` varchar(255) NOT NULL,
  `button35` varchar(255) NOT NULL,
  `button36` varchar(255) NOT NULL,
  `button37` varchar(255) NOT NULL,
  `button38` varchar(255) NOT NULL,
  `button39` varchar(255) NOT NULL,
  `button40` varchar(255) NOT NULL,
  `button41` varchar(255) NOT NULL,
  `button42` varchar(255) NOT NULL,
  `btn_border_radius` varchar(255) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `settings_buttons` (`buttonID`, `name`, `modulname`, `active`, `version`, `button1`, `button2`, `button3`, `button4`, `button5`, `button6`, `button7`, `button8`, `button9`, `button10`, `button11`, `button12`, `button13`, `button14`, `button15`, `button16`, `button17`, `button18`, `button19`, `button20`, `button21`, `button22`, `button23`, `button24`, `button25`, `button26`, `button27`, `button28`, `button29`, `button30`, `button31`, `button32`, `button33`, `button34`, `button35`, `button36`, `button37`, `button38`, `button39`, `button40`, `button41`, `button42`, `btn_border_radius`) VALUES
(1, 'Default', 'default', 0, '0.3', 'rgb(254,130,29)', 'rgb(196,89,1)', 'rgb(255,255,255)', 'rgb(254,130,29)', 'rgb(196,89,1)', 'rgb(108,117,125)', 'rgb(90,98,104)', 'rgb(255,255,255)', 'rgb(108,117,125)', 'rgb(84,91,98)', 'rgb(40,167,69)', 'rgb(33,136,56)', 'rgb(255,255,255)', 'rgb(40,167,69)', 'rgb(30,126,52)', 'rgb(220,53,69)', 'rgb(200,35,51)', 'rgb(255,255,255)', 'rgb(220,53,69)', 'rgb(189,33,48)', 'rgb(255,193,7)', 'rgb(224,168,0)', 'rgb(33,37,41)', 'rgb(255,193,7)', 'rgb(211,158,0)', 'rgb(23,162,184)', 'rgb(19,132,150)', 'rgb(255,255,255)', 'rgb(23,162,184)', 'rgb(17,122,139)', 'rgb(248,249,250)', 'rgb(226,230,234)', 'rgb(33,37,41)', 'rgb(248,249,250)', 'rgb(218,224,229)', 'rgb(52,58,64)', 'rgb(35,39,43)', 'rgb(255,255,255)', 'rgb(52,58,64)', 'rgb(29,33,36)', 'rgb(254,130,29)', 'rgb(196,89,1)', '0px');
-- Ende der Tabelle 'settings_buttons'

-- Tabellenstruktur für Tabelle `settings_imprint`
CREATE TABLE IF NOT EXISTS `settings_imprint` (
  `imprintID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `imprint` text NOT NULL,
  `disclaimer_text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `settings_imprint` (`imprintID`, `imprint`, `disclaimer_text`) VALUES
(1, 'Impressum in deutscher Sprache.<br /><span style="color:#c0392b"><strong>Konfigurieren Sie bitte Ihr Impressum!</strong></span><br />', 'Haftungsausschluss in deutscher Sprache.<br /><span style="color:#c0392b"><strong>Konfigurieren Sie bitte Ihr Haftungsausschluss! </strong></span><br />');
-- Ende der Tabelle 'settings_imprint'

-- Tabellenstruktur für Tabelle `settings_privacy_policy`
CREATE TABLE IF NOT EXISTS `settings_privacy_policy` (
  `privacy_policyID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `date` int(14) NOT NULL,
  `privacy_policy_text` mediumtext NOT NULL
) ENGINE=InnoDB;

INSERT INTO `settings_privacy_policy` (`privacy_policyID`, `date`, `privacy_policy_text`) VALUES
(1, 1576689811, 'Datenschutz-Bestimmungen in deutscher Sprache.<br /><span style=\"color:#c0392b\"><strong>Konfigurieren Sie bitte Ihre Datenschutz-Bestimmungen!</strong></span><br />');
-- Ende der Tabelle 'settings_privacy_policy'

-- Tabellenstruktur für Tabelle `settings_plugins`
CREATE TABLE IF NOT EXISTS `settings_plugins` (
  `pluginID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(255) NOT NULL,
  `modulname` varchar(100) NOT NULL,
  `info` text NOT NULL,
  `admin_file` text NOT NULL,
  `activate` int(1) NOT NULL DEFAULT 1,
  `author` varchar(200) NOT NULL,
  `website` varchar(200) NOT NULL,
  `index_link` varchar(255) NOT NULL,
  `hiddenfiles` varchar(255) NOT NULL,
  `version` varchar(10) NOT NULL,
  `path` varchar(255) NOT NULL,
  `status_display` int(1) NOT NULL DEFAULT 1,
  `plugin_display` int(11) NOT NULL DEFAULT 1,
  `widget_display` int(11) NOT NULL DEFAULT 1,
  `delete_display` int(1) NOT NULL DEFAULT 1,
  `sidebar` varchar(255) NOT NULL DEFAULT 'deactivated'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `settings_plugins` 
(`pluginID`, `name`, `modulname`, `info`, `admin_file`, `activate`, `author`, `website`, `index_link`, `hiddenfiles`, `version`, `path`, `status_display`, `plugin_display`, `widget_display`, `delete_display`, `sidebar`) 
VALUES
(1, 'Startpage', 'startpage', 'Kein Plugin. Bestandteil vom System!!!', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 0, 'full_activated'),
(2, 'Privacy Policy', 'privacy_policy', 'Kein Plugin. Bestandteil vom System!!!', NULL, 1, NULL, NULL, 'privacy_policy', NULL, NULL, NULL, 0, 0, 1, 0, 'deactivated'),
(3, 'Imprint', 'imprint', 'Kein Plugin. Bestandteil vom System!!!', NULL, 1, NULL, NULL, 'imprint', NULL, NULL, NULL, 0, 0, 1, 0, 'deactivated'),
(4, 'Static', 'static', 'Kein Plugin. Bestandteil vom System!!!', NULL, 1, NULL, NULL, 'static', NULL, NULL, NULL, 1, 0, 1, 0, 'deactivated'),
(5, 'Error_404', 'error_404', 'Kein Plugin. Bestandteil vom System!!!', NULL, 1, NULL, NULL, 'error_404', NULL, NULL, NULL, 1, 0, 1, 0, 'deactivated'),
(6, 'Profile', 'profile', 'Kein Plugin. Bestandteil vom System!!!', NULL, 1, NULL, NULL, 'profile', NULL, NULL, NULL, 1, 0, 1, 0, 'deactivated'),
(7, 'Login', 'login', 'Kein Plugin. Bestandteil vom System!!!', NULL, 1, NULL, NULL, 'login', NULL, NULL, NULL, 1, 0, 1, 0, 'deactivated'),
(8, 'Lost Password', 'lostpassword', 'Kein Plugin. Bestandteil vom System!!!', NULL, 1, NULL, NULL, 'lostpassword', NULL, NULL, NULL, 1, 0, 1, 0, 'deactivated'),
(9, 'Contact', 'contact', 'Kein Plugin. Bestandteil vom System!!!', NULL, 1, NULL, NULL, 'contact', NULL, NULL, NULL, 1, 0, 1, 0, 'deactivated'),
(10, 'Register', 'register', 'Kein Plugin. Bestandteil vom System!!!', NULL, 1, NULL, NULL, 'register', NULL, NULL, NULL, 1, 0, 1, 0, 'deactivated'),
(11, 'My Profile', 'myprofile', 'Kein Plugin. Bestandteil vom System!!!', NULL, 1, NULL, NULL, 'myprofile', NULL, NULL, NULL, 1, 0, 1, 0, 'deactivated'),
(12, 'Report', 'report', 'Kein Plugin. Bestandteil vom System!!!', NULL, 1, NULL, NULL, 'report', NULL, NULL, NULL, 1, 0, 1, 0, 'deactivated'),
(13, 'Navigation', 'navigation', 'Mit diesem Plugin könnt ihr euch die Navigation anzeigen lassen.', NULL, 1, 'T-Seven', 'https://webspell-rm.de', NULL, NULL, '0.3', 'includes/plugins/navigation/', 1, 1, 0, 0, 'deactivated'),
(14, 'Footer', 'footer', 'Mit diesem Plugin könnt ihr einen neuen Footer anzeigen lassen.', 'admin_footer', 1, 'T-Seven', 'https://webspell-rm.de', NULL, NULL, '0.1', 'includes/plugins/footer/', 1, 1, 0, 0, 'deactivated');

-- Tabellenstruktur für Tabelle `settings_plugins_widget`
CREATE TABLE IF NOT EXISTS `settings_plugins_widget` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `modulname` varchar(100) NOT NULL,
  `widgetname` varchar(255) NOT NULL,
  `widgetdatei` varchar(255) NOT NULL,
  `area` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `settings_plugins_widget` (`id`, `modulname`, `widgetname`, `widgetdatei`, `area`) VALUES
(1, 'navigation', 'Navigation', 'widget_navigation', 2),
(2, 'footer', 'Footer Easy', 'widget_footer_easy', 6),
(3, 'footer', 'Footer Default', 'widget_footer_default', 6),
(4, 'footer', 'Footer Plugin', 'widget_footer_plugin', 6),
(5, 'footer', 'Footer Box', 'widget_footer_box', 6);
-- Ende der Tabelle 'settings_plugins_widget'

-- Tabellenstruktur für Tabelle `settings_plugins_widget_settings`
CREATE TABLE IF NOT EXISTS `settings_plugins_widget_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `side` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `modulname` varchar(100) NOT NULL,
  `themes_modulname` varchar(255) NOT NULL,
  `widgetname` varchar(255) NOT NULL,
  `widgetdatei` varchar(255) NOT NULL,
  `activated` int(1) DEFAULT '1',
  `sort` int(11) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `settings_plugins_widget_settings` (`id`, `side`, `position`, `modulname`, `themes_modulname`, `widgetname`, `widgetdatei`, `activated`, `sort`) VALUES
(1, '', 'navigation_widget', 'navigation', 'default', 'Navigation', 'widget_navigation', 1, 0),
(2, '', 'footer_widget', 'footer', 'default', 'Footer Easy', 'widget_footer_easy', 1, 0);
-- Ende der Tabelle 'settings_plugins_widget_settings'

-- Tabellenstruktur für Tabelle `banned_ips`
CREATE TABLE IF NOT EXISTS `banned_ips` (
  `banID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `ip` varchar(255) NOT NULL,
  `deltime` int(15) NOT NULL,
  `reason` varchar(255) DEFAULT NULL
) ENGINE=InnoDB;
-- Ende der Tabelle 'banned_ips'

-- Tabellenstruktur für Tabelle `settings_startpage`
CREATE TABLE IF NOT EXISTS `settings_startpage` (
  `pageID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` varchar(255) NOT NULL,
  `startpage_text` longtext NOT NULL,
  `date` int(14) NOT NULL,
  `displayed` varchar(255) DEFAULT '0'
) ENGINE=InnoDB;

INSERT INTO `settings_startpage` (`pageID`, `title`, `startpage_text`, `date`, `displayed`) VALUES (1, '{[de]}Willkommen zu Webspell | RM{[en]}Welcome to Webspell | RM{[it]}Benvenuti su Webspell | RM', '<!-- Page Content -->\r\n<div class=\"container\"><!-- Jumbotron Header -->\r\n<h1>Webspell RM!</h1>\r\n\r\n<p>{[de]}</p>\r\n\r\n<p><strong><u>Was ist Webspell RM?</u></strong><br />\r\n<br />\r\nWebspell RM ist ein Clan &amp; Gamer CMS (<em>Content Management System</em>). Es basiert auf PHP, MySQL und der letzten webSPELL.org GitHub Version (4.3.0). Webspell RM l&auml;uft unter der General Public License. Siehe auch <a href=\"http://wiki.webspell-rm.de/index.php?site=static&amp;staticID=4\" target=\"_blank\">Lizenzvereinbarung</a>.</p>\r\n\r\n<p style=\"text-align:center\"><a class=\"btn btn-info\" href=\"http://demo.webspell-rm.de/\" rel=\"noopener\" role=\"button\" target=\"_blank\"><strong><u>WEBSPELL RM DEMO</u></strong></a> <a class=\"btn btn-success\" href=\"https://webspell-rm.de/index.php?site=forum\" rel=\"noopener\" role=\"button\" target=\"_blank\"><strong><u>WEBSPELL RM SUPPORT</u></strong></a></p>\r\n\r\n<p><strong><u>Was bietet Webspell | RM?</u></strong><br />\r\n<br />\r\nWebspell RM basiert auf Bootstrap und ist einfach anzupassen via Dashboard. Theoretisch sind alle Bootstrap Templates verwendbar. Als Editor wir der CKEditor verwendet. Das CMS ist Multi-Language f&auml;hig und liefert von Haus aus viele Sprachen mit. Das beliebte reCAPTCHA wurde als Spam Schutz integriert. Alle Plugins sind via Webspell RM Installer einfach und problemlos zu installieren.</p>\r\n<!-- Page Features -->\r\n\r\n<div class=\"row text-center\">\r\n<div class=\"col mb-4\">\r\n<div class=\"card h-100\" style=\"width:15rem\"><img alt=\"\" class=\"card-img-top img-fluid\" src=\"https://www.webspell-rm.de/includes/plugins/pic_update/images/173.jpg\" />\r\n<div class=\"card-body\">\r\n<h4>Webside</h4>\r\n\r\n</div>\r\n\r\n<div class=\"card-footer\"><a class=\"btn btn-primary\" href=\"#\">Find Out More!</a></div>\r\n</div>\r\n</div>\r\n\r\n<div class=\"col mb-4\">\r\n<div class=\"card h-100\" style=\"width:15rem\"><img alt=\"\" class=\"card-img-top\" src=\"https://www.webspell-rm.de//includes/plugins/pic_update/images/170.jpg\" />\r\n<div class=\"card-body\">\r\n<h4>Dashboard</h4>\r\n\r\n</div>\r\n\r\n<div class=\"card-footer\"><a class=\"btn btn-primary\" href=\"/admin/admincenter.php\" target=\"_blank\">Find Out More!</a></div>\r\n</div>\r\n</div>\r\n\r\n<div class=\"col mb-4\">\r\n<div class=\"card h-100\" style=\"width:15rem\"><img alt=\"\" class=\"card-img-top img-fluid\" src=\"https://www.webspell-rm.de/includes/plugins/pic_update/images/171.jpg\" />\r\n<div class=\"card-body\">\r\n<h4>Layout</h4>\r\n\r\n</div>\r\n\r\n<div class=\"card-footer\"><a class=\"btn btn-primary\" href=\"/admin/admincenter.php?site=settings_templates\" target=\"_blank\">Find Out More!</a></div>\r\n</div>\r\n</div>\r\n\r\n<div class=\"col mb-4\">\r\n<div class=\"card h-100\" style=\"width:15rem\"><img alt=\"\" class=\"card-img-top img-fluid\" src=\"https://www.webspell-rm.de/includes/plugins/pic_update/images/172.jpg\" />\r\n<div class=\"card-body\">\r\n<h4>Plugin-Installer</h4>\r\n\r\n</div>\r\n\r\n<div class=\"card-footer\"><a class=\"btn btn-primary\" href=\"/admin/admincenter.php?site=plugin_installer\" target=\"_blank\">Find Out More!</a></div>\r\n</div>\r\n</div>\r\n<!-- zweite Reihe -->\r\n\r\n<div class=\"col mb-4\">\r\n<div class=\"card h-100\" style=\"width:15rem\"><img alt=\"\" class=\"card-img-top img-fluid\" src=\"https://www.webspell-rm.de/includes/plugins/pic_update/images/174.jpg\" />\r\n<div class=\"card-body\">\r\n<h4>Theme-Installer</h4>\r\n\r\n</div>\r\n\r\n<div class=\"card-footer\"><a class=\"btn btn-primary\" href=\"/admin/admincenter.php?site=template_installer\" target=\"_blank\">Find Out More!</a></div>\r\n</div>\r\n</div>\r\n\r\n<div class=\"col mb-4\">\r\n<div class=\"card h-100\" style=\"width:15rem\"><img alt=\"\" class=\"card-img-top img-fluid\" src=\"https://www.webspell-rm.de/includes/plugins/pic_update/images/175.jpg\" />\r\n<div class=\"card-body\">\r\n<h4>Updater</h4>\r\n\r\n</div>\r\n\r\n<div class=\"card-footer\"><a class=\"btn btn-primary\" href=\"/admin/admincenter.php?site=update\" target=\"_blank\">Find Out More!</a></div>\r\n</div>\r\n</div>\r\n\r\n<div class=\"col mb-4\">\r\n<div class=\"card h-100\" style=\"width:15rem\"><img alt=\"\" class=\"card-img-top img-fluid\" src=\"https://www.webspell-rm.de/includes/plugins/pic_update/images/176.jpg\" />\r\n<div class=\"card-body\">\r\n<h4>Startpage</h4>\r\n\r\n</div>\r\n\r\n<div class=\"card-footer\"><a class=\"btn btn-primary\" href=\"/admin/admincenter.php?site=settings_startpage\" target=\"_blank\">Find Out More!</a></div>\r\n</div>\r\n</div>\r\n\r\n<div class=\"col mb-4\">\r\n<div class=\"card h-100\" style=\"width:15rem\"><img alt=\"\" class=\"card-img-top img-fluid\" src=\"https://www.webspell-rm.de/includes/plugins/pic_update/images/177.jpg\" />\r\n<div class=\"card-body\">\r\n<h4>Webspell-RM</h4>\r\n\r\n</div>\r\n\r\n<div class=\"card-footer\"><a class=\"btn btn-primary\" href=\"https://www.webspell-rm.de/forum.html\" target=\"_blank\">Support</a> <a class=\"btn btn-primary\" href=\"https://www.webspell-rm.de/wiki.html\" target=\"_blank\">WIKI</a></div>\r\n</div>\r\n</div>\r\n</div>\r\n<!-- /.row --></div>\r\n<!-- /.container -->\r\n\r\n<p>{[en]}</p>\r\n\r\n<p><strong><u>What is Webspell RM?</u></strong><br />\r\n<br />\r\nWebspell RM is a Clan &amp; Gamer CMS (Content Management System). It is based on PHP, MySQL and the latest webSPELL.org GitHub version (4.3.0). Webspell RM runs under the General Public License. See also license agreement <a href=\"http://wiki.webspell-rm.de/index.php?site=static&amp;staticID=4\" target=\"_blank\">license agreement</a>.</p>\r\n\r\n<p style=\"text-align:center\"><a class=\"btn btn-info\" href=\"http://demo.webspell-rm.de/\" rel=\"noopener\" role=\"button\" target=\"_blank\"><strong><u>WEBSPELL RM DEMO</u></strong></a> <a class=\"btn btn-success\" href=\"https://webspell-rm.de/index.php?site=forum\" rel=\"noopener\" role=\"button\" target=\"_blank\"><strong><u>WEBSPELL RM SUPPORT</u></strong></a></p>\r\n\r\n<p><strong><u>What does Webspell | RM offer?</u></strong><br />\r\n<br />\r\nWebspell RM is based on bootstrap and it is easy to customize via dashboard. Theoretically, all bootstrap templates can be used. As editor we use the CKEditor. The CMS is multi-language capable and comes with many native languages. The popular reCAPTCHA was integrated as spam protection. All plugins are easy to install via Webspell RM Installer.</p>\r\n<!-- Page Features -->\r\n\r\n<div class=\"row text-center\">\r\n<div class=\"col mb-4\">\r\n<div class=\"card h-100\" style=\"width:15rem\"><img alt=\"\" class=\"card-img-top img-fluid\" src=\"https://www.webspell-rm.de/includes/plugins/pic_update/images/173.jpg\" />\r\n<div class=\"card-body\">\r\n<h4>Webside</h4>\r\n\r\n</div>\r\n\r\n<div class=\"card-footer\"><a class=\"btn btn-primary\" href=\"#\">Find Out More!</a></div>\r\n</div>\r\n</div>\r\n\r\n<div class=\"col mb-4\">\r\n<div class=\"card h-100\" style=\"width:15rem\"><img alt=\"\" class=\"card-img-top\" src=\"https://www.webspell-rm.de//includes/plugins/pic_update/images/170.jpg\" />\r\n<div class=\"card-body\">\r\n<h4>Dashboard</h4>\r\n\r\n</div>\r\n\r\n<div class=\"card-footer\"><a class=\"btn btn-primary\" href=\"/admin/admincenter.php\" target=\"_blank\">Find Out More!</a></div>\r\n</div>\r\n</div>\r\n\r\n<div class=\"col mb-4\">\r\n<div class=\"card h-100\" style=\"width:15rem\"><img alt=\"\" class=\"card-img-top img-fluid\" src=\"https://www.webspell-rm.de/includes/plugins/pic_update/images/171.jpg\" />\r\n<div class=\"card-body\">\r\n<h4>Layout</h4>\r\n\r\n</div>\r\n\r\n<div class=\"card-footer\"><a class=\"btn btn-primary\" href=\"/admin/admincenter.php?site=settings_templates\" target=\"_blank\">Find Out More!</a></div>\r\n</div>\r\n</div>\r\n\r\n<div class=\"col mb-4\">\r\n<div class=\"card h-100\" style=\"width:15rem\"><img alt=\"\" class=\"card-img-top img-fluid\" src=\"https://www.webspell-rm.de/includes/plugins/pic_update/images/172.jpg\" />\r\n<div class=\"card-body\">\r\n<h4>Plugin-Installer</h4>\r\n\r\n</div>\r\n\r\n<div class=\"card-footer\"><a class=\"btn btn-primary\" href=\"/admin/admincenter.php?site=plugin_installer\" target=\"_blank\">Find Out More!</a></div>\r\n</div>\r\n</div>\r\n<!-- zweite Reihe -->\r\n\r\n<div class=\"col mb-4\">\r\n<div class=\"card h-100\" style=\"width:15rem\"><img alt=\"\" class=\"card-img-top img-fluid\" src=\"https://www.webspell-rm.de/includes/plugins/pic_update/images/174.jpg\" />\r\n<div class=\"card-body\">\r\n<h4>Theme-Installer</h4>\r\n\r\n</div>\r\n\r\n<div class=\"card-footer\"><a class=\"btn btn-primary\" href=\"/admin/admincenter.php?site=template_installer\" target=\"_blank\">Find Out More!</a></div>\r\n</div>\r\n</div>\r\n\r\n<div class=\"col mb-4\">\r\n<div class=\"card h-100\" style=\"width:15rem\"><img alt=\"\" class=\"card-img-top img-fluid\" src=\"https://www.webspell-rm.de/includes/plugins/pic_update/images/175.jpg\" />\r\n<div class=\"card-body\">\r\n<h4>Updater</h4>\r\n\r\n</div>\r\n\r\n<div class=\"card-footer\"><a class=\"btn btn-primary\" href=\"/admin/admincenter.php?site=update\" target=\"_blank\">Find Out More!</a></div>\r\n</div>\r\n</div>\r\n\r\n<div class=\"col mb-4\">\r\n<div class=\"card h-100\" style=\"width:15rem\"><img alt=\"\" class=\"card-img-top img-fluid\" src=\"https://www.webspell-rm.de/includes/plugins/pic_update/images/176.jpg\" />\r\n<div class=\"card-body\">\r\n<h4>Startpage</h4>\r\n\r\n</div>\r\n\r\n<div class=\"card-footer\"><a class=\"btn btn-primary\" href=\"/admin/admincenter.php?site=settings_startpage\" target=\"_blank\">Find Out More!</a></div>\r\n</div>\r\n</div>\r\n\r\n<div class=\"col mb-4\">\r\n<div class=\"card h-100\" style=\"width:15rem\"><img alt=\"\" class=\"card-img-top img-fluid\" src=\"https://www.webspell-rm.de/includes/plugins/pic_update/images/177.jpg\" />\r\n<div class=\"card-body\">\r\n<h4>Webspell-RM</h4>\r\n\r\n</div>\r\n\r\n<div class=\"card-footer\"><a class=\"btn btn-primary\" href=\"https://www.webspell-rm.de/forum.html\" target=\"_blank\">Support</a> <a class=\"btn btn-primary\" href=\"https://www.webspell-rm.de/wiki.html\" target=\"_blank\">WIKI</a></div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n<!-- /.row --></div><!-- /.container -->\r\n\r\n<p>{[it]}</p>\r\n\r\n<p><strong><u>Che cosa &egrave; Webspell RM? </u> </strong><br />\r\n<br />\r\nWebspell RM &egrave; un Clan Gamer CMS (Content Management System). Basato su PHP, MySQL ultima versione di webSPELL.org GitHub (4.3.0). Webspell RM funziona con la General Public License. Vedi anche il contratto di licenza <a href=\"http://wiki.webspell-rm.de/index.php?site=static&amp;staticID=4\" target=\"_blank\"> contratto di licenza </a>.</p>\r\n\r\n<p style=\"text-align:center\"><a class=\"btn btn-info\" href=\"http://demo.webspell-rm.de/\" rel=\"noopener\" role=\"button\" target=\"_blank\"><strong><u>DEMO WEBSPELL RM </u> </strong> </a> <a class=\"btn btn-success\" href=\"https://webspell-rm.de/index. php? site = forum \" rel=\" noopener \" role=\" button \" target=\" _ blank \"> <strong> <u> SUPPORTO RM WEBSPELL </u> </strong> </a></p>\r\n\r\n<p><strong><u>Cosa fa Webspell RM? </u> </strong><br />\r\n<br />\r\nWebspell RM &egrave; basato su bootstrap ed &egrave; facile da personalizzare tramite dashboard. Teoricamente, possono essere utilizzati tutti i modelli di bootstrap. Come editor usiamo CKEditor. Il CMS &egrave; multilingue e viene fornito con molte lingue native. Il popolare reCAPTCHA &egrave; stato integrato come protezione antispam. Tutti i plugin sono facili da installare tramite Webspell RM Installer.</p>\r\n<!-- Page Features -->\r\n\r\n<div class=\"row text-center\">\r\n<div class=\"col mb-4\">\r\n<div class=\"card h-100\" style=\"width:15rem\"><img alt=\"\" class=\"card-img-top img-fluid\" src=\"https://www.webspell-rm.de/includes/plugins/pic_update/images/173.jpg\" />\r\n<div class=\"card-body\">\r\n<h4>Sito Web</h4>\r\n\r\n</div>\r\n\r\n<div class=\"card-footer\"><a class=\"btn btn-primary\" href=\"#\">Find Out More!</a></div>\r\n</div>\r\n</div>\r\n\r\n<div class=\"col mb-4\">\r\n<div class=\"card h-100\" style=\"width:15rem\"><img alt=\"\" class=\"card-img-top\" src=\"https://www.webspell-rm.de//includes/plugins/pic_update/images/170.jpg\" />\r\n<div class=\"card-body\">\r\n<h4>Dashboard</h4>\r\n\r\n</div>\r\n\r\n<div class=\"card-footer\"><a class=\"btn btn-primary\" href=\"/admin/admincenter.php\" target=\"_blank\">Find Out More!</a></div>\r\n</div>\r\n</div>\r\n\r\n<div class=\"col mb-4\">\r\n<div class=\"card h-100\" style=\"width:15rem\"><img alt=\"\" class=\"card-img-top img-fluid\" src=\"https://www.webspell-rm.de/includes/plugins/pic_update/images/171.jpg\" />\r\n<div class=\"card-body\">\r\n<h4>Layout</h4>\r\n\r\n</div>\r\n\r\n<div class=\"card-footer\"><a class=\"btn btn-primary\" href=\"/admin/admincenter.php?site=settings_templates\" target=\"_blank\">Find Out More!</a></div>\r\n</div>\r\n</div>\r\n\r\n<div class=\"col mb-4\">\r\n<div class=\"card h-100\" style=\"width:15rem\"><img alt=\"\" class=\"card-img-top img-fluid\" src=\"https://www.webspell-rm.de/includes/plugins/pic_update/images/172.jpg\" />\r\n<div class=\"card-body\">\r\n<h4>Plugin-Installer</h4>\r\n\r\n</div>\r\n\r\n<div class=\"card-footer\"><a class=\"btn btn-primary\" href=\"/admin/admincenter.php?site=plugin_installer\" target=\"_blank\">Find Out More!</a></div>\r\n</div>\r\n</div>\r\n<!-- zweite Reihe -->\r\n\r\n<div class=\"col mb-4\">\r\n<div class=\"card h-100\" style=\"width:15rem\"><img alt=\"\" class=\"card-img-top img-fluid\" src=\"https://www.webspell-rm.de/includes/plugins/pic_update/images/174.jpg\" />\r\n<div class=\"card-body\">\r\n<h4>Theme-Installer</h4>\r\n\r\n</div>\r\n\r\n<div class=\"card-footer\"><a class=\"btn btn-primary\" href=\"/admin/admincenter.php?site=template_installer\" target=\"_blank\">Find Out More!</a></div>\r\n</div>\r\n</div>\r\n\r\n<div class=\"col mb-4\">\r\n<div class=\"card h-100\" style=\"width:15rem\"><img alt=\"\" class=\"card-img-top img-fluid\" src=\"https://www.webspell-rm.de/includes/plugins/pic_update/images/175.jpg\" />\r\n<div class=\"card-body\">\r\n<h4>Updater</h4>\r\n\r\n</div>\r\n\r\n<div class=\"card-footer\"><a class=\"btn btn-primary\" href=\"/admin/admincenter.php?site=update\" target=\"_blank\">Find Out More!</a></div>\r\n</div>\r\n</div>\r\n\r\n<div class=\"col mb-4\">\r\n<div class=\"card h-100\" style=\"width:15rem\"><img alt=\"\" class=\"card-img-top img-fluid\" src=\"https://www.webspell-rm.de/includes/plugins/pic_update/images/176.jpg\" />\r\n<div class=\"card-body\">\r\n<h4>Startpage</h4>\r\n\r\n</div>\r\n\r\n<div class=\"card-footer\"><a class=\"btn btn-primary\" href=\"/admin/admincenter.php?site=settings_startpage\" target=\"_blank\">Find Out More!</a></div>\r\n</div>\r\n</div>\r\n\r\n<div class=\"col mb-4\">\r\n<div class=\"card h-100\" style=\"width:15rem\"><img alt=\"\" class=\"card-img-top img-fluid\" src=\"https://www.webspell-rm.de/includes/plugins/pic_update/images/177.jpg\" />\r\n<div class=\"card-body\">\r\n<h4>Webspell-RM</h4>\r\n\r\n</div>\r\n\r\n<div class=\"card-footer\"><a class=\"btn btn-primary\" href=\"https://www.webspell-rm.de/forum.html\" target=\"_blank\">Support</a> <a class=\"btn btn-primary\" href=\"https://www.webspell-rm.de/wiki.html\" target=\"_blank\">WIKI</a></div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n<!-- /.row --></div><!-- /.container -->', 1616526018, '1');
-- Ende der Tabelle 'settings_startpage'

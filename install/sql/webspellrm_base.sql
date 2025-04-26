-- Webspell-RM 2.1.7 - Datenbankbasis



-- Tabellenstruktur für Tabelle `logs`
CREATE TABLE IF NOT EXISTS `logs` (
    `logID` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `log_type` VARCHAR(255),
    `log_message` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
-- Ende der Tabelle 'logs'

-- Tabellenstruktur für Tabelle `backups`
CREATE TABLE IF NOT EXISTS `backups` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `filename` text NOT NULL,
  `description` text,
  `createdby` int(11) NOT NULL DEFAULT '0',
  `createdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
-- Ende der Tabelle 'backups'

-- Tabellenstruktur für Tabelle `captcha`
CREATE TABLE IF NOT EXISTS `captcha` (
  `hash` VARCHAR(255) NOT NULL,
  `captcha` INT(11) NOT NULL DEFAULT '0',
  `deltime` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`hash`)
) ENGINE=InnoDB;
-- Ende der Tabelle 'captcha'

-- Tabellenstruktur für Tabelle `contact`
CREATE TABLE IF NOT EXISTS `contact` (
  `contactID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(100) NOT NULL,
  `email` varchar(200) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB;

INSERT INTO `contact` (`name`, `email`, `sort`) VALUES
('Administrator', '{{adminmail}}', 1);
-- Ende der Tabelle 'contact'

-- Tabellenstruktur für Tabelle `counter`
CREATE TABLE IF NOT EXISTS `counter` (
  `hits` int(20) NOT NULL DEFAULT '0',
  `online` int(14) NOT NULL DEFAULT '0',
  `maxonline` int(11) NOT NULL
) ENGINE=InnoDB;

INSERT INTO `counter` (`hits`, `online`, `maxonline`) 
VALUES (1, UNIX_TIMESTAMP(), 1);
-- Ende der Tabelle 'counter'

-- Tabellenstruktur für Tabelle `counter_iplist`
CREATE TABLE IF NOT EXISTS `counter_iplist` (
  `date` DATE NOT NULL,
  `ip` VARCHAR(45) NOT NULL,
  `deleted` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`date`, `ip`)
) ENGINE=InnoDB;
-- Ende der Tabelle 'counter_iplist'

-- Tabellenstruktur für Tabelle `counter_stats`
CREATE TABLE IF NOT EXISTS `counter_stats` (
  `date` DATE NOT NULL,
  `count` INT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`date`)
) ENGINE=InnoDB;
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
) ENGINE=InnoDB;

INSERT INTO `email` (`emailID`, `user`, `password`, `host`, `port`, `debug`, `auth`, `html`, `smtp`, `secure`) 
VALUES (1, '', '', '', 25, 0, 0, 1, 0, 0);
-- Ende der Tabelle 'email'

-- Tabellenstruktur für Tabelle `lock` 
CREATE TABLE IF NOT EXISTS `lock` (
  `time` int(11) NOT NULL,
  `reason` text NOT NULL
) ENGINE=InnoDB;
-- Ende der Tabelle 'lock'

-- Tabellenstruktur für Tabelle `navigation_dashboard_categories`
CREATE TABLE IF NOT EXISTS `navigation_dashboard_categories` (
  `catID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `modulname` varchar(255) NOT NULL,
  `fa_name` varchar(255) NOT NULL DEFAULT '',
  `sort_art` INT(11) DEFAULT 0,
  `sort` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`catID`),
  UNIQUE KEY `modulname` (`modulname`, `catID`)
) ENGINE=InnoDB;

INSERT INTO `navigation_dashboard_categories` (`catID`, `name`, `modulname`, `fa_name`, `sort_art`, `sort`) VALUES
(1, '[[lang:de]]Webseiten Info - Einstellungen[[lang:en]]Website Info - Settings[[lang:it]]Informazioni-Impostazioni Sito', 'cat_webinfo', 'bi bi-gear', 0, 1),
(2, '[[lang:de]]Spam[[lang:en]]Spam[[lang:it]]Spam', 'cat_spam', 'bi bi-exclamation-triangle', 0, 2),
(3, '[[lang:de]]Benutzer Administration[[lang:en]]User Administration[[lang:it]]Amministrazione Utenti', 'cat_admin', 'bi bi-person', 0, 3),
(4, '[[lang:de]]Team Verwaltung[[lang:en]]Team Administration[[lang:it]]Amministrazione della squadra', 'cat_team', 'bi bi-people', 0, 4),
(5, '[[lang:de]]Template - Layout[[lang:en]]Template - Layout[[lang:it]]Template - Disposizione', 'cat_theme', 'bi bi-layout-text-window-reverse', 0, 5),
(6, '[[lang:de]]Plugin & Widget Verwaltung[[lang:en]]Plugin and Widget Management[[lang:it]]Gestione plugin e widget', 'cat_plugin', 'bi bi-puzzle', 0, 6),
(7, '[[lang:de]]Webseiteninhalte[[lang:en]]Website Content[[lang:it]]Contenuto del sito web', 'cat_web_content', 'bi bi-card-checklist', 0, 7),
(8, '[[lang:de]]Grafik - Video - Projekte[[lang:en]]Grafik - Video - Projekte[[lang:it]]Grafica - Video - Progetti', 'cat_gallery', 'bi bi-image', 0, 8),
(9, '[[lang:de]]Header - Slider[[lang:en]]Header - Slider[[lang:it]]Slider-Header', 'cat_slider', 'bi bi-fast-forward-btn', 0, 9),
(10, '[[lang:de]]Game - Voice Server Tools[[lang:en]]Game - Voice Server Tools[[lang:it]]Voice Server Tools', 'cat_game', 'bi bi-controller', 0, 10),
(11, '[[lang:de]]Social Media[[lang:en]]Social Media[[lang:it]]Social Media', 'cat_social', 'bi bi-steam', 0, 11),
(12, '[[lang:de]]Links - Download - Sponsoren[[lang:en]]Links - Download - Sponsore[[lang:it]]Link - Download - Sponsor', 'cat_link', 'bi bi-link', 0, 12);
-- Ende der Tabelle 'navigation_dashboard_categories'

-- Tabellenstruktur für Tabelle `navigation_dashboard_links`
CREATE TABLE IF NOT EXISTS `navigation_dashboard_links` (
  `linkID` int(11) NOT NULL AUTO_INCREMENT,
  `catID` int(11) NOT NULL DEFAULT 0,
  `modulname` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `sort` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`linkID`),
  UNIQUE KEY `modulname` (`modulname`, `linkID`)
) ENGINE=InnoDB;

INSERT INTO `navigation_dashboard_links` (`linkID`, `catID`, `modulname`, `name`, `url`, `sort`) VALUES
(1, 1, 'ac_overview', '[[lang:de]]Webserver-Info[[lang:en]]Webserver Info[[lang:it]]Informazioni Sul Sito', 'admincenter.php?site=overview', 1),
(2, 1, 'ac_page_statistic', '[[lang:de]]Seiten Statistiken[[lang:en]]Page Statistics[[lang:it]]Pagina delle Statistiche', 'admincenter.php?site=page_statistic', 2),
(3, 1, 'ac_visitor_statistic', '[[lang:de]]Besucher Statistiken[[lang:en]]Visitor Statistics[[lang:it]]Statistiche Visitatori', 'admincenter.php?site=visitor_statistic', 3),
(4, 1, 'ac_settings', '[[lang:de]]Allgemeine Einstellungen[[lang:en]]General Settings[[lang:it]]Impostazioni Generali', 'admincenter.php?site=settings', 4),
(5, 1, 'ac_dashboard_navigation', '[[lang:de]]Admincenter Navigation[[lang:en]]Admincenter Navigation[[lang:it]]Menu Navigazione Admin', 'admincenter.php?site=dashboard_navigation', 5),
(6, 1, 'ac_email', '[[lang:de]]E-Mail[[lang:en]]E-Mail[[lang:it]]E-Mail', 'admincenter.php?site=email', 6),
(7, 1, 'ac_contact', '[[lang:de]]Kontakte[[lang:en]]Contacts[[lang:it]]Contatti', 'admincenter.php?site=contact', 7),
(8, 1, 'ac_modrewrite', '[[lang:de]]Mod-Rewrite[[lang:en]]Mod-Rewrite[[lang:it]]Mod-Rewrite', 'admincenter.php?site=modrewrite', 8),
(9, 1, 'ac_database', '[[lang:de]]Datenbank[[lang:en]]Database[[lang:it]]Database', 'admincenter.php?site=database', 9),
(10, 1, 'ac_update', '[[lang:de]]Webspell-RM Update[[lang:en]]Webspell-RM Update[[lang:it]]Aggiornamento Webspell-RM', 'admincenter.php?site=update', 10),
(11, 3, 'ac_users', '[[lang:de]]Registrierte Benutzer[[lang:en]]Registered Users[[lang:it]]Utenti Registrati', 'admincenter.php?site=users', 1),
(12, 2, 'ac_spam_forum', '[[lang:de]]Geblockte Inhalte[[lang:en]]Blocked Content[[lang:it]]Contenuti Bloccati', 'admincenter.php?site=spam&action=forum_spam', 1),
(13, 2, 'ac_spam_user', '[[lang:de]]Nutzer löschen[[lang:en]]Remove User[[lang:it]]Banna Utente', 'admincenter.php?site=spam&action=user', 2),
(14, 2, 'ac_spam_multi', '[[lang:de]]Multi-Accounts[[lang:en]]Multi-Accounts[[lang:it]]Multi-Account', 'admincenter.php?site=spam&action=multi', 3),
(15, 2, 'ac_spam_banned_ips', '[[lang:de]]gebannte IPs[[lang:en]]Banned IPs[[lang:it]]IP Bannate', 'admincenter.php?site=banned_ips', 4),
(16, 5, 'ac_webside_navigation', '[[lang:de]]Webseiten Navigation[[lang:en]]Website Navigation[[lang:it]]Menu Navigazione Web', 'admincenter.php?site=webside_navigation', 1),
(17, 5, 'ac_themes_installer', '[[lang:de]]Themes Installer[[lang:en]]Themes Installer[[lang:it]]Installazione Themes', 'admincenter.php?site=themes_installer', 2),
(18, 5, 'ac_themes', '[[lang:de]]Themes[[lang:en]]Themes[[lang:it]]Temi', 'admincenter.php?site=settings_themes', 3),
(20, 5, 'ac_startpage', '[[lang:de]]Startseite[[lang:en]]Start Page[[lang:it]]Pagina Principale', 'admincenter.php?site=settings_startpage', 5),
(21, 5, 'ac_static', '[[lang:de]]Statische Seiten[[lang:en]]Static Pages[[lang:it]]Pagine Statiche', 'admincenter.php?site=settings_static', 6),
(22, 5, 'ac_imprint', '[[lang:de]]Impressum[[lang:en]]Imprint[[lang:it]]Impronta Editoriale', 'admincenter.php?site=settings_imprint', 7),
(23, 5, 'ac_privacy_policy', '[[lang:de]]Datenschutz-Bestimmungen[[lang:en]]Privacy Policy[[lang:it]]Informativa sulla Privacy', 'admincenter.php?site=settings_privacy_policy', 8),
(24, 6, 'ac_plugin_manager', '[[lang:de]]Plugin & Widget Manager[[lang:en]]Plugin & Widget Manager[[lang:it]]Gestore di Plugin e Widget', 'admincenter.php?site=plugin_manager', 1),
(25, 6, 'ac_plugin_installer', '[[lang:de]]Plugin Installer[[lang:en]]Plugin Installer[[lang:it]]Installazione Plugin', 'admincenter.php?site=plugin_installer', 2),
(26, 1, 'ac_editlang', '[[lang:de]]Spracheditor[[lang:en]]Language Editor[[lang:it]]Editor di Linguaggi', 'admincenter.php?site=editlang', 11),
(27, 7, 'footer', '[[lang:de]]Footer[[lang:en]]Footer[[lang:it]]Piè di pagina', 'admincenter.php?site=fotter', 0),
(28, 3, 'ac_admin_security', '[[lang:de]]Admin Security[[lang:en]]Admin Security[[lang:it]]Sicurezza Admin', 'admincenter.php?site=admin_security', 2),
(29, 3, 'ac_user_roles', '[[lang:de]]User Roles[[lang:en]]User Roles[[lang:it]]Ruoli Utente', 'admincenter.php?site=user_roles', 3),
(31, 3, 'role_permissions', 'Role Permissions.php', 'admincenter.php?site=admin_role_permissions', 1);

-- Ende der Tabelle 'navigation_dashboard_links'

-- Tabellenstruktur für Tabelle `navigation_website_main`
CREATE TABLE IF NOT EXISTS `navigation_website_main` (
  `mnavID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `default` int(11) NOT NULL DEFAULT '1',
  `sort` int(2) NOT NULL DEFAULT '0',
  `isdropdown` int(1) NOT NULL DEFAULT '1',
  `windows` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB;
  
INSERT INTO `navigation_website_main` (`mnavID`, `name`, `url`, `default`, `sort`, `isdropdown`, `windows`) VALUES
(1, '[[lang:de]]HAUPT[[lang:en]]MAIN[[lang:it]]PRINCIPALE', '#', 1, 1, 1, 1),
(2, '[[lang:de]]TEAM[[lang:en]]TEAM[[lang:it]]TEAM', '#', 1, 2, 1, 1),
(3, '[[lang:de]]GEMEINSCHAFT[[lang:en]]COMMUNITY[[lang:it]]COMMUNITY', '#', 1, 3, 1, 1),
(4, '[[lang:de]]MEDIEN[[lang:en]]MEDIA[[lang:it]]MEDIA', '#', 1, 4, 1, 1),
(5, '[[lang:de]]SOCIAL[[lang:en]]SOCIAL[[lang:it]]SOCIAL', '#', 1, 5, 1, 1),
(6, '[[lang:de]]SONSTIGES[[lang:en]]MISCELLANEOUS[[lang:it]]VARIE', '#', 1, 6, 1, 1);
-- Ende der Tabelle 'navigation_website_main'

-- Tabellenstruktur für Tabelle `navigation_website_sub`
CREATE TABLE IF NOT EXISTS `navigation_website_sub` (
  `snavID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `mnavID` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `modulname` varchar(100) NOT NULL,
  `url` varchar(255) NOT NULL,
  `sort` int(2) NOT NULL DEFAULT '0',
  `indropdown` int(1) NOT NULL DEFAULT '1',
  `themes_modulname` varchar(255) DEFAULT 'default'
) ENGINE=InnoDB;
  
INSERT INTO `navigation_website_sub` (`snavID`, `mnavID`, `name`, `modulname`, `url`, `sort`, `indropdown`, `themes_modulname`) VALUES
(1, 6, '[[lang:de]]Kontakt[[lang:en]]Contact[[lang:it]]Contatti', 'contact', 'index.php?site=contact', 1, 1, 'default'),
(2, 6, '[[lang:de]]Datenschutz-Bestimmungen[[lang:en]]Privacy Policy[[lang:it]]Informativa sulla Privacy', 'privacy_policy', 'index.php?site=privacy_policy', 2, 1, 'default'),
(3, 6, '[[lang:de]]Impressum[[lang:en]]Imprint[[lang:it]]Impronta Editoriale', 'imprint', 'index.php?site=imprint', 3, 1, 'default');
-- Ende der Tabelle 'navigation_website_sub'


-- Tabellenstruktur für Tabelle `plugins_footer`
CREATE TABLE IF NOT EXISTS `plugins_footer` (
  `footID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `banner` varchar(255) NOT NULL,
  `about` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `strasse` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telefon` varchar(255) NOT NULL,
  `since` varchar(255) NOT NULL,
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
) ENGINE=InnoDB;

INSERT INTO `plugins_footer` (`footID`, `banner`, `about`, `name`, `strasse`, `email`, `telefon`, `since`, `linkname1`, `navilink1`, `linkname2`, `navilink2`, `linkname3`, `navilink3`, `linkname4`, `navilink4`, `linkname5`, `navilink5`, `linkname6`, `navilink6`, `linkname7`, `navilink7`, `linkname8`, `navilink8`, `linkname9`, `navilink9`, `linkname10`, `navilink10`, `social_text`, `social_link_name1`, `social_link1`, `social_link_name2`, `social_link2`, `social_link_name3`, `social_link3`, `copyright_link_name1`, `copyright_link1`, `copyright_link_name2`, `copyright_link2`, `copyright_link_name3`, `copyright_link3`, `copyright_link_name4`, `copyright_link4`, `copyright_link_name5`, `copyright_link5`, `widget_left`, `widget_center`, `widget_right`, `widgetdatei_left`, `widgetdatei_center`, `widgetdatei_right`) VALUES
(1, '', 'Team Clanname ist eine 1999 gegründete deutsche E-Sport-Organisation...', 'Hans Mustermann', 'Musterhausen 11, Germany', 'mail@Clanname-esport.de', '(123) 456-7890', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'Impressum', 'index.php?site=imprint', 'Datenschutz', 'index.php?site=privacy_policy', 'Kontakt', 'index.php?site=contact', 'Counter', 'index.php?site=counter', '', '', 'blog', 'about_us', 'userlist', '', '', '');
-- Ende der Tabelle 'plugins_footer'

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
) ENGINE=InnoDB;

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
) ENGINE=InnoDB;

INSERT INTO `plugins_startpage_settings_widgets` (`id`, `position`, `modulname`, `themes_modulname`, `widgetname`, `widgetdatei`, `activated`, `sort`) VALUES
(1, 'navigation_widget', 'navigation', 'default', 'Navigation', 'widget_navigation', 1, 1),
(2, 'footer_widget', 'footer', 'default', 'Footer Easy', 'widget_footer_easy', 1, 2);
-- Ende der Tabelle 'plugins_startpage_settings_widgets'

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
) ENGINE=InnoDB;

INSERT IGNORE INTO `settings` (`settingID`, `title`, `hpurl`, `clanname`, `clantag`, `adminname`, `adminemail`, `sball`, `topics`, `posts`, `latesttopics`, `latesttopicchars`, `messages`, `register_per_ip`, `sessionduration`, `closed`, `imprint`, `default_language`, `insertlinks`, `search_min_len`, `max_wrong_pw`, `captcha_math`, `captcha_bgcol`, `captcha_fontcol`, `captcha_type`, `captcha_noise`, `captcha_linenoise`, `bancheck`, `spam_check`, `detect_language`, `spammaxposts`, `spamapiblockerror`, `date_format`, `time_format`, `modRewrite`, `startpage`, `forum_double`, `profilelast`, `de_lang`, `en_lang`, `it_lang`, `birthday`, `keywords`, `description`) VALUES
(1, 'Webspell-RM', '{{weburl}}', 'Clan Name', 'MyClan', '{{adminuser}}', '{{adminmail}}', 30, 20, 10, 10, 18, 20, 1, 0, 0, 1, 'de', 1, 3, 10, 2, '#FFFFFF', '#000000', 2, 100, 10, 1564938159, 0, 0, 0, 0, 'd.m.Y', 'H:i', 0, 'startpage', 1, 10, 1, 1, 1, 0, 'Clandesign, Webspell, Webspell-RM, Wespellanpassungen, Webdesign, Tutorials, Downloads, Webspell-rm, rm, addon, plugin, Templates Webspell Addons, plungin, mods, Webspellanpassungen, Modifikationen und Anpassungen und mehr!', 'Kostenlose Homepage erstellen mit Webspell-RM CMS: Einfach, schnell & kostenlos! In wenigen Minuten mit der eigenen Website online gehen.');
-- Ende der Tabelle 'settings'

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
) ENGINE=InnoDB;

INSERT IGNORE INTO `settings_buttons` (`buttonID`, `name`, `modulname`, `active`, `version`, `button1`, `button2`, `button3`, `button4`, `button5`, `button6`, `button7`, `button8`, `button9`, `button10`, `button11`, `button12`, `button13`, `button14`, `button15`, `button16`, `button17`, `button18`, `button19`, `button20`, `button21`, `button22`, `button23`, `button24`, `button25`, `button26`, `button27`, `button28`, `button29`, `button30`, `button31`, `button32`, `button33`, `button34`, `button35`, `button36`, `button37`, `button38`, `button39`, `button40`, `button41`, `button42`, `btn_border_radius`) VALUES
(81, 'Default', 'default', 0, '0.3', 'rgb(254,130,29)', 'rgb(196,89,1)', 'rgb(255,255,255)', 'rgb(254,130,29)', 'rgb(196,89,1)', 'rgb(108,117,125)', 'rgb(90,98,104)', 'rgb(255,255,255)', 'rgb(108,117,125)', 'rgb(84,91,98)', 'rgb(40,167,69)', 'rgb(33,136,56)', 'rgb(255,255,255)', 'rgb(40,167,69)', 'rgb(30,126,52)', 'rgb(220,53,69)', 'rgb(200,35,51)', 'rgb(255,255,255)', 'rgb(220,53,69)', 'rgb(189,33,48)', 'rgb(255,193,7)', 'rgb(224,168,0)', 'rgb(33,37,41)', 'rgb(255,193,7)', 'rgb(211,158,0)', 'rgb(23,162,184)', 'rgb(19,132,150)', 'rgb(255,255,255)', 'rgb(23,162,184)', 'rgb(17,122,139)', 'rgb(248,249,250)', 'rgb(226,230,234)', 'rgb(33,37,41)', 'rgb(248,249,250)', 'rgb(218,224,229)', 'rgb(52,58,64)', 'rgb(35,39,43)', 'rgb(255,255,255)', 'rgb(52,58,64)', 'rgb(29,33,36)', 'rgb(254,130,29)', 'rgb(196,89,1)', '0px');
-- Ende der Tabelle 'settings_buttons'

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
) ENGINE=InnoDB;

INSERT INTO `settings_expansion` (`themeID`, `name`, `modulname`, `pfad`, `version`, `active`, `express_active`, `nav1`, `nav2`, `nav3`, `nav4`, `nav5`, `nav6`, `nav7`, `nav8`, `nav9`, `nav10`, `nav11`, `nav12`, `nav_text_alignment`, `body1`, `body2`, `body3`, `body4`, `body5`, `background_pic`, `border_radius`, `typo1`, `typo2`, `typo3`, `typo4`, `typo5`, `typo6`, `typo7`, `typo8`, `card1`, `card2`, `foot1`, `foot2`, `foot3`, `foot4`, `foot5`, `foot6`, `calendar1`, `calendar2`, `carousel1`, `carousel2`, `carousel3`, `carousel4`, `logo_pic`, `logotext1`, `logotext2`, `reg_pic`, `reg1`, `reg2`, `headlines`, `sort`) VALUES
(7, 'Default', 'default', 'default', '0.3', 1, 0, 'rgb(51,51,51)', '16px', 'rgb(221,221,221)', 'rgb(254,130,29)', 'rgb(254,130,29)', '2px', 'rgb(221,221,221)', 'rgb(196,89,1)', '#1bdf1b', 'rgb(51,51,51)', 'rgb(221,221,221)', 'rgb(101,100,100)', 'ms-auto', 'Roboto', '13px', 'rgb(255,255,255)', 'rgb(85,85,85)', 'rgb(236,236,236)', '', '0px', '', '', '', 'rgb(254,130,29)', '', '', '', 'rgb(196,89,1)', 'rgb(255,255,255)', 'rgb(221,221,221)', 'rgb(85,85,85)', 'rgb(255,255,255)', 'rgb(255,255,255)', 'rgb(181,179,179)', 'rgb(254,130,29)', 'rgb(255,255,255)', '', '', 'rgb(255,255,255)', 'rgb(254,130,29)', 'rgb(255,255,255)', 'rgb(254,130,29)', 'default_logo.png', '', '', 'default_login_bg.jpg', 'rgb(254,130,29)', 'rgb(255,255,255)', 'headlines_01.css', 1);
-- Ende der Tabelle 'settings_expansion'

-- Tabellenstruktur für Tabelle `settings_imprint`
CREATE TABLE IF NOT EXISTS `settings_imprint` (
  `imprintID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `imprint` text NOT NULL,
  `disclaimer_text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `settings_imprint` (`imprint`, `disclaimer_text`) VALUES
('[[lang:de]]Impressum in deutscher Sprache.<br />
<span style="color:#c0392b"><strong>Konfigurieren Sie bitte Ihr Impressum!</strong></span>

[[lang:en]]Imprint in English.<br />
<span style="color:#c0392b"><strong>Please configure your imprint!</strong></span>

[[lang:it]]Impronta Editoriale in Italiano.<br />
<span style="color:#c0392b"><strong>Si prega di configurare l''impronta!</strong></span>',
'[[lang:de]]Haftungsausschluss in deutscher Sprache.<br />
<span style="color:#c0392b"><strong>Konfigurieren Sie bitte Ihr Haftungsausschluss! </strong></span>

[[lang:en]]Disclaimer in English.<br />
<span style="color:#c0392b"><strong>Please configure your disclaimer!</strong></span>

[[lang:it]]Dichiarazione di non Responsabilità in Italiano.<br />
<span style="color:#c0392b"><strong>Si prega di configurare la Dichiarazione di non Responsabilità!</strong></span>');



-- Ende der Tabelle 'settings_imprint'

-- Tabellenstruktur für Tabelle `settings_languages`
CREATE TABLE IF NOT EXISTS `settings_languages` (
  `langID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `language` varchar(255) NOT NULL,
  `lang` char(2) NOT NULL,
  `alt` varchar(255) NOT NULL
) ENGINE=InnoDB;

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
-- Ende der Tabelle 'settings_languages'

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `settings_plugins` (`pluginID`, `name`, `modulname`, `info`, `admin_file`, `activate`, `author`, `website`, `index_link`, `hiddenfiles`, `version`, `path`, `status_display`, `plugin_display`, `widget_display`, `delete_display`, `sidebar`) VALUES
(1, 'Startpage', 'startpage', '[[lang:de]]Kein Plugin. Bestandteil vom System!!![[lang:en]]No plugin. Part of the system!!![[lang:it]]Nessun plug-in. Parte del sistema!!!', '', 1, '', '', '', '', '', '', 0, 0, 1, 0, 'full_activated'),
(2, 'Privacy Policy', 'privacy_policy', '[[lang:de]]Kein Plugin. Bestandteil vom System!!![[lang:en]]No plugin. Part of the system!!![[lang:it]]Nessun plug-in. Parte del sistema!!!', '', 1, '', '', 'privacy_policy', '', '', '', 0, 0, 1, 0, 'deactivated'),
(3, 'Imprint', 'imprint', '[[lang:de]]Kein Plugin. Bestandteil vom System!!![[lang:en]]No plugin. Part of the system!!![[lang:it]]Nessun plug-in. Parte del sistema!!!', '', 1, '', '', 'imprint', '', '', '', 0, 0, 1, 0, 'deactivated'),
(4, 'Static', 'static', '[[lang:de]]Kein Plugin. Bestandteil vom System!!![[lang:en]]No plugin. Part of the system!!![[lang:it]]Nessun plug-in. Parte del sistema!!!', '', 1, '', '', 'static', '', '', '', 1, 0, 1, 0, 'deactivated'),
(5, 'Error_404', 'error_404', '[[lang:de]]Kein Plugin. Bestandteil vom System!!![[lang:en]]No plugin. Part of the system!!![[lang:it]]Nessun plug-in. Parte del sistema!!!', '', 1, '', '', 'error_404', '', '', '', 1, 0, 1, 0, 'deactivated'),
(6, 'Profile', 'profile', '[[lang:de]]Kein Plugin. Bestandteil vom System!!![[lang:en]]No plugin. Part of the system!!![[lang:it]]Nessun plug-in. Parte del sistema!!!', '', 1, '', '', 'profile', '', '', '', 1, 0, 1, 0, 'deactivated'),
(7, 'Login', 'login', '[[lang:de]]Kein Plugin. Bestandteil vom System!!![[lang:en]]No plugin. Part of the system!!![[lang:it]]Nessun plug-in. Parte del sistema!!!', '', 1, '', '', 'login', '', '', '', 1, 0, 1, 0, 'deactivated'),
(8, 'Lost Password', 'lostpassword', '[[lang:de]]Kein Plugin. Bestandteil vom System!!![[lang:en]]No plugin. Part of the system!!![[lang:it]]Nessun plug-in. Parte del sistema!!!', '', 1, '', '', 'lostpassword', '', '', '', 1, 0, 1, 0, 'deactivated'),
(9, 'Contact', 'contact', '[[lang:de]]Kein Plugin. Bestandteil vom System!!![[lang:en]]No plugin. Part of the system!!![[lang:it]]Nessun plug-in. Parte del sistema!!!', '', 1, '', '', 'contact', '', '', '', 1, 0, 1, 0, 'deactivated'),
(10, 'Register', 'register', '[[lang:de]]Kein Plugin. Bestandteil vom System!!![[lang:en]]No plugin. Part of the system!!![[lang:it]]Nessun plug-in. Parte del sistema!!!', '', 1, '', '', 'register', '', '', '', 1, 0, 1, 0, 'deactivated'),
(11, 'My Profile', 'myprofile', '[[lang:de]]Kein Plugin. Bestandteil vom System!!![[lang:en]]No plugin. Part of the system!!![[lang:it]]Nessun plug-in. Parte del sistema!!!', '', 1, '', '', 'myprofile', '', '', '', 1, 0, 1, 0, 'deactivated'),
(12, 'Report', 'report', '[[lang:de]]Kein Plugin. Bestandteil vom System!!![[lang:en]]No plugin. Part of the system!!![[lang:it]]Nessun plug-in. Parte del sistema!!!', '', 1, '', '', 'report', '', '', '', 1, 0, 1, 0, 'deactivated'),
(13, 'Navigation', 'navigation', '[[lang:de]]Mit diesem Plugin könnt ihr euch die Navigation anzeigen lassen.[[lang:en]]With this plugin you can display navigation.[[lang:it]]Con questo plugin puoi visualizzare la Barra di navigazione predefinita.', '', 1, 'T-Seven', 'https://webspell-rm.de', '', '', '0.3', 'includes/plugins/navigation/', 1, 1, 0, 0, 'deactivated'),
(14, 'Footer', 'footer', '[[lang:de]]Mit diesem Plugin könnt ihr einen neuen Footer anzeigen lassen.[[lang:en]]With this plugin you can have a new Footer displayed.[[lang:it]]Con questo plugin puoi visualizzare un nuovo piè di pagina.', 'admin_footer', 1, 'T-Seven', 'https://webspell-rm.de', '', '', '0.1', 'includes/plugins/footer/', 1, 1, 0, 0, 'deactivated');
-- Ende der Tabelle 'settings_plugins'

-- Tabellenstruktur für Tabelle `settings_plugins_widget`
CREATE TABLE IF NOT EXISTS `settings_plugins_widget` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `modulname` varchar(100) NOT NULL,
  `widgetname` varchar(255) NOT NULL,
  `widgetdatei` varchar(255) NOT NULL,
  `area` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB;

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
) ENGINE=InnoDB;

INSERT INTO `settings_plugins_widget_settings` (`id`, `side`, `position`, `modulname`, `themes_modulname`, `widgetname`, `widgetdatei`, `activated`, `sort`) VALUES
(1, '', 'navigation_widget', 'navigation', 'default', 'Navigation', 'widget_navigation', 1, 0),
(2, '', 'footer_widget', 'footer', 'default', 'Footer Easy', 'widget_footer_easy', 1, 0);
-- Ende der Tabelle 'settings_plugins_widget_settings'

-- Tabellenstruktur für Tabelle `settings_privacy_policy`
CREATE TABLE IF NOT EXISTS `settings_privacy_policy` (
  `privacy_policyID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `date` int(14) NOT NULL,
  `privacy_policy_text` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `settings_privacy_policy` (`privacy_policyID`, `date`, `privacy_policy_text`) VALUES (1, UNIX_TIMESTAMP(), '[[lang:de]] Datenschutz-Bestimmungen in deutscher Sprache.<br /><span style="color:#c0392b"><strong>Konfigurieren Sie bitte Ihre Datenschutz-Bestimmungen!</strong></span><br />[[lang:en]] Privacy Policy in English.<br /><span style="color:#c0392b"><strong>Please configure your Privacy Policy!</strong></span>[[lang:it]] Informativa sulla Privacy in Italiano.<br /><span style="color:#c0392b"><strong>Si prega di configurare l’Informativa sulla Privacy!</strong></span>');
-- Ende der Tabelle 'settings_privacy_policy'

-- Tabellenstruktur für Tabelle `settings_recaptcha`
CREATE TABLE IF NOT EXISTS `settings_recaptcha` (
  `activated` int(11) NOT NULL DEFAULT '0',
  `webkey` varchar(255) NOT NULL,
  `seckey` varchar(255) NOT NULL
) ENGINE=InnoDB;

INSERT INTO `settings_recaptcha` (`activated`, `webkey`, `seckey`) VALUES
(0, 'Web-Key', 'Sec-Key');
-- Ende der Tabelle 'settings_recaptcha'

-- Tabellenstruktur für Tabelle `settings_social_media`
CREATE TABLE IF NOT EXISTS `settings_social_media` (
  `socialID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `twitch` varchar(255) NOT NULL,
  `facebook` varchar(255) NOT NULL,
  `twitter` varchar(255) NOT NULL,
  `youtube` varchar(255) NOT NULL,
  `rss` varchar(255) NOT NULL,
  `vine` varchar(255) NOT NULL,
  `flickr` varchar(255) NOT NULL,
  `linkedin` varchar(255) NOT NULL,
  `instagram` varchar(255) NOT NULL,
  `since` varchar(255) NOT NULL,
  `gametracker` varchar(255) NOT NULL,
  `discord` varchar(255) NOT NULL,
  `steam` varchar(255) NOT NULL
) ENGINE=InnoDB;

INSERT INTO `settings_social_media` (`socialID`, `twitch`, `facebook`, `twitter`, `youtube`, `rss`, `vine`, `flickr`, `linkedin`, `instagram`, `since`, `gametracker`, `discord`, `steam`) VALUES
(1, 'https://www.twitch.tv/pulsradiocom', 'https://www.facebook.com/WebspellRM', 'https://twitter.com/webspell_rm', 'https://www.youtube.com/channel/UCE5yTn9ljzSnC_oMp9Jnckg', '-', '-', '-', '-', '-', '2025', '85.14.228.228:28960', 'https://www.discord.gg/kErxPxb', '-');
-- Ende der Tabelle 'settings_social_media'

-- Tabellenstruktur für Tabelle `settings_startpage`
CREATE TABLE IF NOT EXISTS `settings_startpage` (
  `pageID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` varchar(255) NOT NULL,
  `startpage_text` longtext NOT NULL,
  `creation_date` int(14) NOT NULL DEFAULT 0,
  `displayed` varchar(255) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO settings_startpage (title, startpage_text, creation_date, displayed)
VALUES 
('Startseite', 
'<h1>Webspell | RM 2.1.7</h1>

[[lang:de]]
<p>Herzlich willkommen bei <strong>Webspell | RM</strong> – dem modernen und flexiblen CMS für Gamer, Clans und Communities!</p>
<p>Webspell | RM ist das perfekte Content-Management-System für alle, die eine professionelle und maßgeschneiderte Website für ihre Gaming-Community, Clan oder Organisation erstellen möchten. Mit unserer leistungsstarken Software kannst du eine Plattform aufbauen, die nicht nur funktional, sondern auch ansprechend und einfach zu verwalten ist.</p>
<p><strong>Die Version 2.1.7</strong> bringt zahlreiche Verbesserungen in den Bereichen Design, Sicherheit und Benutzerfreundlichkeit. Die neue Version bietet eine noch stabilere Grundlage, auf der du deine Community-Seite aufbauen kannst, mit einer Vielzahl an Tools und Funktionen, die speziell für die Bedürfnisse von Gamern und Clan-Organisationen entwickelt wurden. Egal, ob du eine <strong>Teamseite</strong>, eine <strong>Turnierplattform</strong> oder eine <strong>Gilden-Website</strong> erstellen möchtest – mit Webspell | RM hast du alle nötigen Werkzeuge, um deine Seite genau nach deinen Vorstellungen zu gestalten.</p>
<ul>
<li><strong>Anpassbares Design</strong>: Wähle aus einer Vielzahl von Designs oder erstelle dein eigenes Layout, das perfekt zu deiner Community passt.</li>
<li><strong>Benutzerfreundliches Backend</strong>: Das Admin-Panel wurde so optimiert, dass du alle Funktionen schnell und einfach nutzen kannst. Verwaltete Mitglieder, Turniere, News und vieles mehr auf einer zentralen Oberfläche.</li>
<li><strong>Sicherheit</strong>: Mit der neuesten Version erhältst du zusätzliche Sicherheitsfunktionen, die deine Daten und die deiner Nutzer noch besser schützen.</li>
<li><strong>Mehrsprachigkeit</strong>: Webspell | RM unterstützt mehrere Sprachen, sodass du deine Community weltweit erreichen kannst.</li>
</ul>
<p>Ob du gerade erst mit deiner Community startest oder schon eine etablierte Gruppe hast, Webspell | RM bietet dir alles, was du brauchst, um eine beeindruckende und funktionale Website zu erstellen. Der flexible Aufbau und die Vielzahl an Erweiterungen ermöglichen es dir, deine Seite mit neuen Features auszustatten, wann immer du es brauchst.</p>

<p><strong>Probier es aus!</strong> Auf unserer <a href="https://www.webspell-templates.de/" target="_blank">offiziellen Website</a> findest du alle Informationen, die du benötigst, um mit Webspell | RM loszulegen. Und mit der <a href="https://www.webspell-templates.de/" target="_blank">Live-Demo</a> kannst du die Funktionen der Software in Echtzeit erleben.</p>

[[lang:en]]
<p>Welcome to <strong>Webspell | RM</strong> – the modern and flexible CMS for gamers, clans, and communities!</p>
<p>Webspell | RM is the perfect content management system for anyone looking to create a professional and customized website for their gaming community, clan, or organization. With our powerful software, you can build a platform that is not only functional but also visually appealing and easy to manage.</p>
<p><strong>Version 2.1.7</strong> brings numerous improvements in design, security, and user-friendliness. The new version offers an even more stable foundation on which you can build your community site, with a variety of tools and features specifically developed for gamers and clan organizations. Whether you\'re creating a <strong>team page</strong>, a <strong>tournament platform</strong>, or a <strong>guild website</strong> – with Webspell | RM, you have all the necessary tools to design your site exactly the way you want.</p>
<ul>
<li><strong>Customizable Design</strong>: Choose from a variety of designs or create your own layout that perfectly fits your community.</li>
<li><strong>User-Friendly Backend</strong>: The admin panel has been optimized for quick and easy access to all features. Manage members, tournaments, news, and much more from a central interface.</li>
<li><strong>Security</strong>: With the latest version, you get additional security features that better protect your data and that of your users.</li>
<li><strong>Multilingual Support</strong>: Webspell | RM supports multiple languages, allowing you to reach your community worldwide.</li>
</ul>
<p>Whether you\'re just starting out with your community or already have an established group, Webspell | RM gives you everything you need to build an impressive and functional website. The flexible structure and a wide range of add-ons make it easy to enhance your site with new features whenever you need them.</p>

<p><strong>Give it a try!</strong> Visit our <a href="https://www.webspell-templates.de/" target="_blank">official website</a> for all the information you need to get started with Webspell | RM. And with the <a href="https://www.webspell-templates.de/" target="_blank">live demo</a>, you can experience the software\'s features in real-time.</p>

[[lang:it]]
<p>Benvenuto su <strong>Webspell | RM</strong> – il CMS moderno e flessibile per gamer, clan e community!</p>
<p>Webspell | RM è il sistema di gestione dei contenuti perfetto per chi desidera creare un sito web professionale e personalizzato per la propria community di gamer, clan o organizzazioni. Con il nostro potente software, puoi costruire una piattaforma che non solo è funzionale, ma anche visivamente attraente e facile da gestire.</p>
<p><strong>La versione 2.1.7</strong> offre numerosi miglioramenti in termini di design, sicurezza e facilità d\'uso. La nuova versione fornisce una base ancora più stabile su cui costruire il sito della tua community, con una varietà di strumenti e funzionalità specificamente sviluppati per gamer e organizzazioni di clan. Che tu stia creando una <strong>pagina del team</strong>, una <strong>piattaforma per tornei</strong> o un <strong>sito per la gilda</strong>, con Webspell | RM hai tutti gli strumenti necessari per progettare il tuo sito come lo desideri.</p>
<ul>
<li><strong>Design personalizzabile</strong>: Scegli tra una varietà di design o crea il tuo layout che si adatta perfettamente alla tua community.</li>
<li><strong>Backend intuitivo</strong>: Il pannello di amministrazione è stato ottimizzato per un facile accesso a tutte le funzionalità. Gestisci membri, tornei, notizie e molto altro da un\'interfaccia centrale.</li>
<li><strong>Sicurezza</strong>: Con la nuova versione, ricevi funzionalità di sicurezza aggiuntive che proteggono meglio i tuoi dati e quelli dei tuoi utenti.</li>
<li><strong>Supporto multilingue</strong>: Webspell | RM supporta più lingue, permettendoti di raggiungere la tua community in tutto il mondo.</li>
</ul>
<p>Che tu stia appena iniziando con la tua community o che tu abbia già un gruppo consolidato, Webspell | RM ti offre tutto ciò di cui hai bisogno per creare un sito impressionante e funzionale. La struttura flessibile e la vasta gamma di estensioni ti permettono di migliorare il tuo sito con nuove funzionalità ogni volta che ne hai bisogno.</p>

<p><strong>Provalo!</strong> Visita il nostro <a href="https://www.webspell-templates.de/" target="_blank">sito ufficiale</a> per tutte le informazioni necessarie per iniziare con Webspell | RM. E con la <a href="https://www.webspell-templates.de/" target="_blank">demo live</a>, puoi provare le funzionalità del software in tempo reale.</p>',
    UNIX_TIMESTAMP(),
    '0');

-- Ende der Tabelle 'settings_startpage'

-- Tabellenstruktur für Tabelle `settings_static`
CREATE TABLE IF NOT EXISTS `settings_static` (
  `staticID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` varchar(255) NOT NULL,
  `accesslevel` int(1) NOT NULL,
  `content` text NOT NULL,
  `date` int(14) NOT NULL,
  `displayed` int(1) DEFAULT '0'
) ENGINE=InnoDB;
-- Ende der Tabelle 'settings_static'

-- Tabellenstruktur für Tabelle `settings_themes`
CREATE TABLE IF NOT EXISTS `settings_themes` (
  `themeID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(255) NOT NULL,
  `modulname` varchar(100) NOT NULL,
  `pfad` varchar(255) DEFAULT '0',
  `version` varchar(11) DEFAULT NULL,
  `active` int(11) DEFAULT NULL
) ENGINE=InnoDB;

INSERT IGNORE INTO `settings_themes` (`themeID`, `name`, `modulname`, `pfad`, `version`, `active`) VALUES
(1, 'Default', 'default', 'default', '0.3', 1);
-- Ende der Tabelle 'settings_themes'

-- Tabellenstruktur für Tabelle `settings_widgets`
CREATE TABLE IF NOT EXISTS `settings_widgets` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `position` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `modulname` varchar(100) NOT NULL,
  `themes_modulname` varchar(255) NOT NULL,
  `widget` varchar(255) DEFAULT 0,
  `widgetname` varchar(255) DEFAULT 0,
  `widgetdatei` varchar(255) DEFAULT 0,
  `activate` int(11) DEFAULT 0,
  `number` int(1) NOT NULL,
  `sort` int(11) DEFAULT 0
) ENGINE=InnoDB;

INSERT INTO `settings_widgets` (`id`, `position`, `description`, `modulname`, `themes_modulname`, `widget`, `widgetname`, `widgetdatei`, `activate`, `number`, `sort`) VALUES
(1, 'page_navigation_widget', 'page_navigation_widget', 'navigation', 'default', 'widget1', 'Navigation', 'widget_navigation', 0, 1, 1),
(2, 'page_footer_widget', 'page_footer_widget', 'footer', 'default', 'widget2', 'Easy Footer Content', 'widget_easyfooter_content', 0, 1, 0);
-- Ende der Tabelle 'settings_widgets'

-- Tabellenstruktur für Tabelle `settings_widgets`
CREATE TABLE IF NOT EXISTS `tags` (
  `rel` varchar(255) NOT NULL,
  `ID` int(11) NOT NULL,
  `tag` varchar(255) NOT NULL
) ENGINE=InnoDB;
-- Ende der Tabelle 'settings_widgets'

-- Tabellenstruktur für Tabelle `users`
CREATE TABLE IF NOT EXISTS `users` (
  `userID` int(11) NOT NULL AUTO_INCREMENT,
  `registerdate` int(14) NOT NULL DEFAULT 0,
  `lastlogin` int(14) NOT NULL DEFAULT 0,
  `password_hash` varchar(255) NOT NULL,
  `password_pepper` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_hide` int(1) NOT NULL DEFAULT 1,
  `email_change` varchar(255) NOT NULL,
  `email_activate` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `gender` varchar(100) NOT NULL DEFAULT 'select_gender',
  `town` varchar(255) NOT NULL,
  `birthday` varchar(255) NOT NULL,
  `facebook` varchar(255) NOT NULL,
  `twitter` varchar(255) NOT NULL,
  `twitch` varchar(255) NOT NULL,
  `steam` varchar(255) NOT NULL,
  `instagram` varchar(255) NOT NULL,
  `youtube` varchar(255) NOT NULL,
  `discord` varchar(255) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `usertext` varchar(255) NOT NULL,
  `userpic` varchar(255) NOT NULL,
  `homepage` varchar(255) NOT NULL,
  `about` text NOT NULL,
  `pmgot` int(11) NOT NULL DEFAULT 0,
  `pmsent` int(11) NOT NULL DEFAULT 0,
  `visits` int(11) NOT NULL DEFAULT 0,
  `banned` varchar(255) DEFAULT NULL,
  `ban_reason` varchar(255) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `topics` text NOT NULL,
  `articles` text NOT NULL,
  `demos` text NOT NULL,
  `files` text NOT NULL,
  `gallery_pictures` text NOT NULL,
  `special_rank` int(11) DEFAULT 0,
  `mailonpm` int(1) NOT NULL DEFAULT 0,
  `userdescription` text NOT NULL,
  `activated` varchar(255) NOT NULL DEFAULT '1',
  `language` varchar(2) NOT NULL,
  `date_format` varchar(255) NOT NULL DEFAULT 'd.m.Y',
  `time_format` varchar(255) NOT NULL DEFAULT 'H:i',
  `newsletter` int(1) DEFAULT 1,
  `links` text NOT NULL,
  `videos` text NOT NULL,
  `games` text NOT NULL,
  `projectlist` text NOT NULL,
  `roleID` int(11) DEFAULT 1,
  PRIMARY KEY (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=1;

INSERT INTO `users` (`userID`, `registerdate`, `lastlogin`, `password_hash`, `password_pepper`, `username`, `email`, `email_hide`, `email_change`, `email_activate`, `firstname`, `lastname`, `gender`, `town`, `birthday`, `facebook`, `twitter`, `twitch`, `steam`, `instagram`, `youtube`, `discord`, `avatar`, `usertext`, `userpic`, `homepage`, `about`, `pmgot`, `pmsent`, `visits`, `banned`, `ban_reason`, `ip`, `topics`, `articles`, `demos`, `files`, `gallery_pictures`, `special_rank`, `mailonpm`, `userdescription`, `activated`, `language`, `date_format`, `time_format`, `newsletter`, `links`, `videos`, `games`, `projectlist`, `roleID`) VALUES
(1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), '{{adminpass}}', '{{adminpepper}}', '{{adminuser}}', '{{adminmail}}', 1, '', '', '', '', 'select_gender', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, NULL, '', '', '|', '', '', '', '', 0, 0, '', '1', '', 'd.m.Y', 'H:i', 1, '', '', '', '', '1');
-- Ende der Tabelle 'users'

-- Tabellenstruktur für Tabelle `banned_ips`
CREATE TABLE IF NOT EXISTS `banned_ips` (
  `banID` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(45) NOT NULL,
  `deltime` datetime NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `userID` int(11) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`banID`),
  KEY `userID` (`userID`),
  CONSTRAINT `fk_userID` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- Ende der Tabelle 'banned_ips'

-- Tabellenstruktur für Tabelle `contact` 
CREATE TABLE IF NOT EXISTS `failed_login_attempts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `ip` varchar(45) NOT NULL,
  `attempt_time` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('failed','blocked') DEFAULT 'failed',
  `reason` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userID` (`userID`),
  CONSTRAINT `fk_failed_login_user` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- Ende der Tabelle 'failed_login_attempts'

-- Tabellenstruktur für Tabelle `user_roles`
CREATE TABLE IF NOT EXISTS `user_roles` (
  `roleID` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`roleID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=17;

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

-- Tabellenstruktur für Tabelle `user_role_assignments`
CREATE TABLE IF NOT EXISTS `user_role_assignments` (
  `assignmentID` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `roleID` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `assigned_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`assignmentID`),
  KEY `roleID` (`roleID`),
  KEY `user_role_assignments` (`userID`) USING BTREE,
  CONSTRAINT `user_role_assignments_admin` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE,
  CONSTRAINT `user_role_assignments_role` FOREIGN KEY (`roleID`) REFERENCES `user_roles` (`roleID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

INSERT INTO `user_role_assignments` (`userID`, `roleID`, `created_at`, `assigned_at`) 
VALUES (1, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);
-- Ende der Tabelle 'user_role_assignments'

-- Tabellenstruktur für Tabelle `user_sessions`
CREATE TABLE IF NOT EXISTS `user_sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` varchar(255) NOT NULL,
  `userID` int(11) NOT NULL,
  `user_ip` varchar(45) DEFAULT NULL,
  `session_data` text DEFAULT NULL,
  `browser` text DEFAULT NULL,
  `last_activity` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_session` (`session_id`),
  KEY `userID` (`userID`),
  CONSTRAINT `fk_sessions_userID` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;
-- Ende der Tabelle 'user_sessions'

-- Tabellenstruktur für Tabelle `admin_access_rights`
CREATE TABLE IF NOT EXISTS `admin_access_rights` (
    `adminID` INT(11) NOT NULL,
    `moduleID` INT(11) NOT NULL,
    `access_level` INT(11) DEFAULT 0,
    PRIMARY KEY (`adminID`, `moduleID`),
    FOREIGN KEY (`adminID`) REFERENCES `users`(`userID`)
) ENGINE=InnoDB;
-- Ende der Tabelle 'admin_access_rights'

-- Tabellenstruktur für Tabelle `user_role_admin_navi_rights`
CREATE TABLE IF NOT EXISTS `user_role_admin_navi_rights` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `roleID` int(11) NOT NULL,
  `type` enum('link','category') NOT NULL,
  `modulname` varchar(255) NOT NULL,
  `accessID` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_access` (`roleID`, `type`, `modulname`),
  CONSTRAINT `user_role_admin_navi_rights_ibfk_1` FOREIGN KEY (`roleID`) REFERENCES `user_roles` (`roleID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=42;

INSERT INTO `user_role_admin_navi_rights` (`id`, `roleID`, `type`, `modulname`, `accessID`) VALUES
(1, 1, 'link', 'ac_overview', 1),
(2, 1, 'link', 'ac_page_statistic', 2),
(3, 1, 'link', 'ac_visitor_statistic', 3),
(4, 1, 'link', 'ac_settings', 4),
(5, 1, 'link', 'ac_dashboard_navigation', 5),
(6, 1, 'link', 'ac_email', 6),
(7, 1, 'link', 'ac_contact', 7),
(8, 1, 'link', 'ac_modrewrite', 8),
(9, 1, 'link', 'ac_database', 9),
(10, 1, 'link', 'ac_update', 10),
(11, 1, 'link', 'ac_users', 11),
(12, 1, 'link', 'ac_spam_forum', 12),
(13, 1, 'link', 'ac_spam_user', 13),
(14, 1, 'link', 'ac_spam_multi', 14),
(15, 1, 'link', 'ac_spam_banned_ips', 15),
(18, 1, 'link', 'ac_themes', 18),
(19, 1, 'link', 'ac_startpage', 20),
(20, 1, 'link', 'ac_static', 21),
(21, 1, 'link', 'ac_imprint', 22),
(22, 1, 'link', 'ac_privacy_policy', 23),
(23, 1, 'link', 'ac_plugin_manager', 24),
(24, 1, 'link', 'ac_plugin_installer', 25),
(25, 1, 'link', 'ac_editlang', 26),
(26, 1, 'link', 'footer', 27),
(27, 1, 'link', 'ac_admin_security', 28),
(28, 1, 'link', 'ac_user_roles', 29),
(29, 1, 'category', 'cat_webinfo', 1),
(30, 1, 'category', 'cat_spam', 2),
(31, 1, 'category', 'cat_admin', 3),
(32, 1, 'category', 'cat_team', 4),
(33, 1, 'category', 'cat_theme', 5),
(34, 1, 'category', 'cat_plugin', 6),
(35, 1, 'category', 'cat_web_content', 7),
(36, 1, 'category', 'cat_gallery', 8),
(37, 1, 'category', 'cat_slider', 9),
(38, 1, 'category', 'cat_game', 10),
(39, 1, 'category', 'cat_social', 11),
(40, 1, 'category', 'cat_link', 12),
(41, 1, 'link', 'role_permissions', 31);
-- Ende der Tabelle 'user_role_admin_navi_rights'


-- Tabellenstruktur für Tabelle `user_role_permissions`
CREATE TABLE IF NOT EXISTS `user_role_permissions` (
  `roleID` int(11) NOT NULL,
  `permission_key` varchar(50) NOT NULL,
  PRIMARY KEY (`roleID`, `permission_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `user_role_permissions` (`roleID`, `permission_key`) VALUES
(1, 'ckeditor_full'),
(1, 'edit_articles'),
(1, 'manage_users'),
(1, 'view_dashboard_only'),
(2, 'edit_articles'),
(2, 'manage_users'),
(3, 'view_dashboard_only');
-- Ende der Tabelle 'user_role_permissions'

-- Tabellenstruktur für Tabelle `user_register_attempts`
CREATE TABLE IF NOT EXISTS `user_register_attempts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `attempt_time` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('success','failed') NOT NULL DEFAULT 'failed',
  `reason` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ip_address` (`ip_address`),
  KEY `attempt_time` (`attempt_time`),
  KEY `username` (`username`),
  KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- Ende der Tabelle 'user_register_attempts'

-- Tabellenstruktur für Tabelle `user_groups`
CREATE TABLE IF NOT EXISTS `user_groups` (
  `usgID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `userID` int(11) NOT NULL DEFAULT '0',
  `news` int(1) NOT NULL DEFAULT '0',
  `news_writer` int(1) NOT NULL,
  `newsletter` int(1) NOT NULL DEFAULT '0',
  `polls` int(1) NOT NULL DEFAULT '0',
  `forum` int(1) NOT NULL DEFAULT '0',
  `moderator` int(1) NOT NULL DEFAULT '0',
  `clanwars` int(1) NOT NULL DEFAULT '0',
  `feedback` int(1) NOT NULL DEFAULT '0',
  `user` int(1) NOT NULL DEFAULT '0',
  `page` int(1) NOT NULL DEFAULT '0',
  `files` int(1) NOT NULL DEFAULT '0',
  `cash` int(1) NOT NULL DEFAULT '0',
  `gallery` int(1) NOT NULL,
  `super` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB;

INSERT INTO `user_groups` (`usgID`, `userID`, `news`, `news_writer`, `newsletter`, `polls`, `forum`, `moderator`, `clanwars`, `feedback`, `user`, `page`, `files`, `cash`, `gallery`, `super`) VALUES
(1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);
-- Ende der Tabelle 'user_groups'

-- Tabellenstruktur für Tabelle `user_forum_groups`
CREATE TABLE IF NOT EXISTS `user_forum_groups` (
  `usfgID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `userID` int(11) NOT NULL DEFAULT '0',
  `group_flag` int(1) NOT NULL
) ENGINE=InnoDB;

INSERT INTO `user_forum_groups` (`usfgID`, `userID`, `group_flag`) VALUES
(1, 1, 1); 
-- Ende der Tabelle 'user_forum_groups'

-- Tabellenstruktur für Tabelle `user_username`
CREATE TABLE IF NOT EXISTS `user_username` (
  `userID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `username` varchar(255) NOT NULL
) ENGINE=InnoDB;

INSERT INTO `user_username` (`userID`, `username`) VALUES (1, '{{adminuser}}');
-- Ende der Tabelle 'user_username'

-- Tabellenstruktur für Tabelle `user_visitors`
CREATE TABLE IF NOT EXISTS `user_visitors` (
  `visitID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `userID` int(11) NOT NULL DEFAULT '0',
  `visitor` int(11) NOT NULL DEFAULT '0',
  `date` int(14) NOT NULL DEFAULT '0'
) ENGINE=InnoDB;
-- Ende der Tabelle 'user_visitors'

-- Tabellenstruktur für Tabelle `whoisonline`
CREATE TABLE IF NOT EXISTS `whoisonline` (
  `time` int(14) NOT NULL DEFAULT '0',
  `ip` varchar(20) NOT NULL,
  `userID` int(11) NOT NULL DEFAULT '0',
  `site` varchar(255) NOT NULL
) ENGINE=InnoDB;
-- Ende der Tabelle 'whoisonline'

-- Tabellenstruktur für Tabelle `whowasonline`
CREATE TABLE IF NOT EXISTS `whowasonline` (
  `time` int(14) NOT NULL DEFAULT '0',
  `ip` varchar(20) NOT NULL,
  `userID` int(11) NOT NULL DEFAULT '0',
  `site` varchar(255) NOT NULL
) ENGINE=InnoDB;
-- Ende der Tabelle 'whowasonline'
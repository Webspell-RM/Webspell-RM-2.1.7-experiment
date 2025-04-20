-- Webspell-RM 2.1.7 - Datenbankbasis



-- Tabelle für Inhalte, News und Regeln
CREATE TABLE IF NOT EXISTS `content` (
    `contentID` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `content_text` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;
-- Ende der Tabelle 'content'

-- Beispiel für Log-Tabelle
CREATE TABLE IF NOT EXISTS `logs` (
    `logID` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `log_type` VARCHAR(255),
    `log_message` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
-- Ende der Tabelle 'logs'

-- Tabelle für die Verwaltung der Spracheinstellungen
CREATE TABLE IF NOT EXISTS `languages` (
    `languageID` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `language_name` VARCHAR(255) NOT NULL,
    `language_code` VARCHAR(10) NOT NULL,
    `is_default` TINYINT(1) DEFAULT 0
) ENGINE=InnoDB;
-- Ende der Tabelle 'languages'


-- Beispiel für Log-Tabelle
CREATE TABLE IF NOT EXISTS `logs` (
    `logID` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `log_type` VARCHAR(255),
    `log_message` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
-- Ende der Tabelle 'logs'



CREATE TABLE IF NOT EXISTS `backups` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `filename` text NOT NULL,
  `description` text,
  `createdby` int(11) NOT NULL DEFAULT '0',
  `createdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;




CREATE TABLE IF NOT EXISTS `banned_ips` (
  `banID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `ip` varchar(255) NOT NULL,
  `deltime` int(15) NOT NULL,
  `reason` varchar(255) DEFAULT NULL
) ENGINE=InnoDB;



CREATE TABLE IF NOT EXISTS `captcha` (
  `hash` VARCHAR(255) NOT NULL,
  `captcha` INT(11) NOT NULL DEFAULT '0',
  `deltime` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`hash`)
) ENGINE=InnoDB;



CREATE TABLE IF NOT EXISTS `contact` (
  `contactID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(100) NOT NULL,
  `email` varchar(200) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB;

INSERT INTO `contact` (`name`, `email`, `sort`) VALUES
('Administrator', '{{adminmail}}', 1);



CREATE TABLE IF NOT EXISTS `cookies` (
  `userID` int(11) NOT NULL,
  `cookie` binary(64) NOT NULL,
  `expiration` int(14) NOT NULL,
  PRIMARY KEY (`userID`,`cookie`),
  KEY `expiration` (`expiration`)
) ENGINE=InnoDB;



CREATE TABLE IF NOT EXISTS `counter` (
  `hits` int(20) NOT NULL DEFAULT '0',
  `online` int(14) NOT NULL DEFAULT '0',
  `maxonline` int(11) NOT NULL
) ENGINE=InnoDB;

INSERT INTO `counter` (`hits`, `online`, `maxonline`) 
VALUES (1, UNIX_TIMESTAMP(), 1);

CREATE TABLE IF NOT EXISTS `counter_iplist` (
  `date` DATE NOT NULL,
  `ip` VARCHAR(45) NOT NULL,
  `deleted` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`date`, `ip`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `counter_stats` (
  `date` DATE NOT NULL,
  `count` INT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`date`)
) ENGINE=InnoDB;


  
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


  
CREATE TABLE IF NOT EXISTS `failed_login_attempts` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `ip` varchar(255) NOT NULL,
  `wrong` int(2) default '0'
) ENGINE=InnoDB;




CREATE TABLE IF NOT EXISTS `lock` (
  `time` int(11) NOT NULL,
  `reason` text NOT NULL
) ENGINE=InnoDB;


-- Tabellenstruktur für Tabelle `navigation_dashboard_categories`
CREATE TABLE `navigation_dashboard_categories` (
  `catID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `modulname` varchar(255) NOT NULL,
  `fa_name` varchar(255) NOT NULL DEFAULT '',
  `sort` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`catID`),
  UNIQUE KEY `modulname` (`modulname`, `catID`)
) ENGINE=InnoDB;


INSERT INTO `navigation_dashboard_categories` (`catID`, `name`, `modulname`, `fa_name`, `sort`) VALUES
(1, 'Webseiten Info - Einstellungen', 'cat_web_info', 'bi bi-gear', 1),
(2, 'Spam', 'cat_spam', 'bi bi-exclamation-triangle', 2),
(3, 'Benutzer Administration', 'cat_user', 'bi bi-person', 3),
(4, 'Team Verwaltung', 'cat_team', 'bi bi-people', 4),
(5, 'Template - Layout', 'cat_temp', 'bi bi-layout-text-window-reverse', 5),
(6, 'Plugin & Widget Verwaltung', 'cat_pwv', 'bi bi-puzzle', 6),
(7, 'Webseiteninhalte', 'cat_web_content', 'bi bi-card-checklist', 7),
(8, 'Grafik - Video - Projekte', 'cat_grafik', 'bi bi-image', 8),
(9, 'Header - Slider', 'cat_header', 'bi bi-fast-forward-btn', 9),
(10, 'Game - Voice Server Tools', 'cat_game', 'bi bi-controller', 10),
(11, 'Social Media', 'cat_social', 'bi bi-steam', 11),
(12, 'Links - Download - Sponsoren', 'cat_links', 'bi bi-link', 12);

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
) ENGINE=InnoDB;



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
(1, 'HAUPT', '#', 1, 1, 1, 1),
(2, 'TEAM', '#', 1, 2, 1, 1),
(3, 'GEMEINSCHAFT', '#', 1, 3, 1, 1),
(4, 'MEDIEN', '#', 1, 4, 1, 1),
(5, 'SOCIAL', '#', 1, 5, 1, 1),
(6, 'SONSTIGES', '#', 1, 6, 1, 1); 

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
(1, 6, 'Kontakt', 'contact', 'index.php?site=contact', 1, 1, 'default'),
(2, 6, 'Datenschutz-Bestimmungen', 'privacy_policy', 'index.php?site=privacy_policy', 2, 1, 'default'),
(3, 6, 'Impressum', 'imprint', 'index.php?site=imprint', 3, 1, 'default');


-- Tabelle erstellen
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

-- Daten einfügen
INSERT INTO `plugins_footer` (`footID`, `banner`, `about`, `name`, `strasse`, `email`, `telefon`, `since`, `linkname1`, `navilink1`, `linkname2`, `navilink2`, `linkname3`, `navilink3`, `linkname4`, `navilink4`, `linkname5`, `navilink5`, `linkname6`, `navilink6`, `linkname7`, `navilink7`, `linkname8`, `navilink8`, `linkname9`, `navilink9`, `linkname10`, `navilink10`, `social_text`, `social_link_name1`, `social_link1`, `social_link_name2`, `social_link2`, `social_link_name3`, `social_link3`, `copyright_link_name1`, `copyright_link1`, `copyright_link_name2`, `copyright_link2`, `copyright_link_name3`, `copyright_link3`, `copyright_link_name4`, `copyright_link4`, `copyright_link_name5`, `copyright_link5`, `widget_left`, `widget_center`, `widget_right`, `widgetdatei_left`, `widgetdatei_center`, `widgetdatei_right`) VALUES
(1, '', 'Team Clanname ist eine 1999 gegründete deutsche E-Sport-Organisation...', 'Hans Mustermann', 'Musterhausen 11, Germany', 'mail@Clanname-esport.de', '(123) 456-7890', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'Impressum', 'index.php?site=imprint', 'Datenschutz', 'index.php?site=privacy_policy', 'Kontakt', 'index.php?site=contact', 'Counter', 'index.php?site=counter', '', '', 'blog', 'about_us', 'userlist', '', '', '');

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

CREATE TABLE IF NOT EXISTS `settings_imprint` (
  `imprintID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `imprint` text NOT NULL,
  `disclaimer_text` text NOT NULL
) ENGINE=InnoDB;

INSERT INTO `settings_imprint` (`imprintID`, `imprint`, `disclaimer_text`) VALUES
(1, 'Impressum in deutscher Sprache.<br /><span style=\"color:#c0392b\"><strong>Konfigurieren Sie bitte Ihr Impressum!</strong></span>', 'Haftungsausschluss in deutscher Sprache.<br /><span style=\"color:#c0392b\"><strong>Konfigurieren Sie bitte Ihr Haftungsausschluss! </strong></span>');

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
) ENGINE=InnoDB;

INSERT INTO `settings_plugins` (`pluginID`, `name`, `modulname`, `info`, `admin_file`, `activate`, `author`, `website`, `index_link`, `hiddenfiles`, `version`, `path`, `status_display`, `plugin_display`, `widget_display`, `delete_display`, `sidebar`) VALUES
(1, 'Startpage', 'startpage', 'Kein Plugin. Bestandteil vom System!!!', '', 1, '', '', '', '', '', '', 0, 0, 1, 0, 'full_activated'),
(2, 'Privacy Policy', 'privacy_policy', 'Kein Plugin. Bestandteil vom System!!!', '', 1, '', '', 'privacy_policy', '', '', '', 0, 0, 1, 0, 'deactivated'),
(3, 'Imprint', 'imprint', 'Kein Plugin. Bestandteil vom System!!!', '', 1, '', '', 'imprint', '', '', '', 0, 0, 1, 0, 'deactivated'),
(4, 'Static', 'static', 'Kein Plugin. Bestandteil vom System!!!', '', 1, '', '', 'static', '', '', '', 1, 0, 1, 0, 'deactivated'),
(5, 'Error_404', 'error_404', 'Kein Plugin. Bestandteil vom System!!!', '', 1, '', '', 'error_404', '', '', '', 1, 0, 1, 0, 'deactivated'),
(6, 'Profile', 'profile', 'Kein Plugin. Bestandteil vom System!!!', '', 1, '', '', 'profile', '', '', '', 1, 0, 1, 0, 'deactivated'),
(7, 'Login', 'login', 'Kein Plugin. Bestandteil vom System!!!', '', 1, '', '', 'login', '', '', '', 1, 0, 1, 0, 'deactivated'),
(8, 'Lost Password', 'lostpassword', 'Kein Plugin. Bestandteil vom System!!!', '', 1, '', '', 'lostpassword', '', '', '', 1, 0, 1, 0, 'deactivated'),
(9, 'Contact', 'contact', 'Kein Plugin. Bestandteil vom System!!!', '', 1, '', '', 'contact', '', '', '', 1, 0, 1, 0, 'deactivated'),
(10, 'Register', 'register', 'Kein Plugin. Bestandteil vom System!!!', '', 1, '', '', 'register', '', '', '', 1, 0, 1, 0, 'deactivated'),
(11, 'My Profile', 'myprofile', 'Kein Plugin. Bestandteil vom System!!!', '', 1, '', '', 'myprofile', '', '', '', 1, 0, 1, 0, 'deactivated'),
(12, 'Report', 'report', 'Kein Plugin. Bestandteil vom System!!!', '', 1, '', '', 'report', '', '', '', 1, 0, 1, 0, 'deactivated'),
(13, 'Navigation', 'navigation', 'Mit diesem Plugin könnt ihr euch die Navigation anzeigen lassen.', '', 1, 'T-Seven', 'https://webspell-rm.de', '', '', '0.3', 'includes/plugins/navigation/', 1, 1, 0, 0, 'deactivated'),
(14, 'Footer', 'footer', 'Mit diesem Plugin könnt ihr einen neuen Footer anzeigen lassen.', 'admin_footer', 1, 'T-Seven', 'https://webspell-rm.de', '', '', '0.1', 'includes/plugins/footer/', 1, 1, 0, 0, 'deactivated');


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


CREATE TABLE IF NOT EXISTS `settings_privacy_policy` (
  `privacy_policyID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `date` int(14) NOT NULL,
  `privacy_policy_text` mediumtext NOT NULL
) ENGINE=InnoDB;

INSERT INTO `settings_privacy_policy` (`privacy_policyID`, `date`, `privacy_policy_text`) VALUES
(1, 1576689811, 'Datenschutz-Bestimmungen in deutscher Sprache.<br /><span style="color:#c0392b"><strong>Konfigurieren Sie bitte Ihre Datenschutz-Bestimmungen!</strong></span>');


CREATE TABLE IF NOT EXISTS `settings_recaptcha` (
  `activated` int(11) NOT NULL DEFAULT '0',
  `webkey` varchar(255) NOT NULL,
  `seckey` varchar(255) NOT NULL
) ENGINE=InnoDB;

INSERT INTO `settings_recaptcha` (`activated`, `webkey`, `seckey`) VALUES
(0, 'Web-Key', 'Sec-Key');


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


CREATE TABLE IF NOT EXISTS `settings_startpage` (
  `pageID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` varchar(255) NOT NULL,
  `startpage_text` longtext NOT NULL,
  `date` int(14) NOT NULL,
  `displayed` varchar(255) DEFAULT '0'
) ENGINE=InnoDB;


CREATE TABLE IF NOT EXISTS `settings_static` (
  `staticID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` varchar(255) NOT NULL,
  `accesslevel` int(1) NOT NULL,
  `content` text NOT NULL,
  `date` int(14) NOT NULL,
  `displayed` int(1) DEFAULT '0'
) ENGINE=InnoDB;


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

CREATE TABLE IF NOT EXISTS `tags` (
  `rel` varchar(255) NOT NULL,
  `ID` int(11) NOT NULL,
  `tag` varchar(255) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `users` (
  `userID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `registerdate` int(14) NOT NULL DEFAULT '0',
  `lastlogin` int(14) NOT NULL DEFAULT '0',
  `password` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `password_pepper` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_hide` int(1) NOT NULL DEFAULT '1',
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
  `pmgot` int(11) NOT NULL DEFAULT '0',
  `pmsent` int(11) NOT NULL DEFAULT '0',
  `visits` int(11) NOT NULL DEFAULT '0',
  `banned` varchar(255) DEFAULT NULL,
  `ban_reason` varchar(255) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `topics` text NOT NULL,
  `articles` text NOT NULL,
  `demos` text NOT NULL,
  `files` text NOT NULL,
  `gallery_pictures` text NOT NULL,
  `special_rank` int(11) DEFAULT '0',
  `mailonpm` int(1) NOT NULL DEFAULT '0',
  `userdescription` text NOT NULL,
  `activated` varchar(255) NOT NULL DEFAULT '1',
  `language` varchar(2) NOT NULL,
  `date_format` varchar(255) NOT NULL DEFAULT 'd.m.Y',
  `time_format` varchar(255) NOT NULL DEFAULT 'H:i',
  `newsletter` int(1) DEFAULT '1',
  `links` text NOT NULL,
  `videos` text NOT NULL,
  `games` text NOT NULL,
  `projectlist` text NOT NULL,
  `roleID` INT(11) DEFAULT 1
) ENGINE=InnoDB;


INSERT INTO `users` (`userID`, `registerdate`, `lastlogin`, `password`, `password_hash`, `password_pepper`, `username`, `email`, `email_hide`, `email_change`, `email_activate`, `firstname`, `lastname`, `gender`, `town`, `birthday`, `facebook`, `twitter`, `twitch`, `steam`, `instagram`, `youtube`, `discord`, `avatar`, `usertext`, `userpic`, `homepage`, `about`, `pmgot`, `pmsent`, `visits`, `banned`, `ban_reason`, `ip`, `topics`, `articles`, `demos`, `files`, `gallery_pictures`, `special_rank`, `mailonpm`, `userdescription`, `activated`, `language`, `date_format`, `time_format`, `newsletter`, `links`, `videos`, `games`, `projectlist`, `roleID`) VALUES
(1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), '{{adminpass}}', '{{adminpass}}', 'new_pepper', '{{adminuser}}', '{{adminmail}}', 1, '', '', '', '', 'select_gender', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, NULL, '', '', '|', '', '', '', '', 0, 0, '', '1', '', 'd.m.Y', 'H:i', 1, '', '', '', '', '1');


-- Tabelle für Benutzerrollen
CREATE TABLE `user_roles` (
  `roleID` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`roleID`)
) ENGINE=InnoDB;

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
-- Tabelle für Admin und Rechte
CREATE TABLE IF NOT EXISTS `admin_access_rights` (
    `adminID` INT(11) NOT NULL,
    `moduleID` INT(11) NOT NULL,
    `access_level` INT(11) DEFAULT 0,
    PRIMARY KEY (`adminID`, `moduleID`),
    FOREIGN KEY (`adminID`) REFERENCES `users`(`userID`)
) ENGINE=InnoDB;
-- Ende der Tabelle 'admin_access_rights'


CREATE TABLE `user_role_admin_navi_rights` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `roleID` int(11) NOT NULL,
  `type` enum('link','category') NOT NULL,
  `modulname` varchar(255) NOT NULL,
  `accessID` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_access` (`roleID`,`type`,`modulname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



-- Tabelle für Admin und Rechte
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
-- Ende der Tabelle 'admin_access_rights'


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

CREATE TABLE IF NOT EXISTS `user_forum_groups` (
  `usfgID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `userID` int(11) NOT NULL DEFAULT '0',
  `group_flag` int(1) NOT NULL
) ENGINE=InnoDB;

INSERT INTO `user_forum_groups` (`usfgID`, `userID`, `group_flag`) VALUES
(1, 1, 1); 


CREATE TABLE IF NOT EXISTS `user_username` (
  `userID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `username` varchar(255) NOT NULL
) ENGINE=InnoDB;

INSERT INTO `user_username` (`userID`, `username`) VALUES (1, '{{adminuser}}');

CREATE TABLE IF NOT EXISTS `user_visitors` (
  `visitID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `userID` int(11) NOT NULL DEFAULT '0',
  `visitor` int(11) NOT NULL DEFAULT '0',
  `date` int(14) NOT NULL DEFAULT '0'
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `whoisonline` (
  `time` int(14) NOT NULL DEFAULT '0',
  `ip` varchar(20) NOT NULL,
  `userID` int(11) NOT NULL DEFAULT '0',
  `site` varchar(255) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `whowasonline` (
  `time` int(14) NOT NULL DEFAULT '0',
  `ip` varchar(20) NOT NULL,
  `userID` int(11) NOT NULL DEFAULT '0',
  `site` varchar(255) NOT NULL
) ENGINE=InnoDB;

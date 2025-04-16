-- phpMyAdmin SQL Dump
-- version 4.9.11
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Erstellungszeit: 16. Apr 2025 um 20:59
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
('cup', 32, 4, '{[de]}Turnier{[en]}Tournament{[it]}Coppa/Torneo', 'admincenter.php?site=admin_cup', 1),
('fightus', 33, 4, '{[de]}Fightus{[en]}Fightus{[it]}Sfide', 'admincenter.php?site=admin_fightus', 1),
('games_pic', 35, 4, '{[de]}Games Pic{[en]}Games Pic{[it]}Immagini Giochi', 'admincenter.php?site=admin_games_pic', 1),
('news_manager', 36, 7, '{[de]}News{[en]}News{[it]}Notizie', 'admincenter.php?site=admin_news_manager', 1),
('forum', 37, 7, '{[de]}Forum{[en]}Forum{[it]}Forum', 'admincenter.php?site=admin_forum', 1),
('files', 38, 12, '{[de]}Download{[en]}Download{[it]}Download', 'admincenter.php?site=admin_files', 1),
('carousel', 39, 9, '{[de]}Carousel{[en]}Carousel{[it]}Carosello Immagini', 'admincenter.php?site=admin_carousel', 1),
('gallery', 40, 8, '{[de]}Mediathek{[en]}Media Library{[it]}Biblioteca multimediale', 'admincenter.php?site=admin_gallery', 1),
('shoutbox', 42, 7, '{[de]}Shoutbox{[en]}shoutbox{[pl]}shoutbox{[it]}Messaggi Shoutbox', 'admincenter.php?site=admin_shoutbox', 1),
('about_us', 43, 4, '{[de]}About Us{[en]}About Us{[it]}Chi Siamo', 'admincenter.php?site=admin_about_us', 1),
('sponsors', 44, 12, '{[de]}Sponsoren{[en]}Sponsors{[it]}Sponsor', 'admincenter.php?site=admin_sponsors', 1),
('servers', 46, 10, '{[de]}Server{[en]}Servers{[it]}Server', 'admincenter.php?site=admin_servers', 1),
('streams', 52, 11, '{[de]}Streams{[en]}Streams{[it]}Stream', 'admincenter.php?site=admin_streams', 1),
('socialmedia', 53, 11, '{[de]}Social Media{[en]}Social Media{[it]}Social Media', 'admincenter.php?site=admin_socialmedia', 1),
('userlist', 54, 3, '{[de]}User Liste{[en]}User List{[it]}Lista Utenti', 'admincenter.php?site=admin_reg_userlist', 1),
('userlist', 55, 3, '{[de]}Letzte Anmeldung{[en]}Last Login{[it]}Ultimi Login', 'admincenter.php?site=admin_lastlogin', 2),
('squads', 56, 4, '{[de]}Squads{[en]}Squads{[it]}Squadre', 'admincenter.php?site=admin_squads', 1),
('clan_rules', 61, 4, '{[de]}Clan Regeln{[en]}Clan Rules{[it]}Regole del Clan', 'admincenter.php?site=admin_clan_rules', 1),
('server_rules', 62, 10, '{[de]}Server Regeln{[en]}Server Rules{[it]}Regole Server', 'admincenter.php?site=admin_server_rules', 1),
('ac_user_roles', 64, 3, 'User Management', 'admincenter.php?site=user_roles', 5);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `navigation_dashboard_links`
--
ALTER TABLE `navigation_dashboard_links`
  ADD PRIMARY KEY (`linkID`),
  ADD UNIQUE KEY `modulname` (`modulname`,`linkID`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `navigation_dashboard_links`
--
ALTER TABLE `navigation_dashboard_links`
  MODIFY `linkID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

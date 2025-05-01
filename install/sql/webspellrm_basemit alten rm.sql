-- phpMyAdmin SQL Dump
-- version 4.9.11
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Erstellungszeit: 25. Apr 2025 um 16:04
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
-- Datenbank: `d038d957`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `navigation_dashboard_links`
--

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
  MODIFY `linkID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

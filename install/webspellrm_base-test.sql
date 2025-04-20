-- Webspell-RM 2.1.7 - Datenbankbasis

-- Tabellen für Benutzer und Admin-Zuweisungen
CREATE TABLE IF NOT EXISTS `users` (
    `userID` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `roleID` INT(11) DEFAULT 1,
    `last_login` DATETIME DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;
-- Ende der Tabelle 'users'

-- Tabelle für Benutzerrollen
CREATE TABLE IF NOT EXISTS `user_roles` (
    `roleID` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `role_name` VARCHAR(255) NOT NULL
) ENGINE=InnoDB;
-- Ende der Tabelle 'user_roles'

-- Tabelle für Benutzerrollen-Zuweisungen
CREATE TABLE IF NOT EXISTS `user_role_assignments` (
    `userID` INT(11) NOT NULL,
    `roleID` INT(11) NOT NULL,
    PRIMARY KEY (`userID`, `roleID`),
    FOREIGN KEY (`userID`) REFERENCES `users`(`userID`),
    FOREIGN KEY (`roleID`) REFERENCES `user_roles`(`roleID`)
) ENGINE=InnoDB;
-- Ende der Tabelle 'user_role_assignments'

-- Tabelle für Einstellungen und Konfiguration
CREATE TABLE IF NOT EXISTS `settings` (
    `setting_id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `setting_name` VARCHAR(255) NOT NULL,
    `setting_value` TEXT
) ENGINE=InnoDB;
-- Ende der Tabelle 'settings'

-- Tabelle für Admin und Rechte
CREATE TABLE IF NOT EXISTS `admin_access_rights` (
    `adminID` INT(11) NOT NULL,
    `moduleID` INT(11) NOT NULL,
    `access_level` INT(11) DEFAULT 0,
    PRIMARY KEY (`adminID`, `moduleID`),
    FOREIGN KEY (`adminID`) REFERENCES `users`(`userID`)
) ENGINE=InnoDB;
-- Ende der Tabelle 'admin_access_rights'

-- Beispiel für Navigationslinks und Module
CREATE TABLE IF NOT EXISTS `navigation_dashboard_links` (
    `linkID` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `module_name` VARCHAR(255) NOT NULL,
    `module_url` VARCHAR(255) NOT NULL,
    `access_level` INT(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB;
-- Ende der Tabelle 'navigation_dashboard_links'

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

-- Weitere Tabellen könnten für das Forum, das Clan-System, Nachrichten etc. hinzugefügt werden


#$transaction = new Transaction($_database);
#global $adminname;
#global $adminpassword;
#global $adminmail;
#global $url;
    
#$new_pepper = Gen_PasswordPepper();
#$adminhash = password_hash($adminpassword.$new_pepper,PASSWORD_BCRYPT,array('cost'=>12));


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
  `hash` VARCHAR(255) NOT NULL DEFAULT '',
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



/*CREATE TABLE IF NOT EXISTS `cookies` (
  `userID` int(11) NOT NULL,
  `cookie` binary(64) NOT NULL,
  `expiration` int(14) NOT NULL,
  PRIMARY KEY (`userID`,`cookie`),
  KEY `expiration` (`expiration`)
) ENGINE=InnoDB;*/



CREATE TABLE IF NOT EXISTS `counter` (
  `hits` int(20) NOT NULL DEFAULT '0',
  `online` int(14) NOT NULL DEFAULT '0',
  `maxonline` int(11) NOT NULL
) ENGINE=InnoDB;

#INSERT INTO `counter` (`hits`, `online`, `maxonline`) VALUES (1, '".time()."', 1);

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

INSERT INTO `email` (`emailID`, `user`, `password`, `host`, `port`, `debug`, `auth`, `html`, `smtp`, `secure`) VALUES
(1, '', '', '', 25, 0, 0, 1, 0, 0);


  
CREATE TABLE IF NOT EXISTS `failed_login_attempts` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `ip` varchar(255) NOT NULL,
  `wrong` int(2) default '0'
) ENGINE=InnoDB;




CREATE TABLE IF NOT EXISTS `lock` (
  `time` int(11) NOT NULL,
  `reason` text NOT NULL
) ENGINE=InnoDB;



CREATE TABLE IF NOT EXISTS `modrewrite` (
  `ruleID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `regex` text NOT NULL,
  `link` text NOT NULL,
  `fields` text NOT NULL,
  `replace_regex` text NOT NULL,
  `replace_result` text NOT NULL,
  `rebuild_regex` text NOT NULL,
  `rebuild_result` text NOT NULL
) ENGINE=InnoDB;










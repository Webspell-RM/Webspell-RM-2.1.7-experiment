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

INSERT INTO `users` (`username`, `password`, `email`, `roleID`, `last_login`)
VALUES ('{{adminuser}}', '{{adminpass}}', '{{adminmail}}', 1, NULL);


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

-- Weitere Tabellen könnten für das Forum, das Clan-System, Nachrichten etc. hinzugefügt werden

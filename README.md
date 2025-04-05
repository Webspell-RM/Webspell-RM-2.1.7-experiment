![Demo](https://www.webspell-rm.de/includes/plugins/pic_update/images/179.png)
>											  
>### WEBSPELL-RM - Release: 2.1.6
>						   
>### WEBSPELL-RM - Release: 2.1.6 - https://www.webspell-rm.de
>
>### WIKI WEBSPELL-RM - Release: 2.1.6 - https://www.webspell-rm.de/wiki.html
>
```
/**
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 *                  Webspell-RM      /                        /   /                                          *
 *                  -----------__---/__---__------__----__---/---/-----__---- _  _ -                         *
 *                   | /| /  /___) /   ) (_ `   /   ) /___) /   / __  /     /  /  /                          *
 *                  _|/_|/__(___ _(___/_(__)___/___/_(___ _/___/_____/_____/__/__/_                          *
 *                               Free Content / Management System                                            *
 *                                           /                                                               *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 * @version         webspell-rm                                                                              *
 *                                                                                                           *
 * @copyright       2018-2025 by webspell-rm.de                                                              *
 * @support         For Support, Plugins, Templates and the Full Script visit webspell-rm.de                 *
 * @website         <https://www.webspell-rm.de>                                                             *
 * @forum           <https://www.webspell-rm.de/forum.html>                                                  *
 * @wiki            <https://www.webspell-rm.de/wiki.html>                                                   *
 *                                                                                                           *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 * @license         Script runs under the GNU GENERAL PUBLIC LICENCE                                         *
 *                  It's NOT allowed to remove this copyright-tag                                            *
 *                  <http://www.fsf.org/licensing/licenses/gpl.html>                                         *
 *                                                                                                           *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 * @author          Code based on WebSPELL Clanpackage (Michael Gruber - webspell.at)                        *
 * @copyright       2005-2011 by webspell.org / webspell.info                                                *
 *                                                                                                           *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
*/
```

# 📁 webSPELL-RM 2.1.6 – Ordnerstruktur
````
/webspell-rm/
├── admin/                       # Adminbereich
│   ├── css/                     # CSS für das Backend
│   ├── images/                  # Bilder für das Backend
│   ├── img/colorpicker/         # Bootstrap Colorpicker
│   ├── js/                      # JS-Funktionen für Adminbereich
│   ├── templates/               # Admin-Templates
│   ├── languages/               # Sprachen für Admin
│   │   ├── de/
│   │   ├── en/
│   │   └── it/
│   └── login/                   # Admin-Login-System
│
├── components/                  # Wiederverwendbare UI-Komponenten
│
├── images/                      # Öffentliche Bilder
│   ├── avatars/                 # Nutzer-Avatare
│   ├── languages/               # Sprach-Flaggen
│   └── userpics/                # Nutzerbilder
│
├── includes/                    # Systemlogik
│   ├── classes/                 # Zentrale PHP-Klassen
│   ├── config/                  # Konfiguration (z. B. DB)
│   ├── functions/               # Hilfsfunktionen
│   ├── langs/                   # Sprachstrings
│   ├── modules/                 # Erweiterbare Funktions-Module
│   └── system/                  # Systemfunktionen und Loader
│
├── install/                     # Installer
│   ├── css/
│   ├── images/
│   ├── installer/
│   ├── languages/
│   └── templates/
│
├── languages/                   # Sprachdateien fürs Frontend
│   ├── de/
│   ├── en/
│   └── it/
│
├── system/                      # Plugins, Widgets, SQL-Importe
│   └── func/                    # Allgemeine Systemfunktionen
│
├── tmp/                         # Temporäre Dateien & Caches
│
├── .gitignore                   # Git-Ignore-Konfiguration
├── .htaccess                    # Apache-Konfiguration
├── .htaccess_ws                 # Webspell Rewrite-Konfig
├── CHANGELOG.md                 # Versionshistorie
├── README.md                    # Projektbeschreibung
├── index.php                    # Einstiegspunkt Frontend
├── license.txt                  # Lizenzbedingungen
├── package.json                 # Node.js-Abhängigkeiten
└── rewrite.php                  # URL-Rewrite-Engine

````

	Webspell-RM is a free Content Management System (CMS) that is available free of charge at https://www.webspell-rm.de. The following information should give you a first impression of how it works.

	Webspell-RM mit angepassten, optimierten und sicherren code

	Webspell-RM with customized, optimized and secure code

	Webspell-RM con codice personalizzato, ottimizzato e sicuro

###############################################

Webspell-RM ist ein CMS mit Plugin System

- installierbare Plugins
- deinstallierbare Plugins
- CKEditor
- Multi-Language
- Google reCAPTCHA
- 404 Fehler Seite
- Bootstrap 5
- Bootstrap Icons
- installierbare Themes
- deinstallierbare Themes
- Webspell-RM Update möglich im Admincenter
- PHP 8.x kompatibel
- DSGVO konform

###############################################

For any questions try to use our Forum!

Bei Fragen nutzen Sie unser Forum!

###############################################

### 1. License

	Webspell-RM is published under GNU General Public License (GPL). It guarantees the free usage, modification and distribution of the Webspell-RM script withing the rules of the GPL.
	You are able to find additional information about license at http://www.fsf.org/licensing/licenses/gpl.html

### 2. Installation

	1. Requirements
	2. Upload Webspell-RM to your web space
	3. Run the Webspell-RM installation

	1. Requirements

	    * Webspace with PHP and mySQL support (MySQL >= 5.6, PHP >= 8.x)
	    * (g)unzip/tar to extract the downloaded Webspell-RM release
	    * A FTP program to upload the Webspell-RM files to your webspace - we recommend SmartFTP



	2. Upload Webspell-RM to your webspace

	    * Start your above downloaded FTP programm
	    * Connect with this FTP program to your webspace FTP server (you will get the access data for this from your webhoster)
	    * Upload ALL the extracted Webspell-RM files and folders to your webspace

	3. Do the Webspell-RM install

	    * Open your webbrowser
	    * Enter the path to the Webspell-RM install folder http://[hostnameofyouwebspace]/install (substitute [hostnameofyouwebspace] with the correct domain name  where you have uploaded Webspell-RM.
	    * Follow the installation steps and enter the correct data
<?php
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

global $myclanname;

// Datenbank-Abfrage für Social Media Links
$ergebnis = safe_query("SELECT * FROM `" . PREFIX . "settings_social_media`");
$socialLinks = [];

while ($row = mysqli_fetch_assoc($ergebnis)) {
    foreach ($row as $platform => $link) {
        $socialLinks[$platform] = $link;
    }
}

// Social Media Icons generieren
$icons = '';
foreach ($socialLinks as $platform => $link) {
    if (!empty($link)) {
        $icons .= '<a href="{{ link }}" target="_blank" class="social-media-circle {{ platform }}">
                     <i class="bi bi-{{ platform }}"></i>
                  </a> ';
    }
}

// Fehlende Variablen setzen
$pagetitle = $pagetitle ?? 'Website';
$rewriteBase = $rewriteBase ?? '/';
$reason = $reason ?? 'Coming Soon!';
$since = $since ?? date("Y"); // Setze das Jahr, falls $since nicht gesetzt ist
$myclanname = $myclname ?? 'DefaultClan'; // Standardwert für den Clanname, falls nicht gesetzt
$copy = date("Y") . ' ' . $myclanname . ' | ' . $since;

// Array für Platzhalter
$data_array = [
    'pagetitle' => $pagetitle,
    'rewriteBase' => $rewriteBase,
    'reason' => $reason,
    'since' => $since,
    'myclanname' => $myclanname,
    'icons' => $icons,
    'copy' => $copy,
];

// HTML-Inhalt
$html = '
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Website using webSPELL-RM CMS">
    <meta name="keywords" content="Clandesign, Webspell, Webspell | RM, Webdesign, Tutorials, Downloads, Webspell-rm, Addons, Plugins, Templates">
    <meta name="robots" content="all">
    <meta name="author" content="webspell-rm.de">
    <link rel="SHORTCUT ICON" href="/includes/themes/default/templates/favicon.ico">

    <title>{{ pagetitle }}</title>
    <base href="{{ rewriteBase }}">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../components/css/lockpage.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body>
    <header id="header" class="text-center">
        <img src="images/webspell-logo-lock.png" alt="Webspell Logo" style="height: 150px"/>
    </header>
    
    <main id="main" class="container text-center">
        <div class="row justify-content-center">
            <h2>We’re Launching Our Website Soon</h2>
        </div>
    </main>
    
    <section id="about" class="about">
        <div class="container login_card text-center">
            <h5>Information</h5>
            <div class="row justify-content-center">
                <div class="col-4" style="background: #fff; color: #000; padding: 10px; border-radius: 5px;">
                    <p>{{ reason }}</p>
                </div>    
            </div>
        </div>
    </section>
    
    <section id="contact" class="contact text-center">
        <div class="card container login_card text-center">
            <div class="card-body">
                <h3>Admin Login</h3>
                <form class="row g-3 form-inline justify-content-center" method="post" action="/includes/modules/checklogin.php">
                    <div class="col-auto">
                        <input name="ws_user" type="text" class="form-control" placeholder="Enter email" required>
                    </div>
                    <div class="col-auto">
                        <input name="password" type="password" class="form-control" placeholder="Password" required>
                    </div>
                    <div class="col-auto">
                        <button type="submit" name="Submit" class="btn btn-success">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    
    <section id="social" class="social text-center">
        <h4>Follow us <small>on Social Media</small></h4>
        {{ icons }}
    </section>
    
    <footer class="footer text-center mt-4">
        <div class="container">
            <small>&copy; {{ copy }} | All rights reserved.</small>
        </div>
    </footer>
</body>
</html>';

// Platzhalter durch die tatsächlichen Werte ersetzen
foreach ($data_array as $key => $value) {
    $html = str_replace("{{ $key }}", $value, $html);
}

// Das finale HTML ausgeben
echo $html;
?>
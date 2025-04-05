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
 * @copyright       2018-2023 by webspell-rm.de                                                              *
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

ob_start();

function generateCallTrace() {
    $trace = debug_backtrace();
    $trace = array_reverse($trace);
    array_pop($trace);
    array_pop($trace);
    
    $basepath = realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR;
    $result = [];

    foreach ($trace as $entry) {
        $file = isset($entry['file']) ? str_replace($basepath, '', $entry['file']) : '[unknown file]';
        $line = isset($entry['line']) ? $entry['line'] : '[unknown line]';
        $function = htmlspecialchars($entry['function'] ?? '[unknown function]');

        $params = [];
        foreach ($entry['args'] ?? [] as $param) {
            $params[] = htmlspecialchars(var_export($param, true));
        }

        $result[] = "{$file}({$line}): <b>{$function}</b>(" . implode(", ", $params) . ")";
    }

    return implode("\n", $result);
}

function system_error($text, $system = 1, $strace = 0) {
    ob_end_clean();
    http_response_code(500);

    global $_database;

    $trace = $strace ? '<pre>' . generateCallTrace() . '</pre>' : '';

    if ($system) {
        $version = 'Unknown';
        if (file_exists('system/version.php')) {
            include 'system/version.php';
        } elseif (file_exists('../system/version.php')) {
            include '../system/version.php';
        }

        $info = '<h1>Error 404</h1>
            <p>Die angefragte Seite konnte nicht gefunden werden.<br>The requested page could not be found.<br>
            La pagina richiesta non è stata trovata.<br><a class="btn btn-success" href="index.php">back</a></p>
            <br> Version: ' . htmlspecialchars($version) . ', PHP Version: ' . phpversion();

        if (isset($_database) && !mysqli_connect_error()) {
            $info .= ', MySQL Version: ' . $_database->server_info;
        }
    } else {
        $info = '<h1>Error 404</h1>
            <p>Die angefragte Seite konnte nicht gefunden werden.<br>The requested page could not be found.<br>
            La pagina richiesta non è stata trovata.<br><a class="btn btn-success" href="index.php">back</a></p>';
    }

    die('<!DOCTYPE html>
        <html lang="de">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta name="description" content="Clanpage using webSPELL RM CMS">
            <title>webSPELL-RM - Error</title>
            <link rel="stylesheet" href="./components/bootstrap/css/bootstrap.min.css">
            <link rel="stylesheet" href="./components/css/lockpage.css">
        </head>
        <body>
        <div class="lock_wrapper">
            <div class="container text-center">
                <div class="col-lg-12">
                    <img class="img-responsive" src="images/logo.png" alt="Logo"/>
                    <div class="shdw"></div>
                </div>
                ' . $info . '
                <h4>INFO</h4> 
                <div class="alert alert-danger"><strong>Ein Fehler ist aufgetreten<br>An error has occurred<br>Si è verificato un errore</strong></div>
                <div class="alert alert-info">' . htmlspecialchars($text) . '</div>
                <div class="alert alert-warning">' . $trace . '</div>    
            </div>
        </div>
        </body>
        </html>
    ');
}
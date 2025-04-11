<?php
namespace webspell;

class AccessControl
{
    public static function enforceLogin(): void
    {
        global $loggedin;

        if (!$loggedin) {
            include("login.php");
            exit;
        }
    }

    public static function enforce(string $module): void
    {
        global $userID, $_language, $cookievalueadmin;

        if ($module === "admin") {
            if (!isanyadmin($userID) || !$cookievalueadmin) {
                die($_language->module['access_denied']);
            }
        }

        // Hier können weitere Module folgen
        // Beispiel:
        // if ($module === "moderator") { ... }
    }
}
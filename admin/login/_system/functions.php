﻿<?php
/**
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*  
 *                                    Webspell-RM      /                        /   /                                                 *
 *                                    -----------__---/__---__------__----__---/---/-----__---- _  _ -                                *
 *                                     | /| /  /___) /   ) (_ `   /   ) /___) /   / __  /     /  /  /                                 *
 *                                    _|/_|/__(___ _(___/_(__)___/___/_(___ _/___/_____/_____/__/__/_                                 *
 *                                                 Free Content / Management System                                                   *
 *                                                             /                                                                      *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 * @version         Webspell-RM                                                                                                       *
 *                                                                                                                                    *
 * @copyright       2018-2022 by webspell-rm.de <https://www.webspell-rm.de>                                                          *
 * @support         For Support, Plugins, Templates and the Full Script visit webspell-rm.de <https://www.webspell-rm.de/forum.html>  *
 * @WIKI            webspell-rm.de <https://www.webspell-rm.de/wiki.html>                                                             *
 *                                                                                                                                    *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 * @license         Script runs under the GNU GENERAL PUBLIC LICENCE                                                                  *
 *                  It's NOT allowed to remove this copyright-tag <http://www.fsf.org/licensing/licenses/gpl.html>                    *
 *                                                                                                                                    *
 * @author          Code based on WebSPELL Clanpackage (Michael Gruber - webspell.at)                                                 *
 * @copyright       2005-2018 by webspell.org / webspell.info                                                                         *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 *                                                                                                                                    *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 */


function widgets_hide () {
    global $hide;

    $sql = safe_query("SELECT module, activated FROM ".PREFIX."settings_head_moduls WHERE activated = '1'");
if(mysqli_num_rows($sql)) {
    while($row = mysqli_fetch_array($sql)) {
        $hide[] = $row['module'];
    }
}
else {
    $hide = array();
}
}

function get_hide () { 
    global $hide1, $hide2, $hide3;



$sql = safe_query("SELECT module, le_activated FROM ".PREFIX."settings_moduls WHERE le_activated = '1'");
if(mysqli_num_rows($sql)) {
    while($row = mysqli_fetch_array($sql)) {
        $hide1[] = $row['module'];
    }
}
else {
    $hide1 = array();
}

$sql = safe_query("SELECT module, re_activated FROM ".PREFIX."settings_moduls WHERE re_activated = '1'");
if(mysqli_num_rows($sql)) {
    while($row = mysqli_fetch_array($sql)) {
        $hide2[] = $row['module'];
    }
}
else {
    $hide2 = array();
}

$sql = safe_query("SELECT module, activated FROM ".PREFIX."settings_moduls WHERE activated = '1'");
if(mysqli_num_rows($sql)) {
    while($row = mysqli_fetch_array($sql)) {
        $hide3[] = $row['module'];
    }
}
else {
    $hide3 = array();
}

}

function get_mainhide () { 
    global $class_maincol, $site, $hide1, $hide2, $hide3;

if (in_array($site, $hide1)) {
                echo "col-lg-9 col-sm-9 col-xs-12";
            }
            elseif (in_array($site, $hide2)) {
                echo "col-lg-9 col-sm-9 col-xs-12";
            }
            elseif (in_array($site, $hide3)) {
                echo "col-lg-12 col-sm-12 col-xs-12";
            } else {
                echo "col-lg-6 col-sm-9 col-xs-12";
            }
}




// ===    

function headfiles($var, $path) {
	$css=""; $js="\n";
	switch($var) {
	case "css":
		if(is_dir($path."css/")) { $subf = "css/"; } else { $subf=""; } $css="";
		$f = array();
		$f[] = glob(preg_replace('/(\*|\?|\[)/', '[$1]', $path.$subf).'*.css');
		$fc = count($f, COUNT_RECURSIVE);
		for($a=0; $a<=$fc; $a++) {
			if(@count($f[$a])>0) {
				for($b=0; $b<=(@count($f[$a])-1); $b++) {
					$css .= '<link type="text/css" rel="stylesheet" href="'.$f[$a][$b].'">'.chr(0x0D).chr(0x0A);
				}
			}
		}
		return $css;
		break;
	case "js":
		if(is_dir($path."js/")) { $subf2 = "js/"; } else { $subf2=""; } $js="";
		$g = array();
		$g[] = glob(preg_replace('/(\*|\?|\[)/', '[$1]', $path.$subf2).'*.js');
		$fc = count($g, COUNT_RECURSIVE);
		for($c=0; $c<=$fc; $c++) {
			if(@count($g[$c])>0) {
				for($d=0; $d<=(@count($g[$c])-1); $d++) {
				$js .= '<script src="'.$g[$c][$d].'"></script>'.chr(0x0D).chr(0x0A);
				}
			}
		}
		return $js;
		break;
	default:
		return "<!-- invalid parameter, use 'css', 'js' or 'components' -->";			
	}
}

// -- LOGIN SESSION -- //

if(file_exists('session.php')) { systeminc('session'); } else { systeminc('../system/session'); }
if(file_exists('ip.php')) { systeminc('ip'); } else { systeminc('../system/ip'); }

// -- GLOBAL WEBSPELL FUNCTIONS -- //

function makepagelink($link, $page, $pages, $sub = '')
{
    $page_link = '<nav>
  <ul class="pagination pagination-sm">';

    if ($page != 1) {
        $page_link .=
        '<li><a href="' . $link . '&amp;' . $sub . 'page=1">&laquo;</a></li> <li><a href="' . $link . '&amp;' . $sub .
        'page=' . ($page - 1) . '">&lsaquo;</a></li>';
    }
    if ($page >= 6) {
        $page_link .= '<li><a href="' . $link . '&amp;' . $sub . 'page=' . ($page - 5) . '">...</a></li>';
    }
    if ($page + 4 >= $pages) {
        $pagex = $pages;
    } else {
        $pagex = $page + 4;
    }
    for ($i = $page - 4; $i <= $pagex; $i++) {
        if ($i <= 0) {
            $i = 1;
        }
        if ($i == $page) {
            $page_link .= '<li class="active"><a href="#" aria-label="Previous"><span aria-hidden="true">' . $i . '</span></a></li>';
        } else {
            $page_link .= '<li><a href="' . $link . '&amp;' . $sub . 'page=' . $i . '">' . $i . '</a></li>';
        }
    }
    if (($pages - $page) >= 5) {
        $page_link .= '<li><a href="' . $link . '&amp;' . $sub . 'page=' . ($page + 5) . '">...</a></li>';
    }
    if ($page != $pages) {
        $page_link .=
        '<li><a href="' . $link . '&amp;' . $sub . 'page=' . ($page + 1) . '">&rsaquo;</a>&nbsp;<a href="' .
        $link . '&amp;' . $sub . 'page=' . $pages . '">&raquo;</a></li>';
    }
    $page_link .= '</ul></nav>';

    return $page_link;
}

function str_break($str, $maxlen)
{
    $nobr = 0;
    $str_br = '';
    $len = mb_strlen($str);
    for ($i = 0; $i < $len; $i++) {
        $char = mb_substr($str, $i, 1);
        if (($char != ' ') && ($char != '-') && ($char != "\n")) {
            $nobr++;
        } else {
            $nobr = 0;
            if ($maxlen + $i > $len) {
                $str_br .= mb_substr($str, $i);
                break;
            }
        }
        if ($nobr > $maxlen) {
            $str_br .= '- ' . $char;
            $nobr = 1;
        } else {
            $str_br .= $char;
        }
    }
    return $str_br;
}

function substri_count_array($haystack, $needle)
{
    $return = 0;
    foreach ($haystack as $value) {
        if (is_array($value)) {
            $return += substri_count_array($value, $needle);
        } else {
            $return += substr_count(strtolower($value), strtolower($needle));
        }
    }
    return $return;
}

function js_replace($string)
{
    $output = preg_replace("/(\\\)/si", '\\\\\1', $string);
    $output = str_replace(
        array("\r\n", "\n", "'", "<script>", "</script>", "<noscript>", "</noscript>"),
        array("\\n", "\\n", "\'", "\\x3Cscript\\x3E", "\\x3C/script\\x3E", "\\x3Cnoscript\\x3E", "\\x3C/noscript\\x3E"),
        $output
    );
    return $output;
}

function percent($sub, $total, $dec)
{
    if ($sub) {
        $perc = $sub / $total * 100;
        $perc = round($perc, $dec);
        return $perc;
    } else {
        return 0;
    }
}

function showlock($reason)
{
    $gettitle = mysqli_fetch_array(safe_query("SELECT title FROM `" . PREFIX . "settings`"));
    $pagetitle = $gettitle['title'];
    $data_array = array();
    $data_array['$pagetitle'] = $pagetitle;
    if (isset($GLOBALS['_modRewrite']) && $GLOBALS['_modRewrite']->enabled()) {
        $data_array['$rewriteBase'] = $GLOBALS['_modRewrite']->getRewriteBase();
    } else {
        $data_array['$rewriteBase'] = '';
    }
    $data_array['$reason'] = $reason;
    #$lock = $GLOBALS["_template"]->replaceTemplate("lock", $data_array);
    echo'
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <meta name="description" content="Clanpage using webSPELL RM CMS">
    <meta name="author" content="webspell.org">
    <meta name="copyright" content="Copyright 2005-2018 by webspell.org / webspell-rm.de">
    <meta name="generator" content="webSPELL-RM">

    <title>'.$pagetitle.'</title>
    <base href="$rewriteBase">
    <link href="components/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="components/css/lockpage.css" rel="stylesheet" type="text/css">
    
</head>

<body>
<div class="lock_wrapper">
    <div class="container text-center">
        <div class="col-lg-12">
            <img class="img-responsive" src="images/logo.png" alt=""/>
            <div class="shdw"></div>
        </div>
        <h2>The page is currently locked, please try again later!<br><br>Die Seite ist derzeit gesperrt. Bitte versuchen Sie es später erneut.</h2>
        <p>'.$reason.'</p>
        <h4>Admin Login</h4> 
        <form class="col-xs-6 col-xs-offset-3 col-sm-4 col-sm-offset-4 col-lg-4 col-lg-offset-4" method="post" name="login" action="/login/admincheck.php">
            <div class="form-group">
                <input class="form-control text-center" name="ws_user" type="text" placeholder="Username">
            </div>
            <div class="form-group">
                <input class="form-control text-center" name="password" type="password" placeholder="Password">
            </div>
            <div class="form-group">
                <input class="form-control btn-success" type="submit" name="Submit" value="login">
            </div>
        </form>
    </div>
</div>
</body>
</html>
';
    die();
}


function checkenv($systemvar, $checkfor)
{
    return stristr(ini_get($systemvar), $checkfor);
}

function mail_protect($mailaddress)
{
    $protected_mail = "";
    $arr = unpack("C*", $mailaddress);
    foreach ($arr as $entry) {
        $protected_mail .= sprintf("%%%X", $entry);
    }
    return $protected_mail;
}

function validate_url($url)
{
    return preg_match(
        // @codingStandardsIgnoreStart
        "/^(ht|f)tps?:\/\/([^:@]+:[^:@]+@)?(?!\.)(\.?(?!-)[0-9\p{L}-]+(?<!-))+(:[0-9]{2,5})?(\/[^#\?]*(\?[^#\?]*)?(#.*)?)?$/sui",
        // @codingStandardsIgnoreEnd
        $url
    );
}

function validate_email($email)
{
    return preg_match(
        // @codingStandardsIgnoreStart
        "/^(?!\.)(\.?[\p{L}0-9!#\$%&'\*\+\/=\?^_`\{\|}~-]+)+@(?!\.)(\.?(?!-)[0-9\p{L}-]+(?<!-))+\.[\p{L}0-9]{2,}$/sui",
        // @codingStandardsIgnoreEnd
        $email
    );
}

if (!function_exists('array_combine')) {
    function array_combine($keyarray, $valuearray)
    {
        $keys = array();
        $values = array();
        $result = array();
        foreach ($keyarray as $key) {
            $keys[] = $key;
        }
        foreach ($valuearray as $value) {
            $values[] = $value;
        }
        foreach ($keys as $access => $resultkey) {
            $result[$resultkey] = $values[$access];
        }
        return $result;
    }
}

if (!function_exists("hash_equals")) {
    function hash_equals($known_str, $user_str)
    {
        $result = 0;

        if (!is_string($known_str)) {
            return false;
        }

        if (!is_string($user_str)) {
            return false;
        }

        if (strlen($known_str) != strlen($user_str)) {
            return false;
        }

        for ($j = 0; $j < strlen($known_str); $j++) {
            $result |= ord($known_str[$j]) ^ ord($user_str[$j]);
        }
        return $result === 0;
    }
}

/* counts empty variables in an array */

function countempty($checkarray)
{

    $ret = 0;

    foreach ($checkarray as $value) {
        if (is_array($value)) {
            $ret += countempty($value);
        } elseif (trim($value) == "") {
            $ret++;
        }
    }

    return $ret;
}

/* checks, if given request-variables are empty */

function checkforempty($valuearray)
{

    $check = array();
    foreach ($valuearray as $value) {
        $check[] = $_REQUEST[$value];
    }

    if (countempty($check) > 0) {
        return false;
    }
    return true;
}

// -- FILESYSTEM -- //
if(file_exists('func/filesystem.php')) { systeminc('func/filesystem'); } else { systeminc('../system/func/filesystem'); }
# systeminc('src/func/filesystem');

// -- DATE-TIME INFORMATION -- //
if(file_exists('func/datetime.php')) { systeminc('func/datetime'); } else { systeminc('../system/func/datetime'); }

// -- USER INFORMATION -- //
if(file_exists('func/user.php')) { systeminc('func/user'); } else { systeminc('../system/func/user'); }

// -- ACCESS INFORMATION -- //
if(file_exists('func/useraccess.php')) { systeminc('func/useraccess'); } else { systeminc('../system/func/useraccess'); }

// -- MESSENGER INFORMATION -- //
if(file_exists('func/messenger.php')) { systeminc('func/messenger'); } else { systeminc('../system/func/messenger'); }

// -- NEWS INFORMATION -- //
if(file_exists('func/news.php')) { systeminc('func/news'); } else { systeminc('../system/func/news'); }

// -- FILES INFORMATION -- //
if(file_exists('func/files.php')) { systeminc('func/files'); } else { systeminc('../system/func/files'); }

// -- GAME INFORMATION -- //
if(file_exists('func/game.php')) { systeminc('func/game'); } else { systeminc('../system/func/game'); }

// -- BOARD INFORMATION -- //
if(file_exists('func/board.php')) { systeminc('func/board'); } else { systeminc('../system/func/board'); }

// -- Page INFORMATION -- //
if(file_exists('func/page.php')) { systeminc('func/page'); } else { systeminc('../system/func/page'); }

// -- CAPTCHA -- //
if(file_exists('func/captcha.php')) { systeminc('func/captcha'); } else { systeminc('../system/func/captcha'); }

// -- LANGUAGE SYSTEM -- //
if(file_exists('func/language.php')) { systeminc('func/language'); } else { systeminc('../system/func/language'); }
# systeminc('src/func/language');
$_language = new \webspell\Language;
$_language->setLanguage($default_language);

// -- TEMPLATE SYSTEM -- //
if(file_exists('func/template.php')) { systeminc('func/template'); } else { systeminc('../system/func/template'); }
# systeminc('src/func/template');
if (!stristr($_SERVER['SCRIPT_NAME'], '/admin/')) {
    $_template = new \webspell\Template();
} else {
    $_template = new \webspell\Template('../templates/');
}
// -- GALLERY -- //

if(file_exists('func/gallery.php')) { systeminc('func/gallery'); } else { systeminc('../system/func/gallery'); }
#systeminc('src/func/gallery');

// -- PASSWORD_HASH -- //
if (version_compare(PHP_VERSION, '5.3.7', '>') && version_compare(PHP_VERSION, '5.5.0', '<')) {
	if(file_exists('func/password.php')) { systeminc('func/password'); } else { systeminc('../system/func/password'); }
	#systeminc('func/password');
}

// -- SPAM -- //
if(file_exists('func/spam.php')) { systeminc('func/spam'); } else { systeminc('../system/func/spam'); }
#systeminc('src/func/spam');

// -- BB CODE -- //
if(file_exists('func/bbcode.php')) { systeminc('func/bbcode'); } else { systeminc('../system/func/bbcode'); }
#systeminc('src/func/bbcode');

// -- Tags -- //
if(file_exists('func/tags.php')) { systeminc('func/tags'); } else { systeminc('../system/func/tags'); }
#systeminc('src/func/tags');

// -- Upload -- //
if(file_exists('func/upload.php')) { systeminc('func/upload'); } else { systeminc('../system/func/upload'); }
#systeminc('src/func/upload');
if(file_exists('func/httpupload.php')) { systeminc('func/httpupload'); } else { systeminc('../system/func/httpupload'); }
#systeminc('src/func/httpupload');
if(file_exists('func/urlupload.php')) { systeminc('func/urlupload'); } else { systeminc('../system/func/urlupload'); }
#systeminc('src/func/urlupload');

// -- Mod Rewrite -- //
if(file_exists('modrewrite.php')) { systeminc('modrewrite'); } else { systeminc('../system/modrewrite'); }
#systeminc('modrewrite');
$GLOBALS['_modRewrite'] = new \webspell\ModRewrite();
if (!stristr($_SERVER['SCRIPT_NAME'], '/admin/') && $modRewrite) {
    $GLOBALS['_modRewrite']->enable();
}

function cleartext($text, $bbcode = true, $calledfrom = 'root')
{
    $text = htmlspecialchars($text);
    $text = strip_tags($text);
    $text = smileys($text, 1, $calledfrom);
    $text = insertlinks($text, $calledfrom);
    $text = flags($text, $calledfrom);
    $text = replacement($text, $bbcode);
    $text = htmlnl($text);
    $text = nl2br($text);

    return $text;
}

function htmloutput($text)
{
    $text = smileys($text);
    $text = insertlinks($text);
    $text = flags($text);
    $text = replacement($text);
    $text = htmlnl($text);
    $text = nl2br($text);

    return $text;
}

function clearfromtags($text)
{
    $text = getinput($text);
    $text = strip_tags($text);
    $text = htmlnl($text);
    $text = nl2br($text);

    return $text;
}

function getinput($text)
{
    $text = htmlspecialchars($text);

    return $text;
}

function getforminput($text)
{
    $text = str_replace(array('\r', '\n'), array("\r", "\n"), $text);
    $text = stripslashes($text);
    $text = htmlspecialchars($text);

    return $text;
}

// -- LOGIN -- //
if(file_exists('login.php')) { systeminc('login'); } else { systeminc('../system/login'); }
#systeminc('login');

if (isset($_COOKIE['language'])) {
    $_language->setLanguage($_COOKIE['language']);
} elseif (isset($_SESSION['language'])) {
    $_language->setLanguage($_SESSION['language']);
} elseif ($autoDetectLanguage) {
    $lang = detectUserLanguage();
    if (!empty($lang)) {
        $_language->setLanguage($lang);
        $_SESSION['language'] = $lang;
    }
}

// -- SITE VARIABLE -- //

if (isset($_GET['site'])) {
    $site = $_GET['site'];
} else {
    $site = '';
}
if ($closed && !isanyadmin($userID)) {
    $dl = mysqli_fetch_array(safe_query("SELECT * FROM `" . PREFIX . "lock` LIMIT 0,1"));
    $reason = $dl['reason'];
    showlock($reason);
}
if (!isset($_SERVER['HTTP_REFERER'])) {
    $_SERVER['HTTP_REFERER'] = "";
}

if (!isset($_SERVER['REQUEST_URI'])) {
    $_SERVER['REQUEST_URI'] = $_SERVER['PHP_SELF'];
    if (isset($_SERVER['QUERY_STRING'])) {
        $_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];
    }
}

// -- BANNED USERS -- //
if (date("dh", $lastBanCheck) != date("dh")) {
    $get = safe_query("SELECT userID, banned FROM `" . PREFIX . "user` WHERE banned IS NOT NULL");
    $removeBan = array();
    while ($ds = mysqli_fetch_assoc($get)) {
        if ($ds['banned'] != "perm") {
            if ($ds['banned'] <= time()) {
                $removeBan[] = 'userID="' . $ds['userID'] . '"';
            }
        }
    }
    if (!empty($removeBan)) {
        $where = implode(" OR ", $removeBan);
        safe_query("UPDATE " . PREFIX . "user SET banned=NULL WHERE " . $where);
    }
    safe_query("UPDATE " . PREFIX . "settings SET bancheck='" . time() . "'");
}

$banned =
    safe_query(
        "SELECT userID, banned, ban_reason FROM `" . PREFIX . "user`
        WHERE (userID='" . $userID . "' OR ip='" . $GLOBALS[ 'ip' ] . "') AND banned IS NOT NULL"
    );
while ($bq = mysqli_fetch_array($banned)) {
    if ($bq['ban_reason']) {
        $reason = "<br>Reason: <mark>" . $bq['ban_reason'] . '</mark>';
    } else {
        $reason = '';
    }
    if ($bq['banned']) {
        $_SESSION = array();

        // remove session cookie
        if (isset($_COOKIE[ session_name() ])) {
            setcookie(session_name(), '', time() - 42000, '/');
        }

        session_destroy();

        // remove login cookie
        webspell\LoginCookie::clear('ws_auth');
        system_error('<strong>You have been banned.</strong>' . $reason, 0);
    }
}

// -- BANNED IPs -- //

safe_query("DELETE FROM `" . PREFIX . "banned_ips` WHERE deltime < '" . time() . "'");

// -- WHO IS - WAS ONLINE -- //

$timeout = 5; // 1 second
$deltime = time() - ($timeout * 60); // IS 1m
$wasdeltime = time() - (60 * 60 * 24); // WAS 24h

safe_query("DELETE FROM `" . PREFIX . "whoisonline` WHERE time < '" . $deltime . "'");  // IS online
safe_query("DELETE FROM `" . PREFIX . "whowasonline` WHERE time < '" . $wasdeltime . "'");  // WAS online

// -- HELP MODE -- //
if(file_exists('help.php')) { systeminc('help'); } else { systeminc('../system/help'); }
#systeminc('help');

// -- WHOISONLINE -- //

if (mb_strlen($site)) {
    if ($userID) {
        // IS online
        if (mysqli_num_rows(safe_query("SELECT userID FROM " . PREFIX . "whoisonline WHERE userID='$userID'"))) {
            safe_query(
                "UPDATE " . PREFIX . "whoisonline SET time='" . time() .
                "', site='$site' WHERE userID='$userID'"
            );
            safe_query("UPDATE " . PREFIX . "user SET lastlogin='" . time() . "' WHERE userID='$userID'");
        } else {
            safe_query(
                "INSERT INTO " . PREFIX . "whoisonline (time, userID, site) VALUES ('" . time() .
                "', '$userID', '$site')"
            );
        }

        // WAS online
        if (mysqli_num_rows(safe_query("SELECT userID FROM " . PREFIX . "whowasonline WHERE userID='$userID'"))) {
            safe_query(
                "UPDATE " . PREFIX . "whowasonline SET time='" . time() .
                "', site='$site' WHERE userID='$userID'"
            );
        } else {
            safe_query(
                "INSERT INTO " . PREFIX . "whowasonline (time, userID, site) VALUES ('" . time() .
                "', '$userID', '$site')"
            );
        }
    } else {
        $anz =
            mysqli_num_rows(
                safe_query(
                    "SELECT ip FROM `" . PREFIX . "whoisonline` WHERE ip='" . $GLOBALS[ 'ip' ] . "'"
                )
            );
        if ($anz) {
            safe_query(
                "UPDATE " . PREFIX . "whoisonline SET time='" . time() . "', site='$site' WHERE ip='" .
                $GLOBALS[ 'ip' ] . "'"
            );
        } else {
            safe_query(
                "INSERT INTO " . PREFIX . "whoisonline (time, ip, site) VALUES ('" . time() . "','" .
                $GLOBALS[ 'ip' ] . "', '$site')"
            );
        }
    }
}

// -- COUNTER -- //

$time = time();
$date = date("d.m.Y", $time);
$deltime = $time - (3600 * 24);
safe_query("DELETE FROM `" . PREFIX . "counter_iplist` WHERE del<" . $deltime);

if (!mysqli_num_rows(safe_query("SELECT ip FROM `" . PREFIX . "counter_iplist` WHERE ip='" . $GLOBALS[ 'ip' ] . "'"))) {
    if ($userID) {
        safe_query("UPDATE `" . PREFIX . "user` SET ip='" . $GLOBALS[ 'ip' ] . "' WHERE userID='" . $userID . "'");
    }
    safe_query("UPDATE `" . PREFIX . "counter` SET hits=hits+1");
    safe_query(
        "INSERT INTO `" . PREFIX . "counter_iplist` (dates, del, ip) VALUES ('" . $date . "', '" . $time . "', '" .
        $GLOBALS[ 'ip' ] . "')"
    );
    if (!mysqli_num_rows(safe_query("SELECT dates FROM `" . PREFIX . "counter_stats` WHERE dates='" . $date . "'"))) {
        safe_query("INSERT INTO `" . PREFIX . "counter_stats` (`dates`, `count`) VALUES ('" . $date . "', '1')");
    } else {
        safe_query("UPDATE " . PREFIX . "counter_stats SET count=count+1 WHERE dates='" . $date . "'");
    }
}

/* update maxonline if necessary */
$res = mysqli_fetch_assoc(safe_query("SELECT count(*) as maxuser FROM `" . PREFIX . "whoisonline`"));
safe_query(
    "UPDATE `" . PREFIX . "counter`
    SET maxonline = '" . $res[ 'maxuser' ] . "'
    WHERE maxonline < '" . $res[ 'maxuser' ] . "'"
);

// -- SEARCH ENGINE OPTIMIZATION (SEO) -- //
if (stristr($_SERVER[ 'PHP_SELF' ], "/admin/") === false) {
	if(file_exists('seo.php')) { systeminc('seo'); } else { systeminc('../system/seo'); }
    #systeminc('seo');
    define('PAGETITLE', getPageTitle());
} else {
    define('PAGETITLE', $GLOBALS['hp_title']);
}

// -- RSS FEEDS -- //
if(file_exists('func/feeds.php')) { systeminc('func/feeds'); } else { systeminc('../system/func/feeds'); }
#systeminc('src/func/feeds');

// -- Email -- //
if(file_exists('func/email.php')) { systeminc('src/func/email'); } else { systeminc('../system/func/email'); }
#systeminc('src/func/email');

function recursiveRemoveDirectory($directory)
{
    foreach (glob("{$directory}/*") as $file) {
        if (is_dir($file)) {
            recursiveRemoveDirectory($file);
        } else {
            unlink($file);
        }
    }
    rmdir($directory);
}
function table_exist($table){ 
    $pTableExist = safe_query("show tables like '" . PREFIX . "" . $table ."'"); 
    if ($rTableExist = mysqli_fetch_array($pTableExist)) { 
     safe_query("DROP TABLE `" . PREFIX . "$table`");
    }else{ 
     return "Keine Tabelle vorhanden mit den Namen $table."; 
    } 
} 


            


function DeleteData($name,$where,$data)

{
if (safe_query("SELECT * FROM `" . PREFIX . "$name` WHERE $where='".$data."'") -> num_rows == 1) { safe_query("DELETE FROM `" . PREFIX . "$name` WHERE $where = '$data'");    // Tabelle Löschen
			}else{
			echo "Keine Tabelle vorhanden mit den Namen $name.";
			}
}




?>
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
 * @copyright       2018-2024 by webspell-rm.de                                                              *
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

#error_reporting(E_ALL);
#ini_set('display_errors', 1);



chdir('../');
include('system/sql.php');
include('system/settings.php');
include('system/functions.php');
include('system/plugin.php');
include('system/widget.php');
include('system/version.php');
include('system/multi_language.php');
chdir('admin');
$load = new plugin_manager();
$_language->readModule('admincenter', false, true);
if (isset($_GET['site'])) {
	$site = $_GET['site'];
} elseif (isset($site)) {
	unset($site);
}
$cookievalueadmin = 'false';
if (isset($_COOKIE['ws_cookie'])) {
	$cookievalueadmin = 'accepted';
}

// Beispiel: Benutzer-ID aus der Sitzung erhalten (falls vorhanden)
$userID = $_SESSION['userID'] ?? 0; // Falls keine userID, dann 0

// Überprüfen, ob der Benutzer eine Rolle zugewiesen hat
if (!$userID || !checkUserRoleAssignment($userID)) {
    die('<div style="background-color: red; color: white; padding: 10px; border-radius: 5px;">
    Zugriff verweigert: Sie haben keine Rolle zugewiesen bekommen.</div>');
}


if (!isset($_SERVER['REQUEST_URI'])) {
	$arr = explode('/', $_SERVER['PHP_SELF']);
	$_SERVER['REQUEST_URI'] = '/' . $arr[count($arr) - 1];
	if ($_SERVER['argv'][0] != '') {
		$_SERVER['REQUEST_URI'] .= '?' . $_SERVER['argv'][0];
	}
}
function getplugincatID($catname)
{
    // Bereite die SQL-Anfrage vor, um SQL-Injection zu verhindern
    $stmt = $_database->prepare("SELECT * FROM `" . PREFIX . "navigation_dashboard_categories` WHERE name LIKE ?");
    $searchTerm = '%' . $catname . '%';
    $stmt->bind_param('s', $searchTerm);  // 's' für String
    $stmt->execute();
    $result = $stmt->get_result();

    // Prüfen, ob eine Kategorie gefunden wurde
    if ($ds = $result->fetch_assoc()) {
        // Kategorie gefunden, nun Links überprüfen
        $stmt2 = $_database->prepare("SELECT * FROM `" . PREFIX . "navigation_dashboard_links` WHERE catID = ?");
        $stmt2->bind_param('i', $ds['catID']);  // 'i' für Integer
        $stmt2->execute();
        $result2 = $stmt2->get_result();

        // Wenn Links vorhanden sind, zurückgeben, dass Links existieren
        if ($result2->num_rows >= 1) {
            return true;
        } else {
            return false; // Keine Links in der Kategorie
        }
    } else {
        // Keine Kategorie mit diesem Namen gefunden
        return false;
    }
}

function dashnavi() {
    global $userID;  // Stellen Sie sicher, dass $userID korrekt gesetzt ist

    $links = '';
    $ergebnis = safe_query("SELECT * FROM " . PREFIX . "navigation_dashboard_categories ORDER BY sort");

    while ($ds = mysqli_fetch_array($ergebnis)) {
        $name = $ds['name'];
        $fa_name = $ds['fa_name'];
        $translate = new multiLanguage(detectCurrentLanguage());
        $translate->detectLanguages($name);
        $name = $translate->getTextByLanguage($name);

        // Überprüfe den Zugriff auf die Kategorie
        if (checkAccessRights($userID, $ds['catID'])) {  // Übergebe $userID an die Funktion
            $links .= '<li><a class=\'has-arrow\' aria-expanded=\'false\' href=\'#\'><i class=\'' . $fa_name . '\' style="font-size: 1rem;"></i> ' . $name . '</a><ul class=\'nav nav-third-level\'>';

            $catlinks = safe_query("SELECT * FROM " . PREFIX . "navigation_dashboard_links WHERE catID='" . $ds['catID'] . "' ORDER BY sort");

            while ($db = mysqli_fetch_array($catlinks)) {
                $linkID = $db['linkID'];  // Setze die Link ID
                $translate->detectLanguages($db['name']);
                $name = $translate->getTextByLanguage($db['name']);

                // Überprüfe den Zugriff auf den Link
                if (checkAccessRights($userID, null, $linkID)) {  // Übergebe $userID an die Funktion
                    $links .= '<li><a href=\'' . $db['url'] . '\'> <i class="bi bi-plus-lg ac-link"></i> ' . $name . '</a></li>';
                }
            }
            $links .= '</ul></li>';
        }
    }

    return $links ? $links : '<li>Keine Zugriffsberechtigten Links gefunden.</li>';
}

if ($userID && !isset($_GET['userID']) && !isset($_POST['userID'])) {
	$ds = mysqli_fetch_array(safe_query("SELECT registerdate FROM `" . PREFIX . "user` WHERE userID='" . $userID . "'"));
	$username = '<a class="nav-link nav-link-3" href="../index.php?site=profile&amp;id=' . $userID . '">' . getnickname($userID) . '</a>';
	$lastlogin = getformatdatetime($_SESSION['ws_lastlogin']);
	$registerdate = getformatdatetime($ds['registerdate']);

	$data_array = array();
	$data_array['$username'] = $username;
	$data_array['$lastlogin'] = $lastlogin;
	$data_array['$registerdate'] = $registerdate;
}

if ($getavatar = getavatar($userID)) {
	$l_avatar = $getavatar;
} else {
	$l_avatar = "noavatar.png";
}

?>
<!DOCTYPE html>
<html lang="<?php echo $_language->language ?>">

<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Website using webSPELL-RM CMS">
	<meta name="copyright" content="Copyright &copy; 2017-2023 by webspell-rm.de">
	<meta name="author" content="webspell-rm.de">

	<link rel="SHORTCUT ICON" href="/admin/favicon.ico">

	<title>Webspell-RM - Bootstrap Admin Theme</title>

	<!-- Bootstrap Core CSS -->
	<link href="/admin/css/bootstrap.min.css" rel="stylesheet">
	<link href="/admin/css/bootstrap-switch.css" rel="stylesheet">

	<!-- side-bar CSS -->
	<link href="/admin/css/page.css" rel="stylesheet">
	<link href="/admin/css/metisMenu.css" rel="stylesheet" />

	<!-- Custom Fonts -->
	<!--<link href='../components/fontawesome/css/all.css' rel='stylesheet' type='text/css'>-->
	<link href="/admin/css/bootstrap-icons.min.css" rel="stylesheet">

	<!-- colorpicker -->
	<link href="/admin/css/bootstrap-colorpicker.min.css" rel="stylesheet">

	<!-- DataTables -->
	<link href="/admin/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />

</head>

<body>

	<div id="wrapper">
		<!-- Navigation -->

		<ul class="nav justify-content-end" style="margin-bottom: 25px;margin-top: 5px;">
			<li class="nav-item">
				<a class="nav-link nav-link-2"><?php echo $_language->module['welcome'] ?> </a>
			</li>
			<li class="nav-item">
				<?php echo @$username ?>
			</li>
			<li class="nav-item dropdown">
				<a class="nav-link nav-link-3 dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
					<?php echo $_language->module['logout'] ?>
				</a>
				<ul class="dropdown-menu">
					<li><a class="dropdown-item" href="../index.php"><i class="bi bi-arrow-clockwise text-success"></i> <?php echo $_language->module['back_to_website'] ?></a></li>
					<li><a class="dropdown-item" href="../index.php?site=logout"><i class="bi bi-x-lg text-danger"></i> <?php echo $_language->module['logout'] ?></a></li>
				</ul>
			</li>
		</ul>



		<!-- /.navbar-top-links -->
		<!-- sidebar-links -->
		<nav class="navbar-default sidebar navbar-dark" role="navigation" style="margin-top: -30px;">
			<div style="padding: 0 0 10px 0;" id="ws-image">
				<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<a class="navbar-brand" href="/admin/admincenter.php">
					<img src="/admin/images/rm.png" style="width: 230px;margin-top: 7px; margin-bottom: 7px;" alt="setting">
				</a><br>
				<img id="avatar-big" style="height: 90px;margin-top: 9px;margin-bottom: 9px; -webkit-box-shadow: 2px 2px 15px 3px rgba(0,0,0,0.54);box-shadow: 2px 2px 15px 3px rgba(0,0,0,0.54);border: 3px solid #fe821d;border-radius: 25px;--bs-tooltip-bg: #fe821d;" src="/images/avatars/<?php echo $l_avatar ?>" data-toggle="tooltip" data-html="true" data-bs-placement="right" data-bs-original-title="<?php echo getnickname($userID) ?>" class="rounded-circle profile_img">
				<div class="sidebar-nav col1lapse navbar-collapse" id="navbarNavDropdown">
					<ul class="nav metismenu text-start navbar-nav" id="side-bar">
						<li class="sidebar-head mm-active">
							<a class="nav-link link-head" href="admincenter.php"> <i class="bi bi-house-door"></i> Dashboard</a>
						</li>
						<?php
						echo dashnavi();
						?>
					</ul>
				</div>
				<!-- Copy -->
				<div class="copy">
					<em>Admin Template by <a href="https://www.webspell-rm.de" target="_blank" rel="noopener">Webspell-RM</a></em>
				</div>
			</div>
		</nav>
		<!-- /.navbar-static-side -->

		<div id="page-wrapper">
			<?php
			if (isset($site) && $site != "news") {
			    $invalide = array('\\', '/', '//', ':', '.');
			    $site = str_replace($invalide, ' ', $site); // Entferne ungültige Zeichen
			    if (file_exists($site . '.php')) {
			        include($site . '.php');
			    } else {
			        // Load Plugins-Admin-File (if exists)
			        chdir("../");
			        $plugin = $load->plugin_data($site, 0, true);
			        $plugin_path = @$plugin['path'];
			        @$ifiles = $plugin['admin_file'];
			        @$tfiles = explode(",", $ifiles);
			        if (file_exists($plugin_path . "admin/" . $site . ".php")) {
			            include($plugin_path . "admin/" . $site . ".php");
			        } else {
			            #chdir("admin");
			            echo '<div class="alert alert-danger" role="alert">' . $_language->module['plugin_not_found'] . '</div>';
			            include('info.php');
			        }
				}
			} else {
				include('info.php');
			}
			?>
		</div><!-- /#wrapper -->
		<!--</div>-->

		<!-- ckeditor -->
		<?php
		// Beispiel: CKEditor für Super-Admin (1) und Admin (2)
		if (hasrole($userID, [1, 2])) {
			echo '<script src="../components/ckeditor/ckeditor.js"></script><script src="../components/ckeditor/config.js"></script>';
		} else {
			echo '<script src="../components/ckeditor/ckeditor.js"></script><script src="../components/ckeditor/user_config.js"></script>';
		}
		?>

		<!-- jQuery -->
		<script src="/admin/js/jquery.min.js"></script>

		<script src="/admin/js/page.js"></script>

		<!-- colorpicker -->
		<script src="/admin/js/bootstrap-colorpicker.min.js"></script>
		<script src="/admin/js/colorpicker-rm.js"></script>

		<!-- Bootstrap -->
		<script src="/admin/js/bootstrap.bundle.min.js"></script>
		<script src="/admin/js/bootstrap-switch.js"></script>

		<!-- Menu Plugin JavaScript -->
		<script src="/admin/js/metisMenu.min.js"></script>
		<script src="/admin/js/side-bar.js"></script>

		<script>
			var calledfrom = 'admin';
		</script>
		<!-- dataTables -->
		<script type="text/javascript" src="/admin/js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" src="/admin/js/dataTables.bootstrap5.min.js"></script>
		<script>
			$(document).ready(function() {
				// Retrieve the value saved in localStorage (if it exists)
				var savedPageLength = localStorage.getItem('datatable_page_length') || 10;

				$('#plugini').DataTable({
					'aLengthMenu': [
						[10, 25, 50, 100, -1],
						[10, 25, 50, 100, "<?php
											$lang_datatable_all = detectCurrentLanguage();
											echo ($lang_datatable_all == 'de') ? "Alle" : (($lang_datatable_all == 'en') ? "All" : "Tutti");
											?>"]
					],
					'pageLength': parseInt(savedPageLength), // Set the saved value
					'language': {
						<?php
						$lang_datatable = detectCurrentLanguage();
						echo "'url': '/components/datatables/langs/" .
							(($lang_datatable == 'de') ? "German" : (($lang_datatable == 'en') ? "English" : "Italian")) . ".json'";
						?>
					}
				});

				var table = $('#plugini').DataTable();

				// Save the value when the user changes the number of items to show
				table.on('length.dt', function(e, settings, len) {
					localStorage.setItem('datatable_page_length', len);
				});

				// Delete confirmation modal
				$('#confirm-delete').on('show.bs.modal', function(e) {
					$(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
					$('.debug-url').html('Delete URL: <strong>' + $(this).find('.btn-ok').attr('href') + '</strong>');
				});
			});
		</script>

		<script type="text/javascript">
			// setup tools tips trigger
			const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
			const tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
				return new Tooltip(tooltipTriggerEl, {
					html: true // <- this should do the trick!
				})
			});
		</script>
</body>

</html>
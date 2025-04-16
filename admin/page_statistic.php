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

$_language->readModule('page_statistic', false, true);

use webspell\AccessControl;
// Admin-Zugriff prüfen
AccessControl::checkAdminAccess('ac_modrewrite');

global $_database;
$count_array = array();

// Tabellenliste (ohne Präfixe)
$tables_array = array(
    "plugins_about_us",
    "plugins_articles",
    "plugins_awards",
    "plugins_bannerrotation",
    "plugins_fight_us_challenge",
    "plugins_clanwars",
    "contact",
    "plugins_faq",
    "plugins_faq_categories",
    "plugins_files",
    "plugins_files_categories",
    "plugins_forum_announcements",
    "plugins_forum_boards",
    "plugins_forum_categories",
    "plugins_forum_groups",
    "plugins_forum_moderators",
    "plugins_forum_posts",
    "plugins_forum_ranks",
    "plugins_forum_topics",
    "plugins_gallery",
    "plugins_gallery_categorys",
    "settings_games",
    "plugins_guestbook",
    "plugins_links",
    "plugins_links_categorys",
    "plugins_linkus",
    "plugins_messenger",
    "plugins_news",
    "plugins_news_rubrics",
    "plugins_news_comments",
    "plugins_partners",
    "plugins_poll",
    "plugins_servers",
    "plugins_shoutbox",
    "plugins_sponsors",
    "squads",
    "static",
    "users", // angepasst von `user` auf `users`
    "plugins_videos",
    "plugins_videos_categories",
    "plugins_videos_comments",
    "plugins_todo",
    "plugins_streams",
    "plugins_pic_update",
);

$db_size = 0;
$db_size_op = 0;

// Aktuellen Datenbanknamen ermitteln
if (!isset($db)) {
    $get = safe_query("SELECT DATABASE()");
    $ret = mysqli_fetch_array($get);
    $db = $ret[0];
}

// Gesamtanzahl Tabellen
$query = safe_query("SHOW TABLES");
$count_tables = mysqli_num_rows($query);

// Durchlaufe alle Tabellen
foreach ($tables_array as $table) {
    if (mysqli_num_rows(safe_query("SHOW TABLE STATUS FROM `$db` LIKE '$table'"))) {
        $check = mysqli_query($_database, "SELECT * FROM `$table`");
        if ($check) {
            $sql = safe_query("SHOW TABLE STATUS FROM `$db` LIKE '$table'");
            $data = mysqli_fetch_array($sql);
            $db_size += ($data['Data_length'] + $data['Index_length']);
            if (strtolower($data['Engine']) == "myisam") {
                $db_size_op += $data['Data_free'];
            }

            $table_name = isset($_language->module[$table]) ? $_language->module[$table] : ucfirst(str_replace("_", " ", $table));
            $count_array[] = array($table_name, $data['Rows']);
        }
    }
}
?>

<div class="card">
    <div class="card-header">
        <i class="bi bi-database"></i> <?php echo $_language->module['database']; ?>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="row bt">
                    <div class="col-md-6"><?php echo $_language->module['mysql_version']; ?>:</div>
                    <div class="col-md-6"><span class="pull-right text-muted small"><em><?php echo mysqli_get_server_info($_database); ?></em></span></div>
                </div>
                <div class="row bt">
                    <div class="col-md-6"><?php echo $_language->module['size']; ?>:</div>
                    <div class="col-md-6"><span class="pull-right text-muted small"><em><?php echo $db_size; ?> Bytes (<?php echo round($db_size / 1024 / 1024, 2); ?> MB)</em></span></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row bt">
                    <div class="col-md-6"><?php echo $_language->module['overhead']; ?>:</div>
                    <div class="col-md-6"><span class="pull-right text-muted small"><em>
                        <?php echo $db_size_op; ?> Bytes
                        <?php if ($db_size_op != 0) {
                            echo '<a href="admincenter.php?site=database&amp;action=optimize&amp;back=page_statistic"><font color="red"><b>' . $_language->module['optimize'] . '</b></font></a>';
                        } ?>
                    </em></span></div>
                </div>
                <div class="row bt">
                    <div class="col-md-6"><?php echo $_language->module['tables']; ?>:</div>
                    <div class="col-md-6"><span class="pull-right text-muted small"><em><?php echo $count_tables; ?></em></span></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <i class="bi bi-list-ol"></i> <?php echo $_language->module['page_stats']; ?>
    </div>
    <div class="card-body">
        <div class="panel panel-default">
            <div class="row">
                <?php
                for ($i = 0; $i < count($count_array); $i += 2) {
                    ?>
                    <div class="col-md-6">
                        <div class="row bte">
                            <div class="col-md-6"><?php echo $count_array[$i][0]; ?>:</div>
                            <div class="col-md-6"><span class="pull-right text-muted small"><em><?php echo $count_array[$i][1]; ?></em></span></div>
                        </div>
                    </div>
                    <?php if (isset($count_array[$i + 1])) { ?>
                        <div class="col-md-6">
                            <div class="row bte">
                                <div class="col-md-6"><?php echo $count_array[$i + 1][0]; ?>:</div>
                                <div class="col-md-6"><span class="pull-right text-muted small"><em><?php echo $count_array[$i + 1][1]; ?></em></span></div>
                            </div>
                        </div>
                    <?php } ?>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>

<?php
$name = $_GET[ 'modulname' ];
// Name Tabelle | Where Klause | ID name
DeleteData("settings_plugins","modulname",$name);
DeleteData("navigation_dashboard_links","modulname",$name);
DeleteData("navigation_website_sub","modulname",$name);
DeleteData("settings_module","modulname",$name);
DeleteData("settings_widgets","modulname",$name);
safe_query("DROP TABLE IF EXISTS "plugins_".$name."");
safe_query("DROP TABLE IF EXISTS "plugins_footer_target");
?>
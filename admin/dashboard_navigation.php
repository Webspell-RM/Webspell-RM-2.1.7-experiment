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

// Sichere die Eingabewerte aus $_POST
$name = $_POST['name'];
$action = $_POST['action'];
$catID = $_POST['catID'];
$hash = $_POST['captcha_hash'];

// Wenn keine Aktion ausgewählt wurde
if ($action == '') {
    // Zeige die Übersicht der Kategorien an
    $anz = mysqli_num_rows(safe_query("SELECT catID FROM `navigation_dashboard_categories`"));

    if ($anz > 0) {
        $cats = safe_query("SELECT * FROM `navigation_dashboard_categories` ORDER BY `cat_name`");
        while ($cat = mysqli_fetch_assoc($cats)) {
            echo "<div>{$cat['cat_name']} - Kategorie ID: {$cat['catID']}</div>";
        }
    } else {
        echo "Es sind keine Kategorien vorhanden.";
    }
}

// Wenn Aktion "save" durchgeführt wird
elseif ($action == 'save') {
    // Sicherstellen, dass der Captcha-Wert korrekt ist
    $captcha = new \webspell\Captcha;
    if ($captcha->checkCaptcha(0, $hash)) {
        // Überprüfen, ob der Kategorie-Name angegeben wurde
        if (!empty($name)) {
            // Neue Kategorie speichern
            $insertQuery = "INSERT INTO `navigation_dashboard_categories` (`cat_name`) VALUES ('" . escape($name, 'string') . "')";
            safe_query($insertQuery);
            echo "Kategorie wurde erfolgreich gespeichert.";
        } else {
            echo "Bitte einen Kategorienamen angeben.";
        }
    } else {
        echo "Captcha-Überprüfung fehlgeschlagen.";
    }
}

// Wenn Aktion "delete" durchgeführt wird
elseif ($action == 'delete') {
    // Sicherstellen, dass eine gültige Kategorie-ID angegeben wurde
    if (is_numeric($catID) && $catID > 0) {
        // Lösche die Kategorie
        $deleteQuery = "DELETE FROM `navigation_dashboard_categories` WHERE `catID` = " . (int)$catID;
        safe_query($deleteQuery);
        echo "Kategorie wurde erfolgreich gelöscht.";
    } else {
        echo "Ungültige Kategorie-ID.";
    }
}

// Wenn Aktion "edit" durchgeführt wird
elseif ($action == 'edit') {
    // Sicherstellen, dass eine gültige Kategorie-ID und ein Name angegeben wurde
    if (is_numeric($catID) && $catID > 0 && !empty($name)) {
        // Bearbeite die Kategorie
        $updateQuery = "UPDATE `navigation_dashboard_categories` SET `cat_name` = '" . escape($name, 'string') . "' WHERE `catID` = " . (int)$catID;
        safe_query($updateQuery);
        echo "Kategorie wurde erfolgreich bearbeitet.";
    } else {
        echo "Bitte eine gültige Kategorie-ID und einen Namen angeben.";
    }
}



if (isset($_GET[ 'action' ])) {
    $action = $_GET[ 'action' ];
} else {
    $action = '';
}

if ($action == "add") {
    // Ausgabe der Karte mit Header und Breadcrumb-Navigation
    echo '<div class="card">
        <div class="card-header"><i class="bi bi-menu-app"></i> 
            ' . $_language->module['dashnavi'] . '
        </div>
            
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="admincenter.php?site=dashboard_navigation">' . $_language->module['dashnavi'] . '</a></li>
    <li class="breadcrumb-item active" aria-current="page">' . $_language->module['add_link'] . '</li>
  </ol>
</nav>
     <div class="card-body">';

    // Abfrage der Kategorien aus der Tabelle `navigation_dashboard_categories`
    $ergebnis = safe_query("SELECT * FROM `navigation_dashboard_categories` ORDER BY `sort`");
    $cats = '<select class="form-select" name="catID">';
    
    // Schleife über alle Kategorien
    while ($ds = mysqli_fetch_array($ergebnis)) {
         $name = $ds['name'];

        // Übersetzung der Kategoriebezeichnung
        $translate = new multiLanguage(detectCurrentLanguage());
        $translate->detectLanguages($name);
        $name = $translate->getTextByLanguage($name);
        
        // Datenarray zur späteren Verwendung
        $data_array = array();
        $data_array['$name'] = $ds['name'];

        // Optionen im Dropdown-Menü hinzufügen
        $cats .= '<option value="' . $ds['catID'] . '">' . $name . '</option>';
    }
    $cats .= '</select>';

    // Captcha-Objekt zur Generierung eines Hashes
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    // Ausgabe des Formulars zur Hinzufügung eines neuen Links
    echo '<form class="form-horizontal" method="post" action="admincenter.php?site=dashboard_navigation">
    <div class="mb-3 row">
    <label class="col-md-2 control-label">'.$_language->module['category'].':</label>
    <div class="col-md-8"><span class="text-muted small"><em>
      ' . $cats . '</em></span>
    </div>
    </div>
 <div class="mb-3 row">
    <label class="col-md-2 control-label"></label>
    <div class="col-md-8">'.$_language->module['info'].'</div>
  </div> 


    <div class="mb-3 row">
    <label class="col-md-2 control-label">'.$_language->module['name'].':</label>
    <div class="col-md-8"><span class="text-muted small"><em>
        <input class="form-control" type="text" name="name" size="60"></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-md-2 control-label">'.$_language->module['url'].':</label>
    <div class="col-md-8"><span class="text-muted small"><em>
        <input class="form-control" type="text" name="url" size="60"></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <div class="col-md-offset-2 col-md-10">
      <input type="hidden" name="captcha_hash" value="' . $hash . '"><button class="btn btn-success" type="submit" name="save"><i class="bi bi-box-arrow-down"></i> ' . $_language->module['add_link'] . '</button>
    </div>
  </div>
   
          </form></div></div>';

}elseif ($action == "edit") {
    // Ausgabe der Karte mit Header und Breadcrumb-Navigation
    echo '<div class="card">
        <div class="card-header"><i class="bi bi-menu-app"></i> 
            ' . $_language->module['dashnavi'] . '
        </div>
            
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="admincenter.php?site=dashboard_navigation">' . $_language->module['dashnavi'] . '</a></li>
    <li class="breadcrumb-item active" aria-current="page">' . $_language->module['edit_link'] . '</li>
  </ol>
</nav>
     <div class="card-body">';

    // Holen des linkID aus der URL
    $linkID = $_GET['linkID'];
    
    // Abfrage des Links aus der Tabelle `navigation_dashboard_links`
    $ergebnis = safe_query("SELECT * FROM `navigation_dashboard_links` WHERE `linkID` = '$linkID'");
    $ds = mysqli_fetch_array($ergebnis);

    // Abfrage der Kategorien aus der Tabelle `navigation_dashboard_categories`
    $category = safe_query("SELECT * FROM `navigation_dashboard_categories` ORDER BY `sort`");
    $cats = '<select class="form-select" name="catID">';
    
    // Schleife über alle Kategorien
    while ($dc = mysqli_fetch_array($category)) {
        $name = $dc['name'];

        // Übersetzung der Kategoriebezeichnung
        $translate = new multiLanguage(detectCurrentLanguage());
        $translate->detectLanguages($name);
        $name = $translate->getTextByLanguage($name);
        
        // Überprüfen, ob die Kategorie des Links mit der aktuellen Kategorie übereinstimmt
        if ($ds['catID'] == $dc['catID']) {
            $selected = " selected=\"selected\"";
        } else {
            $selected = "";
        }
        
        // Hinzufügen der Option zur Auswahl
        $cats .= '<option value="' . $dc['catID'] . '"' . $selected . '>' . $name . '</option>';
    }
    $cats .= '</select>';

    // Captcha-Objekt zur Generierung eines Hashes
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    // Ausgabe des Formulars zur Bearbeitung des Links
    echo '<form class="form-horizontal" method="post" action="admincenter.php?site=dashboard_navigation">
    <div class="mb-3 row">
    <label class="col-md-2 control-label">'.$_language->module['category'].':</label>
    <div class="col-md-8"><span class="text-muted small"><em>
      ' . $cats . '</em></span>
    </div>
  </div>
  
  <div class="mb-3 row">
    <label class="col-md-2 control-label">'.$_language->module['name'].':</label>
    <div class="col-md-8"> '.$_language->module['info'].' <span class="text-muted small"><em>
      <input class="form-control" type="text" name="name" value="' . getinput($ds['name']) . '" size="60"></em></span>
    </div>
  </div>
  
  <div class="mb-3 row">
    <label class="col-md-2 control-label">'.$_language->module['url'].':</label>
    <div class="col-md-8"><span class="text-muted small"><em>
      <input class="form-control" type="text" name="url" value="' . getinput($ds['url']) . '" size="60"></em></span>
    </div>
  </div>
  
  <div class="mb-3 row">
    <div class="col-md-offset-2 col-md-10">
      <input type="hidden" name="captcha_hash" value="'.$hash.'" />
      <input type="hidden" name="linkID" value="' . $linkID . '">
      <button class="btn btn-warning" type="submit" name="saveedit"><i class="bi bi-box-arrow-down"></i> ' . $_language->module['edit_link'] . '</button>
    </div>
  </div>
</form>
</div></div>';

}elseif ($action == "addcat") {
    // Ausgabe der Karte mit Header und Breadcrumb-Navigation
    echo '<div class="card">
        <div class="card-header"><i class="bi bi-menu-app"></i> 
            ' . $_language->module['dashnavi'] . '
        </div>
            
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="admincenter.php?site=dashboard_navigation">' . $_language->module['dashnavi'] . '</a></li>
    <li class="breadcrumb-item active" aria-current="page">' . $_language->module['add_category'] . '</li>
  </ol>
</nav>
     <div class="card-body">';

    // Erstelle eine neue Captcha-Transaktion
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    // Formular zur Hinzufügung einer neuen Kategorie
    echo '<form class="form-horizontal" method="post" action="admincenter.php?site=dashboard_navigation">
    
    <div class="mb-3 row">
    <label class="col-md-2 control-label">'.$_language->module['fa_name'].':</label>
    <div class="col-md-8"><span class="text-muted small"><em>
      <input class="form-control" type="text" name="fa_name" size="60"></em></span>
    </div>
  </div>
  
  <div class="mb-3 row">
    <label class="col-md-2 control-label">'.$_language->module['name'].':</label>
    <div class="col-md-8"><span class="text-muted small"><em>
      <input class="form-control" type="text" name="name" size="60"></em></span>
    </div>
  </div>
  
  <div class="mb-3 row">
    <div class="col-md-offset-2 col-md-10">
      <input type="hidden" name="captcha_hash" value="'.$hash.'" />
      <button class="btn btn-success" type="submit" name="savecat"><i class="bi bi-box-arrow-down"></i> ' . $_language->module['add_category'] . '</button>
    </div>
  </div>

    </form>
    </div></div>';

}elseif ($action == "editcat") {
    // Ausgabe der Karte mit Header und Breadcrumb-Navigation
    echo '<div class="card">
        <div class="card-header"><i class="bi bi-menu-app"></i> 
            ' . $_language->module['dashnavi'] . '
        </div>
            
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="admincenter.php?site=dashboard_navigation">' . $_language->module['dashnavi'] . '</a></li>
    <li class="breadcrumb-item active" aria-current="page">' . $_language->module['edit_category'] . '</li>
  </ol>
</nav>
     <div class="card-body">';

    // Hole die catID aus der URL
    $catID = $_GET['catID'];

    // SQL-Abfrage, um die Kategorie-Daten zu holen
    $ergebnis = safe_query("SELECT * FROM `navigation_dashboard_categories` WHERE `catID`='$catID'");
    $ds = mysqli_fetch_array($ergebnis);

    // Erstelle eine neue Captcha-Transaktion
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    // Formular zur Bearbeitung der Kategorie
    echo '<form class="form-horizontal" method="post" action="admincenter.php?site=dashboard_navigation">
    
    <div class="mb-3 row">
    <label class="col-md-2 control-label">'.$_language->module['fa_name'].':</label>
    <div class="col-md-8"><span class="text-muted small"><em>
      <input class="form-control" type="text" name="fa_name" value="' . getinput($ds['fa_name']) . '" size="60"></em></span>
    </div>
  </div>

  <div class="mb-3 row">
    <label class="col-md-2 control-label">' . $_language->module['name'] . ':</label>
    <div class="col-md-8"><span class="text-muted small"><em>
      <input class="form-control" type="text" name="name" value="' . getinput($ds['name']) . '" size="60"></em></span>
    </div>
  </div>

  <div class="mb-3 row">
    <div class="col-md-offset-2 col-md-10">
      <input type="hidden" name="captcha_hash" value="'.$hash.'" />
      <input type="hidden" name="catID" value="' . $catID . '">
      <button class="btn btn-warning" type="submit" name="saveeditcat"><i class="bi bi-box-arrow-down"></i> ' . $_language->module['edit_category'] . '</button>
    </div>
  </div>

    </form></div></div>';

} else {

echo '<div class="card">
    <div class="card-header"><i class="bi bi-menu-app"></i>
        ' . $_language->module['dashnavi'] . '
    </div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">' . $_language->module['dashnavi'] . '</li>
        </ol>
    </nav>

    <div class="card-body">
        <div class="mb-3 row">
            <label class="col-md-1 control-label">' . $_language->module['options'] . ':</label>
            <div class="col-md-8">
                <a class="btn btn-primary" href="admincenter.php?site=dashboard_navigation&amp;action=addcat" class="input"><i class="bi bi-plus-circle"></i> ' .
                    $_language->module['new_category'] . '</a>
                <a class="btn btn-primary" href="admincenter.php?site=dashboard_navigation&amp;action=add" class="input"><i class="bi bi-plus-circle"></i> ' .
                    $_language->module['new_link'] . '</a>
            </div>
        </div>';

echo '<form method="post" action="admincenter.php?site=dashboard_navigation">
    <table class="table">
        <thead>
            <tr>
                <th width="25%"><b>' . $_language->module['name'] . '</b></th>
                <th width="25%"><b>Link</b></th>
                <th width="20%"><b>' . $_language->module['actions'] . '</b></th>
                <th width="8%"><b>' . $_language->module['sort'] . '</b></th>
            </tr>
        </thead>';

$ergebnis = safe_query("SELECT * FROM `navigation_dashboard_categories` ORDER BY `sort`");
$tmp = mysqli_fetch_assoc(safe_query("SELECT count(`catID`) AS `cnt` FROM `navigation_dashboard_categories`"));
$anz = $tmp['cnt'];

$CAPCLASS = new \webspell\Captcha;
$CAPCLASS->createTransaction();
$hash = $CAPCLASS->getHash();

while ($ds = mysqli_fetch_array($ergebnis)) {
    // Sortierung der Kategorien
    $list = '<select name="sortcat[]">';
    for ($n = 1; $n <= $anz; $n++) {
        $list .= '<option value="' . $ds['catID'] . '-' . $n . '">' . $n . '</option>';
    }
    $list .= '</select>';
    $list = str_replace(
        'value="' . $ds['catID'] . '-' . $ds['sort'] . '"',
        'value="' . $ds['catID'] . '-' . $ds['sort'] . '" selected="selected"',
        $list
    );

    $sort = $list;

    // Aktionen für jede Kategorie
    $catactions =
        '<a class="btn btn-warning" href="admincenter.php?site=dashboard_navigation&amp;action=editcat&amp;catID=' . $ds['catID'] .
        '" class="input"><i class="bi bi-pencil-square"></i> ' . $_language->module['edit'] . '</a>
        
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=dashboard_navigation&amp;delcat=true&amp;catID=' . $ds['catID'] .
        '&amp;captcha_hash=' . $hash . '"><i class="bi bi-trash3"></i> ' . $_language->module['delete'] . '</button>
        
        <!-- Modal -->
        <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><i class="bi bi-menu-app"></i> ' . $_language->module['dashnavi'] . '</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="' . $_language->module['close'] . '"></button>
                    </div>
                    <div class="modal-body"><p><i class="bi bi-trash3"></i> ' . $_language->module['really_delete_category'] . '</p></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> ' . $_language->module['close'] . '</button>
                        <a class="btn btn-danger btn-ok"><i class="bi bi-trash3"></i> ' . $_language->module['delete'] . '</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal END -->';

    $name = $ds['name'];
    $translate = new multiLanguage(detectCurrentLanguage());
    $translate->detectLanguages($name);
    $name = $translate->getTextByLanguage($name);
    
    echo '<tr class="table-secondary">
        <td width="25%" class="td_head admin-nav-modal"><b>' . $name . '</b></td>
        <td width="25%" class="td_head admin-nav-modal"></td>
        <td width="20%" class="td_head">' . $catactions . '</td>
        <td width="8%" class="td_head">' . $sort . '</td>
    </tr>';

    // Links zu der Kategorie
    $links = safe_query("SELECT * FROM `navigation_dashboard_links` WHERE `catID`='" . $ds['catID'] . "' ORDER BY `sort`");
    $tmp = mysqli_fetch_assoc(
        safe_query(
            "SELECT count(`linkID`) AS `cnt`
            FROM `navigation_dashboard_links` WHERE `catID`='" . $ds['catID'] . "'"
        )
    );
    $anzlinks = $tmp['cnt'];

    $i = 1;
    if (mysqli_num_rows($links)) {
        while ($db = mysqli_fetch_array($links)) {
            $td = ($i % 2) ? 'td1' : 'td2';

            $name = $db['name'];
            $translate = new multiLanguage(detectCurrentLanguage());
            $translate->detectLanguages($name);
            $name = $translate->getTextByLanguage($name);
            
            $linklist = '<select name="sortlinks[]">';
            for ($n = 1; $n <= $anzlinks; $n++) {
                $linklist .= '<option value="' . $db['linkID'] . '-' . $n . '">' . $n . '</option>';
            }
            $linklist .= '</select>';
            $linklist = str_replace(
                'value="' . $db['linkID'] . '-' . $db['sort'] . '"',
                'value="' . $db['linkID'] . '-' . $db['sort'] . '" selected="selected"',
                $linklist
            );

            echo '<tr>
                <td class="' . $td . '">&nbsp;-&nbsp;<b>' . $name . '</b></td>
                <td class="' . $td . '"><small>' . $db['url'] . '</small></td>
                <td class="' . $td . '">
                    <a href="admincenter.php?site=dashboard_navigation&amp;action=edit&amp;linkID=' . $db['linkID'] . '" class="btn btn-warning"><i class="bi bi-pencil-square"></i> ' . $_language->module['edit'] . '</a>

                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete-link" data-href="admincenter.php?site=dashboard_navigation&delete=true&linkID=' . $db['linkID'] . '&captcha_hash=' . $hash . '"><i class="bi bi-trash3"></i> ' . $_language->module['delete'] . '</button>

                    <!-- Modal -->
                    <div class="modal fade" id="confirm-delete-link" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel"><i class="bi bi-menu-app"></i> ' . $_language->module['dashnavi'] . '</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="' . $_language->module['close'] . '"></button>
                                </div>
                                <div class="modal-body"><p><i class="bi bi-trash3"></i> ' . $_language->module['really_delete_link'] . '</p></div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> ' . $_language->module['close'] . '</button>
                                    <a class="btn btn-danger btn-ok"><i class="bi bi-trash3"></i> ' . $_language->module['delete'] . '</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal END -->
                </td>
                <td class="' . $td . '">' . $linklist . '</td>
            </tr>';
            $i++;
        }
    } else {
        echo '<tr>
            <td class="td1" colspan="5">' . $_language->module['no_additional_links_available'] . '</td>
        </tr>';
    }
}

echo '<tr>
        <td class="td_head" colspan="6" align="right">
            <button class="btn btn-primary" type="submit" name="sortieren"><i class="bi bi-sort-numeric-up"></i>  ' . $_language->module['to_sort'] . '</button>
        </td>
    </tr>
</table>
</form></div></div>';

}

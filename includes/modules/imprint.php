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


$_language->readModule('imprint');
global $hp_title;

$config = mysqli_fetch_array(safe_query("SELECT selected_style FROM settings_headstyle_config WHERE id=1"));
$class = htmlspecialchars($config['selected_style']);

// Header-Daten
$data_array = [
    'class'    => $class,
    'title' => $_language->module['title'],
    'subtitle' => 'Imprint'
];

echo $tpl->loadTemplate("imprint", "head", $data_array, 'theme');


// Impressum-Daten auslesen
$stmt = $_database->prepare("SELECT * FROM settings_imprint LIMIT 1");
$stmt->execute();
$result = $stmt->get_result();
$imprint_data = $result->fetch_assoc();

// Typ-Bezeichnungen
$type_labels = [
    'private' => $_language->module['private_option'] ?? 'Privat',
    'association' => $_language->module['association_option'] ?? 'Verein',
    'small_business' => $_language->module['small_business_option'] ?? 'Kleinunternehmer',
    'company' => $_language->module['company_option'] ?? 'Unternehmen',
    'unknown' => 'Unbekannt'
];


$translate = new multiLanguage(detectCurrentLanguage());
$translate->detectLanguages($imprint_data['disclaimer']);
// Basis-Labels (immer vorhanden)
$data_array = [
    'impressum_type_label' => $_language->module['impressum_type_label'] ?? 'Typ',
    'represented_by_label' => $_language->module['represented_by_company_label'] ?? $_language->module['represented_by_label'] ?? 'Vertreten durch',
    'tax_id_label' => $_language->module['tax_id_company_label'] ?? $_language->module['tax_id_label'] ?? 'Steuernummer',
    'email_label' => $_language->module['email_label'] ?? 'E-Mail',
    'website_label' => $_language->module['website_label'] ?? 'Webseite',
    'phone_label' => $_language->module['phone_label'] ?? 'Telefon',
    'disclaimer_label' => $_language->module['disclaimer_label'] ?? 'Haftungsausschluss',
    'association_label' => $_language->module['association_label'] ?? 'Vereinsname',
    'imprint_info' => $_language->module['imprint_info'] ?? '',
];

// Dynamisches name_label je nach Typ setzen
$type = $imprint_data['type'] ?? 'unknown';
switch ($type) {
    case 'association':
        $data_array['name_label'] = $_language->module['association_label'] ?? 'Vereinsname';
        break;
    case 'company':
    case 'small_business':
        $data_array['name_label'] = $_language->module['company_name_label'] ?? 'Firmenname';
        break;
    default:
        $data_array['name_label'] = $_language->module['name_label'] ?? 'Name';
        break;
}

// Werte zuweisen (mit sicheren Fallbacks)
$data_array += [
    'impressum_hp_name' => $hp_title,
    'impressum_type' => $type_labels[$type] ?? $type_labels['unknown'],
    'company_name' => $imprint_data['company_name'] ?? '',
    'represented_by' => $imprint_data['represented_by'] ?? '',
    'tax_id' => $imprint_data['tax_id'] ?? '',
    'email' => $imprint_data['email'] ?? '',
    'website' => $imprint_data['website'] ?? '',
    'phone' => $imprint_data['phone'] ?? '',
    #'disclaimer' => $imprint_data['disclaimer'] ?? ''
    'disclaimer' => $translate->getTextByLanguage($imprint_data['disclaimer'])
];



// Template für das Frontend laden
echo $tpl->loadTemplate("imprint", "content", $data_array, 'theme');


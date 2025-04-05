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

if (isset($_GET['type'])) {
    $type = $_GET['type'];
} else {
    $type = null;
}

$_language->readModule('error');

// Default error message
$error_header = "Error";
$error_message = "An error occurred.";

if ($type == 404) {
    $error_header = $_language->module['error_404'];
    $error_message = $_language->module['message_404'];
}

// Load template with direct replacement for placeholders
$template = $GLOBALS['_template']->loadTemplate("error_template", [
    'error_header' => $error_header,
    'error_message' => $error_message
]);
echo $template;

// Check if 'url' is set in GET request
if (isset($_GET['url']) && !empty($_GET['url'])) {
    $urlparts = preg_split('/[\s.,-\/]+/si', $_GET['url']);
    $results = array();

    // Loop through each tag from the URL parts and fetch data
    foreach ($urlparts as $tag) {
        $sql = safe_query("SELECT * FROM " . PREFIX . "tags WHERE tag='" . mysqli_real_escape_string($GLOBALS['$_database'], $tag) . "'");
        if ($sql->num_rows) {
            while ($ds = mysqli_fetch_assoc($sql)) {
                $data_check = null;

                // Check for related content based on the tag
                switch ($ds['rel']) {
                    case "news":
                        $data_check = \webspell\Tags::getNews($ds['ID']);
                        break;
                    case "articles":
                        $data_check = \webspell\Tags::getArticle($ds['ID']);
                        break;
                    case "static":
                        $data_check = \webspell\Tags::getStaticPage($ds['ID']);
                        break;
                    case "faq":
                        $data_check = \webspell\Tags::getFaq($ds['ID']);
                        break;
                }

                if (is_array($data_check)) {
                    $results[] = $data_check;
                }
            }
        }
    }

    // Display search results
    if (count($results) > 0) {
        $template = $GLOBALS['_template']->loadTemplate("search_results_template", [
            'results_count' => count($results),
            'results_found' => $_language->module['results_found']
        ]);
        echo $template;

        // Display each result
        foreach ($results as $entry) {
            $date = getformatdate($entry['date']);
            $type = $entry['type'];
            $auszug = $entry['content'];
            $link = $entry['link'];
            $title = $entry['title'];

            // Directly replace placeholders in the template
            $template = $GLOBALS['_template']->loadTemplate("search_entry_template", [
                'date' => $date,
                'link' => $link,
                'title' => $title,
                'auszug' => $auszug
            ]);
            echo $template;
        }
    } else {
        // No results found
        $template = $GLOBALS['_template']->loadTemplate("no_results_template", [
            'no_results_found' => $_language->module['no_results_found']
        ]);
        echo $template;
    }
} else {
    // No URL provided
    $template = $GLOBALS['_template']->loadTemplate("no_url_template", [
        'no_url_provided' => $_language->module['no_url_provided']
    ]);
    echo $template;
}

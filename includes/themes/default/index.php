<?php

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
 * @copyright       2018-2024 by webspell-rm.de <https://www.webspell-rm.de>                                                          *
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

$_language->readModule('index');

$index_language = $_language->module;


// Überprüfe, ob die Session bereits gestartet wurde
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


header('X-UA-Compatible: IE=edge');
?>
<!DOCTYPE html>
<html class="h-100" lang="<?php echo $_language->language; ?>">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <meta name="description" content="<?php echo htmlspecialchars($description, ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($keywords, ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="robots" content="all">
    <meta name="abstract" content="Anpasser an Webspell-RM">
    <meta name="copyright" content="Copyright &copy; 2018-2025 by webspell-rm.de">
    <meta name="author" content="webspell-rm.de">
    <meta name="distribution" content="global">
    <link rel="SHORTCUT ICON" href="./includes/themes/<?php echo htmlspecialchars($theme_name, ENT_QUOTES, 'UTF-8'); ?>/images/favicon.ico">

    <!-- Head & Title include -->
    <title><?= get_sitetitle(); ?></title>
    <!--<base href="<?php #echo htmlspecialchars($rewriteBase, ENT_QUOTES, 'UTF-8'); ?>">-->
    <base href="/">

    <link type="application/rss+xml" rel="alternate" href="tmp/rss.xml" title="<?php echo htmlspecialchars($myclanname, ENT_QUOTES, 'UTF-8'); ?> - RSS Feed">
    <link type="text/css" rel="stylesheet" href="./components/cookies/css/cookieconsent.css" media="print" onload="this.media='all'">
    <link type="text/css" rel="stylesheet" href="./components/cookies/css/iframemanager.css" media="print" onload="this.media='all'">
    
    <?php
    $lang = $_language->language; // Language Variable setzen         
    echo $components_css;
    echo $theme_css;
    echo '<!--Plugin css-->' . PHP_EOL;
    echo ($_pluginmanager->plugin_loadheadfile_css());
    echo '<!--Plugin css END-->' . PHP_EOL;
    echo '<!--Widget css-->' . PHP_EOL;
    echo ($_pluginmanager->plugin_loadheadfile_widget_css());
    echo '<!--Widget css END-->' . PHP_EOL;
    ?>
    <link type="text/css" rel="stylesheet" href="./includes/themes/<?php echo htmlspecialchars($theme_name, ENT_QUOTES, 'UTF-8'); ?>/css/stylesheet.css" />
</head>

<body>
    <div class="d-flex flex-column sticky-footer-wrapper">
        <?php echo get_lock_modul(); ?>
        <?php echo get_header_modul(); ?>
        <?php echo get_navigation_modul(); ?>
        <?php echo get_content_head_modul(); ?>
        <main class="flex-fill">
            <div class="container">
                <div class="row">
                    <?php echo get_left_side_modul(); ?>
                    <div class="col">
                        <?php echo get_content_up_modul(); ?>
                        <?php echo get_mainContent(); ?>
                        <?php echo get_content_down_modul(); ?>
                    </div>
                    <?php echo get_right_side_modul(); ?>
                </div>
            </div>
        </main>
        <?php echo get_content_foot_modul(); ?>
        <?php echo get_footer_modul(); ?>
    </div>
    <div class="scroll-top-wrapper">
        <span class="scroll-top-inner">
            <i class="bi bi-arrow-up-circle" style="font-size: 2rem;" aria-label="Nach oben scrollen"></i>
        </span>
    </div>
    <div class="cookies-wrapper">
        <span class="cookies-top-inner">
            <i class="bi bi-gear" style="font-size: 2rem;" data-cc="c-settings" data-toggle="tooltip" data-bs-title="Cookie settings"></i>
        </span>
    </div>
    <script defer src="https://www.google.com/recaptcha/api.js"></script>
    <?php
    echo $components_js;
    echo $theme_js;
    echo '<!--Plugin js-->' . PHP_EOL;
    echo ($_pluginmanager->plugin_loadheadfile_js());
    echo '<!--Plugin js END-->' . PHP_EOL;
    echo '<!--Widget js-->' . PHP_EOL;
    echo ($_pluginmanager->plugin_loadheadfile_widget_js());
    echo '<!--Widget js END-->' . PHP_EOL;
    /* ckeditor */
    #echo get_editor();
    /* ckeditor END*/
    ?>
    <!-- Cookies Abfrage -->
    <script defer src="./components/cookies/js/iframemanager.js"></script>
    <script defer src="./components/cookies/js/cookieconsent.js"></script>
    <script defer src="./components/cookies/js/cookieconsent-init.js"></script>
    <script defer src="./components/cookies/js/app.js"></script>
    <!-- Language recognition for DataTables -->
    <script>
        const LangDataTables = <? echo "'$_language->language'"; ?>
    </script>
    <script type="text/javascript">
        // Example starter JavaScript for disabling form submissions if there are invalid fields
        (function() {
            'use strict'

            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.querySelectorAll('.needs-validation')

            // Loop over them and prevent submission
            Array.prototype.slice.call(forms)
                .forEach(function(form) {
                    form.addEventListener('submit', function(event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }

                        form.classList.add('was-validated')
                    }, false)
                })
        })()
    </script>
</body>

</html>
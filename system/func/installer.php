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

function rmmodinstall($rubric, $modus, $dir, $id, $getversion) {
    include('../system/func/update_base.php');

    $list = array('0', '1', '2', '3', '4', '5', '6');
    if ($modus == 'install') {
        $installmodus = 'setup';
        $installmodustwo = 'install';
    } else {
        $installmodus = 'update';
        $installmodustwo = 'update';
    }

    if ($rubric == 'temp') {
        $plugin = $updateserverurl.'/theme/style-base_v.'.$getversion.'/';
        $pluginlist = $updateserverurl.'/theme/style-base_v.'.$getversion.'/list.json';
        $instdir = 'expansion';
        $contenthead = 'Themefiles';
    } else {
        $plugin = $updateserverurl.'/plugin/plugin-base_v.'.$getversion.'/';
        $pluginlist = $updateserverurl.'/plugin/plugin-base_v.'.$getversion.'/list.json';
        $instdir = 'plugins';
        $contenthead = 'Pluginfiles';
    }

    $dir = str_replace('/', '', $dir);

    $filesgrant = array();
    if ($rubric == 'temp') {
        $result = curl_json2array($pluginlist);
        if (isset($result['item'.$id]['required'])) {
            $replacement[] = $dir;
            $pattern = explode(',', $result['item'.$id]['required']);
            foreach ($pattern as $value) {
                $replacement[] .= $value;
            }
            $multivar = array($dir);
            unset($replacement['0']);
            $multivarplugin = $replacement;
        } else {
            $multivar = array($dir);
            $multivarplugin = '';
        }
    } else {
        $result = curl_json2array($pluginlist);
        if (isset($result['item'.$id]['required'])) {
            $replacement[] = $dir;
            $pattern = explode(',', $result['item'.$id]['required']);
            foreach ($pattern as $value) {
                $replacement[] .= $value;
            }
            $multivar = $replacement;
        } else {
            $multivar = array($dir);
        }
    }

    foreach (array_merge(array_filter($multivar)) as $dir) {
        unset($filesgrant);
        $url = $plugin.$dir.'/'.$installmodus.'.json';
        try {
            $result = curl_json2array($url);
            if ($result != "NULL") {
                foreach ($list as $value) {
                    $index = $value;
                    $files = @count($result['items'][$index])-1;
                    if ($files != '0') {
                        for ($i = 1; $i <= $files; $i++) {
                            try {
                                if (isset($result['items'][$index]['file'.$i])) {
                                    $filePath = '../includes/'.$instdir.'/'.$result['items'][$index]['file'.$i];
                                    $pathParts = pathinfo($filePath);
                                    if (!file_exists($pathParts['dirname'])) {
                                        mkdir($pathParts['dirname'], 0777, true);
                                    }

                                    // Überprüfen, ob der Pfad eine Datei ist und nicht ein Verzeichnis
                                    if (!is_dir($filePath)) {
                                        $content = $plugin.$result['items'][$index]['file'.$i].'.txt';
                                        $curl = curl_init();
                                        curl_setopt($curl, CURLOPT_URL, $content);
                                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                                        $content = curl_exec($curl);
                                        file_put_contents($filePath, $content);
                                        curl_close($curl);
                                        $filesgrant[] = 'File created: '.$filePath.'<br />';
                                    } else {
                                        echo 'Es handelt sich um ein Verzeichnis, keine Datei: ' . $filePath;
                                    }
                                } else {
                                    echo 'Fehlendes Array-Element oder Datei für ' . $index . ' und ' . $i;
                                }
                            } catch (Exception $s) {
                                echo $s->getMessage();
                            }
                        }
                    }
                }

                echo'
                    <div class=\'card\'>
                        <div class=\'card-header\'>
                            Loading '.$contenthead.'
                        </div>
                        <div class=\'card-body\'>
                            <div class="alert alert-success" role="alert">
                ';
                foreach ($filesgrant as $filesgranted) {
                    echo $filesgranted;
                }
                echo "All ".$contenthead." downloaded successfully <br />Alle ".$contenthead." erfolgreich heruntergeladen<br />Tutti i ".$contenthead." sono stati scaricati correttamente<br />";
                echo'
                            </div>
                        </div>
                    </div>
                ';
                if (file_exists('../includes/'.$instdir.'/'.$dir.'/'.$installmodustwo.'.php')) {
                    include('../includes/'.$instdir.'/'.$dir.'/'.$installmodustwo.'.php');
                } else {
                    echo '<br />No installation file found';
                }
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    if (@$multivarplugin != '') {
        $plugin = $updateserverurl.'/plugin/plugin-base_v.'.$getversion.'/';
        $pluginlist = $updateserverurl.'/plugin/plugin-base_v.'.$getversion.'/list.json';
        $instdir = 'plugins';
        $contenthead = 'Pluginfiles';

        foreach (array_merge(array_filter($multivarplugin)) as $dir) {
            unset($filesgrant);
            unset($add_plugin);
            $url = $plugin.$dir.'/'.$installmodus.'.json';
            try {
                $result = curl_json2array($url);
                if ($result != "NULL") {
                    foreach ($list as $value) {
                        $index = $value;
                        $files = count($result['items'][$index])-1;
                        if ($files != '0') {
                            for ($i = 1; $i <= $files; $i++) {
                                try {
                                    if (isset($result['items'][$index]['file'.$i])) {
                                        $filePath = '../includes/'.$instdir.'/'.$result['items'][$index]['file'.$i];
                                        $pathParts = pathinfo($filePath);
                                        if (!file_exists($pathParts['dirname'])) {
                                            mkdir($pathParts['dirname'], 0777, true);
                                        }

                                        // Überprüfen, ob der Pfad eine Datei ist und nicht ein Verzeichnis
                                        if (!is_dir($filePath)) {
                                            $content = $plugin.$result['items'][$index]['file'.$i].'.txt';
                                            $curl = curl_init();
                                            curl_setopt($curl, CURLOPT_URL, $content);
                                            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                                            $content = curl_exec($curl);
                                            file_put_contents($filePath, $content);
                                            curl_close($curl);
                                            $filesgrant[] = 'File created: '.$filePath.'<br />';
                                        } else {
                                            echo 'Es handelt sich um ein Verzeichnis, keine Datei: ' . $filePath;
                                        }
                                    } else {
                                        echo 'Fehlendes Array-Element oder Datei für ' . $index . ' und ' . $i;
                                    }
                                } catch (Exception $s) {
                                    echo $s->getMessage();
                                }
                            }
                        }
                    }

                    echo'
                        <div class=\'card\'>
                            <div class=\'card-header\'>
                                Loading '.$contenthead.'
                            </div>
                            <div class=\'card-body\'>
                                <div class="alert alert-success" role="alert">
                    ';
                    foreach ($filesgrant as $filesgranted) {
                        echo $filesgranted;
                    }
                    echo "All ".$contenthead." downloaded successfully <br />Alle ".$contenthead." erfolgreich heruntergeladen<br />Tutti i ".$contenthead." sono stati scaricati correttamente<br />";
                    echo'
                                </div>
                            </div>
                        </div>
                    ';
                    if (file_exists('../includes/'.$instdir.'/'.$dir.'/'.$installmodustwo.'.php')) {
                        include('../includes/'.$instdir.'/'.$dir.'/'.$installmodustwo.'.php');
                    } else {
                        echo '<br />No installation file found';
                    }
                }
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }
    }

    echo '<div class="card">
            <div class="card-header">'.$str.' Installation:</div>
            <div class="card-body">
                <div class="alert alert-success">
                    <span class="text-dark fs-5">Installation:</span>
                    <span class="d-block text-dark">Installation completed successfully!<br>Installation erfolgreich abgeschlossen!</span>
                    <br /><br />
                    <a class="btn btn-secondary" href="javascript:history.back();reload()">Go Back</a>
                </div>
            </div>
          </div><br />';
}

<?php
// Sprachmodul laden
$_language->readModule('overview', false, true);

use webspell\AccessControl;

// Admin-Zugriff überprüfen
AccessControl::checkAdminAccess('ac_overview');

// Serverinformationen sammeln
$phpversion = phpversion();
$zendversion = zend_version();
$mysqlversion = mysqli_get_server_info($_database);

// Konfigurationen abrufen
$get_phpini_path = get_cfg_var('cfg_file_path') ?: 'n/a';
$get_allow_url_fopen = get_cfg_var('allow_url_fopen');
$get_allow_url_include = get_cfg_var('allow_url_include');
$get_display_errors = get_cfg_var('display_errors');
$get_file_uploads = get_cfg_var('file_uploads');
$get_log_errors = get_cfg_var('log_errors');
$get_max_execution_time = get_cfg_var('max_execution_time');
$get_open_basedir = get_cfg_var('open_basedir');
$get_post_max_size = get_cfg_var('post_max_size');
$get_register_globals = get_cfg_var('register_globals');
$get_upload_max_filesize = get_cfg_var('upload_max_filesize');
$get_short_open_tag = get_cfg_var('short_open_tag');

// CURL-Check
$curl_installed = function_exists('curl_version');

// GD-Check
$gd_installed = function_exists('gd_info');
$get_gdtypes = [];

if ($gd_installed) {
    $gdinfo = gd_info();
    if (!empty($gdinfo)) {
        foreach (['FreeType Support', 'GIF Read Support', 'GIF Create Support', 'JPG Support', 'JPEG Support', 'PNG Support', 'WBMP Support', 'XBM Support', 'XPM Support'] as $feature) {
            if (!empty($gdinfo[$feature])) {
                $get_gdtypes[] = $feature;
            }
        }
    }
}

// Datenbankname abrufen
$get = safe_query("SELECT DATABASE()");
$ret = mysqli_fetch_array($get);
$db = $ret[0] ?? 'n/a';


// System-Informationen auslesen
$phpVersion = phpversion();
$phpMemoryLimit = ini_get('memory_limit');
$phpPostMaxSize = ini_get('post_max_size');
$phpSessionSavePath = ini_get('session.save_path');
$phpDefaultCharset = ini_get('default_charset');
$phpTimezone = ini_get('date.timezone') ?: 'Nicht gesetzt';
$phpDisabledFunctions = ini_get('disable_functions') ?: 'Keine deaktivierten Funktionen';
$serverSoftware = $_SERVER['SERVER_SOFTWARE'] ?? 'Unbekannt';
$serverOS = php_uname();
$mysqlVersion = $_database->query("SELECT VERSION()")->fetch_row()[0];

// Hilfsfunktionen für Badge-Farben
function getBadgeClass($value, $goodThreshold, $warningThreshold = null) {
    if (is_numeric($value)) {
        $value = (int) $value;
    } elseif (preg_match('/(\d+)([KMG]?)/', $value, $matches)) {
        $num = (int) $matches[1];
        $unit = strtoupper($matches[2]);
        if ($unit === 'K') $num *= 1024;
        if ($unit === 'M') $num *= 1024 * 1024;
        if ($unit === 'G') $num *= 1024 * 1024 * 1024;
        $value = $num;
    }

    if ($warningThreshold !== null) {
        if ($value >= $goodThreshold) {
            return 'success';
        } elseif ($value >= $warningThreshold) {
            return 'warning';
        } else {
            return 'danger';
        }
    } else {
        return $value >= $goodThreshold ? 'success' : 'danger';
    }
}


// RAM Verbrauch
$currentMemory = memory_get_usage(true); // in Bytes
$peakMemory = memory_get_peak_usage(true); // in Bytes

// Umrechnung in MB
$currentMemoryMB = $currentMemory / 1024 / 1024;
$peakMemoryMB = $peakMemory / 1024 / 1024;

// Annahme Max Memory Limit aus PHP (für Balkenanzeige)
$memoryLimit = ini_get('memory_limit');
$memoryLimitBytes = (is_numeric($memoryLimit)) ? (int)$memoryLimit * 1024 * 1024 : 128 * 1024 * 1024; // Default 128MB
if (strpos($memoryLimit, 'M') !== false) {
    $memoryLimitBytes = (int)$memoryLimit * 1024 * 1024;
} elseif (strpos($memoryLimit, 'G') !== false) {
    $memoryLimitBytes = (int)$memoryLimit * 1024 * 1024 * 1024;
}
$currentMemoryPercent = ($currentMemory / $memoryLimitBytes) * 100;
$peakMemoryPercent = ($peakMemory / $memoryLimitBytes) * 100;

// Server Load (nur wenn Funktion vorhanden)
$serverLoad = function_exists('sys_getloadavg') ? sys_getloadavg() : null;
?>



<div class="container mt-5">
    <h2>Systemübersicht</h2>
    <div class="row">
        <!-- PHP & Server -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>PHP und Serverinformationen</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item">PHP Version: <span class="text-success"><?= htmlspecialchars($phpversion) ?></span></li>
                        <li class="list-group-item">Zend Version: <span class="text-success"><?= htmlspecialchars($zendversion) ?></span></li>
                        <li class="list-group-item">MySQL Version: <span class="text-success"><?= htmlspecialchars($mysqlversion) ?></span></li>
                        <li class="list-group-item">max_execution_time: <span class="<?= $get_max_execution_time < 30 ? 'text-danger' : 'text-success' ?>"><?= (int)$get_max_execution_time ?>s</span></li>
                        <li class="list-group-item">file_uploads: <span class="<?= $get_file_uploads ? 'text-success' : 'text-danger' ?>"><?= $get_file_uploads ? 'Aktiviert' : 'Deaktiviert' ?></span></li>
                        <li class="list-group-item">register_globals: <span class="<?= $get_register_globals ? 'text-danger' : 'text-success' ?>"><?= $get_register_globals ? 'Aktiviert' : 'Deaktiviert' ?></span></li>
                        <li class="list-group-item">upload_max_filesize: <span class="<?= $get_upload_max_filesize > 16 ? 'text-warning' : 'text-success' ?>"><?= htmlspecialchars($get_upload_max_filesize) ?> MB</span></li>
                        <li class="list-group-item">post_max_size: <span class="<?= $get_post_max_size > 8 ? 'text-warning' : 'text-success' ?>"><?= htmlspecialchars($get_post_max_size) ?> MB</span></li>
                        <li class="list-group-item">open_basedir: <span class="<?= $get_open_basedir ? 'text-success' : 'text-warning' ?>"><?= $get_open_basedir ? 'Aktiviert' : 'Deaktiviert' ?></span></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- GD & cURL -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>GD und cURL Informationen</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item">GD Bibliothek: <span class="<?= $gd_installed ? 'text-success' : 'text-danger' ?>"><?= $gd_installed ? 'Aktiviert' : 'Deaktiviert' ?></span></li>
                        <li class="list-group-item">cURL: <span class="<?= $curl_installed ? 'text-success' : 'text-danger' ?>"><?= $curl_installed ? 'Aktiviert' : 'Deaktiviert' ?></span></li>
                        <li class="list-group-item">GD unterstützte Formate: <span class="text-success"><?= $gd_installed ? htmlspecialchars(implode(', ', $get_gdtypes)) : 'n/a' ?></span></li>
                        <li class="list-group-item">short_open_tag: <span class="<?= $get_short_open_tag ? 'text-success' : 'text-warning' ?>"><?= $get_short_open_tag ? 'Aktiviert' : 'Deaktiviert' ?></span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <footer class="text-center mt-4">
        <small>Datenbank: <?= htmlspecialchars($db) ?></small>
    </footer>
</div>

<div class="container my-4">
    <h3>Systeminformationen</h3>
    
    <div class="row">
        <!-- PHP & MySQL Infos -->
        <div class="col-md-6">
            <div class="card mb-4 shadow-sm">
                <div class="card-header">
                    <h5>PHP & MySQL</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item">
                            PHP-Version: 
                            <span class="badge bg-<?php echo version_compare($phpVersion, '8.0', '>=') ? 'success' : 'warning'; ?>">
                                <?php echo $phpVersion; ?>
                            </span>
                        </li>
                        <li class="list-group-item">
                            MySQL-Version: 
                            <span class="badge bg-<?php echo version_compare($mysqlVersion, '8.0', '>=') ? 'success' : 'warning'; ?>">
                                <?php echo $mysqlVersion; ?>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Server Infos -->
        <div class="col-md-6">
            <div class="card mb-4 shadow-sm">
                <div class="card-header">
                    <h5>Server</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item">
                            Server-Software: 
                            <span class="text-info"><?php echo htmlspecialchars($serverSoftware); ?></span>
                        </li>
                        <li class="list-group-item">
                            Betriebssystem: 
                            <span class="text-info"><?php echo htmlspecialchars($serverOS); ?></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Weitere Server Infos -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5>Weitere Server-Einstellungen</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item">
                            Memory Limit: 
                            <span class="badge bg-<?php echo getBadgeClass($phpMemoryLimit, 134217728, 67108864); ?>">
                                <?php echo $phpMemoryLimit; ?>
                            </span>
                        </li>
                        <li class="list-group-item">
                            POST Max Size: 
                            <span class="badge bg-<?php echo getBadgeClass($phpPostMaxSize, 8388608, 4194304); ?>">
                                <?php echo $phpPostMaxSize; ?>
                            </span>
                        </li>
                        <li class="list-group-item">
                            Session Save Path: 
                            <span class="text-info"><?php echo htmlspecialchars($phpSessionSavePath); ?></span>
                        </li>
                        <li class="list-group-item">
                            Default Charset: 
                            <span class="badge bg-<?php echo strtolower($phpDefaultCharset) === 'utf-8' ? 'success' : 'warning'; ?>">
                                <?php echo htmlspecialchars($phpDefaultCharset); ?>
                            </span>
                        </li>
                        <li class="list-group-item">
                            Zeitzone: 
                            <span class="badge bg-<?php echo $phpTimezone !== 'Nicht gesetzt' ? 'success' : 'danger'; ?>">
                                <?php echo htmlspecialchars($phpTimezone); ?>
                            </span>
                        </li>
                        <li class="list-group-item">
                            Deaktivierte Funktionen: 
                            <span class="text-secondary small"><?php echo htmlspecialchars($phpDisabledFunctions); ?></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>

<?php if ($serverLoad): ?>
<div class="container my-4">
    <h3>Server Auslastung</h3>

    <div class="card shadow-sm">
        <div class="card-header">
            <h5>Load Average (1 / 5 / 15 Minuten)</h5>
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    1 Minute: 
                    <span class="badge bg-<?php echo ($serverLoad[0] < 1.0) ? 'success' : (($serverLoad[0] < 5.0) ? 'warning' : 'danger'); ?>">
                        <?php echo number_format($serverLoad[0], 2); ?>
                    </span>
                </li>
                <li class="list-group-item">
                    5 Minuten: 
                    <span class="badge bg-<?php echo ($serverLoad[1] < 1.0) ? 'success' : (($serverLoad[1] < 5.0) ? 'warning' : 'danger'); ?>">
                        <?php echo number_format($serverLoad[1], 2); ?>
                    </span>
                </li>
                <li class="list-group-item">
                    15 Minuten: 
                    <span class="badge bg-<?php echo ($serverLoad[2] < 1.0) ? 'success' : (($serverLoad[2] < 5.0) ? 'warning' : 'danger'); ?>">
                        <?php echo number_format($serverLoad[2], 2); ?>
                    </span>
                </li>
            </ul>
            <small class="text-muted mt-2 d-block">
                Werte &lt; 1.0 sind optimal. Hohe Werte deuten auf Serverauslastung hin.
            </small>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="container my-4">
    <h3>Speicherverbrauch</h3>

    <div class="card shadow-sm">
        <div class="card-header">
            <h5>Arbeitsspeicher (RAM)</h5>
        </div>
        <div class="card-body">

            <p>Aktueller Verbrauch: <?php echo number_format($currentMemoryMB, 2); ?> MB</p>
            <div class="progress mb-3" style="height: 25px;">
                <div class="progress-bar <?php echo ($currentMemoryPercent < 50) ? 'bg-success' : (($currentMemoryPercent < 80) ? 'bg-warning' : 'bg-danger'); ?>"
                     role="progressbar" style="width: <?php echo number_format($currentMemoryPercent, 1); ?>%;" 
                     aria-valuenow="<?php echo number_format($currentMemoryPercent, 1); ?>" aria-valuemin="0" aria-valuemax="100">
                    <?php echo number_format($currentMemoryPercent, 1); ?> %
                </div>
            </div>

            <p>Maximaler Verbrauch: <?php echo number_format($peakMemoryMB, 2); ?> MB</p>
            <div class="progress" style="height: 25px;">
                <div class="progress-bar <?php echo ($peakMemoryPercent < 50) ? 'bg-success' : (($peakMemoryPercent < 80) ? 'bg-warning' : 'bg-danger'); ?>"
                     role="progressbar" style="width: <?php echo number_format($peakMemoryPercent, 1); ?>%;" 
                     aria-valuenow="<?php echo number_format($peakMemoryPercent, 1); ?>" aria-valuemin="0" aria-valuemax="100">
                    <?php echo number_format($peakMemoryPercent, 1); ?> %
                </div>
            </div>

            <small class="text-muted mt-2 d-block">
                Basierend auf PHP Memory Limit: <?php echo ini_get('memory_limit'); ?>
            </small>

        </div>
    </div>
</div>

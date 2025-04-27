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
// RAM Verbrauch
$currentMemory = memory_get_usage(true); // in Bytes
$peakMemory = memory_get_peak_usage(true); // in Bytes

// Umrechnung in MB
$currentMemoryMB = $currentMemory / 1024 / 1024;
$peakMemoryMB = $peakMemory / 1024 / 1024;

// Annahme Max Memory Limit aus PHP (für Balkenanzeige)
$memoryLimit = ini_get('memory_limit');
$memoryLimitBytes = 128 * 1024 * 1024; // Default 128MB, falls memory_limit nicht gesetzt oder ungültig ist

if ($memoryLimit !== false) {
    // Prüfung, ob die Größe in MB oder GB angegeben ist
    if (strpos($memoryLimit, 'M') !== false) {
        $memoryLimitBytes = (int)$memoryLimit * 1024 * 1024;
    } elseif (strpos($memoryLimit, 'G') !== false) {
        $memoryLimitBytes = (int)$memoryLimit * 1024 * 1024 * 1024;
    } elseif (strpos($memoryLimit, 'K') !== false) {
        $memoryLimitBytes = (int)$memoryLimit * 1024;
    } else {
        // Annahme, dass die Zahl in Bytes vorliegt
        $memoryLimitBytes = (int)$memoryLimit;
    }
}

// Berechnung der prozentualen Nutzung
$currentMemoryPercent = ($currentMemory / $memoryLimitBytes) * 100;
$peakMemoryPercent = ($peakMemory / $memoryLimitBytes) * 100;

// Server Load (nur wenn Funktion vorhanden)
$serverLoad = function_exists('sys_getloadavg') ? sys_getloadavg() : null;












// Beispielhafte Initialisierung von $ramUsage (dies sollte durch die tatsächliche Logik ersetzt werden)
$ramUsage = [
    'current' => 1024, // Aktueller Verbrauch in MB (Beispielwert)
    'usagePercentage' => 75 // Prozentualer Verbrauch (Beispielwert)
];

// Sicherstellen, dass $ramUsage gesetzt ist
if (isset($ramUsage) && is_array($ramUsage) && isset($ramUsage['current']) && isset($ramUsage['usagePercentage'])) {
    $currentRam = number_format($ramUsage['current'], 2); // Formatieren der RAM-Verbrauchswerte
    $ramUsagePercentage = number_format($ramUsage['usagePercentage'], 1);
} else {
    $currentRam = '0.00'; // Standardwert bei Fehler
    $ramUsagePercentage = '0'; // Standardwert bei Fehler
}
?>



<div class="container py-5">
    <h1 class="mb-5 text-center fw-bold display-5">Systemstatus</h1>

    <div class="row g-4">
        <!-- PHP Infos -->
        <div class="col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 transition hover-shadow">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="bi bi-code-slash me-2"></i> PHP & Server
                    </h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="bi bi-chevron-right text-primary me-2"></i> PHP Version: 
                            <span class="badge bg-light text-dark"><?= htmlspecialchars($phpversion) ?></span>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-chevron-right text-primary me-2"></i> Zend Version: 
                            <span class="badge bg-light text-dark"><?= htmlspecialchars($zendversion) ?></span>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-chevron-right text-primary me-2"></i> MySQL Version: 
                            <span class="badge bg-light text-dark"><?= htmlspecialchars($mysqlversion) ?></span>
                        </li>
                        <li class="list-group-item">
                            <i class="bi bi-chevron-right text-primary me-2"></i> max_execution_time: 
                            <span class="badge bg-light text-<?= $get_max_execution_time < 30 ? 'danger' : 'success' ?>">
                                <?= (int)$get_max_execution_time ?>s
                            </span>
                        </li>
                        <li class="list-group-item">
                            <i class="bi bi-chevron-right text-primary me-2"></i> file_uploads: 
                            <span class="badge bg-light text-<?= $get_file_uploads ? 'success' : 'danger' ?>">
                                <?= $get_file_uploads ? 'Aktiviert' : 'Deaktiviert' ?>
                            </span>
                        </li>
                        <li class="list-group-item">
                            <i class="bi bi-chevron-right text-primary me-2"></i> register_globals: 
                            <span class="badge bg-light text-<?= $get_register_globals ? 'danger' : 'success' ?>">
                                <?= $get_register_globals ? 'Aktiviert' : 'Deaktiviert' ?>
                            </span>
                        </li>
                        <li class="list-group-item">
                            <i class="bi bi-chevron-right text-primary me-2"></i> upload_max_filesize: 
                            <span class="badge bg-light text-<?= $get_upload_max_filesize > 16 ? 'warning' : 'success' ?>">
                                <?= htmlspecialchars($get_upload_max_filesize) ?> MB
                            </span>
                        </li>
                        <li class="list-group-item">
                            <i class="bi bi-chevron-right text-primary me-2"></i> post_max_size: 
                            <span class="badge bg-light text-<?= $get_post_max_size > 8 ? 'warning' : 'success' ?>">
                                <?= htmlspecialchars($get_post_max_size) ?> MB
                            </span>
                        </li>
                        <li class="list-group-item">
                            <i class="bi bi-chevron-right text-primary me-2"></i> open_basedir: 
                            <span class="badge bg-light text-<?= $get_open_basedir ? 'success' : 'warning' ?>">
                                <?= $get_open_basedir ? 'Aktiviert' : 'Deaktiviert' ?>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- GD Infos -->
        <div class="col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 transition hover-shadow">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="bi bi-image me-2"></i> GD & cURL
                    </h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="bi bi-check2-circle text-success me-2"></i> GD Bibliothek:
                            <span class="badge <?= $gd_installed ? 'bg-success' : 'bg-danger' ?>">
                                <?= $gd_installed ? 'Aktiviert' : 'Deaktiviert' ?>
                            </span>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check2-circle text-success me-2"></i> cURL:
                            <span class="badge <?= $curl_installed ? 'bg-success' : 'bg-danger' ?>">
                                <?= $curl_installed ? 'Aktiviert' : 'Deaktiviert' ?>
                            </span>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check2-circle text-success me-2"></i> GD Bibliothek:
                            <span class="badge bg-light text-<?= $gd_installed ? 'success' : 'danger' ?>"><?= $gd_installed ? 'Aktiviert' : 'Deaktiviert' ?></span>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check2-circle text-success me-2"></i> cURL:
                            <span class="badge bg-light text-<?= $curl_installed ? 'success' : 'danger' ?>"><?= $curl_installed ? 'Aktiviert' : 'Deaktiviert' ?></span>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check2-circle text-success me-2"></i> GD unterstützte Formate:
                            <span class="text-success small"><?= $gd_installed ? htmlspecialchars(implode(', ', $get_gdtypes)) : 'n/a' ?></span>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check2-circle text-success me-2"></i> short_open_tag:
                            <span class="badge bg-light text-<?= $get_short_open_tag ? 'success' : 'warning' ?>">
                                <?= $get_short_open_tag ? 'Aktiviert' : 'Deaktiviert' ?>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Server Infos -->
        <div class="col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 transition hover-shadow">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="bi bi-server me-2"></i> Server Details
                    </h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="bi bi-hdd-network text-warning me-2"></i> Server-Software:
                            <span class="text-muted"><?= htmlspecialchars($serverSoftware) ?></span>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-cpu text-warning me-2"></i> Betriebssystem:
                            <span class="text-muted"><?= htmlspecialchars($serverOS) ?></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Serverauslastung -->
        <div class="col-md-6 col-xl-4">
            <?php if ($serverLoad): ?>
                <div class="card border-0 shadow-sm rounded-4 h-100 transition hover-shadow">
                    <div class="card-body">
                        <h5 class="card-title mb-4">
                            <i class="bi bi-speedometer2 me-2"></i> Serverauslastung
                        </h5>
                        <ul class="list-unstyled">
                            <?php foreach ($serverLoad as $minutes => $load): ?>
                                <li class="mb-2">
                                    <?= $minutes === 0 ? "1 Minute" : ($minutes === 1 ? "5 Minuten" : "15 Minuten") ?>:
                                    <span class="badge bg-<?= ($load < 1.0) ? 'success' : (($load < 5.0) ? 'warning' : 'danger') ?>">
                                        <?= number_format($load, 2) ?>
                                    </span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Speicher -->
        <div class="col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 transition hover-shadow">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="bi bi-memory me-2"></i> Speicherverbrauch
                    </h5>
                    
                    <!-- Aktueller Verbrauch -->
                    <div class="mt-4">
                        <p class="mb-1 text-muted">Aktueller Verbrauch: </p>
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar bg-<?= ($currentMemoryPercent < 50) ? 'success' : (($currentMemoryPercent < 80) ? 'warning' : 'danger') ?>"
                             role="progressbar" style="width: <?= number_format($currentMemoryPercent, 1) ?>%;" 
                             aria-valuenow="<?= number_format($currentMemoryPercent, 1) ?>" aria-valuemin="0" aria-valuemax="100">
                            <?= number_format($currentMemoryPercent, 1) ?>%
                        </div>

                    </div>
                    <span><?= number_format($currentMemoryMB, 2) ?> MB</span>
                </div>

                    <!-- Maximaler RAM-Verbrauch -->
                    <div class="mt-4">
                        <p class="mb-1 text-muted">Maximaler RAM-Verbrauch:</p>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar bg-info" role="progressbar" style="width: <?= number_format($peakMemoryPercent, 1) ?>%;" 
                                 aria-valuenow="<?= number_format($peakMemoryPercent, 1) ?>" aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                        <span><?= number_format($peakMemoryMB, 2) ?> MB</span>
                    </div>

                    <!-- Memory Limit -->
                    <div class="mt-3">
                        <p class="mb-1 text-muted">Memory Limit:</p>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar bg-secondary" role="progressbar" style="width: <?= number_format(($currentMemoryPercent / 100) * 100, 1) ?>%;" 
                                 aria-valuenow="<?= number_format(($currentMemoryPercent / 100) * 100, 1) ?>" aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                        <span><?= number_format($memoryLimitBytes / 1024 / 1024, 2) ?> MB</span>
                    </div>

                    <!-- Server Load Anzeige -->
                    <?php if ($serverLoad !== null): ?>
                        <div class="mt-4">
                            <p class="mb-1 text-muted">Server Load (1, 5, 15 Minuten):</p>
                            <div class="d-flex justify-content-between">
                                <span><strong><?= implode(", ", $serverLoad) ?></strong></span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>




        <!-- Arbeitsspeicher (RAM) -->
        <div class="col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 transition hover-shadow">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="bi bi-stack me-2"></i> Arbeitsspeicher (RAM)
                    </h5>
                    <p>Aktueller Verbrauch: <?= number_format($ramUsage['current'], 2) ?> MB</p>
                    <div class="progress">
                        <div class="progress-bar bg-success" style="width: <?= $ramUsage['usagePercentage'] ?>%;" aria-valuenow="<?= $ramUsage['usagePercentage'] ?>" aria-valuemin="0" aria-valuemax="100">
                            <?= $ramUsage['usagePercentage'] ?>%
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<?



/*<div class="container py-5">
    <h1 class="mb-5 text-center fw-bold display-5">Systemstatus</h1>

    <div class="row g-4">
        <!-- PHP Infos -->
        <div class="col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 transition hover-shadow">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="bi bi-code-slash me-2"></i> PHP & Server
                    </h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="bi bi-chevron-right text-primary me-2"></i> PHP Version: 
                            <span class="badge bg-light text-dark"><?= htmlspecialchars($phpversion) ?></span>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-chevron-right text-primary me-2"></i> Zend Version: 
                            <span class="badge bg-light text-dark"><?= htmlspecialchars($zendversion) ?></span>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-chevron-right text-primary me-2"></i> MySQL Version: 
                            <span class="badge bg-light text-dark"><?= htmlspecialchars($mysqlversion) ?></span>
                        </li>
                        <li class="list-group-item">PHP Version: <span class="badge bg-success"><?= htmlspecialchars($phpversion) ?></span></li>
                        <li class="list-group-item">Zend Version: <span class="badge bg-success"><?= htmlspecialchars($zendversion) ?></span></li>
                        <li class="list-group-item">MySQL Version: <span class="badge bg-success"><?= htmlspecialchars($mysqlversion) ?></span></li>
                        <li class="list-group-item">max_execution_time: <span class="badge bg-<?= $get_max_execution_time < 30 ? 'danger' : 'success' ?>"><?= (int)$get_max_execution_time ?>s</span></li>
                        <li class="list-group-item">file_uploads: <span class="badge bg-<?= $get_file_uploads ? 'success' : 'danger' ?>"><?= $get_file_uploads ? 'Aktiviert' : 'Deaktiviert' ?></span></li>
                        <li class="list-group-item">register_globals: <span class="badge bg-<?= $get_register_globals ? 'danger' : 'success' ?>"><?= $get_register_globals ? 'Aktiviert' : 'Deaktiviert' ?></span></li>
                        <li class="list-group-item">upload_max_filesize: <span class="badge bg-<?= $get_upload_max_filesize > 16 ? 'warning' : 'success' ?>"><?= htmlspecialchars($get_upload_max_filesize) ?> MB</span></li>
                        <li class="list-group-item">post_max_size: <span class="badge bg-<?= $get_post_max_size > 8 ? 'warning' : 'success' ?>"><?= htmlspecialchars($get_post_max_size) ?> MB</span></li>
                        <li class="list-group-item">open_basedir: <span class="badge bg-<?= $get_open_basedir ? 'success' : 'warning' ?>"><?= $get_open_basedir ? 'Aktiviert' : 'Deaktiviert' ?></span></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- GD Infos -->
        <div class="col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 transition hover-shadow">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="bi bi-image me-2"></i> GD & cURL
                    </h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="bi bi-check2-circle text-success me-2"></i> GD Bibliothek:
                            <span class="badge <?= $gd_installed ? 'bg-success' : 'bg-danger' ?>">
                                <?= $gd_installed ? 'Aktiviert' : 'Deaktiviert' ?>
                            </span>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check2-circle text-success me-2"></i> cURL:
                            <span class="badge <?= $curl_installed ? 'bg-success' : 'bg-danger' ?>">
                                <?= $curl_installed ? 'Aktiviert' : 'Deaktiviert' ?>
                            </span>
                        </li>
                        <li class="list-group-item">GD Bibliothek: <span class="badge bg-<?= $gd_installed ? 'success' : 'danger' ?>"><?= $gd_installed ? 'Aktiviert' : 'Deaktiviert' ?></span></li>
                        <li class="list-group-item">cURL: <span class="badge bg-<?= $curl_installed ? 'success' : 'danger' ?>"><?= $curl_installed ? 'Aktiviert' : 'Deaktiviert' ?></span></li>
                        <li class="list-group-item">GD unterstützte Formate: <span class="text-success small"><?= $gd_installed ? htmlspecialchars(implode(', ', $get_gdtypes)) : 'n/a' ?></span></li>
                        <li class="list-group-item">short_open_tag: <span class="badge bg-<?= $get_short_open_tag ? 'success' : 'warning' ?>"><?= $get_short_open_tag ? 'Aktiviert' : 'Deaktiviert' ?></span></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Server Infos -->
        <div class="col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 transition hover-shadow">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="bi bi-server me-2"></i> Server Details
                    </h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="bi bi-hdd-network text-warning me-2"></i> Server-Software: 
                            <span class="text-muted"><?= htmlspecialchars($serverSoftware) ?></span>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-cpu text-warning me-2"></i> Betriebssystem: 
                            <span class="text-muted"><?= htmlspecialchars($serverOS) ?></span>
                        </li>
                    
                        <li class="list-group-item">PHP-Version: 
                            <span class="badge bg-<?= version_compare($phpVersion, '8.0', '>=') ? 'success' : 'warning'; ?>">
                                <?= $phpVersion ?>
                            </span>
                        </li>
                        <li class="list-group-item">MySQL-Version: 
                            <span class="badge bg-<?= version_compare($mysqlVersion, '8.0', '>=') ? 'success' : 'warning'; ?>">
                                <?= $mysqlVersion ?>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Load Average -->
    <?php if ($serverLoad): ?>
    <div class="row g-4 mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 h-100 transition hover-shadow">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="bi bi-speedometer2 me-2"></i> Serverauslastung
                    </h5>
                    <ul class="list-unstyled">
                        <?php foreach ($serverLoad as $minutes => $load): ?>
                            <li class="mb-2">
                                <?= $minutes === 0 ? "1 Minute" : ($minutes === 1 ? "5 Minuten" : "15 Minuten") ?>:
                                <span class="badge bg-<?= ($load < 1.0) ? 'success' : (($load < 5.0) ? 'warning' : 'danger') ?>">
                                    <?= number_format($load, 2) ?>
                                </span>
                            </li>
                        <?php endforeach; ?>

                <li class="list-group-item">Memory Limit: 
                    <span class="badge bg-<?= getBadgeClass($phpMemoryLimit, 134217728, 67108864) ?>">
                        <?= $phpMemoryLimit ?>
                    </span>
                </li>
                <li class="list-group-item">POST Max Size: 
                    <span class="badge bg-<?= getBadgeClass($phpPostMaxSize, 8388608, 4194304) ?>">
                        <?= $phpPostMaxSize ?>
                    </span>
                </li>
                <li class="list-group-item">Session Save Path: <span class="text-info"><?= htmlspecialchars($phpSessionSavePath) ?></span></li>
                <li class="list-group-item">Default Charset: 
                    <span class="badge bg-<?= strtolower($phpDefaultCharset) === 'utf-8' ? 'success' : 'warning' ?>">
                        <?= htmlspecialchars($phpDefaultCharset) ?>
                    </span>
                </li>
                <li class="list-group-item">Zeitzone: 
                    <span class="badge bg-<?= $phpTimezone !== 'Nicht gesetzt' ? 'success' : 'danger' ?>">
                        <?= htmlspecialchars($phpTimezone) ?>
                    </span>
                </li>
                <li class="list-group-item">Deaktivierte Funktionen: 
                    <span class="text-secondary small"><?= htmlspecialchars($phpDisabledFunctions) ?></span>
                </li>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Speicher -->
    <div class="row g-4 mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 h-100 transition hover-shadow">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="bi bi-memory me-2"></i> Speicherverbrauch
                    </h5>
                    <p class="mb-2">Aktueller Verbrauch: <strong><?= number_format($currentMemoryMB, 2) ?> MB</strong></p>
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar bg-<?= ($currentMemoryPercent < 50) ? 'success' : (($currentMemoryPercent < 80) ? 'warning' : 'danger') ?>"
                             role="progressbar" style="width: <?= number_format($currentMemoryPercent, 1) ?>%;" aria-valuenow="<?= number_format($currentMemoryPercent, 1) ?>" aria-valuemin="0" aria-valuemax="100">
                            <?= number_format($currentMemoryPercent, 1) ?>%
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php if ($serverLoad): ?>
<div class="container my-5">
    <h3 class="mb-4 text-center">Serverauslastung</h3>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-warning text-dark rounded-top-4">
            <h5 class="mb-0">Load Average (1 / 5 / 15 Minuten)</h5>
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                <?php foreach ($serverLoad as $minutes => $load): ?>
                    <li class="list-group-item">
                        <?= $minutes === 0 ? "1 Minute" : ($minutes === 1 ? "5 Minuten" : "15 Minuten") ?>:
                        <span class="badge bg-<?= ($load < 1.0) ? 'success' : (($load < 5.0) ? 'warning' : 'danger') ?>">
                            <?= number_format($load, 2) ?>
                        </span>
                    </li>
                <?php endforeach; ?>
            </ul>
            <small class="text-muted d-block mt-2">
                Werte unter 1.0 sind optimal. Hohe Werte deuten auf Serverbelastung hin.
            </small>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="container my-5">
    <h3 class="mb-4 text-center">Speicherverbrauch</h3>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-success text-white rounded-top-4">
            <h5 class="mb-0">Arbeitsspeicher (RAM)</h5>
        </div>
        <div class="card-body">
            <p>Aktueller Verbrauch: <?= number_format($currentMemoryMB, 2) ?> MB</p>
            <div class="progress mb-3" style="height: 25px;">
                <div class="progress-bar bg-<?= ($currentMemoryPercent < 50) ? 'success' : (($currentMemoryPercent < 80) ? 'warning' : 'danger') ?>" role="progressbar" style="width: <?= number_format($currentMemoryPercent, 1) ?>%;" aria-valuenow="<?= number_format($currentMemoryPercent, 1) ?>" aria-valuemin="0" aria-valuemax="100">
                    <?= number_format($currentMemoryPercent, 1) ?> %
                </div>
            </div>

            <p>Maximaler Verbrauch: <?= number_format($peakMemoryMB, 2) ?> MB</p>
            <div class="progress" style="height: 25px;">
                <div class="progress-bar bg-<?= ($peakMemoryPercent < 50) ? 'success' : (($peakMemoryPercent < 80) ? 'warning' : 'danger') ?>" role="progressbar" style="width: <?= number_format($peakMemoryPercent, 1) ?>%;" aria-valuenow="<?= number_format($peakMemoryPercent, 1) ?>" aria-valuemin="0" aria-valuemax="100">
                    <?= number_format($peakMemoryPercent, 1) ?> %
                </div>
            </div>
        </div>
    </div>
</div>
</div>
*/
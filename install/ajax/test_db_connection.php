<?php
// Nur für JSON-AJAX!
ini_set('display_errors', 1);
error_reporting(E_ALL);
ob_clean();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $host = $_POST['db_host'] ?? '';
    $user = $_POST['db_user'] ?? '';
    $pass = $_POST['db_password'] ?? '';
    $dbname = $_POST['db_name'] ?? '';

    $mysqli = @new mysqli($host, $user, $pass, $dbname);

    if ($mysqli->connect_error) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Verbindung fehlgeschlagen: ' . $mysqli->connect_error
        ]);
        exit();
    }

    echo json_encode(['status' => 'success']);
    exit();
}
echo json_encode(['status' => 'error', 'message' => 'Ungültige Anfrage']);
exit();


#session_start();
header('Content-Type: application/json');
echo json_encode([
    'progress' => isset($_SESSION['progress']) ? $_SESSION['progress'] : 0
]);
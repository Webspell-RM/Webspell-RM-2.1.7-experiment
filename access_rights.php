<?php
require_once("../system/sql.php"); // enthält $_database und SESSION-Start
require_once("../system/session.php");
require_once("../system/functions.php");

session_start();

echo "<h2>Session Debug:</h2>";
print_r($_SESSION);

if (!isset($_SESSION['userID'])) {
    die("<strong>⚠️ Du bist NICHT eingeloggt!</strong>");
}

$userID = intval($_SESSION['userID']);

echo "<h2>DB-Abfrage für userID $userID</h2>";

require_once("../system/sql.php");

$stmt = $_database->prepare("SELECT userID, nickname, is_admin FROM " . PREFIX . "user WHERE userID = ?");
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

echo "<pre>";
print_r($data);
echo "</pre>";

if (!$data) {
    die("<strong>❌ Kein User gefunden!</strong>");
}

if (intval($data['is_admin']) !== 1) {
    die("<strong>🚫 Zugriff verweigert: is_admin ist NICHT 1</strong>");
}

echo "<strong>✅ Zugriff erlaubt! Du bist ein Admin.</strong>";


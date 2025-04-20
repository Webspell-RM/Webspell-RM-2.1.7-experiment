<?php
session_start();
header('Content-Type: application/json');
echo json_encode([
    'progress' => isset($_SESSION['progress']) ? $_SESSION['progress'] : 0
]);
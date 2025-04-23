<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// userID aus URL oder Session
$userID = isset($_GET['userID']) ? (int) $_GET['userID'] : ($_SESSION['userID'] ?? 0);

if ($userID === 0) {
    echo "Kein Benutzer angegeben.";
    exit();
}

// Datenbankabfrage
$query = "
    SELECT u.*, r.role_name, u.lastlogin AS last_visit
    FROM users u
    LEFT JOIN user_roles r ON u.roleID = r.roleID
    WHERE u.userID = $userID
";
$result = $_database->query($query);

if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "Benutzer nicht gefunden.";
    exit();
}

// Variablen vorbereiten
$username       = htmlspecialchars($user['username']);
$about_me       = !empty($user['about_me']) ? $user['about_me'] : 'Keine Informationen über mich.';
$register_date = !empty($user['registerdate']) && is_numeric($user['registerdate']) ? date('d.m.Y', (int) $user['registerdate']) : 'Unbekannt';



$last_visit = !empty($user['lastlogin']) && is_numeric($user['lastlogin']) ? date('d.m.Y H:i', (int) $user['lastlogin']) : 'Nie besucht';

$logins         = isset($user['logins']) ? (int)$user['logins'] : 100;
$online_time    = isset($user['online_time']) ? round($user['online_time'] / 60) . " Minuten" : '0 Minuten';
$points         = isset($user['points']) ? (int)$user['points'] : 75;

$avatar_url     = !empty($user['avatar']) ? '/images/avatars/' . $user['avatar'] : '/images/avatars/noavatar.png';
$role_name      = !empty($user['role_name']) ? $user['role_name'] : 'Benutzer';
$location       = !empty($user['location']) ? $user['location'] : 'Unbekannter Ort';

$level          = floor($points / 100);
$level_percent  = $points % 100;

$twitter_url    = !empty($user['twitter']) ? htmlspecialchars($user['twitter']) : '#';
$facebook_url   = !empty($user['facebook']) ? htmlspecialchars($user['facebook']) : '#';
$discord_url    = !empty($user['discord']) ? htmlspecialchars($user['discord']) : '#';

$is_own_profile = ($_SESSION['userID'] ?? 0) === $userID;
$edit_button    = $is_own_profile ? '<a href="edit_profile.php" class="btn btn-outline-primary mt-3"><i class="fas fa-user-edit"></i> Profil bearbeiten</a>' : '';

// Demo-Daten
$posts = 42;
$comments = 103;

// Angenommen, $user['last_activity'] enthält den Unix-Timestamp der letzten Aktivität
$last_activity = (int) $user['last_visit'];
$current_time = time(); // aktueller Unix-Timestamp

// Berechne den Unterschied in Sekunden
$online_seconds = $current_time - $last_activity;

// Berechne Stunden und Minuten
$online_hours = floor($online_seconds / 3600);
$online_minutes = floor(($online_seconds % 3600) / 60);

// Zeige die Online-Zeit im Format "XX Stunden, YY Minuten" an
$online_time = $online_hours . " Stunden, " . $online_minutes . " Minuten";


// Angenommen, $user['userID'] enthält die Benutzer-ID
$userID = (int) $user['userID'];

// Zähle die Anzahl der Logins des Benutzers (Anzahl der Sessions)
$query = $_database->prepare("SELECT COUNT(*) AS login_count FROM user_sessions WHERE userID = ?");
$query->bind_param('i', $userID);
$query->execute();
$query->bind_result($logins_count);
$query->fetch();
$query->close();

// Zeige die Anzahl der Logins an
$logins = $logins_count > 0 ? $logins_count : 0;
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Profil - <?= $username ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .profile-card { box-shadow: 0 4px 8px rgba(0,0,0,0.05); border-radius: 1rem; }
        .profile-header img { border-radius: 50%; max-width: 150px; border: 4px solid #fff; }
        .badge { font-size: 0.9rem; }
        .level-bar { height: 20px; background: #e9ecef; border-radius: 10px; overflow: hidden; }
        .level-progress { height: 100%; background: linear-gradient(90deg, #007bff, #00c6ff); }
        .social-icons a { color: #444; margin-right: 10px; font-size: 1.3rem; }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="card profile-card p-4">
        <div class="row align-items-center">
            <div class="col-md-3 text-center">
                <img src="<?= $avatar_url ?>" alt="<?= $username ?>" class="img-fluid mb-3">
                <div class="social-icons">
                    <a href="<?= $twitter_url ?>" target="_blank"><i class="fab fa-twitter"></i></a>
                    <a href="<?= $discord_url ?>" target="_blank"><i class="fab fa-discord"></i></a>
                    <a href="#" target="_blank"><i class="fab fa-github"></i></a>
                </div>
            </div>
            <div class="col-md-9">
                <h2><?= $username ?> <span class="badge bg-secondary"><?= $role_name ?></span></h2>
                <p><i class="fas fa-calendar-alt"></i> Mitglied seit: <?= $register_date ?></p>
                <p><i class="fas fa-map-marker-alt"></i> <?= $location ?></p>
                <div class="mb-2">
                    <span class="badge bg-primary">Beiträge: <?= $posts ?></span>
                    <span class="badge bg-success">Kommentare: <?= $comments ?></span>
                    <span class="badge bg-info">Punkte: <?= $points ?></span>
                </div>
                <div>
                    <label class="form-label">Level <?= $level ?></label>
                    <div class="level-bar">
                        <div class="level-progress" style="width: <?= $level_percent ?>%;"></div>
                    </div>
                </div>
                <?= $edit_button ?>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-body">
            <h5>Über mich</h5>
            <p><?= nl2br(htmlspecialchars($about_me)) ?></p>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-body">
            <h5>Letzte Aktivität</h5>
            <p><strong>Letzter Besuch:</strong> <?= $last_visit ?></p>
            <p><strong>Logins:</strong> <?= $logins ?></p>
            <p><strong>Online-Zeit:</strong> <?= $online_time ?></p>
        </div>
    </div>
</div>
</body>
</html>

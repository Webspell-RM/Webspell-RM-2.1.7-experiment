<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

use webspell\LanguageService;

global $languageService;

$lang = $languageService->detectLanguage();
$languageService->readModule('profile');

// Style aus settings holen
$config = mysqli_fetch_array(safe_query("SELECT selected_style FROM settings_headstyle_config WHERE id=1"));
$class = htmlspecialchars($config['selected_style']);

// Header-Daten
$data_array_header = [
    'class'    => $class,
    'title'    => $_language->module['title'],
    'subtitle' => 'Profil'
];
echo $tpl->loadTemplate("profile", "head", $data_array_header);

// userID aus GET oder Session
$userID = isset($_GET['userID']) ? (int)$_GET['userID'] : ($_SESSION['userID'] ?? 0);
if ($userID === 0) {
    echo "Kein Benutzer angegeben.";
    exit();
}


// user_profile laden
$sql_users = "SELECT * FROM users WHERE userID = $userID LIMIT 1";
$result_users = $_database->query($sql_users);
if (!$result_users || $result_users->num_rows === 0) {
    echo "Benutzerprofil nicht gefunden.";
    exit();
}
$user_users = $result_users->fetch_assoc();

// user_profile laden
$sql_profiles = "SELECT * FROM user_profiles WHERE userID = $userID LIMIT 1";
$result_profiles = $_database->query($sql_profiles);
$user_profile = $result_profiles->fetch_assoc();

// user_stats laden
$sql_stats = "SELECT * FROM user_stats WHERE userID = $userID LIMIT 1";
$result_stats = $_database->query($sql_stats);
$user_stats = $result_stats && $result_stats->num_rows > 0 ? $result_stats->fetch_assoc() : [];

// user_settings laden (optional, falls benötigt)
$sql_settings = "SELECT * FROM user_settings WHERE userID = $userID LIMIT 1";
$result_settings = $_database->query($sql_settings);
$user_settings = $result_settings && $result_settings->num_rows > 0 ? $result_settings->fetch_assoc() : [];

// user_socials laden
$sql_socials = "SELECT * FROM user_socials WHERE userID = $userID LIMIT 1";
$result_socials = $_database->query($sql_socials);
$user_socials = $result_socials && $result_socials->num_rows > 0 ? $result_socials->fetch_assoc() : [];

// user + role + lastlogin + registerdate laden
$sql = "
    SELECT 
        u.username,
        u.registerdate,
        u.lastlogin,
        r.role_name
    FROM users u
    LEFT JOIN user_role_assignments ura ON u.userID = ura.userID
    LEFT JOIN user_roles r ON ura.roleID = r.roleID
    WHERE u.userID = $userID
    LIMIT 1
";
$result = $_database->query($sql);
if ($result && $row = $result->fetch_assoc()) {
    $username = htmlspecialchars($row['username']);
    $role_name = !empty($row['role_name']) ? htmlspecialchars($row['role_name']) : 'Benutzer';

    // registerdate und lastlogin aus users holen
    $register_date_raw = $row['registerdate'];
    $last_visit_raw = $row['lastlogin'];
} else {
    $username = 'Unbekannt';
    $role_name = 'Benutzer';
    $register_date_raw = null;
    $last_visit_raw = null;
}

// Werte vorbereiten und sichern
$firstname    = htmlspecialchars($user_profile['firstname'] ?? 'Nicht angegeben');
$lastname     = htmlspecialchars($user_profile['lastname'] ?? 'Nicht angegeben');
$about_me     = !empty($user_profile['about_me']) ? htmlspecialchars($user_profile['about_me']) : 'Keine Informationen über mich.';
$register_date = (!empty($register_date_raw) && strtotime($register_date_raw) !== false)
    ? date('d.m.Y', strtotime($register_date_raw))
    : 'Unbekannt';

$last_visit = (!empty($last_visit_raw) && strtotime($last_visit_raw) !== false)
    ? date('d.m.Y H:i', strtotime($last_visit_raw))
    : 'Nie besucht';

$points = isset($user_stats['points']) ? (int)$user_stats['points'] : 0;

$avatar_url = !empty($user_profile['avatar']) ? '' . $user_profile['avatar'] : "/images/avatars/noavatar.png";

$location = !empty($user_profile['location']) ? htmlspecialchars($user_profile['location']) : 'Unbekannter Ort';
$age = !empty($user_profile['age']) ? (int)$user_profile['age'] : 'Nicht angegeben';
$sexuality = !empty($user_profile['sexuality']) ? htmlspecialchars($user_profile['sexuality']) : 'Nicht angegeben';

// Levelberechnung
$level = floor($points / 100);
$level_percent = $points % 100;

// Social URLs
$facebook_url  = !empty($user_socials['facebook']) ? htmlspecialchars($user_socials['facebook']) : '#';
$twitter_url   = !empty($user_socials['twitter']) ? htmlspecialchars($user_socials['twitter']) : '#';
$instagram_url = !empty($user_socials['instagram']) ? htmlspecialchars($user_socials['instagram']) : '#';
$website_url   = !empty($user_socials['website']) ? htmlspecialchars($user_socials['website']) : '#';
$github_url    = !empty($user_socials['github']) ? htmlspecialchars($user_socials['github']) : '#';

// Prüfen ob eigenes Profil
$is_own_profile = ($_SESSION['userID'] ?? 0) === $userID;
$edit_button = $is_own_profile ? '<a href="index.php?site=edit_profile" class="btn btn-outline-primary mt-3"><i class="fas fa-user-edit"></i> Profil bearbeiten</a>' : '';

// Banned-Status (aktuell leer, ggf. anpassen)
#$isLocked = isset($user_users['is_locked']) ? (int)$user_users['is_locked'] : 0;
$isLocked = isset($user_users['is_locked']) && (int)$user_users['is_locked'] === 1;
#$isLocked = $user_users['is_locked'] === 1;


// Letzte Aktivität und Online-Zeit berechnen
$last_activity = (!empty($last_visit_raw) && strtotime($last_visit_raw) !== false) ? strtotime($last_visit_raw) : 0;
$current_time = time();

if ($last_activity > 0 && $last_activity <= $current_time) {
    $online_seconds = $current_time - $last_activity;
    $online_hours = floor($online_seconds / 3600);
    $online_minutes = floor(($online_seconds % 3600) / 60);
    $online_time = "$online_hours Stunden, $online_minutes Minuten";
} else {
    $online_time = "Keine Aktivität";
}

// Anzahl der Logins aus user_sessions
$stmt = $_database->prepare("SELECT COUNT(*) AS login_count FROM user_sessions WHERE userID = ?");
$stmt->bind_param('i', $userID);
$stmt->execute();
$stmt->bind_result($logins_count);
$stmt->fetch();
$stmt->close();
$logins = $logins_count > 0 ? $logins_count : 0;

// Dummy-Daten für Beiträge & Kommentare (kannst du ersetzen)
$posts    = 42;
$comments = 103;

if ($isLocked == 1 ) {
    $isrowLocked='<div class="alert alert-danger d-flex align-items-center" role="alert">
        <i class="bi bi-lock-fill me-2"></i> Dieses Profil ist gesperrt.
    </div>';
}

$data_array = [
    'username'        => $username,
    'user_picture'    => $avatar_url,
    'user_role'       => $role_name,
    'user_points'     => $points,
    'user_about'      => $about_me,
    'user_signature'  => $user_profile['signature'] ?? '„Keep coding & carry on.“',
    'user_name'       => $firstname,
    'user_surname'    => $lastname,
    'user_age'        => $age,
    'user_location'   => $location,
    'user_sexuality'  => $sexuality,
    'user_posts'      => '<ul><li>Beitrag 1</li><li>Beitrag 2</li></ul>', // Ersetzen mit dynamischen Posts, wenn möglich
    'register_date'   => $register_date, 
    'user_activity'   => '<p>Zuletzt online: ' . $last_visit . '</p><p>Online-Zeit: ' . $online_time . '</p><p>Logins: ' . $logins . '</p>',
    'github_url'      => $github_url,
    'twitter_url'     => $twitter_url,
    'facebook_url'    => $facebook_url,
    'website_url'     => $website_url,
    'instagram_url'   => $instagram_url,
    'last_visit'      => $last_visit,
    'user_level'      => $level,
    'level_progress'  => $level_percent,
    'edit_button'     => $edit_button,
    'comments_count'  => $comments,
    'posts_count'     => $posts,
    'isLocked'        => $isrowLocked ?? '',
];

// Ausgabe Template
echo $tpl->loadTemplate("profile", "content", $data_array);

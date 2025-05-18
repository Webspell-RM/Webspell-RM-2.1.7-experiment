<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$_language->readModule('profile');

$config = mysqli_fetch_array(safe_query("SELECT selected_style FROM settings_headstyle_config WHERE id=1"));
$class = htmlspecialchars($config['selected_style']);

// Header-Daten
$data_array = [
    'class'    => $class,
    'title' => $_language->module['title'],
    'subtitle' => 'Profil'
];
echo $tpl->loadTemplate("profile", "head", $data_array);

$userID = isset($_GET['userID']) ? (int)$_GET['userID'] : ($_SESSION['userID'] ?? 0);

if ($userID === 0) {
    echo "Kein Benutzer angegeben.";
    exit();
}

$sql = "
    SELECT 
        u.*,
        r.role_name
    FROM users u
    LEFT JOIN user_role_assignments ura ON u.userID = ura.userID
    LEFT JOIN user_roles r ON ura.roleID = r.roleID
    WHERE u.userID = $userID
    LIMIT 1
";

try {
    $result = $_database->query($sql);
    if ($result && $user = $result->fetch_assoc()) {

        // Werte aufbereiten
        $username       = htmlspecialchars($user['username']);
        $about_me       = !empty($user['about_me']) ? $user['about_me'] : 'Keine Informationen über mich.';
        #$register_date  = !empty($user['registerdate']) && is_numeric($user['registerdate']) ? date('d.m.Y', (int)$user['registerdate']) : 'Unbekannt';
        $register_date  = (!empty($user['registerdate']) && strtotime($user['registerdate']) !== false)
    ? date('d.m.Y', strtotime($user['registerdate']))
    : 'Unbekannt';
        #$last_visit     = !empty($user['lastlogin']) && is_numeric($user['lastlogin']) ? date('d.m.Y H:i', (int)$user['lastlogin']) : 'Nie besucht';
        $last_visit     = (!empty($user['lastlogin']) && strtotime($user['lastlogin']) !== false)
    ? date('d.m.Y H:i', strtotime($user['lastlogin']))
    : 'Nie besucht';

        $points         = isset($user['points']) ? (int)$user['points'] : 0;
        $avatar_url     = !empty($user['avatar']) ? '/images/avatars/' . $user['avatar'] : "/images/avatars/noavatar.png";
        $role_name      = !empty($user['role_name']) ? $user['role_name'] : 'Benutzer';
        $location       = !empty($user['location']) ? $user['location'] : 'Unbekannter Ort';

        $level          = floor($points / 100);
        $level_percent  = $points % 100;

        $twitter_url    = !empty($user['twitter']) ? htmlspecialchars($user['twitter']) : '#';
        $facebook_url   = !empty($user['facebook']) ? htmlspecialchars($user['facebook']) : '#';
        $discord_url    = !empty($user['discord']) ? htmlspecialchars($user['discord']) : '#';

        $is_own_profile = ($_SESSION['userID'] ?? 0) === $userID;
        $edit_button    = $is_own_profile ? '<a href="edit_profile.php" class="btn btn-outline-primary mt-3"><i class="fas fa-user-edit"></i> Profil bearbeiten</a>' : '';
        #$edit_button    = '<a href="edit_profile.php" class="btn btn-outline-primary mt-3"><i class="fas fa-user-edit"></i> Profil bearbeiten</a>';

        $banned         = !empty($user['banned'] ?? 0) == 1
    ? '<div class="alert alert-danger" role="alert"><i class="bi bi-exclamation-square"></i> Dieser Benutzer ist gebannt!</div>' : '';

        $last_activity = !empty($user['lastlogin']) ? strtotime($user['lastlogin']) : 0;
$current_time = time();

if ($last_activity > 0 && $last_activity <= $current_time) {
    $online_seconds = $current_time - $last_activity;
    $online_hours = floor($online_seconds / 3600);
    $online_minutes = floor(($online_seconds % 3600) / 60);
    $online_time = "$online_hours Stunden, $online_minutes Minuten";
} else {
    $online_time = "Keine Aktivität";
}

        // Anzahl der Logins berechnen
        $stmt = $_database->prepare("SELECT COUNT(*) AS login_count FROM user_sessions WHERE userID = ?");
        $stmt->bind_param('i', $userID);
        $stmt->execute();
        $stmt->bind_result($logins_count);
        $stmt->fetch();
        $stmt->close();

        $logins = $logins_count > 0 ? $logins_count : 0;

        // Dummy-Daten
        $posts    = 42;
        $comments = 103;



    } else {
        echo "Benutzer nicht gefunden.";
        exit();
    }
} catch (mysqli_sql_exception $e) {
    echo "SQL-Fehler: " . $e->getMessage();
    exit();
}

$data_array = [
    'username'        => htmlspecialchars($user['username']),
    'user_picture'    => $avatar_url,
    'user_role'       => $role_name,
    'user_points'     => $points,
    'user_about'      => $about_me,
    'user_signature'  => $user['signature'] ?? '„Keep coding & carry on.“',
    'user_name'       => htmlspecialchars($user['firstname'] ?? 'Max'),
    'user_surname'    => htmlspecialchars($user['lastname'] ?? 'Muster'),
    'user_age'        => htmlspecialchars($user['age'] ?? 'Nicht angegeben'),
    'user_location'   => $location,
    'user_sexuality'  => htmlspecialchars($user['sexuality'] ?? 'Nicht angegeben'),
    'user_posts'      => '<ul><li>Beitrag 1</li><li>Beitrag 2</li></ul>', // Optional dynamisch ersetzen
    'register_date'   => $register_date, 
    'user_activity'   => '<p>Zuletzt online: ' . $last_visit . '</p><p>Online-Zeit: ' . $online_time . '</p><p>Logins: ' . $logins . '</p>',
    #'user_activity'   => '<p>Zuletzt online: ' . $last_visit . '</p><p>Logins: ' . $logins . '</p>',

    'github_url'      => htmlspecialchars($user['github'] ?? '#'),
    'twitter_url'     => $twitter_url,
    'facebook_url'    => $facebook_url,
    'discord_url'     => $discord_url,
    'instagram_url'   => htmlspecialchars($user['instagram'] ?? '#'),
    'website_url'     => htmlspecialchars($user['website'] ?? '#'),
    'register_date'   => $register_date,
    'last_visit'      => $last_visit,
    'user_level'      => $level,
    'level_progress'  => $level_percent, // z. B. für Fortschrittsanzeige in %
    'edit_button'     => $edit_button,
    'comments_count'  => $comments,
    'posts_count'     => $posts,
    'banned' => $banned
];

echo $tpl->loadTemplate("profile", "content", $data_array);

?>






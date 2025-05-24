<?php


$userID = $_SESSION['userID'] ?? null;
if (!$userID) {
    die('Nicht eingeloggt.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = $_POST['firstname'] ?? '';
    $lastname = $_POST['lastname'] ?? '';
    $location = $_POST['location'] ?? '';
    $about_me = $_POST['about_me'] ?? '';

    $twitter = $_POST['twitter'] ?? '';
    $facebook = $_POST['facebook'] ?? '';
    $website = $_POST['website'] ?? '';
    $github = $_POST['github'] ?? '';
    $instagram = $_POST['instagram'] ?? '';

    $dark_mode = isset($_POST['dark_mode']) ? 1 : 0;
    $email_notifications = isset($_POST['email_notifications']) ? 1 : 0;

    $avatar_url = null;
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['avatar']['tmp_name'];
        $fileName = basename($_FILES['avatar']['name']);
        $fileType = mime_content_type($fileTmpPath);
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

        if (!in_array($fileType, $allowedTypes)) {
            die('Ungültiger Dateityp.');
        }

        $uploadDir = 'images/avatars/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $ext = pathinfo($fileName, PATHINFO_EXTENSION);
        $newName = "avatar_user{$userID}_" . time() . '.' . $ext;
        $avatarPath = $uploadDir . $newName;

        if (!move_uploaded_file($fileTmpPath, $avatarPath)) {
            die('Upload fehlgeschlagen.');
        }

        $avatar_url = $avatarPath;
    }

    $result = $_database->query("SELECT userID FROM user_profiles WHERE userID = $userID");
    if ($result->num_rows > 0) {
        $query = "UPDATE user_profiles SET 
            firstname = '$firstname',
            lastname = '$lastname',
            location = '$location',
            about_me = '$about_me'";
        if ($avatar_url) {
            $query .= ", avatar = '$avatar_url'";
        }
        $query .= " WHERE userID = $userID";
    } else {
        $columns = "userID, firstname, lastname, location, about_me";
        $values = "$userID, '$firstname', '$lastname', '$location', '$about_me'";
        if ($avatar_url) {
            $columns .= ", avatar";
            $values .= ", '$avatar_url'";
        }
        $query = "INSERT INTO user_profiles ($columns) VALUES ($values)";
    }
    $_database->query($query);

    $result = $_database->query("SELECT userID FROM user_socials WHERE userID = $userID");
    if ($result->num_rows > 0) {
        $_database->query("UPDATE user_socials SET 
            instagram = '$instagram',
            github = '$github',
            twitter = '$twitter',
            facebook = '$facebook',
            website = '$website'
            WHERE userID = $userID");
    } else {
        $_database->query("INSERT INTO user_socials (userID, twitter, facebook, website, github, instagram) 
            VALUES ($userID, '$twitter', '$facebook', '$website', '$github', '$instagram')");
    }

    $result = $_database->query("SELECT userID FROM user_settings WHERE userID = $userID");
    if ($result->num_rows > 0) {
        $_database->query("UPDATE user_settings SET 
            dark_mode = $dark_mode, 
            email_notifications = $email_notifications 
            WHERE userID = $userID");
    } else {
        $_database->query("INSERT INTO user_settings (userID, dark_mode, email_notifications) 
            VALUES ($userID, $dark_mode, $email_notifications)");
    }

    header("Location: index.php?site=profile&userID=$userID");
    exit;
}

$firstname = $lastname = $location = $about_me = $avatar = '';
$twitter = $facebook = $website = $github = $instagram = '';
$dark_mode = $email_notifications = 0;

$result = $_database->query("SELECT firstname, lastname, location, about_me, avatar FROM user_profiles WHERE userID = $userID");
if ($row = $result->fetch_assoc()) {
    extract($row);
}

$result = $_database->query("SELECT twitter, facebook, website, github, instagram FROM user_socials WHERE userID = $userID");
if ($row = $result->fetch_assoc()) {
    extract($row);
}

$result = $_database->query("SELECT dark_mode, email_notifications FROM user_settings WHERE userID = $userID");
if ($row = $result->fetch_assoc()) {
    extract($row);
}

$data_array = [
    'userID' => $userID,
    'firstname' => $firstname,
    'lastname' => $lastname,
    'location' => $location,
    'about_me' => $about_me,
    'avatar_url' => $avatar,
    'twitter' => $twitter,
    'facebook' => $facebook,
    'website' => $website,
    'github' => $github,
    'instagram' => $instagram,
    'dark_mode_checked' => $dark_mode ? 'checked' : '',
    'email_notifications_checked' => $email_notifications ? 'checked' : '',
];

echo $tpl->loadTemplate("edit_profiles", "content", $data_array, 'theme');
?>

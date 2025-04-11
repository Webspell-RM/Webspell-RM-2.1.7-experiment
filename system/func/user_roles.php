<?php
require_once("../system/sql.php");
require_once("../system/functions.php");

// Rollen erstellen
if (isset($_POST['create_role']) && !empty($_POST['role_name'])) {
    $roleName = escape($_POST['role_name']);
    safe_query("INSERT INTO " . PREFIX . "user_roles (name) VALUES ('$roleName')");
}

// Rolle löschen
if (isset($_GET['delete'])) {
    $roleID = (int)$_GET['delete'];
    safe_query("DELETE FROM " . PREFIX . "user_roles WHERE roleID = '$roleID'");
}

// Rollen abrufen
$roles = safe_query("SELECT * FROM " . PREFIX . "user_roles ORDER BY name");
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Rollenverwaltung</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2 class="mb-4">Admin-Rollen verwalten</h2>

    <!-- Neue Rolle hinzufügen -->
    <form method="post" class="row g-3 mb-5">
        <div class="col-auto">
            <input type="text" name="role_name" class="form-control" placeholder="Neue Rolle" required>
        </div>
        <div class="col-auto">
            <button type="submit" name="create_role" class="btn btn-primary">Hinzufügen</button>
        </div>
    </form>

    <!-- Rollenliste -->
    <table class="table table-bordered bg-white shadow-sm">
        <thead class="table-light">
        <tr>
            <th>Rollenname</th>
            <th style="width: 150px">Aktionen</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($role = mysqli_fetch_assoc($roles)) : ?>
            <tr>
                <td><?= htmlspecialchars($role['name']) ?></td>
                <td>
                    <a href="?delete=<?= $role['roleID'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Wirklich löschen?')">Löschen</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

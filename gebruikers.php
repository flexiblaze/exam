<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$role = $_SESSION['role'];

// Alleen directie mag gebruikers beheren
if ($role !== 'directie') {
    header('Location: dashboard.php');
    exit;
}

// Alle gebruikers ophalen
$stmt = $pdo->query("SELECT id, gebruikersnaam, rollen, is_geverifieerd FROM gebruiker ORDER BY gebruikersnaam ASC");
$gebruikers = $stmt->fetchAll();

// Nieuwe gebruiker toevoegen
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $gebruikersnaam = $_POST['gebruikersnaam'];
    $wachtwoord = password_hash($_POST['wachtwoord'], PASSWORD_DEFAULT);
    $rollen = $_POST['rollen'];
    $is_geverifieerd = isset($_POST['is_geverifieerd']) ? 1 : 0;

    $stmt = $pdo->prepare("INSERT INTO gebruiker (gebruikersnaam, wachtwoord, rollen, is_geverifieerd) VALUES (?, ?, ?, ?)");
    $stmt->execute([$gebruikersnaam, $wachtwoord, $rollen, $is_geverifieerd]);

    header('Location: gebruikers.php');
    exit;
}

// Gebruiker verwijderen
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];
    $stmt = $pdo->prepare("DELETE FROM gebruiker WHERE id = ?");
    $stmt->execute([$user_id]);

    header('Location: gebruikers.php');
    exit;
}

// Gebruiker bewerken
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_user'])) {
    $user_id = $_POST['user_id'];
    $gebruikersnaam = $_POST['gebruikersnaam'];
    $rollen = $_POST['rollen'];
    $is_geverifieerd = isset($_POST['is_geverifieerd']) ? 1 : 0;

    $stmt = $pdo->prepare("UPDATE gebruiker SET gebruikersnaam = ?, rollen = ?, is_geverifieerd = ? WHERE id = ?");
    $stmt->execute([$gebruikersnaam, $rollen, $is_geverifieerd, $user_id]);

    header('Location: gebruikers.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gebruikersbeheer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">Gebruikersbeheer</h2>

        <!-- Gebruiker toevoegen -->
        <h4>Nieuwe gebruiker toevoegen</h4>
        <form method="POST" class="mb-4">
            <div class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="gebruikersnaam" class="form-control" placeholder="Gebruikersnaam" required>
                </div>
                <div class="col-md-3">
                    <input type="password" name="wachtwoord" class="form-control" placeholder="Wachtwoord" required>
                </div>
                <div class="col-md-3">
                    <select name="rollen" class="form-select" required>
                        <option value="directie">Directie</option>
                        <option value="magazijnmedewerker">Magazijnmedewerker</option>
                        <option value="winkelpersoneel">Winkelpersoneel</option>
                        <option value="chauffeur">Chauffeur</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="checkbox" name="is_geverifieerd"> Geverifieerd
                </div>
                <div class="col-md-1">
                    <button type="submit" name="add_user" class="btn btn-primary">Toevoegen</button>
                </div>
            </div>
        </form>

        <!-- Gebruikerslijst -->
        <h4>Geregistreerde Gebruikers</h4>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Gebruikersnaam</th>
                    <th>Rol</th>
                    <th>Geverifieerd</th>
                    <th>Acties</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($gebruikers as $gebruiker): ?>
                <tr>
                    <td><?= htmlspecialchars($gebruiker['gebruikersnaam']); ?></td>
                    <td><?= htmlspecialchars($gebruiker['rollen']); ?></td>
                    <td><?= $gebruiker['is_geverifieerd'] ? 'Ja' : 'Nee'; ?></td>
                    <td>
                        <!-- Bewerken -->
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $gebruiker['id']; ?>">Bewerken</button>

                        <!-- Verwijderen -->
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?= $gebruiker['id']; ?>">
                            <button type="submit" name="delete_user" class="btn btn-danger btn-sm">Verwijderen</button>
                        </form>
                    </td>
                </tr>

                <!-- Bewerken Modal -->
                <div class="modal fade" id="editModal<?= $gebruiker['id']; ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Gebruiker Bewerken</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST">
                                    <input type="hidden" name="user_id" value="<?= $gebruiker['id']; ?>">
                                    <div class="mb-3">
                                        <label>Gebruikersnaam</label>
                                        <input type="text" name="gebruikersnaam" class="form-control" value="<?= htmlspecialchars($gebruiker['gebruikersnaam']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Rol</label>
                                        <select name="rollen" class="form-select" required>
                                            <option value="directie" <?= $gebruiker['rollen'] === 'directie' ? 'selected' : ''; ?>>Directie</option>
                                            <option value="magazijnmedewerker" <?= $gebruiker['rollen'] === 'magazijnmedewerker' ? 'selected' : ''; ?>>Magazijnmedewerker</option>
                                            <option value="winkelpersoneel" <?= $gebruiker['rollen'] === 'winkelpersoneel' ? 'selected' : ''; ?>>Winkelpersoneel</option>
                                            <option value="chauffeur" <?= $gebruiker['rollen'] === 'chauffeur' ? 'selected' : ''; ?>>Chauffeur</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <input type="checkbox" name="is_geverifieerd" <?= $gebruiker['is_geverifieerd'] ? 'checked' : ''; ?>> Geverifieerd
                                    </div>
                                    <button type="submit" name="edit_user" class="btn btn-success">Opslaan</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="dashboard.php" class="btn btn-secondary">Terug naar Dashboard</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

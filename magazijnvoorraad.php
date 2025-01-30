<?php
session_start();
require 'backend/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$role = $_SESSION['role'];

// Alleen directie en magazijnmedewerkers mogen magazijnvoorraad beheren
if ($role !== 'directie' && $role !== 'magazijnmedewerker') {
    header('Location: dashboard.php');
    exit;
}

// Filter en zoekfunctionaliteit
$search_query = "";
$params = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
    $search = '%' . $_POST['search'] . '%';
    $search_query = "WHERE a.naam LIKE ? OR c.categorie LIKE ?";
    $params = [$search, $search];
}

// Magazijnvoorraad ophalen
$query = "SELECT m.id, a.naam, a.merk, a.kleur, a.afmeting, a.ean_nummer, m.aantal, c.categorie
          FROM voorraad m
          JOIN artikel a ON m.artikel_id = a.id
          JOIN categorie c ON a.categorie_id = c.id
          $search_query
          ORDER BY c.categorie, a.naam";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$voorraad = $stmt->fetchAll();

// Aantal artikelen aanpassen
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_aantal'])) {
    $voorraad_id = $_POST['voorraad_id'];
    $nieuw_aantal = $_POST['nieuw_aantal'];

    $stmt = $pdo->prepare("UPDATE voorraad SET aantal = ? WHERE id = ?");
    $stmt->execute([$nieuw_aantal, $voorraad_id]);

    header('Location: magazijnvoorraad.php');
    exit;
}

// Artikel naar winkelvoorraad verplaatsen
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['move_to_winkel'])) {
    $voorraad_id = $_POST['voorraad_id'];

    // Haal artikelgegevens op
    $stmt = $pdo->prepare("SELECT * FROM voorraad WHERE id = ?");
    $stmt->execute([$voorraad_id]);
    $artikel = $stmt->fetch();

    if ($artikel) {
        $stmt = $pdo->prepare("INSERT INTO winkelvoorraad (artikel_id, aantal) VALUES (?, ?) 
                               ON DUPLICATE KEY UPDATE aantal = aantal + VALUES(aantal)");
        $stmt->execute([$artikel['artikel_id'], $artikel['aantal']]);

        // Verminder magazijnvoorraad
        $stmt = $pdo->prepare("DELETE FROM voorraad WHERE id = ?");
        $stmt->execute([$voorraad_id]);
    }

    header('Location: magazijnvoorraad.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Magazijnvoorraad</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">Magazijnvoorraad</h2>

        <!-- Zoekfunctie -->
        <form method="POST" class="mb-4">
            <div class="row g-3">
                <div class="col-md-8">
                    <input type="text" name="search" class="form-control" placeholder="Zoek op artikel of categorie">
                </div>
                <div class="col-md-4">
                    <button type="submit" name="search_submit" class="btn btn-primary w-100">Zoeken</button>
                </div>
            </div>
        </form>

        <!-- Voorraad overzicht -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Categorie</th>
                    <th>Artikel</th>
                    <th>Merk</th>
                    <th>Kleur</th>
                    <th>Afmeting</th>
                    <th>EAN</th>
                    <th>Aantal</th>
                    <th>Acties</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($voorraad as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['categorie']); ?></td>
                    <td><?= htmlspecialchars($item['naam']); ?></td>
                    <td><?= htmlspecialchars($item['merk']); ?></td>
                    <td><?= htmlspecialchars($item['kleur']); ?></td>
                    <td><?= htmlspecialchars($item['afmeting']); ?></td>
                    <td><?= htmlspecialchars($item['ean_nummer']); ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="voorraad_id" value="<?= $item['id']; ?>">
                            <input type="number" name="nieuw_aantal" class="form-control form-control-sm d-inline" style="width: 70px;" value="<?= $item['aantal']; ?>">
                            <button type="submit" name="update_aantal" class="btn btn-success btn-sm">Opslaan</button>
                        </form>
                    </td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="voorraad_id" value="<?= $item['id']; ?>">
                            <button type="submit" name="move_to_winkel" class="btn btn-warning btn-sm">Naar winkel</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="dashboard.php" class="btn btn-secondary mt-3">Terug naar Dashboard</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$role = $_SESSION['role'];

if ($role !== 'directie' && $role !== 'magazijnmedewerker') {
    header('Location: dashboard.php');
    exit;
}

// Zoekfunctionaliteit
$search_query = "";
$params = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
    $search = '%' . $_POST['search'] . '%';
    $search_query = "WHERE a.naam LIKE ? OR a.ean_nummer LIKE ?";
    $params = [$search, $search];
}

// Voorraad ophalen
$query = "SELECT v.id, a.naam, a.merk, a.kleur, a.afmeting, a.ean_nummer, v.aantal, v.locatie, v.reparatie_nodig, v.verkoop_gereed, a.prijs_ex_btw, v.ingeboekt_op
          FROM voorraad v
          JOIN artikel a ON v.artikel_id = a.id
          $search_query
          ORDER BY a.naam";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$voorraad = $stmt->fetchAll();

// Aantal artikelen aanpassen
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_aantal'])) {
    $voorraad_id = $_POST['voorraad_id'];
    $nieuw_aantal = $_POST['nieuw_aantal'];

    $stmt = $pdo->prepare("UPDATE voorraad SET aantal = ? WHERE id = ?");
    $stmt->execute([$nieuw_aantal, $voorraad_id]);

    header('Location: voorraad.php');
    exit;
}

// Status wijzigen (Reparatie nodig / Verkoop-gereed)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $voorraad_id = $_POST['voorraad_id'];
    $reparatie_nodig = $_POST['reparatie_nodig'];
    $verkoop_gereed = $_POST['verkoop_gereed'];

    $stmt = $pdo->prepare("UPDATE voorraad SET reparatie_nodig = ?, verkoop_gereed = ? WHERE id = ?");
    $stmt->execute([$reparatie_nodig, $verkoop_gereed, $voorraad_id]);

    header('Location: voorraad.php');
    exit;
}

// Artikel verwijderen
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_artikel'])) {
    $voorraad_id = $_POST['voorraad_id'];
    $stmt = $pdo->prepare("DELETE FROM voorraad WHERE id = ?");
    $stmt->execute([$voorraad_id]);

    header('Location: voorraad.php');
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
        <h2 class="text-center">Magazijnvoorraad Beheer</h2>

        <!-- Zoekfunctie -->
        <form method="POST" class="mb-4">
            <div class="row g-3">
                <div class="col-md-8">
                    <input type="text" name="search" class="form-control" placeholder="Zoek op artikelnaam of EAN-nummer">
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
                    <th>Artikel</th>
                    <th>Merk</th>
                    <th>Kleur</th>
                    <th>Afmeting</th>
                    <th>EAN</th>
                    <th>Aantal</th>
                    <th>Locatie</th>
                    <th>Prijs (ex. BTW)</th>
                    <th>Reparatie Nodig</th>
                    <th>Verkoop Gereed</th>
                    <th>Acties</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($voorraad as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['naam']); ?></td>
                    <td><?= htmlspecialchars($item['merk']); ?></td>
                    <td><?= htmlspecialchars($item['kleur']); ?></td>
                    <td><?= htmlspecialchars($item['afmeting']); ?></td>
                    <td><?= htmlspecialchars($item['ean_nummer']); ?></td>
                    <td><?= htmlspecialchars($item['aantal']); ?></td>
                    <td><?= htmlspecialchars($item['locatie']); ?></td>
                    <td>€ <?= number_format($item['prijs_ex_btw'], 2, ',', '.'); ?></td>
                    <td><?= $item['reparatie_nodig'] ? 'Ja' : 'Nee'; ?></td>
                    <td><?= $item['verkoop_gereed'] ? 'Ja' : 'Nee'; ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="voorraad_id" value="<?= $item['id']; ?>">
                            <button type="submit" name="delete_artikel" class="btn btn-danger btn-sm">Verwijderen</button>
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

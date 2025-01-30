<?php
session_start();
require 'backend/db.php';

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

// Artikel- en Voorraadgegevens ophalen
$query = "SELECT a.id AS artikel_id, a.naam, a.merk, a.kleur, a.afmeting, a.ean_nummer, a.prijs_ex_btw, 
                 v.id AS voorraad_id, v.aantal, v.locatie, v.reparatie_nodig, v.verkoop_gereed, v.ingeboekt_op
          FROM artikel a
          LEFT JOIN voorraad v ON a.id = v.artikel_id
          $search_query
          ORDER BY a.naam";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$artikelen_voorraad = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Magazijnvoorraad & Artikelen Beheer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">Magazijnvoorraad & Artikelen Overzicht</h2>

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

        <!-- Voorraad en Artikelen overzicht -->
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
                    <th>Ingeboekt Op</th>
                    <th>Acties</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($artikelen_voorraad as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['naam']); ?></td>
                    <td><?= htmlspecialchars($item['merk']); ?></td>
                    <td><?= htmlspecialchars($item['kleur']); ?></td>
                    <td><?= htmlspecialchars($item['afmeting']); ?></td>
                    <td><?= htmlspecialchars($item['ean_nummer']); ?></td>
                    <td><?= $item['aantal'] !== null ? htmlspecialchars($item['aantal']) : 'N/A'; ?></td>
                    <td><?= $item['locatie'] !== null ? htmlspecialchars($item['locatie']) : 'N/A'; ?></td>
                    <td>€ <?= number_format($item['prijs_ex_btw'], 2, ',', '.'); ?></td>
                    <td><?= $item['reparatie_nodig'] ? 'Ja' : 'Nee'; ?></td>
                    <td><?= $item['verkoop_gereed'] ? 'Ja' : 'Nee'; ?></td>
                    <td><?= $item['ingeboekt_op'] ? htmlspecialchars($item['ingeboekt_op']) : 'N/A'; ?></td>
                    <td>
                        <?php if ($item['voorraad_id']): ?>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="voorraad_id" value="<?= $item['voorraad_id']; ?>">
                            <button type="submit" name="delete_artikel" class="btn btn-danger btn-sm">Verwijderen</button>
                        </form>
                        <?php endif; ?>
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

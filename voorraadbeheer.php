<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Haal categorieën en statussen op
$categorieen = $pdo->query("SELECT id, categorie FROM categorie ORDER BY categorie ASC")->fetchAll(PDO::FETCH_ASSOC);
$statussen = $pdo->query("SELECT id, status FROM status ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);

// **Voorraad toevoegen**
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['voorraad_toevoegen'])) {
    $artikel_id = $_POST['artikel_id'];
    $locatie = trim($_POST['locatie']);
    $aantal = $_POST['aantal'];
    $prijs = $_POST['prijs'];
    $status_id = isset($_POST['status_id']) ? $_POST['status_id'] : 1;

    // Controleer of artikel bestaat
    $artikelCheck = $pdo->prepare("SELECT COUNT(*) FROM artikel WHERE id = ?");
    $artikelCheck->execute([$artikel_id]);

    if ($artikelCheck->fetchColumn() == 0) {
        $error = "Ongeldig artikel.";
    } else {
        // Voeg voorraad toe
        $stmt = $pdo->prepare("INSERT INTO voorraad (artikel_id, locatie, aantal, prijs, status_id, ingeboekt_op) 
                            VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$artikel_id, $locatie, $aantal, $prijs, $status_id]);

        header("Location: voorraadbeheer.php");
        exit;
    }
}

// Haal artikelen op
$artikelen = $pdo->query("SELECT id, naam FROM artikel ORDER BY naam ASC")->fetchAll(PDO::FETCH_ASSOC);

// Haal bestaande voorraad op
$voorraad = $pdo->query("
    SELECT v.id, a.naam AS artikel_naam, a.merk, a.ean_nummer, c.categorie AS categorie, 
           v.locatie, v.aantal, v.prijs, s.status, v.ingeboekt_op
    FROM voorraad v
    JOIN artikel a ON v.artikel_id = a.id
    JOIN categorie c ON a.categorie_id = c.id
    JOIN status s ON v.status_id = s.id
    ORDER BY v.ingeboekt_op DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voorraadbeheer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- ✅ **Sidebar navigatie zoals artikelbeheer.php** -->
<div class="d-flex">
    <nav class="bg-dark text-white p-3 vh-100" style="width: 250px; position: fixed;">
        <h4 class="mb-3">Kringloop Centrum</h4>
        <ul class="nav flex-column">
            <li class="nav-item"><a href="dashboard.php" class="nav-link text-white">📊 Dashboard</a></li>
            <li class="nav-item"><a href="ritplanning.php" class="nav-link text-white">🚛 Rittenplanning</a></li>
            <li class="nav-item"><a href="voorraadbeheer.php" class="nav-link text-primary">📦 Voorraadbeheer</a></li>
            <li class="nav-item"><a href="gebruikers.php" class="nav-link text-white">👤 Gebruikersbeheer</a></li>
            <li class="nav-item"><a href="opbrengst.php" class="nav-link text-white">💰 Opbrengst</a></li>
            <li class="nav-item mt-4"><a href="logout.php" class="btn btn-danger w-100">🚪 Afmelden</a></li>
        </ul>
    </nav>

    <!-- ✅ **Content zoals in artikelbeheer.php** -->
    <div class="container mt-4" style="margin-left: 260px; width: calc(100% - 260px);">
        <h1 class="mb-4">Voorraadbeheer</h1>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" class="mb-4">
            <h3>Voorraad Toevoegen</h3>

            <div class="mb-3">
                <label for="artikel_id" class="form-label">Artikel</label>
                <select name="artikel_id" id="artikel_id" class="form-control" required>
                    <?php foreach ($artikelen as $art): ?>
                        <option value="<?= $art['id']; ?>"><?= htmlspecialchars($art['naam']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="locatie" class="form-label">Locatie</label>
                <input type="text" name="locatie" id="locatie" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="aantal" class="form-label">Aantal</label>
                <input type="number" name="aantal" id="aantal" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="prijs" class="form-label">Prijs (€)</label>
                <input type="number" step="0.01" name="prijs" id="prijs" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="status_id" class="form-label">Status</label>
                <select name="status_id" id="status_id" class="form-control">
                    <?php foreach ($statussen as $status): ?>
                        <option value="<?= $status['id']; ?>"><?= htmlspecialchars($status['status']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" name="voorraad_toevoegen" class="btn btn-success">Voorraad Toevoegen</button>
        </form>

        <!-- ✅ **Voorraad Overzicht** -->
        <h3>Voorraad Overzicht</h3>
        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Artikel</th>
                    <th>Merk</th>
                    <th>Categorie</th>
                    <th>EAN-Nummer</th>
                    <th>Locatie</th>
                    <th>Aantal</th>
                    <th>Prijs (€)</th>
                    <th>Status</th>
                    <th>Datum</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($voorraad as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['artikel_naam']); ?></td>
                        <td><?= htmlspecialchars($item['merk']); ?></td>
                        <td><?= htmlspecialchars($item['categorie']); ?></td>
                        <td><?= htmlspecialchars($item['ean_nummer']); ?></td>
                        <td><?= htmlspecialchars($item['locatie']); ?></td>
                        <td><?= htmlspecialchars($item['aantal']); ?></td>
                        <td>€<?= number_format($item['prijs'], 2, ',', '.'); ?></td>
                        <td><?= htmlspecialchars($item['status']); ?></td>
                        <td><?= htmlspecialchars($item['ingeboekt_op']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>

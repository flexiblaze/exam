<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$role = $_SESSION['role'];

// Alleen directie en magazijnmedewerkers mogen artikelen beheren
if ($role !== 'directie' && $role !== 'magazijnmedewerker') {
    header('Location: dashboard.php');
    exit;
}

// Categorieën ophalen
$categorie_stmt = $pdo->query("SELECT * FROM categorie ORDER BY categorie ASC");
$categorie_result = $categorie_stmt->fetchAll();

// Artikelen ophalen
$stmt = $pdo->query("SELECT a.id, a.naam, a.prijs_ex_btw, a.merk, a.kleur, a.afmeting, a.aantal, a.ean_nummer, c.categorie 
                     FROM artikel a
                     JOIN categorie c ON a.categorie_id = c.id
                     ORDER BY c.categorie, a.naam");
$artikelen = $stmt->fetchAll();

// Artikel toevoegen
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_artikel'])) {
    $categorie_id = $_POST['categorie_id'];
    $naam = $_POST['naam'];
    $prijs_ex_btw = $_POST['prijs_ex_btw'];
    $merk = $_POST['merk'];
    $kleur = $_POST['kleur'];
    $afmeting = $_POST['afmeting'];
    $aantal = $_POST['aantal'];
    $ean_nummer = $_POST['ean_nummer'];

    $stmt = $pdo->prepare("INSERT INTO artikel (categorie_id, naam, prijs_ex_btw, merk, kleur, afmeting, aantal, ean_nummer) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$categorie_id, $naam, $prijs_ex_btw, $merk, $kleur, $afmeting, $aantal, $ean_nummer]);

    header('Location: artikelen.php');
    exit;
}

// Artikel verwijderen
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_artikel'])) {
    $artikel_id = $_POST['artikel_id'];
    $stmt = $pdo->prepare("DELETE FROM artikel WHERE id = ?");
    $stmt->execute([$artikel_id]);

    header('Location: artikelen.php');
    exit;
}

// Artikel bewerken
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_artikel'])) {
    $artikel_id = $_POST['artikel_id'];
    $categorie_id = $_POST['categorie_id'];
    $naam = $_POST['naam'];
    $prijs_ex_btw = $_POST['prijs_ex_btw'];
    $merk = $_POST['merk'];
    $kleur = $_POST['kleur'];
    $afmeting = $_POST['afmeting'];
    $aantal = $_POST['aantal'];
    $ean_nummer = $_POST['ean_nummer'];

    $stmt = $pdo->prepare("UPDATE artikel SET categorie_id = ?, naam = ?, prijs_ex_btw = ?, merk = ?, kleur = ?, afmeting = ?, aantal = ?, ean_nummer = ? WHERE id = ?");
    $stmt->execute([$categorie_id, $naam, $prijs_ex_btw, $merk, $kleur, $afmeting, $aantal, $ean_nummer, $artikel_id]);

    header('Location: artikelen.php');
    exit;
}

// Categorie toevoegen
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_categorie'])) {
    $categorie = $_POST['categorie'];

    $stmt = $pdo->prepare("INSERT INTO categorie (categorie) VALUES (?)");
    $stmt->execute([$categorie]);

    header('Location: artikelen.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artikelen Beheer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">Artikelen Beheer</h2>

        <!-- Categorie toevoegen -->
        <h4>Nieuwe categorie toevoegen</h4>
        <form method="POST" class="mb-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <input type="text" name="categorie" class="form-control" placeholder="Nieuwe categorie" required>
                </div>
                <div class="col-md-3">
                    <button type="submit" name="add_categorie" class="btn btn-secondary">Categorie Toevoegen</button>
                </div>
            </div>
        </form>

        <!-- Artikel toevoegen -->
        <h4>Nieuw artikel toevoegen</h4>
        <form method="POST" class="mb-4">
            <div class="row g-3">
                <div class="col-md-2">
                    <select name="categorie_id" class="form-select" required>
                        <option value="">Selecteer Categorie</option>
                        <?php foreach ($categorie_result as $categorie): ?>
                            <option value="<?= $categorie['id']; ?>"><?= htmlspecialchars($categorie['categorie']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="text" name="naam" class="form-control" placeholder="Artikelnaam" required>
                </div>
                <div class="col-md-1">
                    <input type="number" step="0.01" name="prijs_ex_btw" class="form-control" placeholder="Prijs (ex. BTW)" required>
                </div>
                <div class="col-md-1">
                    <input type="text" name="merk" class="form-control" placeholder="Merk" required>
                </div>
                <div class="col-md-1">
                    <input type="text" name="kleur" class="form-control" placeholder="Kleur" required>
                </div>
                <div class="col-md-1">
                    <input type="text" name="afmeting" class="form-control" placeholder="Afmeting" required>
                </div>
                <div class="col-md-1">
                    <input type="number" name="aantal" class="form-control" placeholder="Aantal" required>
                </div>
                <div class="col-md-2">
                    <input type="text" name="ean_nummer" class="form-control" placeholder="EAN-nummer" required>
                </div>
                <div class="col-md-1">
                    <button type="submit" name="add_artikel" class="btn btn-primary">Toevoegen</button>
                </div>
            </div>
        </form>

        <!-- Artikelenlijst -->
        <h4>Geregistreerde Artikelen</h4>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Categorie</th>
                    <th>Naam</th>
                    <th>Merk</th>
                    <th>Kleur</th>
                    <th>Afmeting</th>
                    <th>Aantal</th>
                    <th>EAN-nummer</th>
                    <th>Prijs (ex. BTW)</th>
                    <th>Acties</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($artikelen as $artikel): ?>
                <tr>
                    <td><?= htmlspecialchars($artikel['categorie']); ?></td>
                    <td><?= htmlspecialchars($artikel['naam']); ?></td>
                    <td><?= htmlspecialchars($artikel['merk']); ?></td>
                    <td><?= htmlspecialchars($artikel['kleur']); ?></td>
                    <td><?= htmlspecialchars($artikel['afmeting']); ?></td>
                    <td><?= htmlspecialchars($artikel['aantal']); ?></td>
                    <td><?= htmlspecialchars($artikel['ean_nummer']); ?></td>
                    <td>€ <?= number_format($artikel['prijs_ex_btw'], 2, ',', '.'); ?></td>
                    <td><form method="POST"><input type="hidden" name="artikel_id" value="<?= $artikel['id']; ?>"><button type="submit" name="delete_artikel" class="btn btn-danger btn-sm">Verwijderen</button></form></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="dashboard.php" class="btn btn-secondary">Terug naar Dashboard</a>
    </div>
</body>
</html>

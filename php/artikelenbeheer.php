<?php
require '../backend/db.php';
include 'sidebar.php'; // Voeg de sidebar toe

// Zoekfunctie
$search_query = "";
if (isset($_GET['search'])) {
    $search_query = $_GET['search'];
    $stmt = $pdo->prepare("SELECT id, ean_nummer, naam AS categorie, merk, kleur, afmeting, aantal 
                           FROM artikel 
                           WHERE ean_nummer LIKE ? 
                           OR naam LIKE ? 
                           OR merk LIKE ? 
                           OR kleur LIKE ? 
                           OR afmeting LIKE ?
                           ORDER BY naam ASC");
    $stmt->execute(["%$search_query%", "%$search_query%", "%$search_query%", "%$search_query%", "%$search_query%"]);
    $artikelen = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $artikelen = $pdo->query("SELECT id, ean_nummer, naam AS categorie, merk, kleur, afmeting, aantal FROM artikel ORDER BY naam ASC")->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artikelenbeheer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Content begint hier, met de juiste afstand vanaf de sidebar -->
<div class="content">
    <h1 class="mb-4">Artikelenbeheer</h1>

    <!-- Zoekbalk -->
    <form method="GET" class="d-flex mb-4">
        <input type="text" name="search" class="form-control me-2" placeholder="Zoek op EAN, categorie, merk, kleur, afmeting..." value="<?= htmlspecialchars($search_query); ?>">
        <button type="submit" class="btn btn-secondary">Zoeken</button>
    </form>

    <table class="table mt-4">
        <thead class="table-dark">
            <tr>
                <th>EAN</th>
                <th>Naam (Categorie)</th>
                <th>Merk</th>
                <th>Kleur</th>
                <th>Afmeting/Maat</th>
                <th>Aantal</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($artikelen) > 0): ?>
                <?php foreach ($artikelen as $artikel): ?>
                    <tr>
                        <td><?= htmlspecialchars($artikel['ean_nummer']); ?></td>
                        <td><?= htmlspecialchars($artikel['categorie']); ?></td>
                        <td><?= htmlspecialchars($artikel['merk'] ?? 'Onbekend'); ?></td>
                        <td><?= htmlspecialchars($artikel['kleur'] ?? 'Onbekend'); ?></td>
                        <td><?= htmlspecialchars($artikel['afmeting'] ?? 'Onbekend'); ?></td>
                        <td><?= htmlspecialchars($artikel['aantal']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">Geen artikelen gevonden.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        &copy; <?= date('Y'); ?> Kringloop Centrum Duurzaam | Alle rechten voorbehouden.
    </div>
</div>

</body>
</html>

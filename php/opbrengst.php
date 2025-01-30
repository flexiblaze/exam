<?php
session_start();
require '../backend/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

// Verwijderen van een verkoop
if (isset($_GET['delete'])) {
    $verkoop_id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM verkopen WHERE id = ?");
    $stmt->execute([$verkoop_id]);
    header("Location: opbrengst.php");
    exit;
}

// Zoekfilter op datum
$datum_van = isset($_GET['datum_van']) ? $_GET['datum_van'] : '';
$datum_tot = isset($_GET['datum_tot']) ? $_GET['datum_tot'] : '';

$query = "SELECT v.id, a.naam AS artikel, k.naam AS klant, v.verkocht_op, a.prijs_ex_btw 
          FROM verkopen v
          JOIN artikel a ON v.artikel_id = a.id
          JOIN klant k ON v.klant_id = k.id";

$params = [];
if ($datum_van && $datum_tot) {
    $query .= " WHERE v.verkocht_op BETWEEN ? AND ?";
    $params[] = $datum_van;
    $params[] = $datum_tot;
}

//query voor het soorteren op datum verkocht
$query .= " ORDER BY v.verkocht_op DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$verkopen = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Totaalopbrengst berekenen
$totaal_opbrengst = $pdo->prepare("SELECT SUM(a.prijs_ex_btw) AS totaal FROM verkopen v JOIN artikel a ON v.artikel_id = a.id WHERE v.verkocht_op BETWEEN ? AND ?");
$totaal_opbrengst->execute([$datum_van ?: '0000-00-00', $datum_tot ?: '9999-12-31']);
$totaal = $totaal_opbrengst->fetch()['totaal'] ?? 0;
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Opbrengst Verkoop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="content">
    <h1 class="mb-4">Opbrengst Verkoop</h1>

    <!-- Datum filter -->
    <form method="GET" class="d-flex mb-4">
        <input type="date" name="datum_van" class="form-control me-2" value="<?= htmlspecialchars($datum_van); ?>">
        <input type="date" name="datum_tot" class="form-control me-2" value="<?= htmlspecialchars($datum_tot); ?>">
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>

    <h4 class="mb-3">Totaalopbrengst: €<?= number_format($totaal, 2, ',', '.'); ?></h4>

    <table class="table mt-4">
        <thead class="table-dark">
            <tr>
                <th>Artikel</th>
                <th>Klant</th>
                <th>Datum Verkoop</th>
                <th>Prijs</th>
                <th>Actie</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($verkopen) > 0): ?>
                <?php foreach ($verkopen as $verkoop): ?>
                    <tr>
                        <td><?= htmlspecialchars($verkoop['artikel']); ?></td>
                        <td><?= htmlspecialchars($verkoop['klant']); ?></td>
                        <td><?= htmlspecialchars($verkoop['verkocht_op']); ?></td>
                        <td>€<?= number_format($verkoop['prijs_ex_btw'], 2, ',', '.'); ?></td>
                        <td>
                            <a href="opbrengst.php?delete=<?= $verkoop['id']; ?>" class="btn btn-danger btn-sm">Verwijderen</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">Geen verkopen gevonden.</td>
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

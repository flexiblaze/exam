<?php
session_start();
require 'backend/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$role = $_SESSION['role'];

// Alleen directie mag opbrengsten bekijken
if ($role !== 'directie') {
    header('Location: dashboard.php');
    exit;
}

// Datumfilter verwerken
$filter_query = "";
$params = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['filter'])) {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    if (!empty($start_date) && !empty($end_date)) {
        $filter_query = "WHERE v.verkocht_op BETWEEN ? AND ?";
        $params = [$start_date, $end_date];
    }
}

// Verkochte artikelen ophalen
$query = "SELECT v.id, k.naam AS klant, a.naam AS artikel, a.prijs_ex_btw, v.verkocht_op 
          FROM verkopen v
          JOIN klant k ON v.klant_id = k.id
          JOIN artikel a ON v.artikel_id = a.id
          $filter_query
          ORDER BY v.verkocht_op DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$verkopen = $stmt->fetchAll();

// Totaalopbrengst berekenen
$total_opbrengst = 0;
foreach ($verkopen as $verkoop) {
    $total_opbrengst += $verkoop['prijs_ex_btw'];
}
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
    <div class="container mt-4">
        <h2 class="text-center">Opbrengst Verkoop</h2>

        <!-- Filteren op periode -->
        <h4>Filter op periode</h4>
        <form method="POST" class="mb-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="start_date" class="form-label">Startdatum</label>
                    <input type="date" name="start_date" id="start_date" class="form-control">
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label">Einddatum</label>
                    <input type="date" name="end_date" id="end_date" class="form-control">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" name="filter" class="btn btn-primary w-100">Filteren</button>
                </div>
            </div>
        </form>

        <!-- Overzicht verkochte artikelen -->
        <h4>Verkochte artikelen</h4>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Klant</th>
                    <th>Artikel</th>
                    <th>Prijs (ex. BTW)</th>
                    <th>Verkocht op</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($verkopen as $verkoop): ?>
                <tr>
                    <td><?= htmlspecialchars($verkoop['klant']); ?></td>
                    <td><?= htmlspecialchars($verkoop['artikel']); ?></td>
                    <td>€ <?= number_format($verkoop['prijs_ex_btw'], 2, ',', '.'); ?></td>
                    <td><?= htmlspecialchars($verkoop['verkocht_op']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h4 class="mt-4">Totale Opbrengst: <strong>€ <?= number_format($total_opbrengst, 2, ',', '.'); ?></strong></h4>

        <a href="dashboard.php" class="btn btn-secondary mt-3">Terug naar Dashboard</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

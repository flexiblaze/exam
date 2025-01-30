<?php
session_start();
require '../backend/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$role = $_SESSION['role'];

// Alleen chauffeurs en directie mogen ritten bekijken
if ($role !== 'chauffeur' && $role !== 'directie') {
    header('Location: dashboard.php');
    exit;
}

// Haal alle geplande ritten op
$stmt = $pdo->query("SELECT r.id, r.kenteken, r.afspraak_op, r.ophalen_of_bezorgen, k.naam AS klant 
                     FROM planning r 
                     JOIN klant k ON r.klant_id = k.id 
                     ORDER BY r.afspraak_op ASC");
$ritten = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ritten Overzicht</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">Geplande Ritten</h2>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Kenteken</th>
                    <th>Klant</th>
                    <th>Type Rit</th>
                    <th>Datum & Tijd</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ritten as $rit): ?>
                <tr>
                    <td><?= htmlspecialchars($rit['kenteken']); ?></td>
                    <td><?= htmlspecialchars($rit['klant']); ?></td>
                    <td><?= htmlspecialchars($rit['ophalen_of_bezorgen']); ?></td>
                    <td><?= htmlspecialchars($rit['afspraak_op']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="dashboard.php" class="btn btn-secondary">Terug naar Dashboard</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

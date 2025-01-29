<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$role = $_SESSION['role'];

// Alleen winkelpersoneel en directie mogen ritten plannen
if ($role !== 'winkelpersoneel' && $role !== 'directie') {
    header('Location: dashboard.php');
    exit;
}

// Ritten ophalen
$stmt = $pdo->query("SELECT r.id, r.kenteken, r.afspraak_op, r.ophalen_of_bezorgen, k.naam AS klant 
                     FROM planning r 
                     JOIN klant k ON r.klant_id = k.id 
                     ORDER BY r.afspraak_op ASC");
$ritten = $stmt->fetchAll();

// Formulier verwerken (toevoegen of verwijderen)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_rit'])) {
        $kenteken = $_POST['kenteken'];
        $klant_id = $_POST['klant_id'];
        $ophalen_of_bezorgen = $_POST['ophalen_of_bezorgen'];
        $afspraak_op = $_POST['afspraak_op'];

        $stmt = $pdo->prepare("INSERT INTO planning (kenteken, klant_id, ophalen_of_bezorgen, afspraak_op) 
                               VALUES (?, ?, ?, ?)");
        $stmt->execute([$kenteken, $klant_id, $ophalen_of_bezorgen, $afspraak_op]);

        header('Location: ritplanning.php');
        exit;
    }

    if (isset($_POST['delete_rit'])) {
        $rit_id = $_POST['rit_id'];
        $stmt = $pdo->prepare("DELETE FROM planning WHERE id = ?");
        $stmt->execute([$rit_id]);

        header('Location: ritplanning.php');
        exit;
    }
}

// Klanten ophalen voor dropdown menu
$klanten = $pdo->query("SELECT id, naam FROM klant")->fetchAll();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ritten Planning</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">Ritten Planning</h2>

        <h4>Nieuwe rit toevoegen</h4>
        <form method="POST" class="mb-4">
            <div class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="kenteken" class="form-control" placeholder="Kenteken" required>
                </div>
                <div class="col-md-3">
                    <select name="klant_id" class="form-select" required>
                        <option value="">Selecteer Klant</option>
                        <?php foreach ($klanten as $klant): ?>
                            <option value="<?= $klant['id']; ?>"><?= htmlspecialchars($klant['naam']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="ophalen_of_bezorgen" class="form-select" required>
                        <option value="ophalen">Ophalen</option>
                        <option value="bezorgen">Bezorgen</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="datetime-local" name="afspraak_op" class="form-control" required>
                </div>
            </div>
            <button type="submit" name="add_rit" class="btn btn-primary mt-3">Toevoegen</button>
        </form>

        <h4>Geplande ritten</h4>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Kenteken</th>
                    <th>Klant</th>
                    <th>Type Rit</th>
                    <th>Datum & Tijd</th>
                    <th>Acties</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ritten as $rit): ?>
                <tr>
                    <td><?= htmlspecialchars($rit['kenteken']); ?></td>
                    <td><?= htmlspecialchars($rit['klant']); ?></td>
                    <td><?= htmlspecialchars($rit['ophalen_of_bezorgen']); ?></td>
                    <td><?= htmlspecialchars($rit['afspraak_op']); ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="rit_id" value="<?= $rit['id']; ?>">
                            <button type="submit" name="delete_rit" class="btn btn-danger btn-sm">Verwijderen</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="dashboard.php" class="btn btn-secondary">Terug naar Dashboard</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

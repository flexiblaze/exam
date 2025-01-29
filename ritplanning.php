<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Haal bestaande artikelen en klanten op voor dropdowns
$artikelen = $pdo->query("SELECT id, naam FROM artikel")->fetchAll(PDO::FETCH_ASSOC);
$klanten = $pdo->query("SELECT id, naam FROM klant")->fetchAll(PDO::FETCH_ASSOC);

// Voeg een nieuwe rit toe als het formulier is ingediend
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_rit'])) {
    $artikel_id = $_POST['artikel_id'];
    $klant_id = $_POST['klant_id'];
    $kenteken = $_POST['kenteken'];
    $ophalen_of_bezorgen = $_POST['ophalen_of_bezorgen'];
    $afspraak_op = $_POST['afspraak_op'];

    // Controleer of artikel en klant bestaan
    $artikel_check = $pdo->prepare("SELECT id FROM artikel WHERE id = ?");
    $artikel_check->execute([$artikel_id]);

    $klant_check = $pdo->prepare("SELECT id FROM klant WHERE id = ?");
    $klant_check->execute([$klant_id]);

    if ($artikel_check->rowCount() === 0) {
        echo "<div class='alert alert-danger'>Fout: Geselecteerd artikel bestaat niet.</div>";
    } elseif ($klant_check->rowCount() === 0) {
        echo "<div class='alert alert-danger'>Fout: Geselecteerde klant bestaat niet.</div>";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO planning (artikel_id, klant_id, kenteken, ophalen_of_bezorgen, afspraak_op) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$artikel_id, $klant_id, $kenteken, $ophalen_of_bezorgen, $afspraak_op]);
            echo "<div class='alert alert-success'>Rit succesvol toegevoegd!</div>";
        } catch (PDOException $e) {
            echo "<div class='alert alert-danger'>Fout bij toevoegen rit: " . $e->getMessage() . "</div>";
        }
    }
}

// Verwijder een rit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_rit'])) {
    $rit_id = $_POST['rit_id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM planning WHERE id = ?");
        $stmt->execute([$rit_id]);
        echo "<div class='alert alert-success'>Rit succesvol verwijderd!</div>";
    } catch (PDOException $e) {
        echo "<div class='alert alert-danger'>Fout bij verwijderen rit: " . $e->getMessage() . "</div>";
    }
}

// Filter ritten op datum
$date_filter = "";
if (isset($_GET['date_from']) && isset($_GET['date_to'])) {
    $date_from = $_GET['date_from'];
    $date_to = $_GET['date_to'];
    $date_filter = "WHERE afspraak_op BETWEEN '$date_from' AND '$date_to'";
}

$ritten = $pdo->query("
    SELECT p.id, a.naam AS artikel, k.naam AS klant, p.kenteken, p.ophalen_of_bezorgen, p.afspraak_op 
    FROM planning p
    JOIN artikel a ON p.artikel_id = a.id
    JOIN klant k ON p.klant_id = k.id
    $date_filter
    ORDER BY p.afspraak_op DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ritplanning</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h1 class="mb-4">Plan een nieuwe rit</h1>
    
    <form method="POST" class="card p-4 shadow-sm">
        <div class="mb-3">
            <label for="artikel_id" class="form-label">Artikel</label>
            <select name="artikel_id" id="artikel_id" class="form-select" required>
                <option value="" disabled selected>Selecteer een artikel</option>
                <?php foreach ($artikelen as $artikel) { ?>
                    <option value="<?= $artikel['id']; ?>"><?= htmlspecialchars($artikel['naam']); ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="klant_id" class="form-label">Klant</label>
            <select name="klant_id" id="klant_id" class="form-select" required>
                <option value="" disabled selected>Selecteer een klant</option>
                <?php foreach ($klanten as $klant) { ?>
                    <option value="<?= $klant['id']; ?>"><?= htmlspecialchars($klant['naam']); ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="kenteken" class="form-label">Kenteken</label>
            <input type="text" name="kenteken" id="kenteken" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="ophalen_of_bezorgen" class="form-label">Ophalen of Bezorgen</label>
            <select name="ophalen_of_bezorgen" id="ophalen_of_bezorgen" class="form-select" required>
                <option value="ophalen">Ophalen</option>
                <option value="bezorgen">Bezorgen</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="afspraak_op" class="form-label">Afspraak Datum</label>
            <input type="datetime-local" name="afspraak_op" id="afspraak_op" class="form-control" required>
        </div>

        <button type="submit" name="add_rit" class="btn btn-primary w-100">Rit Toevoegen</button>
    </form>

    <h2 class="mt-5">Overzicht van geplande ritten</h2>

    <form method="GET" class="d-flex gap-2">
        <input type="date" name="date_from" class="form-control" required>
        <input type="date" name="date_to" class="form-control" required>
        <button type="submit" class="btn btn-secondary">Filter op datum</button>
    </form>

    <table class="table mt-4">
        <thead class="table-dark">
            <tr>
                <th>Artikel</th>
                <th>Klant</th>
                <th>Kenteken</th>
                <th>Ophalen/Bezorgen</th>
                <th>Afspraak Datum</th>
                <th>Actie</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ritten as $rit) { ?>
                <tr>
                    <td><?= htmlspecialchars($rit['artikel']); ?></td>
                    <td><?= htmlspecialchars($rit['klant']); ?></td>
                    <td><?= htmlspecialchars($rit['kenteken']); ?></td>
                    <td><?= htmlspecialchars($rit['ophalen_of_bezorgen']); ?></td>
                    <td><?= htmlspecialchars($rit['afspraak_op']); ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="rit_id" value="<?= $rit['id']; ?>">
                            <button type="submit" name="delete_rit" class="btn btn-danger btn-sm">Verwijderen</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>

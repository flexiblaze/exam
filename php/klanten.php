<?php
session_start();
require '../backend/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$role = $_SESSION['role'];

// Alleen winkelpersoneel en directie mogen klanten beheren
if ($role !== 'winkelpersoneel' && $role !== 'directie') {
    header('Location: dashboard.php');
    exit;
}

// Klanten ophalen
$stmt = $pdo->query("SELECT * FROM klant ORDER BY naam ASC");
$klanten = $stmt->fetchAll();

// Klant toevoegen
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_klant'])) {
    $naam = $_POST['naam'];
    $adres = $_POST['adres'];
    $plaats = $_POST['plaats'];
    $telefoon = $_POST['telefoon'];
    $email = $_POST['email'];

    $stmt = $pdo->prepare("INSERT INTO klant (naam, adres, plaats, telefoon, email) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$naam, $adres, $plaats, $telefoon, $email]);

    header('Location: klanten.php');
    exit;
}

// Klant verwijderen
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_klant'])) {
    $klant_id = $_POST['klant_id'];
    $stmt = $pdo->prepare("DELETE FROM klant WHERE id = ?");
    $stmt->execute([$klant_id]);

    header('Location: klanten.php');
    exit;
}

// Klant bewerken
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_klant'])) {
    $klant_id = $_POST['klant_id'];
    $naam = $_POST['naam'];
    $adres = $_POST['adres'];
    $plaats = $_POST['plaats'];
    $telefoon = $_POST['telefoon'];
    $email = $_POST['email'];

    $stmt = $pdo->prepare("UPDATE klant SET naam = ?, adres = ?, plaats = ?, telefoon = ?, email = ? WHERE id = ?");
    $stmt->execute([$naam, $adres, $plaats, $telefoon, $email, $klant_id]);

    header('Location: klanten.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klantenbeheer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">Klanten & Personen Beheer</h2>

        <!-- Klant toevoegen -->
        <h4>Nieuwe klant toevoegen</h4>
        <form method="POST" class="mb-4">
            <div class="row g-3">
                <div class="col-md-2">
                    <input type="text" name="naam" class="form-control" placeholder="Naam" required>
                </div>
                <div class="col-md-2">
                    <input type="text" name="adres" class="form-control" placeholder="Adres" required>
                </div>
                <div class="col-md-2">
                    <input type="text" name="plaats" class="form-control" placeholder="Plaats" required>
                </div>
                <div class="col-md-2">
                    <input type="text" name="telefoon" class="form-control" placeholder="Telefoon" required>
                </div>
                <div class="col-md-2">
                    <input type="email" name="email" class="form-control" placeholder="E-mail" required>
                </div>
                <div class="col-md-2">
                    <button type="submit" name="add_klant" class="btn btn-primary">Toevoegen</button>
                </div>
            </div>
        </form>

        <!-- Klantenlijst -->
        <h4>Geregistreerde Klanten</h4>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Naam</th>
                    <th>Adres</th>
                    <th>Plaats</th>
                    <th>Telefoon</th>
                    <th>E-mail</th>
                    <th>Acties</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($klanten as $klant): ?>
                <tr>
                    <td><?= htmlspecialchars($klant['naam']); ?></td>
                    <td><?= htmlspecialchars($klant['adres']); ?></td>
                    <td><?= htmlspecialchars($klant['plaats']); ?></td>
                    <td><?= htmlspecialchars($klant['telefoon']); ?></td>
                    <td><?= htmlspecialchars($klant['email']); ?></td>
                    <td>
                        <!-- Bewerken -->
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $klant['id']; ?>">Bewerken</button>

                        <!-- Verwijderen -->
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="klant_id" value="<?= $klant['id']; ?>">
                            <button type="submit" name="delete_klant" class="btn btn-danger btn-sm">Verwijderen</button>
                        </form>
                    </td>
                </tr>

                <!-- Bewerken Modal -->
                <div class="modal fade" id="editModal<?= $klant['id']; ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Klant Bewerken</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST">
                                    <input type="hidden" name="klant_id" value="<?= $klant['id']; ?>">
                                    <div class="mb-3">
                                        <label>Naam</label>
                                        <input type="text" name="naam" class="form-control" value="<?= htmlspecialchars($klant['naam']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Adres</label>
                                        <input type="text" name="adres" class="form-control" value="<?= htmlspecialchars($klant['adres']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Plaats</label>
                                        <input type="text" name="plaats" class="form-control" value="<?= htmlspecialchars($klant['plaats']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Telefoon</label>
                                        <input type="text" name="telefoon" class="form-control" value="<?= htmlspecialchars($klant['telefoon']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>E-mail</label>
                                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($klant['email']); ?>" required>
                                    </div>
                                    <button type="submit" name="edit_klant" class="btn btn-success">Opslaan</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="dashboard.php" class="btn btn-secondary">Terug naar Dashboard</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

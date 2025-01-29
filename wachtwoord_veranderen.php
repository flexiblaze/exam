<?php  
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $old_password = trim($_POST['old_password']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (empty($old_password) || empty($new_password) || empty($confirm_password)) {
        $error = "Vul alle velden in.";
    } elseif ($new_password !== $confirm_password) {
        $error = "Nieuw wachtwoord en bevestiging komen niet overeen.";
    } elseif (strlen($new_password) < 6) {
        $error = "Het nieuwe wachtwoord moet minimaal 6 tekens lang zijn.";
    } else {
        $stmt = $pdo->prepare("SELECT wachtwoord FROM gebruiker WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();

        if ($user && password_verify($old_password, $user['wachtwoord'])) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE gebruiker SET wachtwoord = ? WHERE id = ?");
            $stmt->execute([$hashed_password, $user_id]);

            $success = "Wachtwoord succesvol gewijzigd.";
        } else {
            $error = "Oud wachtwoord is onjuist.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wachtwoord Wijzigen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .card { width: 100%; max-width: 400px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); padding: 20px; background: #fff; }
        .btn-primary { background-color: #007bff; border: none; }
        .btn-primary:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <div class="card">
        <h2 class="text-center text-primary">Wachtwoord Wijzigen</h2>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="old_password" class="form-label">Oud Wachtwoord</label>
                <input type="password" name="old_password" id="old_password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="new_password" class="form-label">Nieuw Wachtwoord</label>
                <input type="password" name="new_password" id="new_password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Bevestig Nieuw Wachtwoord</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Wachtwoord Wijzigen</button>
        </form>

        <p class="text-center mt-3"><a href="dashboard.php">Terug naar Dashboard</a></p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

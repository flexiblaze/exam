<?php  
session_start();
require 'db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error = "Vul alle velden in.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM gebruiker WHERE gebruikersnaam = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['wachtwoord'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['gebruikersnaam'];
            $_SESSION['role'] = $user['rollen'];
            header('Location: dashboard.php');
            exit;
        } else {
            $error = "Onjuist wachtwoord of gebruiker niet gevonden.";
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $new_username = trim($_POST['new_username']);
    $new_password = trim($_POST['new_password']);

    if (empty($new_username) || empty($new_password)) {
        $error = "Vul alle velden in voor registratie.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM gebruiker WHERE gebruikersnaam = ?");
        $stmt->execute([$new_username]);
        $existing_user = $stmt->fetch();

        if ($existing_user) {
            $error = "Gebruikersnaam bestaat al.";
        } else {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO gebruiker (gebruikersnaam, wachtwoord, rollen, is_geverifieerd) VALUES (?, ?, 'gebruiker', 1)");
            $stmt->execute([$new_username, $hashed_password]);

            $success = "Account aangemaakt. Je kunt nu inloggen.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inloggen / Registreren</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .login-card { width: 100%; max-width: 400px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); padding: 20px; background: #fff; }
        .btn-primary { background-color: #007bff; border: none; }
        .btn-primary:hover { background-color: #0056b3; }
        .toggle-link { color: #007bff; cursor: pointer; text-decoration: underline; }
    </style>
</head>
<body>
    <div class="login-card">
        <h2 class="text-center text-primary" id="form-title">Inloggen</h2>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form method="POST" id="login-form">
            <input type="hidden" name="login">
            <div class="mb-3">
                <label for="username" class="form-label">Gebruikersnaam</label>
                <input type="text" name="username" id="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Wachtwoord</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Inloggen</button>
        </form>

        <form method="POST" id="register-form" style="display: none;">
            <input type="hidden" name="register">
            <div class="mb-3">
                <label for="new_username" class="form-label">Nieuwe Gebruikersnaam</label>
                <input type="text" name="new_username" id="new_username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="new_password" class="form-label">Nieuw Wachtwoord</label>
                <input type="password" name="new_password" id="new_password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Registreren</button>
        </form>

        <p class="text-center mt-3">
            <span id="toggle-text">Nog geen account?</span>
            <span class="toggle-link" id="toggle-link">Registreer hier</span>
        </p>
    </div>

    <script>
        const loginForm = document.getElementById('login-form');
        const registerForm = document.getElementById('register-form');
        const toggleText = document.getElementById('toggle-text');
        const toggleLink = document.getElementById('toggle-link');
        const formTitle = document.getElementById('form-title');

        toggleLink.addEventListener('click', () => {
            if (loginForm.style.display === 'none') {
                loginForm.style.display = 'block';
                registerForm.style.display = 'none';
                formTitle.innerText = 'Inloggen';
                toggleText.innerText = 'Nog geen account?';
                toggleLink.innerText = 'Registreer hier';
            } else {
                loginForm.style.display = 'none';
                registerForm.style.display = 'block';
                formTitle.innerText = 'Registreren';
                toggleText.innerText = 'Heb je al een account?';
                toggleLink.innerText = 'Log in hier';
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

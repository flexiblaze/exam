<?php
session_start();
require 'db.php'; // Zorg ervoor dat je een db.php hebt met PDO-verbinding

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $gebruikersnaam = trim($_POST['gebruikersnaam']);
    $wachtwoord = trim($_POST['wachtwoord']);

    if (!empty($gebruikersnaam) && !empty($wachtwoord)) {
        $stmt = $pdo->prepare("SELECT * FROM gebruiker WHERE gebruikersnaam = ?");
        $stmt->execute([$gebruikersnaam]);
        $user = $stmt->fetch();

        if (!$user) {
            die("Geen gebruiker gevonden met deze gebruikersnaam: " . htmlspecialchars($gebruikersnaam));
        }


        $stmt->execute([$gebruikersnaam]);
        $user = $stmt->fetch();

        if ($user && password_verify($wachtwoord, $user['wachtwoord'])) {
            if ($user['is_geverifieerd'] == 1) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['gebruikersnaam'] = $user['gebruikersnaam'];
                $_SESSION['rol'] = $user['rollen'];

                // Redirect op basis van de rol
                switch ($user['rollen']) {
                    case 'directie':
                        header("Location: admin_dashboard.php");
                        break;
                    case 'magazijnmedewerker':
                        header("Location: magazijn_dashboard.php");
                        break;
                    case 'winkelpersoneel':
                        header("Location: winkel_dashboard.php");
                        break;
                    case 'chauffeur':
                        header("Location: chauffeur_dashboard.php");
                        break;
                    default:
                        header("Location: login.php");
                        break;
                }
                exit;
            } else {
                $error = "Je account is nog niet geverifieerd.";
            }
        } else {
            $error = "Ongeldige inloggegevens!";
        }
    } else {
        $error = "Vul alle velden in!";
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <?php if (!empty($error)) echo "<p style='color: red;'>$error</p>"; ?>
    <form method="post">
        <label>Gebruikersnaam:</label>
        <input type="text" name="gebruikersnaam" required>
        <br>
        <label>Wachtwoord:</label>
        <input type="password" name="wachtwoord" required>
        <br>
        <button type="submit">Inloggen</button>
    </form>
</body>
</html>

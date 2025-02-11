<?php
session_start();
require '../backend/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kringloop Centrum Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="styling/style.css" rel="stylesheet">
</head>


<body>
    <header class="d-flex justify-content-between align-items-center p-3 bg-primary text-white">


        <nav>
            <ul class="nav">
<<<<<<< HEAD:dashboard.php
                <li class="nav-item"><a class="nav-link text-white fw-bold" href="#">Kringloop Centrum</a></li>
=======
                <li class="nav-item"><a class="nav-link text-white">Kringloop Centrum</a></li>
>>>>>>> dd5e7204265ca1ffe676f2bf9623e7c8330b2cd3:php/dashboard.php
                <li class="nav-item"><a class="nav-link text-white" href="dashboard.php">Home</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">Ritten</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="ritplanning.php">Ritten Planning</a></li>
                    </ul>
                </li>
                <?php if ($role === 'directie' || $role === 'magazijnmedewerker'): ?>
                    <li class="nav-item"><a class="nav-link text-white" href="voorraadbeheer.php">Voorraad</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="artikelenbeheer.php">Artikelen Beheer</a></li>
                <?php endif; ?>
                <?php if ($role === 'winkelpersoneel' || $role === 'directie'): ?>
                    <li class="nav-item"><a class="nav-link text-white" href="klanten.php">Klantenbeheer</a></li>
                <?php endif; ?>
                <?php if ($role === 'directie'): ?>
                    <li class="nav-item"><a class="nav-link text-white" href="gebruikers.php">Gebruikersbeheer</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="opbrengst.php">Opbrengst Verkoop</a></li>
                <?php endif; ?>
            </ul>
        </nav>
<<<<<<< HEAD:dashboard.php
        <div class="d-flex align-items-center">
            <!-- ✅ Dropdown menu voor de gebruiker -->
            <div class="dropdown me-3">
                <button class="btn btn-light dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <?= htmlspecialchars($_SESSION['username']); ?> (<?= htmlspecialchars($_SESSION['role']); ?>)
                </button>
                <ul class="dropdown-menu" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href="wachtwoord_veranderen.php">Wachtwoord Veranderen</a></li>
                    <li><a class="dropdown-item text-danger" href="logout.php">Uitloggen</a></li>
                </ul>
            </div>
        </div>
=======


        <a href="../backend/logout.php" class="btn btn-danger">Uitloggen</a>
>>>>>>> dd5e7204265ca1ffe676f2bf9623e7c8330b2cd3:php/dashboard.php
    </header>

    <main class="container mt-4">
        <h2 class="text-center">Welkom, <?= htmlspecialchars($_SESSION['username']); ?></h2>
        <p class="text-center">Je rol: <strong><?= htmlspecialchars($_SESSION['role']); ?></strong></p>

        <div class="row row-cols-1 row-cols-md-2 g-4">
            <div class="col">
                <div class="card text-center">
                    <div class="card-body">
                        <h2 class="card-title">Ritten</h2>
                        <p class="card-text">Beheer en plan ritten voor ophalen en bezorgen.</p>
                        <a href="ritplanning.php" class="btn btn-primary">Ritten Planning</a>
                    </div>
                </div>
            </div>
            <?php if ($role === 'directie' || $role === 'magazijnmedewerker'): ?>
            <div class="col">
                <div class="card text-center">
                    <div class="card-body">
                        <h2 class="card-title">Voorraad Beheer</h2>
                        <p class="card-text">Bekijk en beheer de voorraad</p>
                        <a href="voorraadbeheer.php" class="btn btn-primary">Voorraad</a>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-center">
                    <div class="card-body">
                        <h2 class="card-title">Artikelen Beheer</h2>
                        <p class="card-text">Beheer alle artikelen en voorraadstatus.</p>
                        <a href="artikelenbeheer.php" class="btn btn-primary">Artikelen Beheer</a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <?php if ($role === 'winkelpersoneel' || $role === 'directie'): ?>
            <div class="col">
                <div class="card text-center">
                    <div class="card-body">
                        <h2 class="card-title">Klantenbeheer</h2>
                        <p class="card-text">Beheer klantgegevens en verkoopinformatie.</p>
                        <a href="klanten.php" class="btn btn-primary">Klantenbeheer</a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <?php if ($role === 'directie'): ?>
            <div class="col">
                <div class="card text-center">
                    <div class="card-body">
                        <h2 class="card-title">Gebruikersbeheer</h2>
                        <p class="card-text">Beheer gebruikers, rollen en toegangsrechten.</p>
                        <a href="gebruikers.php" class="btn btn-primary">Gebruikersbeheer</a>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-center">
                    <div class="card-body">
                        <h2 class="card-title">Opbrengst Verkoop</h2>
                        <p class="card-text">Bekijk verkoopopbrengsten per periode.</p>
                        <a href="opbrengst.php" class="btn btn-primary">Opbrengst Verkoop</a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- ✅ Footer -->
    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p>&copy; <?= date("Y"); ?> Kringloop Centrum Duurzaam | Alle rechten voorbehouden</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
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
                <li class="nav-item"><a class="nav-link text-white" href="#">Kringloop Centrum</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="dashboard.php">Home</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">Rollen</a>
                    <ul class="dropdown-menu">
                        <?php if ($role === 'directie' || $role === 'magazijnmedewerker'): ?>
                            <li><a class="dropdown-item" href="voorraad.php">Magazijnvoorraad</a></li>
                            <li><a class="dropdown-item" href="artikelen.php">Artikelen Beheer</a></li>
                        <?php endif; ?>
                        <?php if ($role === 'winkelpersoneel' || $role === 'directie'): ?>
                            <li><a class="dropdown-item" href="klanten.php">Klantenbeheer</a></li>
                        <?php endif; ?>
                        <?php if ($role === 'directie'): ?>
                            <li><a class="dropdown-item" href="gebruikers.php">Gebruikersbeheer</a></li>
                            <li><a class="dropdown-item" href="opbrengst.php">Opbrengst Verkoop</a></li>
                        <?php endif; ?>
                        <li><a class="dropdown-item" href="ritplanning.php">Ritten Planning</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <a href="logout.php" class="btn btn-danger">Uitloggen</a>
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
                        <h2 class="card-title">Magazijnvoorraad</h2>
                        <p class="card-text">Bekijk en beheer de voorraad in het magazijn.</p>
                        <a href="voorraad.php" class="btn btn-primary">Magazijnvoorraad</a>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-center">
                    <div class="card-body">
                        <h2 class="card-title">Artikelen Beheer</h2>
                        <p class="card-text">Beheer alle artikelen en voorraadstatus.</p>
                        <a href="artikelen.php" class="btn btn-primary">Artikelen Beheer</a>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

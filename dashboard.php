<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'];
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
        <li class="nav-item"><a class="nav-link text-white" href="#">Home</a></li>

        <!-- Dropdown menu voor Ritten -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-white" href="#" id="rittenDropdown" role="button" data-bs-toggle="dropdown">
            Ritten
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="ritplanning.php">Ritten Planning</a></li>
          </ul>
        </li>

        <li class="nav-item"><a class="nav-link text-white" href="voorraad.php">Voorraad Beheer</a></li>

        <!-- Beheer dropdown (alleen zichtbaar voor directie) -->
        <?php if ($role === 'directie'): ?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-white" href="#" id="beheerDropdown" role="button" data-bs-toggle="dropdown">
            Beheer
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="gebruikers.php">Gebruikersbeheer</a></li>
            <li><a class="dropdown-item" href="artikelen.php">Artikelen Beheer</a></li>
            <li><a class="dropdown-item" href="winkelvoorraad.php">Winkelvoorraad Beheer</a></li>
            <li><a class="dropdown-item" href="opbrengst.php">Opbrengst Verkoop</a></li>
            <li><a class="dropdown-item" href="overzichten.php">Wekelijkse / Maandelijkse Overzichten</a></li>
            <li><a class="dropdown-item" href="klanten.php">Klanten & Personen Beheer</a></li>
            <li><a class="dropdown-item" href="ritplanning.php">Ritten Planning</a></li>
          </ul>
        </li>
        <?php endif; ?>

        <li class="nav-item"><a class="nav-link text-white" href="wachtwoord_veranderen.php">Wachtwoord Wijzigen</a></li>
      </ul>
    </nav>
    
    <div>
      <span class="me-3">Ingelogd als: <strong><?= htmlspecialchars($role); ?></strong></span>
      <a href="logout.php" class="btn btn-danger">Afmelden</a>
    </div>
  </header>

  <main class="container mt-4">
    <h2 class="text-center">Welkom, <?= htmlspecialchars($username); ?>!</h2>
    <p class="text-center text-muted">Je bent ingelogd als <strong><?= htmlspecialchars($role); ?></strong>.</p>

    <div class="row row-cols-1 row-cols-md-2 g-4">
      <!-- Magazijnmedewerker -->
      <?php if ($role === 'magazijnmedewerker' || $role === 'directie'): ?>
      <div class="col">
        <div class="card text-center">
          <div class="card-body">
            <h2 class="card-title">Magazijnvoorraad</h2>
            <p class="card-text">Beheer de voorraad in het magazijn.</p>
            <a href="voorraad.php" class="btn btn-primary">Ga naar voorraadbeheer</a>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card text-center">
          <div class="card-body">
            <h2 class="card-title">Artikelen Beheer</h2>
            <p class="card-text">Voeg nieuwe artikelen toe en beheer bestaande artikelen.</p>
            <a href="artikelen.php" class="btn btn-primary">Ga naar artikelen</a>
          </div>
        </div>
      </div>
      <?php endif; ?>

      <!-- Winkelpersoneel -->
      <?php if ($role === 'winkelpersoneel' || $role === 'directie'): ?>
      <div class="col">
        <div class="card text-center">
          <div class="card-body">
            <h2 class="card-title">Winkelvoorraad</h2>
            <p class="card-text">Beheer de voorraad in de winkel.</p>
            <a href="winkelvoorraad.php" class="btn btn-primary">Ga naar winkelvoorraad</a>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card text-center">
          <div class="card-body">
            <h2 class="card-title">Klantenbeheer</h2>
            <p class="card-text">Beheer klanten en personen.</p>
            <a href="klanten.php" class="btn btn-primary">Ga naar klantenbeheer</a>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card text-center">
          <div class="card-body">
            <h2 class="card-title">Ritplanning</h2>
            <p class="card-text">Beheer de rittenplanning.</p>
            <a href="ritplanning.php" class="btn btn-primary">Ga naar ritplanning</a>
          </div>
        </div>
      </div>
      <?php endif; ?>

      <!-- Chauffeur -->
      <?php if ($role === 'chauffeur' || $role === 'directie'): ?>
      <div class="col">
        <div class="card text-center">
          <div class="card-body">
            <h2 class="card-title">Ritten Planning</h2>
            <p class="card-text">Bekijk en beheer ritten.</p>
            <a href="ritplanning.php" class="btn btn-primary">Ga naar ritplanning</a>
          </div>
        </div>
      </div>
      <?php endif; ?>

      <!-- Directie specifieke opties -->
      <?php if ($role === 'directie'): ?>
      <div class="col">
        <div class="card text-center">
          <div class="card-body">
            <h2 class="card-title">Gebruikersbeheer</h2>
            <p class="card-text">Beheer en blokkeer gebruikers.</p>
            <a href="gebruikers.php" class="btn btn-secondary">Ga naar gebruikersbeheer</a>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card text-center">
          <div class="card-body">
            <h2 class="card-title">Opbrengst Verkoop</h2>
            <p class="card-text">Bekijk de wekelijkse of maandelijkse opbrengsten.</p>
            <a href="opbrengst.php" class="btn btn-secondary">Bekijk opbrengsten</a>
          </div>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

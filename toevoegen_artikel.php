<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Voorraad Beheer</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container-fluid">
  <div class="row">
    <!-- Zijbalk -->
    <nav class="col-md-2 bg-light min-vh-100 p-3">
      <h4>Menu</h4>
      <ul class="nav flex-column">
        <li class="nav-item">
          <a class="nav-link text-dark" href="#">Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="#">Voorraadbeheer</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="#">Ritplanning</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="#">Instellingen</a>
        </li>
      </ul>
    </nav>

    <!-- Content -->
    <main class="col-md-10 p-4">
      <h1 class="mb-4">artikel toevoegen</h1>

      <!-- Voorraad Tabel -->
      <table class="table table-hover table-bordered">
        <thead class="table-light">
          <tr>
            <th scope="col">Selectie</th>
            <th scope="col">ID</th>
            <th scope="col">Artikel ID</th>
            <th scope="col">Locatie</th>
            <th scope="col">Aantal</th>
            <th scope="col">Status ID</th>
            <th scope="col">Ingeboekt Op</th>
          </tr>
        </thead>
        <tbody>
        <?php
        // Database verbinding
        include('backend/db.php');

        ?>
        </tbody>
      </table>
    </main>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
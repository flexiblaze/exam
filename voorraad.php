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
          <a class="nav-link text-dark" href="#">Hoofdpagina</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="#">Persoonsgegevens</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="#">Klantgegevens</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="#">Voorraadbeheer</a>
          <li class="nav-item">
          <a class="nav-link text-dark" href="#">Opbrengst verkopen</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="#">Rit planning</a>
        </li>
        </li>
      </ul>
    </nav>

    <!-- Content -->
    <main class="col-md-10 p-4">
      <h1 class="mb-4">Voorraad Beheer</h1>
      
      <!-- Zoeken en Acties -->
      <div class="d-flex justify-content-between mb-3">
        <input type="text" class="form-control w-25" placeholder="Zoeken">
        <button class="btn btn-primary">Nieuwe Items</button>
      </div>

      <!-- Voorraad Tabel -->
      <table class="table table-hover table-bordered">
        <thead class="table-light">
          <tr>
            <th scope="col">Selectie</th>
            <th scope="col">ID</th>
            <th scope="col">Categorie</th>
            <th scope="col">Beschrijving</th>
            <th scope="col">Hoeveelheid</th>
            <th scope="col">Acties</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><input type="checkbox"></td>
            <td>1</td>
            <td>Muts</td>
            <td>Wintermuts blauw</td>
            <td>3</td>
            <td>
              <button class="btn btn-sm btn-secondary">...</button>
            </td>
          </tr>
          <tr>
            <td><input type="checkbox"></td>
            <td>2</td>
            <td>Sokken</td>
            <td>Sportsokken wit</td>
            <td>10</td>
            <td>
              <button class="btn btn-sm btn-secondary">...</button>
            </td>
          </tr>
          <tr>
            <td><input type="checkbox"></td>
            <td>3</td>
            <td>T-shirt</td>
            <td>Zwart basic T-shirt</td>
            <td>7</td>
            <td>
              <button class="btn btn-sm btn-secondary">...</button>
            </td>
          </tr>
        </tbody>
      </table>
    </main>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kringloop Centrum Dashboard</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="style.css" rel="stylesheet">
  <style>
  </style>
</head>
<body>
  <header class="d-flex justify-content-between align-items-center p-3">
    <nav>
      <ul class="nav">
        <li class="nav-item"><a class="nav-link text-white" href="#">Kringloop Centrum</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="#">Home</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="#">Ritten</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="#">Voorraad Beheer</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="#">Beheer</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="#">Admin</a></li>
      </ul>
    </nav>
    <button class="btn btn-danger">Aanmelden</button>
  </header>

  <main class="container mt-4">
    <div class="row row-cols-1 row-cols-md-2 g-4">
      <div class="col">
        <div class="card text-center">
          <div class="card-body">
            <h2 class="card-title">Ritten</h2>
            <p class="card-text">Beschrijving</p>
            <button class="btn btn-primary">Ga naar ritten</button>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card text-center">
          <div class="card-body">
            <h2 class="card-title">Voorraad beheer</h2>
            <p class="card-text">Beschrijving</p>
            <button class="btn btn-primary">Ga naar voorraad beheer</button>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card text-center">
          <div class="card-body">
            <h2 class="card-title">Kledingstukken</h2>
            <p class="card-text">Beschrijving</p>
            <button class="btn btn-primary">Ga naar kledingstukken</button>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card text-center">
          <div class="card-body">
            <h2 class="card-title">Klanten</h2>
            <p class="card-text">Beschrijving</p>
            <button class="btn btn-primary">Ga naar klanten</button>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

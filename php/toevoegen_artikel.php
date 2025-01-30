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


<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Product Toevoegen</title>
</head>
<body>
<?      
include('../backend/db.php');
?>
    <form method="POST">
        <input type="text" name="naam" placeholder="Naam" required>
        <input type="number" name="prijs" placeholder="Prijs" step="0.01" required>
        <select name="categorie" required>
            <option value="">Categorie</option>
            <?php foreach ($categorieen as $categorie) { ?>
                <option value="<?= $categorie['id']; ?>"><?= htmlspecialchars($categorie['categorie']); ?></option>
            <?php } ?>
        </select>
        <button type="submit">Toevoegen</button>
    </form>
        </tbody>
      </table>
    </main>
  </div>
</div>

<?      
include('../backend/db.php');

$query = "SELECT id, categorie FROM categorie";
$stmt = $pdo->prepare($query);
$stmt->execute();
$categorieen = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $naam = $_POST['naam'] ?? '';
    $prijs = $_POST['prijs'] ?? '';
    $categorie_id = $_POST['categorie'] ?? '';

    if ($naam && $prijs && $categorie_id) {
        $stmt = $pdo->prepare("INSERT INTO producten (naam, prijs, categorie_id) VALUES (?, ?, ?)");
        $stmt->execute([$naam, $prijs, $categorie_id]);
        echo "<p>Product toegevoegd!</p>";
    }
}
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
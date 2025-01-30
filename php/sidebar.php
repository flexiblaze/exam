<?php
session_start();
require '../backend/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

// Haal de gebruikersrol op
$gebruiker_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT rollen FROM gebruiker WHERE id = ?");
$stmt->execute([$gebruiker_id]);
$gebruiker = $stmt->fetch();
$rol = $gebruiker['rollen'] ?? 'Onbekend';
?>

<!-- Sidebar Navigatie -->
<div class="sidebar">
    <h2 class="logo">Kringloop Centrum</h2>
    <ul>
        <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
        <li><a href="ritplanning.php"><i class="fas fa-truck"></i> Ritplanning</a></li>
        <li><a href="voorraadbeheer.php"><i class="fas fa-boxes"></i> Voorraadbeheer</a></li>
        <li><a href="artikelenbeheer.php"><i class="fas fa-list"></i> Artikelenbeheer</a></li>
        <li><a href="gebruikers.php"><i class="fas fa-users"></i> Gebruikersbeheer</a></li>
        <li><a href="opbrengst.php"><i class="fas fa-chart-bar"></i> Opbrengst</a></li>
        <li class="logout"><a href="../backend/logout.php"><i class="fas fa-sign-out-alt"></i> Afmelden</a></li>
    </ul>
</div>

<!-- Sidebar Stijl -->
<style>
    body {
        display: flex;
        margin: 0;
        padding: 0;
    }
    
    .sidebar {
        position: fixed;
        left: 0;
        top: 0;
        height: 100%;
        width: 250px;
        background-color: #343a40;
        padding: 20px;
        color: white;
        overflow-y: auto;
    }

    .sidebar .logo {
        font-size: 22px;
        font-weight: bold;
        text-align: center;
        margin-bottom: 20px;
        border-bottom: 2px solid white;
        padding-bottom: 10px;
    }

    .sidebar ul {
        list-style-type: none;
        padding: 0;
    }

    .sidebar ul li {
        padding: 10px 0;
    }

    .sidebar ul li a {
        color: white;
        text-decoration: none;
        font-size: 18px;
        display: flex;
        align-items: center;
        padding: 8px;
        transition: 0.3s;
    }

    .sidebar ul li a i {
        margin-right: 10px;
    }

    .sidebar ul li a:hover {
        background: #007bff;
        border-radius: 5px;
        padding-left: 15px;
        text-decoration: underline;
    }

    .logout {
        margin-top: 20px;
        border-top: 2px solid white;
        padding-top: 10px;
    }

    /* Zorg ervoor dat de content naast de sidebar correct wordt weergegeven */
    .content {
        margin-left: 270px; /* Sidebar breedte + wat extra ruimte */
        padding: 20px;
        width: calc(100% - 270px);
    }
</style>

<!-- Font Awesome Icons -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

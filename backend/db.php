<?php
$host = 'localhost';  // Database host (meestal 'localhost' bij XAMPP)
$dbname = 'duurzaam'; // Vervang met jouw database naam
$username = 'root';   // Standaard XAMPP-gebruiker
$password = '';       // Laat leeg als je geen wachtwoord hebt ingesteld in XAMPP

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,  // Foutmeldingen inschakelen
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Resultaten als associatieve array ophalen
        PDO::ATTR_EMULATE_PREPARES => false // Beveiliging tegen SQL-injectie
    ]);
} catch (PDOException $e) {
    die("Databaseverbinding mislukt: " . $e->getMessage());
}
?>

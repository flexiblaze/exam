<?

$host       = 'localhost';
$database   = 'duurzaam';
$user       = 'root';
$password   = '';

try {
    $this->connection = new PDO($database . ":host=" . $host . ';port=' . $port, $user, $password);
    $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $this->connection;
} catch (PDOException $e) {
    echo $e->getMessage();
}

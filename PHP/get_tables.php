<?php
// Verifica si hay una sesión iniciada y obtiene las credenciales de conexión
session_start();
if (!isset($_SESSION['username']) || !isset($_SESSION['password'])) {
    die("No hay una sesión iniciada.");
}
$username = $_SESSION['username'];
$password = $_SESSION['password'];

// Verifica si se proporcionó el nombre de la base de datos
if (!isset($_GET['database'])) {
    die("No se especificó una base de datos.");
}
$database = $_GET['database'];

// Configura la conexión a MySQL
$servername = "localhost";

// Crea la conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verifica la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtiene la lista de tablas
$result = $conn->query("SHOW TABLES");

$tables = array();
while ($row = $result->fetch_row()) {
    $tables[] = $row[0];
}

// Devuelve la lista de tablas como JSON
echo json_encode($tables);

// Cierra la conexión
$conn->close();



?>
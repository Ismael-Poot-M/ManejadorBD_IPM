<?php
// Verifica si hay una sesión iniciada y obtiene las credenciales de conexión
session_start();
if (!isset($_SESSION['username']) || !isset($_SESSION['password'])) {
    die("No hay una sesión iniciada.");
}
$username = $_SESSION['username'];
$password = $_SESSION['password'];

// Configura la conexión a MySQL
$servername = "localhost";

// Crea la conexión
$conn = new mysqli($servername, $username, $password);

// Verifica la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtiene la lista de bases de datos
$result = $conn->query("SHOW DATABASES");

$databases = array();
while ($row = $result->fetch_assoc()) {
    $databases[] = $row['Database'];
}

// Devuelve la lista de bases de datos como JSON
echo json_encode($databases);

// Cierra la conexión
$conn->close();
?>

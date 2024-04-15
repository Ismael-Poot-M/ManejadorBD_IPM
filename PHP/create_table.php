<?php
// Verificar si hay una sesión iniciada y obtiene las credenciales de conexión
session_start();
if (!isset($_SESSION['username']) || !isset($_SESSION['password'])) {
    // Si no hay una sesión iniciada, devuelve un mensaje de error
    $response = array("success" => false, "error" => "No hay una sesión iniciada.");
    echo json_encode($response);
    exit; // Sale del script
}

// Obtener las credenciales de la sesión
$username = $_SESSION['username'];
$password = $_SESSION['password'];

// Verificar si se enviaron los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['databaseName']) && isset($_POST['tableName']) && isset($_POST['numColumns'])) {
    // Obtener los datos del formulario
    $databaseName = $_POST['databaseName'];
    $tableName = $_POST['tableName'];
    $numColumns = $_POST['numColumns'];

    // Configurar la conexión a MySQL
    $servername = "localhost"; // Cambia esto si el servidor MySQL no está en localhost

    // Crear la conexión utilizando las credenciales de la sesión
    $conn = new mysqli($servername, $username, $password, $databaseName);

    // Verificar la conexión
    if ($conn->connect_error) {
        // Si hay un error al conectar, devuelve un mensaje de error
        $response = array("success" => false, "error" => "Error al conectar al servidor MySQL: " . $conn->connect_error);
        echo json_encode($response);
    } else {
        // Construir la consulta SQL para crear la tabla
        $sql = "CREATE TABLE $tableName (id INT AUTO_INCREMENT PRIMARY KEY";

        // Agregar las columnas adicionales
        for ($i = 1; $i <= $numColumns; $i++) {
            $sql .= ", columna_$i VARCHAR(255)"; // Cambia VARCHAR(255) según el tipo de datos que necesites
        }

        $sql .= ")";

        // Ejecutar la consulta SQL
        if ($conn->query($sql) === TRUE) {
            // La tabla se creó correctamente
            $response = array("success" => true, "message" => "La tabla se creó correctamente");
            echo json_encode($response);
        } else {
            // Si hay un error al ejecutar la consulta SQL, devuelve un mensaje de error
            $response = array("success" => false, "error" => "Error al crear la tabla: " . $conn->error);
            echo json_encode($response);
        }
    }

    // Cerrar la conexión
    $conn->close();
} else {
    // Si no se enviaron los datos del formulario, devuelve un mensaje de error
    $response = array("success" => false, "error" => "No se recibieron los datos necesarios para crear la tabla.");
    echo json_encode($response);
}
?>

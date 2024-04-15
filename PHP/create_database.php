<?php
// Verifica si se proporcionó el nombre de la nueva base de datos en la solicitud GET
if (isset($_GET['dbName'])) {
    $dbName = $_GET['dbName'];

    // Obtén las credenciales de la sesión
    session_start();
    if (!isset($_SESSION['username']) || !isset($_SESSION['password'])) {
        // Si no hay una sesión iniciada, devuelve un mensaje de error
        $response = array("success" => false, "error" => "No hay una sesión iniciada.");
        echo json_encode($response);
        exit; // Sale del script
    }

    // Obtiene las credenciales de la sesión
    $username = $_SESSION['username'];
    $password = $_SESSION['password'];

    // Configura la conexión a MySQL
    $servername = "localhost"; // Cambia esto si el servidor MySQL no está en localhost

    // Crea la conexión a MySQL usando las credenciales de la sesión
    $conn = new mysqli($servername, $username, $password);

    // Verifica la conexión
    if ($conn->connect_error) {
        // Si hay un error al conectar, devuelve un mensaje de error
        $response = array("success" => false, "error" => "Error al conectar al servidor MySQL: " . $conn->connect_error);
        echo json_encode($response);
    } else {
        // Intenta crear la nueva base de datos
        $sql = "CREATE DATABASE IF NOT EXISTS $dbName";
        if ($conn->query($sql) === TRUE) {
            // Si la creación es exitosa, devuelve un mensaje de éxito
            $response = array("success" => true);
            echo json_encode($response);
        } else {
            // Si hay un error al crear la base de datos, devuelve un mensaje de error
            $response = array("success" => false, "error" => "Error al crear la base de datos: " . $conn->error);
            echo json_encode($response);
        }
    }

    // Cierra la conexión
    $conn->close();
} else {
    // Si no se proporcionó el nombre de la base de datos, devuelve un mensaje de error
    $response = array("success" => false, "error" => "No se proporcionó el nombre de la base de datos.");
    echo json_encode($response);
}
?>

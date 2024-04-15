<?php
// Iniciar sesión
session_start();

// Verificar si se enviaron los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si los campos de usuario y contraseña están vacíos
    if (empty($_POST['username']) || empty($_POST['password'])) {
        echo "Por favor, complete todos los campos.";
    } else {
        // Los campos no están vacíos, proceder con el inicio de sesión
        // Configuración del servidor MySQL
        $servername = "localhost"; // Cambia esto si el servidor MySQL no está en localhost
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Intentar establecer la conexión a la base de datos MySQL
        $conn = new mysqli($servername, $username, $password);

        // Verificar la conexión
        if ($conn->connect_error) {
            echo "Error al conectar al servidor MySQL: " . $conn->connect_error;
        } else {
            // Las credenciales son válidas, iniciar sesión y redirigir al usuario
            $_SESSION['username'] = $username;
            $_SESSION['password'] = $password; // Almacenar la contraseña en la sesión
            header("Location: dashboard.php");
            exit;
        }

        // Cerrar la conexión a la base de datos
        $conn->close();
    }
} else {
    // Si no se envió el formulario a través de POST, redirigir o mostrar un mensaje de error
    echo "Ha ocurrido un error al procesar el formulario.";
}
?>

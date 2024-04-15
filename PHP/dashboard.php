<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interfaz MySQL</title>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
    />
    <link rel="stylesheet" href="../CSS/dashboard.css">
</head>
<body>
<div class="sidebar">
        <div class="databases" id="databases">
            <h2>Bases de Datos</h2>
            <ul id="databaseList">
                <li id="createDbOption">
                    Nueva base de datos
                </li>
                <!-- Aquí se cargarán dinámicamente las bases de datos existentes -->
            </ul>
        </div>
    </div>
    <div class="content">
        <div class="form-container" id="formContainer">
            <!-- Aquí se mostrará el formulario -->
        </div>
    </div>

    <script src="../JS/basetablas.js"></script>
</body>
</html>

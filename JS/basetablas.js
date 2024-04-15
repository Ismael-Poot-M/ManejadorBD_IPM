function loadDatabases() {
    var createDbOption = document.getElementById("createDbOption");
    if (createDbOption) {
        createDbOption.addEventListener("click", function(event) {
            event.stopPropagation();
            toggleCreateDatabaseForm(); // Llamar a la función para mostrar el formulario de creación de base de datos
        });
    }
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                var databases = JSON.parse(xhr.responseText);
                var databasesContainer = document.getElementById("databases");
                databases.forEach(function(database) {
                    var databaseElement = document.createElement("div");
                    databaseElement.classList.add("database");

                    // Crear el icono de expansión
                    var expandIcon = document.createElement("span");
                    expandIcon.classList.add("expand-icon");
                    expandIcon.textContent = "+";
                    expandIcon.addEventListener("click", function(event) {
                        event.stopPropagation();
                        toggleTables(database, databaseElement);
                        toggleCreateTableForm(database); // Mostrar formulario de creación de tabla
                        expandIcon.textContent = expandIcon.textContent === "+" ? "-" : "+"; // Cambiar el icono
                    });

                    // Agregar el icono al principio del nombre de la base de datos
                    var heading = document.createElement("h3");
                    heading.textContent = database;
                    heading.insertBefore(expandIcon, heading.firstChild);
                    databaseElement.appendChild(heading);
                    databasesContainer.appendChild(databaseElement);
                });
            } else {
                console.error("Error al cargar las bases de datos");
            }
        }
    };
    xhr.open("GET", "../PHP/get_databases.php");
    xhr.send();
}

function loadTables(databaseName, container) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                var tables = JSON.parse(xhr.responseText);
                var tableList = document.createElement("ul");
                tableList.classList.add("tables");
                tables.forEach(function(table) {
                    var listItem = document.createElement("li");

                    // Crear el icono de tabla
                    var tableIcon = document.createElement("span");
                    tableIcon.classList.add("fa", "fa-table", "table-icon");

                    // Agregar el icono al principio del nombre de la tabla
                    var tableName = document.createElement("span");
                    tableName.textContent = table;
                    tableName.insertBefore(tableIcon, tableName.firstChild);
                    listItem.appendChild(tableName);
                    tableList.appendChild(listItem);
                });
                container.appendChild(tableList);
            } else {
                console.error("Error al cargar las tablas de la base de datos " + databaseName);
            }
        }
    };
    xhr.open("GET", "../PHP/get_tables.php?database=" + encodeURIComponent(databaseName));
    xhr.send();
}

function toggleTables(databaseName, container) {
    var tablesList = container.querySelector(".tables");
    if (tablesList) {
        container.removeChild(tablesList);
    } else {
        loadTables(databaseName, container);
    }
}

function toggleCreateTableForm(databaseName) {
    var formContainer = document.getElementById("formContainer");
    var formTable = formContainer.querySelector(".create-table-form");
    var formDb = formContainer.querySelector(".create-database-form");

    // Ocultar el formulario de creación de bases de datos si está presente
    if (formDb) {
        formContainer.removeChild(formDb);
    }

    if (formTable) {
        formContainer.removeChild(formTable);
    } else {
        var createTableForm = document.createElement("form");
        createTableForm.classList.add("create-table-form");
        createTableForm.innerHTML = `
            <h4>Crear Tabla</h4>
            <label for="tableName">Nombre:</label>
            <input type="text" id="tableName" name="tableName" required><br>
            <label for="numColumns">Columnas:</label>
            <input type="number" id="numColumns" name="numColumns" min="1" required><br>
            <button type="submit">Crear Tabla</button>
        `;
        createTableForm.addEventListener("submit", function(event) {
            event.preventDefault();
            createTable(databaseName, createTableForm);
        });
        formContainer.appendChild(createTableForm);
    }
}


function createTable(databaseName, form) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    alert(response.message);
                } else {
                    alert("Error al crear la tabla: " + response.error);
                }
            } else {
                alert("Error al crear la tabla. Inténtalo de nuevo más tarde.");
            }
        }
    };
    xhr.open("POST", "../PHP/create_table.php");
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    var formData = new FormData(form);
    formData.append("databaseName", databaseName);
    xhr.send(new URLSearchParams(formData));
}

function createDatabase(dbName) {
    console.log("Creando base de datos: " + dbName); // Verificación en consola
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    // Si la base de datos se creó con éxito, agregamos la nueva base de datos a la lista existente
                    var databasesContainer = document.getElementById("databases");
                    var databaseElement = document.createElement("div");
                    databaseElement.classList.add("database");

                    // Crear el icono de expansión
                    var expandIcon = document.createElement("span");
                    expandIcon.classList.add("expand-icon");
                    expandIcon.textContent = "+";
                    expandIcon.addEventListener("click", function(event) {
                        event.stopPropagation();
                        toggleTables(dbName, databaseElement);
                        toggleCreateTableForm(dbName); // Mostrar formulario de creación de tabla
                        expandIcon.textContent = expandIcon.textContent === "+" ? "-" : "+"; // Cambiar el icono
                    });

                    // Agregar el icono al principio del nombre de la base de datos
                    var heading = document.createElement("h3");
                    heading.textContent = dbName;
                    heading.insertBefore(expandIcon, heading.firstChild);
                    databaseElement.appendChild(heading);
                    databasesContainer.appendChild(databaseElement);

                    alert("Base de datos creada con éxito.");
                } else {
                    alert("Error al crear la base de datos: " + response.error);
                }
            } else {
                alert("Error al crear la base de datos. Inténtalo de nuevo más tarde.");
            }
        }
    };
    xhr.open("GET", "../PHP/create_database.php?dbName=" + encodeURIComponent(dbName));
    xhr.send();
}


window.onload = function() {
    loadDatabases();
};

function toggleCreateDatabaseForm() {
    var formContainer = document.getElementById("formContainer");
    var formTable = formContainer.querySelector(".create-table-form");
    var formDb = formContainer.querySelector(".create-database-form");

    // Ocultar el formulario de creación de tablas si está presente
    if (formTable) {
        formContainer.removeChild(formTable);
    }

    if (formDb) {
        formContainer.removeChild(formDb);
    } else {
        var createDatabaseForm = document.createElement("form");
        createDatabaseForm.classList.add("create-database-form");
        createDatabaseForm.innerHTML = `
            <h4>Crear Nueva Base de Datos</h4>
            <label for="dbName">Nombre de la Base de Datos:</label>
            <input type="text" id="dbName" name="dbName" required><br>
            <button type="submit">Crear Base de Datos</button>
        `;
        createDatabaseForm.addEventListener("submit", function(event) {
            event.preventDefault();
            var dbName = document.getElementById("dbName").value;
            createDatabase(dbName);
        });
        formContainer.appendChild(createDatabaseForm);
    }
}
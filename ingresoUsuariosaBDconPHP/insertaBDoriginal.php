<?php

// Parámetros de conexión
$serverName = "MARIETTA"; // SI ES WINDOWS AUTHENTICATION no necesito uid ni pwd ---- "Uid" => "", "PWD" => ""
$connectionOptions = array(
    "Database" => "UsuariosApp",
    "Uid" => "", 
    "PWD" => ""
);

// Conexión a SQL Server
$conn = sqlsrv_connect($serverName, $connectionOptions);

if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Obtener datos del formulario

// Validar que los campos existen en $_POST
$nombre = isset($_POST['nombre']) ? $_POST['nombre'] : null;
$edad = isset($_POST['edad']) ? $_POST['edad'] : null;
$genero = isset($_POST['genero']) ? $_POST['genero'] : null;

if ($nombre === null || $edad === null || $genero === null) {
    die("Faltan datos en el formulario.");
}



// Consulta preparada
$consultasql = "INSERT INTO usuarios (nombre, edad, genero) VALUES (?, ?, ?)";
$parametros = array($nombre, $edad, $genero);

$stmtSQL = sqlsrv_query($conn, $consultasql, $parametros);

if ($stmtSQL === false) {
    echo "Error: ";
    print_r(sqlsrv_errors());
} else {
    echo "Datos insertados correctamente.";
}



// Mostrar todos los registros de la tabla usuarios
$sqlSelect = "SELECT * FROM usuarios";
$query = sqlsrv_query($conn, $sqlSelect);

echo "<h3>Registros en la tabla usuarios:</h3>";
while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
    echo "Nombre: " . $row['nombre'] . " | Edad: " . $row['edad'] . " | Género: " . $row['genero'] . "<br>";
}


// Liberar recursos y cerrar conexión
if ($stmtSQL) sqlsrv_free_stmt($stmtSQL);
sqlsrv_close($conn);
?>









//version 2 con mensajes de error 









<?php

// Configuración inicial: Asegúrate de que los errores de PHP se muestren para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ===============================================
// 1. CONEXIÓN A SQL SERVER
// ===============================================

$serverName = "MARIETTA"; 
// Para Autenticación de Windows, se necesita el driver SQLSRV de PHP y que el servicio de PHP/Web Server 
// (e.g., IIS o Apache ejecutándose como un usuario con acceso a SQL Server) pueda autenticarse.
$connectionOptions = array(
    "Database" => "UsuariosApp",
    "Uid" => "", // Dejar vacío para Autenticación de Windows
    "PWD" => ""  // Dejar vacío para Autenticación de Windows
);

echo "<h2>Estado de la Conexión</h2>";
$conn = sqlsrv_connect($serverName, $connectionOptions);

if ($conn === false) {
    echo "<p style='color: red; font-weight: bold;'>❌ ERROR DE CONEXIÓN A SQL SERVER.</p>";
    echo "<pre>";
    die(print_r(sqlsrv_errors(), true));
    echo "</pre>";
} else {
    echo "<p style='color: green;'>✅ Conexión a SQL Server establecida correctamente.</p>";
}

// ===============================================
// 2. OBTENER Y VALIDAR DATOS DEL FORMULARIO
// ===============================================

// Utiliza la sintaxis de "coalesce" (??) para obtener el valor o null
$nombre = $_POST['nombre'] ?? null;
$edad = $_POST['edad'] ?? null;
$genero = $_POST['genero'] ?? null;

echo "<h2>Datos Recibidos del Formulario (POST)</h2>";
echo "<pre>";
print_r($_POST);
echo "</pre>";

// Validación estricta para asegurar que tenemos los datos clave
if ($nombre === null || $edad === null || $genero === null || $nombre === '' || $edad === '') {
    echo "<p style='color: red; font-weight: bold;'>❌ ERROR DE DATOS: Faltan datos clave (nombre, edad, o genero).</p>";
    // Esto es un punto común de fallo si los inputs HTML no tienen el atributo 'name'
    die();
}

// Convertir edad a entero, ya que viene como string del POST
// Esto ayuda a prevenir errores de tipo de dato si la columna 'edad' es INT
$edad_int = (int)$edad; 

// ===============================================
// 3. EJECUTAR INSERCIÓN
// ===============================================

echo "<h2>Resultado de la Inserción</h2>";

// Consulta preparada: SIEMPRE usa parámetros para seguridad
$consultasql = "INSERT INTO usuarios (nombre, edad, genero) VALUES (?, ?, ?)";
// Asegúrate de que los tipos de datos en el array coincidan con el orden de la consulta
$parametros = array($nombre, $edad_int, $genero);

$stmtSQL = sqlsrv_query($conn, $consultasql, $parametros);

if ($stmtSQL === false) {
    echo "<p style='color: red; font-weight: bold;'>❌ ERROR DE EJECUCIÓN: No se pudo insertar el registro.</p>";
    echo "<p>Esto puede ser por un error en la sintaxis SQL, un 'NOT NULL' faltante, o un tipo de dato incorrecto.</p>";
    echo "<h3>Detalles del Error de SQL Server:</h3>";
    echo "<pre>";
    // Muestra el array detallado de errores de SQL Server
    print_r(sqlsrv_errors());
    echo "</pre>";
} else {
    echo "<p style='color: green; font-weight: bold;'>✅ Datos insertados correctamente en la base de datos.</p>";
}

// ===============================================
// 4. MOSTRAR REGISTROS (PARA VERIFICACIÓN)
// ===============================================

echo "<h2>Registros en la tabla 'usuarios' (Después de la operación)</h2>";
$sqlSelect = "SELECT nombre, edad, genero FROM usuarios"; // Seleccionar solo columnas necesarias
$query = sqlsrv_query($conn, $sqlSelect);

if ($query === false) {
    echo "<p style='color: red;'>❌ ERROR al intentar leer los registros de la tabla.</p>";
    print_r(sqlsrv_errors());
} else {
    $count = 0;
    while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
        echo "Nombre: " . htmlspecialchars($row['nombre']) . " | Edad: " . htmlspecialchars($row['edad']) . " | Género: " . htmlspecialchars($row['genero']) . "<br>";
        $count++;
    }
    if ($count === 0) {
        echo "<p>No hay registros en la tabla 'usuarios'.</p>";
    }
    if (isset($stmtSQL) && $stmtSQL !== false) {
         echo "<p style='font-weight: bold;'>Total de registros en la tabla: {$count}</p>";
    }
}


// ===============================================
// 5. LIMPIEZA
// ===============================================
if (isset($stmtSQL) && $stmtSQL) sqlsrv_free_stmt($stmtSQL);
if (isset($query) && $query) sqlsrv_free_stmt($query);
sqlsrv_close($conn);

?>



















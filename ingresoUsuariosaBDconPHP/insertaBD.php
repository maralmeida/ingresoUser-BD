<?php

// ===============================================
// 0. CONFIGURACIÓN Y CHEQUEO DE POST
// ===============================================
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Si no se han recibido datos POST, muestra un mensaje de error claro y termina
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST)) {
    echo "<h1>🔴 ERROR CRÍTICO: No se recibieron datos del formulario.</h1>";
    echo "<p>Esto sucede si el método de envío no es POST, si el formulario HTML no está bien formado (falta 'name' o 'action'), o si el servidor web no procesa la solicitud POST correctamente.</p>";
    die();
}

// Parámetros de conexión
$serverName = "MARIETTA"; 
$connectionOptions = array(
    "Database" => "UsuariosApp",
    "Uid" => "", 
    "PWD" => ""
);

// Conexión a SQL Server
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
// 1. OBTENER Y VALIDAR DATOS DEL FORMULARIO
// ===============================================
$nombre = $_POST['nombre'] ?? null;
$edad = $_POST['edad'] ?? null;
$genero = $_POST['genero'] ?? null;

echo "<h2>Datos Recibidos del Formulario (POST)</h2>";
echo "<pre>";
print_r($_POST);
echo "</pre>";

if ($nombre === null || $edad === null || $genero === null || $nombre === '' || $edad === '') {
    echo "<p style='color: red; font-weight: bold;'>❌ ERROR DE DATOS: Faltan datos clave (nombre, edad, o genero están nulos/vacíos).</p>";
    die();
}

$edad_int = (int)$edad; // Convertir a entero para MSSQL

// ===============================================
// 2. PRUEBA DE FUEGO: INSERCIÓN CON DATOS FIJOS (Solo para depuración)
// ===============================================
/*
// **QUITAR COMENTARIOS PARA PROBAR SI SOLO LA INSERCIÓN FUNCIONA**
// Si esta sección tiene éxito, el problema está en los datos del formulario (Tipos de datos o NULL).
// Si falla, el problema es la tabla (Restricciones o tipos de columna).
$nombreFijo = "TEST_FIJO";
$edadFija = 99;
$generoFijo = "M";

$consultasql_fijo = "INSERT INTO usuarios (nombre, edad, genero) VALUES (?, ?, ?)";
$parametros_fijo = array($nombreFijo, $edadFija, $generoFijo);

$stmtSQL_fijo = sqlsrv_query($conn, $consultasql_fijo, $parametros_fijo);

if ($stmtSQL_fijo === false) {
    echo "<h3>❌ FALLÓ LA PRUEBA DIRECTA</h3>";
    print_r(sqlsrv_errors());
} else {
    echo "<h3>✅ PRUEBA DIRECTA EXITOSA</h3>";
    sqlsrv_free_stmt($stmtSQL_fijo);
}
*/
// ===============================================
// 3. EJECUTAR INSERCIÓN CON DATOS DEL USUARIO
// ===============================================

echo "<h2>Resultado de la Inserción con Datos de Usuario</h2>";

$consultasql = "INSERT INTO usuarios (nombre, edad, genero) VALUES (?, ?, ?)";
$parametros = array($nombre, $edad_int, $genero);

$stmtSQL = sqlsrv_query($conn, $consultasql, $parametros);

if ($stmtSQL === false) {
    echo "<p style='color: red; font-weight: bold;'>❌ ERROR DE EJECUCIÓN: No se pudo insertar el registro.</p>";
    echo "<h3>Detalles del Error de SQL Server:</h3>";
    echo "<pre>";
    print_r(sqlsrv_errors()); // Muestra el error de MSSQL (Constraint, Tipo de dato, etc.)
    echo "</pre>";
} else {
    echo "<p style='color: green; font-weight: bold;'>✅ Datos insertados correctamente en la base de datos.</p>";
}

// ===============================================
// 4. MOSTRAR REGISTROS Y LIMPIEZA
// ===============================================

// ... (El código de SELECT y cierre de conexión sigue igual)

if (isset($stmtSQL) && $stmtSQL) sqlsrv_free_stmt($stmtSQL);
// ... (código de select y cierre)
$sqlSelect = "SELECT nombre, edad, genero FROM usuarios"; 
$query = sqlsrv_query($conn, $sqlSelect);

echo "<h3>Registros en la tabla usuarios:</h3>";
while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
    echo "Nombre: " . $row['nombre'] . " | Edad: " . $row['edad'] . " | Género: " . $row['genero'] . "<br>";
}

if (isset($query) && $query) sqlsrv_free_stmt($query);
sqlsrv_close($conn);

?>
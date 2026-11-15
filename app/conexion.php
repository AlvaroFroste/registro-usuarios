
<?php
// Obtenemos los datos de conexión desde Docker
$host = getenv('DB_HOST');
$dbname = getenv('DB_NAME');
$user = getenv('DB_USER');
$password = getenv('DB_PASSWORD');

try {
    // Conectamos PHP con MariaDB
    $conexion = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $user,
        $password
    );

    // Activamos los errores de PDO
    $conexion->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

    // Devolvemos los resultados como arrays asociativos
    $conexion->setAttribute(
        PDO::ATTR_DEFAULT_FETCH_MODE,
        PDO::FETCH_ASSOC
    );

} catch (PDOException $e) {
    // No mostramos datos internos de conexión
    error_log($e->getMessage());
    http_response_code(500);
    exit("Error al conectar con la base de datos.");
}

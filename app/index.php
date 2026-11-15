
<?php
// Incluimos la conexión con MariaDB
require_once 'conexion.php';

$mensaje = '';
$tipo = '';

// Función para mostrar texto de forma segura
function escapar($texto) {
    return htmlspecialchars(
        (string)$texto,
        ENT_QUOTES,
        'UTF-8'
    );
}

// Comprobamos si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Recogemos los datos introducidos
    $nombre = trim($_POST['nombre'] ?? '');
    $apellidos = trim($_POST['apellidos'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validamos los campos
    if (
        $nombre === '' ||
        $apellidos === '' ||
        $email === '' ||
        $password === ''
    ) {
        $mensaje = 'Todos los campos son obligatorios.';
        $tipo = 'error';

    } elseif (
        mb_strlen($nombre) > 100 ||
        mb_strlen($apellidos) > 150 ||
        strlen($email) > 255
    ) {
        $mensaje = 'Alguno de los campos es demasiado largo.';
        $tipo = 'error';

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensaje = 'El correo electrónico no es válido.';
        $tipo = 'error';

    } elseif (strlen($password) < 8) {
        $mensaje = 'La contraseña debe tener al menos 8 caracteres.';
        $tipo = 'error';

    } else {
        try {
            // Convertimos la contraseña en un hash seguro
            $hash = password_hash($password, PASSWORD_DEFAULT);

            // Preparamos la consulta para evitar inyecciones SQL
            $sql = "INSERT INTO usuarios
                    (nombre, apellidos, email, password)
                    VALUES (:nombre, :apellidos, :email, :password)";

            $consulta = $conexion->prepare($sql);

            // Ejecutamos la consulta con los datos del usuario
            $consulta->execute([
                ':nombre' => $nombre,
                ':apellidos' => $apellidos,
                ':email' => $email,
                ':password' => $hash
            ]);

            $mensaje = 'Usuario registrado correctamente.';
            $tipo = 'exito';

        } catch (PDOException $e) {

            // Código SQLSTATE para valores duplicados
            if ($e->getCode() === '23000') {
                $mensaje = 'Este correo electrónico ya está registrado.';
            } else {
                error_log($e->getMessage());
                $mensaje = 'Error al registrar el usuario.';
            }

            $tipo = 'error';
        }
    }
}

// Consultamos los usuarios registrados
try {
    $consulta = $conexion->query(
        "SELECT id, nombre, apellidos, email, fecha_registro
         FROM usuarios
         ORDER BY id DESC"
    );

    $usuarios = $consulta->fetchAll();

} catch (PDOException $e) {
    error_log($e->getMessage());
    $usuarios = [];
    $mensaje = 'Error al consultar los usuarios.';
    $tipo = 'error';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <title>Registro de usuarios</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="contenedor">

    <h1>Registro de usuarios</h1>
    <p>Formulario conectado a MariaDB mediante PHP y Docker.</p>

    <?php if ($mensaje !== ''): ?>
        <div class="mensaje <?= escapar($tipo) ?>">
            <?= escapar($mensaje) ?>
        </div>
    <?php endif; ?>

    <h2>Registrar nuevo usuario</h2>

    <form method="POST">

        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre"
               maxlength="100" required>

        <label for="apellidos">Apellidos:</label>
        <input type="text" id="apellidos" name="apellidos"
               maxlength="150" required>

        <label for="email">Correo electrónico:</label>
        <input type="email" id="email" name="email"
               maxlength="255" required>

        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password"
               minlength="8" required>

        <button type="submit">Registrarse</button>

    </form>

    <h2>Usuarios registrados</h2>

    <div class="tabla-contenedor">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Correo</th>
                    <th>Fecha de registro</th>
                </tr>
            </thead>
            <tbody>

            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?= escapar($usuario['id']) ?></td>
                    <td><?= escapar($usuario['nombre']) ?></td>
                    <td><?= escapar($usuario['apellidos']) ?></td>
                    <td><?= escapar($usuario['email']) ?></td>
                    <td><?= escapar($usuario['fecha_registro']) ?></td>
                </tr>
            <?php endforeach; ?>

            <?php if (empty($usuarios)): ?>
                <tr>
                    <td colspan="5">No hay usuarios registrados.</td>
                </tr>
            <?php endif; ?>

            </tbody>
        </table>
    </div>

</div>

</body>
</html>

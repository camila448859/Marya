<?php
// profile.php: muestra la información del usuario conectado
session_start();

// Verifica si el usuario ha iniciado sesión; si no, redirige al login
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit();
}

// Conexión a la base de datos usando estilo procedural (igual que login.php)
$con = mysqli_connect("localhost", "root", "CAMILA", "marya");
if (mysqli_connect_errno()) {
    die("Error de conexión: " . mysqli_connect_error());
}
mysqli_set_charset($con, 'utf8mb4');

// Obtener el ID de usuario de la sesión
$userId = (int) $_SESSION['id_usuario'];

// CONSULTA: Datos del usuario
$sql = "
    SELECT
        u.nombre AS usuario,
        u.edad,
        t.nuemro AS numero_tarjeta,
        dir.calle,
        dir.numero AS numero_direccion,
        cp.cp AS codigo_postal,
        d.delegacion,
        c.colonia
    FROM usuarios u
    LEFT JOIN tarjetas t       ON u.i_tarjeta = t.i_tarjeta
    LEFT JOIN direcciones dir  ON u.i_direc   = dir.i_direc
    LEFT JOIN cp              ON dir.i_cp    = cp.i_cp
    LEFT JOIN delegaciones d   ON dir.i_del   = d.i_del
    LEFT JOIN colonias c       ON dir.i_col   = c.i_col
    WHERE u.i_usuario = ?
";
// Preparar y ejecutar la consulta
$stmt = mysqli_prepare($con, $sql);
if (!$stmt) {
    die("Error al preparar consulta de usuario: " . mysqli_error($con));
}
mysqli_stmt_bind_param($stmt, 'i', $userId) or die("Error en bind_param: " . mysqli_error($con));
mysqli_stmt_execute($stmt) or die("Error en execute: " . mysqli_error($con));
$result = mysqli_stmt_get_result($stmt);
if (!$result || mysqli_num_rows($result) !== 1) {
    die("Usuario no encontrado.");
}
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Cerrar conexión
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <main class="container">
        <h1>Perfil de <?php echo htmlspecialchars($user['usuario'], ENT_QUOTES, 'UTF-8'); ?></h1>
        <section class="profile-info">
            <p><strong>Nombre de usuario:</strong> <?php echo htmlspecialchars($user['usuario'], ENT_QUOTES, 'UTF-8'); ?></p>
            <p><strong>Edad:</strong> <?php echo htmlspecialchars($user['edad'], ENT_QUOTES, 'UTF-8'); ?> años</p>
            <p><strong>Tarjeta:</strong> <?php echo htmlspecialchars($user['numero_tarjeta'], ENT_QUOTES, 'UTF-8'); ?></p>
            <p><strong>Dirección:</strong> <?php echo htmlspecialchars($user['calle'], ENT_QUOTES, 'UTF-8'); ?>, No. <?php echo htmlspecialchars($user['numero_direccion'], ENT_QUOTES, 'UTF-8'); ?></p>
            <p><strong>Colonia:</strong> <?php echo htmlspecialchars($user['colonia'], ENT_QUOTES, 'UTF-8'); ?></p>
            <p><strong>Delegación / Municipio:</strong> <?php echo htmlspecialchars($user['delegacion'], ENT_QUOTES, 'UTF-8'); ?></p>
            <p><strong>Código Postal:</strong> <?php echo htmlspecialchars($user['codigo_postal'], ENT_QUOTES, 'UTF-8'); ?></p>
        </section>

        <nav class="actions">
            <a class="btn" href="edit_profile.php">Editar Perfil</a>
            <a class="btn" href="logout.php">Cerrar Sesión</a>
            <a class="btn" href="../index.php">Inicio</a>
        </nav>
    </main>
</body>
</html>

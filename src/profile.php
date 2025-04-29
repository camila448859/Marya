<?php 
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit();
}

$con = mysqli_connect("localhost", "root", "CAMILA", "marya");
if (mysqli_connect_errno()) {
    die("Error de conexión: " . mysqli_connect_error());
}
mysqli_set_charset($con, 'utf8mb4');

$userId = (int) $_SESSION['id_usuario'];
$sql = "
        SELECT
        u.nombre               AS usuario,
        u.edad,
        t.nuemro               AS numero_tarjeta,
        dir.calle,
        dir.numero             AS numero_direccion,
        cp.cp                  AS codigo_postal,
        d.delegacion,
        col.colonia,
        c.fecha_hora,
        GROUP_CONCAT(prod.nombre SEPARATOR ', ') AS productos
    FROM usuarios u
    LEFT JOIN tarjetas t     ON u.i_tarjeta = t.i_tarjeta
    LEFT JOIN direcciones dir ON u.i_direc   = dir.i_direc
    LEFT JOIN cp             ON dir.i_cp    = cp.i_cp
    LEFT JOIN delegaciones d ON dir.i_del   = d.i_del
    LEFT JOIN colonias col   ON dir.i_col   = col.i_col
    LEFT JOIN usu_com uc     ON u.i_usuario = uc.i_usuario
    LEFT JOIN compra c       ON uc.i_compra = c.i_compra
    LEFT JOIN com_pro cp2    ON c.i_compra  = cp2.i_compra
    LEFT JOIN producto prod ON cp2.i_producto = prod.i_producto
    WHERE u.i_usuario = ?
    GROUP BY c.i_compra;
";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, 'i', $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if (!$result || mysqli_num_rows($result)!==1) {
    die("Usuario no encontrado.");
}
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);
mysqli_close($con);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Perfil de Usuario</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f3f4;
      font-family: 'Segoe UI', sans-serif;
      color: #333;
    }
    .text-marya { 
        color: #a98189 !important; 
    }
    .bg-marya { 
        background-color: #a98189 !important; color: #fff; 
    }
    .btn-marya { 
        background-color: #a98189; color: #fff; 
    }
    .btn-marya:hover { 
        background-color: #8b6b73; 
    }
    .card-header {
      background-color: #fff;
      border-bottom: 2px solid #a98189;
    }
  </style>
</head>
<body>

  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
      <a class="navbar-brand text-marya fw-bold" href="#">Marya</a>
      <div class="ms-auto">
        <a href="logout.php" class="btn btn-outline-secondary btn-sm">Cerrar Sesión</a>
      </div>
    </div>
  </nav>

  <div class="container py-5">
    <h2 class="text-marya mb-4 text-center">Bienvenido, <?php echo htmlspecialchars($user['usuario'], ENT_QUOTES); ?></h2>
    <div class="row g-4">

      <div class="col-lg-4">
        <div class="card shadow-sm">
          <div class="card-header text-marya">
            <h5 class="mb-0">Datos de Cuenta</h5>
          </div>
          <div class="card-body">
            <p><strong>Usuario:</strong><br><?php echo htmlspecialchars($user['usuario'], ENT_QUOTES); ?></p>
            <p><strong>Edad:</strong><br><?php echo htmlspecialchars($user['edad'], ENT_QUOTES); ?> años</p>
            <p><strong>Tarjeta:</strong><br><?php echo htmlspecialchars($user['numero_tarjeta'], ENT_QUOTES); ?></p>
          </div>
        </div>
      </div>

      <div class="col-lg-8">
        <div class="card shadow-sm">
          <div class="card-header text-marya">
            <h5 class="mb-0">Dirección</h5>
          </div>
          <div class="card-body">
            <p><strong>Calle:</strong><br><?php echo htmlspecialchars($user['calle'], ENT_QUOTES); ?>, No. <?php echo htmlspecialchars($user['numero_direccion'], ENT_QUOTES); ?></p>
            <p><strong>Colonia:</strong><br><?php echo htmlspecialchars($user['colonia'], ENT_QUOTES); ?></p>
            <p><strong>Delegación / Municipio:</strong><br><?php echo htmlspecialchars($user['delegacion'], ENT_QUOTES); ?></p>
            <p><strong>Código Postal:</strong><br><?php echo htmlspecialchars($user['codigo_postal'], ENT_QUOTES); ?></p>
          </div>
        </div>
      </div>

      <div class="col-lg-8">
        <div class="card shadow-sm">
          <div class="card-header text-marya">
            <h5 class="mb-0">Compras</h5>
          </div>
          <div class="card-body">
            <p><strong>Fecha y hora:</strong><br><?php echo htmlspecialchars($user['fecha_hora'], ENT_QUOTES); ?></p>
            <p><strong>Producto:</strong><br><?php echo htmlspecialchars($user['productos'], ENT_QUOTES); ?></p>
          </div>
        </div>
      </div>

    </div>

    <div class="text-center mt-5">
      <a href="edit_profile.php" class="btn btn-marya me-2">Editar Perfil</a>
      <a href="../index.php"     class="btn btn-outline-secondary">Volver al Inicio</a>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

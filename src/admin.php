<?php
    session_start();

    if (!isset($_SESSION['id_usuario'])) {
        header('Location: login.php');
        exit();
    }
    $userId = (int) $_SESSION['id_usuario'];
    
    $con = mysqli_connect("localhost", "root", "CAMILA", "marya");
    if (mysqli_connect_errno()) {
        die('Error de conexión: ' . mysqli_connect_error());
    }
    mysqli_set_charset($con, 'utf8mb4');

    $sql ="
        SELECT
        c.i_compra                             AS compra_id,
        c.fecha_hora                           AS fecha_hora,
        u.nombre                               AS usuario,
        GROUP_CONCAT(p.nombre ORDER BY p.nombre SEPARATOR ', ') AS productos
        FROM compra c
        JOIN usu_com uc     ON c.i_compra   = uc.i_compra
        JOIN usuarios u     ON uc.i_usuario = u.i_usuario
        JOIN com_pro cp     ON c.i_compra   = cp.i_compra
        JOIN producto p     ON cp.i_producto = p.i_producto
        GROUP BY
        c.i_compra,
        c.fecha_hora,
        u.nombre
        ORDER BY
        c.fecha_hora DESC;

    ";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['update'])) {
        $id = (int) $_POST['i_producto'];
        $nombre = mysqli_real_escape_string($con, trim($_POST['nombre']));
        $precio = (int) $_POST['precio'];
        $marca = (int) $_POST['marca'];
        $categoria = (int) $_POST['categoria'];
        $presentacion = (int) $_POST['presentacion'];
    
        $qry = "
          UPDATE producto SET
            nombre = '$nombre',
            precio = $precio,
            i_marca = $marca,
            i_categoria = $categoria,
            i_presentacion = $presentacion
          WHERE i_producto = $id
        ";
        if (mysqli_query($con,$qry)) {
            $msg = "Producto #$id actualizado correctamente.";
        } else {
            $msg = "Error al actualizar: " . mysqli_error($con);
        }
    }

    $editing = false;
    if (isset($_GET['edit'])) {
        $editing = true;
        $id = (int) $_GET['edit'];
        $result = mysqli_query($con, "SELECT * FROM producto WHERE i_producto = $id LIMIT 1");
        $prod = mysqli_fetch_assoc($result);
        mysqli_free_result($result);
    }

    $resAll = mysqli_query($con, "SELECT * FROM producto ORDER BY i_producto");
    $productos = mysqli_fetch_all($resAll, MYSQLI_ASSOC);
    mysqli_free_result($resAll);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
        background-color: #f8f3f4;
        }
        .text-marya { 
            color: #a98189 !important; 
        }
        .bg-marya { 
            background-color: #a98189 !important; 
            color: #fff !important; 
        }
        .navbar-brand { 
            font-weight: bold; 
        }
        .cantidad-input {
        width: 60px;
        }
        .total {
        font-weight: bold;
        }
        .btn-marya, .btn-success {
            background-color: #a98189;
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            transition: background-color 0.3s;
        }
        .btn-marya:hover, .btn-success:hover {
            background-color: #8b6b73;
        }
  </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-white shadow-sm mb-4">
        <div class="container">
        <a class="navbar-brand text-marya" href="#">Marya</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarMenu">
            <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link" href="../index.php">Inicio</a></li>
            <li class="nav-item"><a class="nav-link" href="products.php">Productos</a></li>
            <li class="nav-item"><a class="nav-link" href="carrito.php">Carrito</a></li>
            <li class="nav-item"><a class="nav-link" href="profile.php">Perfil</a></li>
            </ul>
        </div>
        </div>
    </nav>
    <div class="container py-5">
        <h2 class="text-marya mb-4 text-center">Bienvenida Camila</h2>

        <div class="form-container me-3">
            <h2>Añadir productos</h2>
            <form method="GET" action="addproducts.php">
                <label>Nombre:</label>
                <input type="text" name="nombre" id="nombre" class="form-control" required>

                <label>Precio:</label>
                <input type="text" name="precio" id="precio" class="form-control" required>

                <label>Marca:</label>
                <select name="marca" id="marca" class="form-select" required>
                    <option value="">--Seleccione--</option>
                    <option value="1">Benefit</option>
                    <option value="2">Chanel</option>
                    <option value="3">Dior</option>
                    <option value="4">Rare Beauty</option>
                </select>

                <label>Categoria:</label>
                <select name="categoria" id="categoria" class="form-select" required>
                    <option value="">--Seleccione--</option>
                    <option value="1">Labiales</option>
                    <option value="2">Cara</option>
                    <option value="3">SkinCare</option>
                    <option value="4">Ojos</option>
                </select>

                <label>Presentación:</label>
                <select name="presentacion" id="presentacion" class="form-select" required>
                    <option value="">--Seleccione--</option>
                    <option value="1">Liquido</option>
                    <option value="2">Crema</option>
                    <option value="3">Polvo</option>
                    <option value="4">Spray</option>
                </select>
                <br>
                <button type="submit" name="añadir" class="btn btn-success w-100">Añadir</button>
            </form>
        </div>
        <br>
        <h2>Modifica tus productos</h2>

        <?php if (!empty($msg)): ?>
            <div class="alert alert-info"><?= htmlspecialchars($msg, ENT_QUOTES) ?></div>
        <?php endif; ?>

        <?php if ($editing && $prod): ?>
        <div class="card mb-5">
            <div class="card-body">
                <h4 class="card-title">Editar producto #<?= $prod['i_producto'] ?></h4>
                <form method="POST" class="row g-3">
                    <input type="hidden" name="i_producto" value="<?= $prod['i_producto'] ?>">
                    <div class="col-md-6">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="nombre" class="form-control" required
                        value="<?= htmlspecialchars($prod['nombre'], ENT_QUOTES) ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Precio</label>
                        <input type="number" name="precio" class="form-control" required
                        value="<?= $prod['precio'] ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Marca</label>
                        <select name="marca" class="form-select" required>
                            <option value="1" <?= $prod['i_marca']==1?'selected':'' ?>>Benefit</option>
                            <option value="2" <?= $prod['i_marca']==2?'selected':'' ?>>Chanel</option>
                            <option value="3" <?= $prod['i_marca']==3?'selected':'' ?>>Dior</option>
                            <option value="4" <?= $prod['i_marca']==4?'selected':'' ?>>Rare Beauty</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Categoría</label>
                        <select name="categoria" class="form-select" required>
                            <option value="1" <?= $prod['i_categoria']==1?'selected':'' ?>>Labiales</option>
                            <option value="2" <?= $prod['i_categoria']==2?'selected':'' ?>>Cara</option>
                            <option value="3" <?= $prod['i_categoria']==3?'selected':'' ?>>SkinCare</option>
                            <option value="4" <?= $prod['i_categoria']==4?'selected':'' ?>>Ojos</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Presentación</label>
                        <select name="presentacion" class="form-select" required>
                            <option value="1" <?= $prod['i_presentacion']==1?'selected':'' ?>>Líquido</option>
                            <option value="2" <?= $prod['i_presentacion']==2?'selected':'' ?>>Crema</option>
                            <option value="3" <?= $prod['i_presentacion']==3?'selected':'' ?>>Polvo</option>
                            <option value="4" <?= $prod['i_presentacion']==4?'selected':'' ?>>Spray</option>
                        </select>
                    </div>
                    <div class="col-12 text-end">
                        <button type="submit" name="update" class="btn btn-marya">Guardar cambios</button>
                        <a href="admin.php" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
        <?php endif; ?>

        <table class="table table-striped">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Marca</th>
                    <th>Categoría</th>
                    <th>Presentación</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($productos as $p): ?>
                <tr>
                    <td><?= $p['i_producto'] ?></td>
                    <td><?= htmlspecialchars($p['nombre'],ENT_QUOTES) ?></td>
                    <td>$<?= number_format($p['precio'],2) ?></td>
                    <td><?= $p['i_marca'] ?></td>
                    <td><?= $p['i_categoria'] ?></td>
                    <td><?= $p['i_presentacion'] ?></td>
                    <td>
                        <a href="admin.php?edit=<?= $p['i_producto'] ?>" class="btn btn-sm btn-marya">
                        Editar
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <h2>Historial de compras</h2>
        <div class="table-responsive">
            <table class="table table-hover bg-white shadow-sm">
                <thead class="table-secondary">
                    <tr>
                        <th>Compra</th>
                        <th>Fecha y Hora</th>
                        <th>Usuario</th>
                        <th>Productos</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($res)): ?>
                    <tr>
                        <td><?= $row['compra_id'] ?></td>
                        <td><?= $row['fecha_hora'] ?></td>
                        <td><?= htmlspecialchars($row['usuario'], ENT_QUOTES) ?></td>
                        <td><?= htmlspecialchars($row['productos'], ENT_QUOTES) ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
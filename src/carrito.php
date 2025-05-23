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

  $sql = "
    SELECT
    c.i_compra,
    c.fecha_hora,
    p.i_producto,
    p.nombre,
    p.precio,
    COUNT(*) AS cantidad
    FROM compra c
    JOIN usu_com uc ON c.i_compra = uc.i_compra
    JOIN com_pro cp ON c.i_compra = cp.i_compra
    JOIN producto p ON cp.i_producto = p.i_producto
    WHERE uc.i_usuario = ? 
    AND c.estado = 0
    GROUP BY p.i_producto, p.nombre, p.precio, c.i_compra, c.fecha_hora
    ORDER BY c.fecha_hora DESC
  ";

  $stmt = mysqli_prepare($con, $sql);
  mysqli_stmt_bind_param($stmt, 'i', $userId);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Carrito de Compras</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f3f4;
    }

    .text-marya { 
        color: #a98189 !important; 
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
          <li class="nav-item"><a class="nav-link active text-marya" href="carrito.php">Carrito</a></li>
          <li class="nav-item"><a class="nav-link" href="profile.php">Perfil</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container py-5">
    <h2 class="text-marya mb-4 text-center">Tu Carrito</h2>

    <?php if (mysqli_num_rows($result) === 0): ?>
      <div class="alert alert-info text-center">
        No has añadido productos aún. <a href="products.php">Ver catálogo</a>
      </div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-hover bg-white shadow-sm">
          <thead class="table-secondary">
            <tr>
              <th>Producto</th>
              <th class="currency">Precio</th>
              <th class="text-center">Cantidad</th>
              <th class="text-center">Eliminar</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
              <td><?= htmlspecialchars($row['nombre'], ENT_QUOTES) ?></td>
              <td class="currency" data-price="<?= $row['precio'] ?>">
                $<?= number_format($row['precio'], 2) ?>
              </td>
              <td class="text-center" data-qty="<?= $row['cantidad'] ?>">
                <?= $row['cantidad'] ?>
              </td>
              <td class="text-center">
              <form method="GET" action="deletecarrito.php" onsubmit="return confirm('¿Eliminar este producto del carrito?');">
                <input type="hidden" name="i_producto" value="<?php echo (int)$row['i_producto']; ?>">
                <button type="submit" class="btn-delete" title="Eliminar">🗑</button>
              </form>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
          <tfoot>
            <tr>
              <th class="text-end" colspan="2">Total:</th>
              <th class="text-center currency" id="totalValue">$0.00</th>
              <th></th>
            </tr>
          </tfoot>
        </table>
      </div>
      <div class="text-center mt-4">
        <a href="products.php" class="btn btn-outline-secondary me-2">Seguir Comprando</a>
        <a href="compra.php" class="btn btn-marya">Finalizar Compra</a>
      </div>
    <?php endif; ?>

  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      let total = 0;
      document.querySelectorAll('tbody tr').forEach(function(row) {
        const price = parseFloat(row.querySelector('[data-price]').getAttribute('data-price'));
        const qty   = parseInt(row.querySelector('[data-qty]').getAttribute('data-qty'), 10);
        total += price * qty;
      });
      document.getElementById('totalValue').textContent = '$' + total.toFixed(2);
    });
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
mysqli_stmt_close($stmt);
mysqli_close($con);
?>


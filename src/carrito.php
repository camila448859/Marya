<?php 
session_start();
$con = mysqli_connect("localhost", "root", "CAMILA", "marya");

if (!isset($_SESSION['id_usuario'])) {
  header('Location: login.php');
  exit();
}

if (mysqli_connect_errno()) {
    echo "<div class='alert alert-danger'>Error al conectar a MySQL: " . mysqli_connect_error() . "</div>";
} else {
    $result = mysqli_query($con, 
        "SELECT 
            producto.nombre AS producto,
            producto.precio AS precio
        FROM compra
        JOIN com_pro ON compra.i_compro = com_pro.i_compro
        JOIN producto ON com_pro.i_producto = producto.i_producto
        ORDER BY compra.fecha_hora DESC
        LIMIT 10;"
    );
  
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

    .carrito-header {
      background-color: #a98189;
      color: white;
      padding: 30px 0;
      text-align: center;
      font-weight: bold;
      font-size: 2rem;
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

  <div class="carrito-header">Tu Carrito de Compras</div>

  <div class="container my-5">
    <div class="table-responsive">
      <table class="table align-middle">
        <thead class="table-dark">
          <tr>
            <th>Producto</th>
            <th>Precio</th>
            <th>Cantidad</th>
            <th>Total</th>
            <th></th>
          </tr>
        </thead>
        <tbody id="carrito-body">
          <?php 
            $totalGeneral = 0;
            while ($row = mysqli_fetch_assoc($result)) {
              $producto = $row['producto'];
              $precio = $row['precio'];
              $cantidad = 1; // manejar por código
              $total = $precio * $cantidad;
              $totalGeneral += $total;
          ?>
            <tr>
              <td><?= htmlspecialchars($producto) ?></td>
              <td>$<?= number_format($precio, 2) ?></td>
              <td>
                <input type="number" class="form-control cantidad-input" value="<?= $cantidad ?>" min="1" onchange="calcularTotales()">
              </td>
              <td class="total">$<?= number_format($total, 2) ?></td>
              <td>
                <button class="btn btn-sm btn-danger" onclick="eliminarFila(this)">Eliminar</button>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>

    <div class="text-end pe-2">
      <h5>Total del carrito: <span id="total-general">$<?= number_format($totalGeneral, 2) ?></span></h5>
    </div>
  </div>

  <script>
    function calcularTotales() {
      let totalGeneral = 0;
      const filas = document.querySelectorAll('#carrito-body tr');

      filas.forEach(fila => {
        const precio = parseFloat(fila.cells[1].textContent.replace('$', ''));
        const cantidad = parseInt(fila.querySelector('input').value);
        const total = precio * cantidad;
        fila.querySelector('.total').textContent = `$${total.toFixed(2)}`;
        totalGeneral += total;
      });

      document.getElementById('total-general').textContent = `$${totalGeneral.toFixed(2)}`;
    }

    function eliminarFila(boton) {
      const fila = boton.closest('tr');
      fila.remove();
      calcularTotales();
    }

    window.onload = calcularTotales;
  </script>

</body>
</html>
<?php } ?>

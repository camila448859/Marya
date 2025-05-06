<?php
session_start();
$con = mysqli_connect("localhost", "root", "CAMILA", "marya");
if (mysqli_connect_errno()) {
    die("Error de conexión: " . mysqli_connect_error());
}
mysqli_set_charset($con, 'utf8mb4');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['agregar'])){
    if (!isset($_SESSION['id_usuario'])){
        header('Location:login.php');
        exit();
    }
    $userId = (int) $_SESSION['id_usuario'];
    $prodId = intval($_POST['i_producto']);

    $qr = mysqli_query($con, "SELECT i_tarjeta, i_direc FROM usuarios WHERE i_usuario = $userId LIMIT 1");

    if (!$qr || mysqli_num_rows($qr) !==1){
        die("Usuario o información de dirección inválida.");
    }
    $u = mysqli_fetch_assoc($qr);

    mysqli_query(
        $con, 
        "INSERT INTO compra (i_tarjeta, i_direc, fecha_hora) 
        VALUES ({$u['i_tarjeta']},{$u['i_direc']}, NOW())" 
    ) or die("Error al crear compra: " .mysqli_error($con));
    
    $i_compra = mysqli_insert_id($con);

    mysqli_query(
        $con,
        "INSERT INTO usu_com (i_usuario, i_compra) VALUES ($userId, $i_compra)"
    ) or die ("Error al enlazar usuario-compra: " .mysqli_error($con));

    $i_uscom = mysqli_insert_id($con);

    mysqli_query(
        $con,
        "UPDATE compra SET i_uscom = $i_uscom WHERE i_compra = $i_compra;"
    ) or die ("Error al enlazar usuario-compra: " .mysqli_error($con));

    mysqli_query(
        $con,
        "INSERT INTO com_pro (i_compra, i_producto) VALUES ($i_compra, $prodId)"
    ) or die("Error al agregar producto a la compra: " .mysqli_error($con));

    $i_compro = mysqli_insert_id($con);

    mysqli_query(
        $con,
        "UPDATE compra SET i_compro = $i_compro WHERE i_compra = $i_compra;"
    ) or die("Error al agregar producto a la compra: " .mysqli_error($con));

    header('Location:carrito.php');
    exit();
}

$res = mysqli_query($con, "SELECT i_producto, nombre, precio FROM producto");
$productos = [];
while ($row = mysqli_fetch_assoc($res)) {
    $productos[] = $row;
}
mysqli_free_result($res);
mysqli_close($con);

$imagenes = [
    1 => '../images/benefit_benetint.PNG',
    2 => '../images/benefit_blushpeachin.PNG',
    3 => '../images/benefit_brocha.PNG',
    4 => '../images/benefit_cheekyloveletter.PNG',
    5 => '../images/benefit_highlighterdandelion.PNG',
    6 => '../images/benefit_miniboxbroncer.PNG',
    7 => '../images/benefit_pore.PNG',
    8 => '../images/benefit_settinggel.PNG',
    9 => '../images/chanel_jouescontraste.PNG',
    10 => '../images/chanel_jouescontrastebrocha.PNG',
    11 => '../images/chanel_leliftproconcentrecontour.PNG',
    12 => '../images/chanel_lelinerdechanel.PNG',
    13 => '../images/chanel_lerougeduoultratenue.PNG',
    14 => '../images/chanel_rougeallure.PNG',
    15 => '../images/chanel_rougeallurevelvetlesperles.PNG',
    16 => '../images/chanel_rougecocobaumebrillo.PNG',
    17 => '../images/dior_addictlipmaximer.PNG',
    18 => '../images/dior_diorshowmascara.PNG',
    19 => '../images/dior_diorshowonstageliner.PNG',
    20 => '../images/dior_diorshowpumpnvolume.PNG',
    21 => '../images/dior_foreverglowmaximer.PNG',
    22 => '../images/dior_foreverhydranudebase.PNG',
    23 => '../images/dior_foreverskinglowbase.PNG',
    24 => '../images/dior_lipglowbalsamoph.PNG',
    25 => '../images/rarebeauty_browharmonyprecisionpencil.PNG',
    26 => '../images/rarebeauty_kindwordslipliner.PNG',
    27 => '../images/rarebeauty_kindwordsmattelipstick.PNG',
    28 => '../images/rarebeauty_positivelightundereye.PNG',
    29 => '../images/rarebeauty_softpinchliquidblushmini.PNG',
    30 => '../images/rarebeauty_softpinchmattebouncyblush.PNG',
    31 => '../images/rarebeauty_softpinchtintedlipoil.PNG',
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Catálogo de Productos</title>

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
        background-color: #a98189 !important; 
        color: #fff !important; 
    }
    .btn-marya { 
        background-color: #a98189; 
        color: #fff; 
        border-radius: 0.5rem; 
        padding: 0.5rem 1rem; 
        font-weight: 600; 
    }
    .btn-marya:hover { 
        background-color: #8b6b73; 
    }
    .product-img { 
        width: 90px; 
        height: 90px; 
        object-fit: cover; 
        border-radius: 0.5rem; 
    }
    .navbar-brand { 
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
          <li class="nav-item"><a class="nav-link active text-marya" href="products.php">Productos</a></li>
          <li class="nav-item"><a class="nav-link" href="carrito.php">Carrito</a></li>
          <li class="nav-item"><a class="nav-link" href="profile.php">Perfil</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container">
    <h2 class="text-marya mb-4">Catálogo de Productos</h2>
    <div class="table-responsive">
      <table class="table align-middle bg-white shadow-sm">
        <thead class="bg-marya">
          <tr>
            <th scope="col" class="text-white">Imagen</th>
            <th scope="col" class="text-white">Producto</th>
            <th scope="col" class="text-white text-end">Precio</th>
            <th scope="col" class="text-white">Acción</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($productos as $prod): ?>
          <?php $id = $prod['i_producto']; ?>
          <?php $img = $imagenes[$id] ?? 'https://via.placeholder.com/60'; ?>
          <tr>
            <td><img src="<?= htmlspecialchars($img, ENT_QUOTES) ?>" alt="" class="product-img"></td>
            <td><?= htmlspecialchars($prod['nombre'], ENT_QUOTES) ?></td>
            <td class="text-end">$<?= number_format($prod['precio'],2) ?></td>
            <td>
              <form method="POST" action="products.php">
                <input type="hidden" name="i_producto" value="<?= $id ?>">
                <button type="submit" name="agregar" class="btn btn-marya btn-sm">Agregar</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <footer class="bg-white text-center py-4 mt-5 shadow-sm">
    <div class="container">
      <p class="mb-0">&copy; <?= date('Y') ?> Marya. Todos los derechos reservados.</p>
    </div>
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

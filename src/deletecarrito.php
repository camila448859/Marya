<?php
 session_start();

 if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit();
  }
 $userId = (int) $_SESSION['id_usuario'];

 $prodId  = isset($_GET['i_producto']) ? intval($_GET['i_producto']) : 0;
 if (!$prodId) {
    header('Location: carrito.php');
    exit();
 }

 $con = mysqli_connect("localhost", "root", "CAMILA", "marya");
  if (mysqli_connect_errno()) {
    die('Error de conexión: ' . mysqli_connect_error());
  }
  mysqli_set_charset($con, 'utf8mb4');

  mysqli_query($con, "SET FOREIGN_KEY_CHECKS = 0");

  $sql = "
    SELECT cp.i_compro, cp.i_compra
    FROM com_pro cp
    JOIN usu_com uc ON cp.i_compra = uc.i_compra
    WHERE uc.i_usuario = ?
    AND cp.i_producto = ?
    ";

  $stmt = mysqli_prepare($con,$sql);
  mysqli_stmt_bind_param($stmt, 'ii', $userId, $prodId);
  mysqli_stmt_execute($stmt);
  $res = mysqli_stmt_get_result($stmt);

  while ($row = mysqli_fetch_assoc($res)){
    $i_compro = (int)$row['i_compro'];
    $i_compra = (int)$row['i_compra'];

    mysqli_query($con, "DELETE FROM com_pro WHERE i_compro = $i_compro");
    mysqli_query($con, "DELETE FROM usu_com WHERE i_compra = $i_compra");
    mysqli_query($con, "DELETE FROM compra WHERE i_compra = $i_compra");
  }

  mysqli_stmt_close($stmt);

  mysqli_query($con, "SET FOREIGN_KEY_CHEKS = 1");

  mysqli_close($con);
  header('Location: carrito.php');
  exit();
   
?>
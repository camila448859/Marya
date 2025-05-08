<?php
    session_start();
    if (!isset($_SESSION['id_usuario'])){
        header('Location: login.php');
        exit();
    }
    $userId = (int)$_SESSION['id_usuario'];

    $con = mysqli_connect('localhost','root','CAMILA','marya');
    if (!$con) die('Error de conexión: '.mysqli_connect_error());
    mysqli_set_charset($con,'utf8mb4');

    $sql = "
        UPDATE compra c
        JOIN usu_com uc ON c.i_compra = uc.i_compra
        SET c.estado = 1
        WHERE uc.i_usuario = ?
        AND c.estado = 0
    ";

    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $userId);
    mysqli_stmt_execute($stmt) or die('Error al finalizar compra: '.mysqli_error($con));

    mysqli_stmt_close($stmt);
    mysqli_close($con);

    header('Location: profile.php');
    exit();
?>
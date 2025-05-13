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

    if (isset($_GET['añadir'])){
        $nombre = mysqli_real_escape_string($con, trim($_GET['nombre']));
        $precio = (int)($_GET['precio']);
        $marca = (int)($_GET['marca']);
        $categoria = (int)($_GET['categoria']);
        $presentacion = (int)($_GET['presentacion']);

        $sql = "
            INSERT INTO producto (i_marca, i_categoria, i_presentacion, nombre, precio) 
            VALUES ($marca, $categoria, $presentacion, '$nombre', $precio)
        ";

        if (mysqli_query($con, $sql)) {
            header('Location: products.php?add_success=1');
            exit();
        } else {
            die('Error al añadir producto: '.mysqli_error($con));
        }
    }

    mysqli_close($con);

?>
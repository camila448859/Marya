<?php
    session_start();
    $con = mysqli_connect("localhost", "root", "CAMILA", "marya");

    if (!isset($_SESSION['id_usuario'])) {
        header('Location: login.php');
        exit();
    }

   
?>
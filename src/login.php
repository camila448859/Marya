<?php
    session_start();
    $con = mysqli_connect("localhost", "root", "CAMILA", "marya");
    
    //Lista para las direcciones
    $cpResult = mysqli_query($con, "SELECT i_cp, cp FROM cp ORDER BY cp");
    $delResult = mysqli_query($con, "SELECT i_del, delegacion FROM delegaciones ORDER BY delegacion");
    $colResult= mysqli_query($con, "SELECT i_col, colonia FROM colonias ORDER BY colonia");

    if (mysqli_connect_errno()){
        die("Error de conexion: ".mysqli_connect_error());
    }

    if (isset($_POST['login'])){
        $usuario = $_POST['usuario'];
        $pass = $_POST['pass'];

        $q = mysqli_query($con, "SELECT * FROM usuarios WHERE nombre = '$usuario' AND `contraseña` ='$pass'");
        if ($row = mysqli_fetch_assoc($q)){
            $_SESSION['id_usuario'] = $row['i_usuario'];
            $_SESSION['nombre_usuario'] = $row['nombre'];
            header("Location: ../index.php");
            exit;
        }else{
            $error_login = "❌ Usuario o contraseña incorrectos.";
        }
    }

    if (isset($_POST['registrar'])){

        $nombre = $_POST['nuevo_usuario'];
        $contra = $_POST['nueva_contra'];
        $edad = $_POST['edad'];
        $i_gen = isset($_POST['genero']) ? intval($_POST['genero']) : 0;
        $tarjeta = $_POST['tarjeta'];
        $i_del = $_POST['i_del'];
        $i_cp = $_POST['i_cp'];
        $i_col = $_POST['i_col'];
        $calle = trim($_POST['calle']);
        $numero = trim($_POST['numero']);

        $check = mysqli_query($con, "SELECT * FROM usuarios WHERE nombre ='$nombre'");
        
        if (mysqli_num_rows($check) > 0){
            $error_registro = "⚠️ Ese nombre de usuario ya está en uso.";
        }else{

            $res = mysqli_query($con, "INSERT INTO tarjetas (nuemro) VALUES ('$tarjeta')");
            if (!$res){
                die("Error al insertar tarjeta: " .mysqli_error($con));
            }
            $i_tarjeta = mysqli_insert_id($con);
            
            if ($i_tarjeta <1){
                die("No se obtuvo un ID válido de tarjetas.");
            }

            //direccion
            mysqli_query($con, "INSERT INTO direcciones (i_cp, i_del, i_col, calle, numero) VALUES ('$i_cp','$i_del','$i_col','$calle','$numero')");
            $i_direc = mysqli_insert_id($con);

            //Insertar usuario
            $insert = mysqli_query($con, "INSERT INTO usuarios (i_gen, i_tarjeta, i_direc, nombre, `contraseña`, edad)
            VALUES ('$i_gen', '$i_tarjeta', '$i_direc', '$nombre', '$contra', '$edad')");

            if ($insert){
                $success_registro = "✅ Cuenta creada con éxito. ¡Ahora inicia sesión!";
            }else{
                die("MysQL error: " .mysqli_error($con));
                $error_registro = "❌ Error al registrar usuario.";
            }
        }
    }
    mysqli_close($con);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
        background-color: #f8f3f4;
        font-family: 'Segoe UI', sans-serif;
        color: #333;
        margin: 0;
        padding: 0;
        }

        h1, h2, h3 {
        color: #a98189;
        font-weight: bold;
        }

        a {
        color: #a98189;
        text-decoration: none;
        }

        a:hover {
        text-decoration: underline;
        }

        .form-container {
        background-color: #ffffff;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 0 20px rgba(169, 129, 137, 0.2);
        margin: 40px auto;
        max-width: 450px;
        }

        .form-control {
        border: 1px solid #ccc;
        border-radius: 8px;
        padding: 10px;
        }

        .form-control:focus {
        border-color: #a98189;
        box-shadow: 0 0 0 0.2rem rgba(169, 129, 137, 0.25);
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

        .alert {
        border-radius: 8px;
        padding: 10px 15px;
        margin-bottom: 20px;
        }

        .text-center {
        text-align: center;
        }

        @media (max-width: 576px) {
        .form-container {
            padding: 20px;
            margin: 20px 10px;
        }
        }
    </style>
    <title>Login/Registro</title>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">

            <div class="col-md-5 form.container me-3">
                <h2>Iniciar sesión</h2>
                <?php if (isset($error_login)) echo "<div class='alert alert-danger'>$error_login</div>"; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label>Usuario</label>
                        <input type="text" name="usuario" id="usuario" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Contraseña</label>
                        <input type="password" name="pass" id="pass" class="form-control" required>
                    </div>
                    <button type="submit" name="login" class="btn btn-marya w-100">Entrar</button>
                </form>
            </div>

            <div class="col-md-5 form-container">
                <h2>Crear cuenta</h2>
                <?php if (isset($error_registro)) echo "<div class='alert alert-danger'>$error_registro</div>"; ?>
                <?php if (isset($success_registro)) echo "<div class='alert alert-success'>$success_registro</div>"; ?>

                <form method="POST">
                    <div class="mb-3"><label>Nombre de usuario</label>
                        <input type="text" name="nuevo_usuario" id="nuevo_usuario" class="form-control" required>
                    </div>
                    <div class="mb-3"><label>Contraseña</label>
                        <input type="password" name="nueva_contra" id="nueva_contra" class="form-control" required>
                    </div>
                    <div class="mb-3"><label>Edad</label>
                        <input type="number" name="edad" id="edad" class="form-control" required>
                    </div>

                    <div class="mb-3"><label>Género</label>
                        <select name="genero" id="genero" class="form-select" required>
                            <option value="1">Femenino</option>
                            <option value="2">Masculino</option>
                        </select>
                    </div>
                    <hr>

                    <h5>Datos de tarjeta</h5>
                    <div class="mb-3"><label>Número de tarjeta</label>
                        <input type="number" name="tarjeta" id="tarjeta" class="form-control" required>
                    </div>
                    <hr>
                    
                    <h5>Dirección</h5>

                    <label for="cp">Código Postal</label>
                    <select name="i_cp" id="cp" class="form-control" required>
                        <option value="">--Seleccione--</option>
                        <?php while($cp = mysqli_fetch_assoc($cpResult)): ?>
                            <option value="<?= $cp['i_cp'] ?>">
                                <?= htmlspecialchars($cp['cp']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>

                    <label for="delegacion">Delegación / Municipio</label>
                    <select name="i_del" id="delegacion" class="form-control" required>
                    <option value="">--Seleccione--</option>
                    <?php while($d = mysqli_fetch_assoc($delResult)): ?>
                            <option value="<?= $d['i_del'] ?>">
                                <?= htmlspecialchars($d['delegacion']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>

                    <label for="colonia">Colonia</label>
                    <select name="i_col" id="colonia" class="form-control" required>
                    <option value="">--Seleccione--</option>
                    <?php while($c = mysqli_fetch_assoc($colResult)): ?>
                        <option value="<?= $c['i_col'] ?>">
                            <?= htmlspecialchars($c['colonia']) ?>
                        </option>
                    <?php endwhile; ?>
                    </select>

                    <div class="mb-3"><label>Calle</label>
                        <input type="text" name="calle" id="calle" class="form-control" required>
                    </div>
                    <div class="mb-3"><label>Número</label>
                        <input type="text" name="numero" id="numero" class="form-control" required>
                    </div>
                    <button type="submit" name="registrar" class="btn btn-success w-100">Registrarse</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
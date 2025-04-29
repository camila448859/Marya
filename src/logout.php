<?php
// logout.php: destruye la sesión y redirige al login
session_start();

// Eliminar todas las variables de sesión
$_SESSION = [];

// Si se desea destruir completamente la sesión, incluyendo la cookie de sesión:
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );
}

// Finalmente, destruir la sesión
session_destroy();

// Redirigir al formulario de login
header('Location: login.php');
exit();

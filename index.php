<?php 
    session_start();
    $con = mysqli_connect("localhost", "root", "CAMILA", "marya");

    if (mysqli_connect_errno()){
        die("Error de conexion: ".mysqli_connect_error());
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="style.css"> 
    <title>Marya Beauté</title>
</head>

<body>
    <nav class="navbar navbar-custom sticky-top py-3">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="search-box">
                <input type="text" placeholder="Buscar">
                <span class="material-icons">search</span>
            </div>
            <h1 class="m-0 fw-bold">Marya Beauté</h1>

            <div class="d-flex align-items-center grap-3">
                <a href="./src/profile.php"><span class="material-icons">person</span></a>
                <div class="cart-icon">
                    <a href="./src/carrito.php"><span class="material-icons">shopping_cart</span></a>
                </div>
            </div>
        </div>
        <div class="nav-links text-center mt-3">
            <a href="#inicio">Inicio</a>
            <a href="./src/products.php">Productos</a>
            <a href="#contact">Contacto</a>
        </div>
    </nav>

    <div class="content text-center mt-3">
        <div id="inicio">
            <div id="mktg" class="container my-5">
                <div id="carruselMarya" class="carousel slide rounded overflow-hidden shadow" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="./images/OS_RAREBEAUTY_BOUTIQUE_OJOS_BB.jpg" class="d-block w-100" alt="Promo 1">
                        </div>
                        <div class="carousel-item">
                            <table>
                                <th><img src="./images/NUEVA_OS_BoutiqueBenefit_TB31.gif" class="d-block w-20" alt="Promo 2"></th>
                                <th><img src="./images/NUEVA_OS_BoutiqueBenefit_TB32.gif" class="d-block w-20" alt="Promo 2.1"></th>
                                <th><img src="./images/NUEVA_OS_BoutiqueBenefit_TB33.gif" class="d-block w-20" alt="Promo 2.2"></th>
                            </table>
                        </div>
                        <div class="carousel-item">
                            <img src="./images/DIOR_CAPTURE[ox-c treatment].avif" class="d-block w-100" alt="Prome 3">
                        </div>
                    </div>

                    <button class="carousel-control-prev" type="button" data-bs-target="#carruselMarya" data-bs-slide="next">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carruselMarya" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
            <table id="favs">
                <h1>Favoritos</h1>
                    <tr>
                        <th>Benefit</th>
                        <th>Chanel</th>
                        <th>Dior</th>
                        <th>Rare Beauty</th>
                    </tr>
                    <tr>
                        <th><img src="./images/benefit_benetint.PNG" alt="bene tint"></th>
                        <th><img src="./images/chanel_jouescontraste.PNG" alt="joues contraste"></th>
                        <th><img src="./images/dior_addictlipmaximer.PNG" alt="addict lip maximer"></th>
                        <th><img src="./images/rarebeauty_browharmonyprecisionpencil.PNG" alt="brow harmony precision pencil"></th>
                    </tr>
                    <tr>
                        <th>Bene tint <h2>$520.00</h2></th>
                        <th>Joues Contraste blush <h2>$1,060.00</h2></th>
                        <th>Adict Lip Maximer <h2>$910.00</h2></th>
                        <th>Brow Harmony Precision Pencil <h2>$510.00</h2></th>
                    </tr>
            </table>
            <div id="video" class="container my-5 ratio ratio-16x9 rounded shadow" style="border: 4px solid #a98189;">
                <iframe width="560" height="315" 
                    src="https://www.youtube.com/embed/Y4naInbLQxM?si=ak8g-qsRD1U3RCuW" 
                    title="YouTube video player" 
                    frameborder="0" 
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                    referrerpolicy="strict-origin-when-cross-origin" 
                    allowfullscreen>
                </iframe>
            </div>
            <br>
            <table id="favs">
                <h1>Marcas</h1>
                    <tr>
                        <th>Benefit</th>
                        <th>Chanel</th>
                        <th>Dior</th>
                        <th>Rare Beauty</th>
                    </tr>
                    <tr>
                        <th><img src="./images/BENEFIT.png" alt="benefitlogo"></th>
                        <th><img src="./images/CHANEL.png" alt="chanel logo"></th>
                        <th><img src="./images/DIOR.png" alt="dior logo"></th>
                        <th><img src="./images/RAREBEAUTY.png" alt="Rare beauty logo"></th>
                    </tr>
            </table>
            <div id="video" class="container my-5 ratio ratio-16x9 rounded shadow" style="border: 4px solid #a98189;">
                <iframe width="560" height="315" 
                src="https://www.youtube.com/embed/g_egyx3GLys?si=i5NH7j-MqGSaFZu0"
                    title="YouTube video player" 
                    frameborder="0" 
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                    referrerpolicy="strict-origin-when-cross-origin" 
                    allowfullscreen>
                </iframe>
            </div>
            <table id="favs">
                <h1>Categorias</h1>
                    <tr>
                        <th>Labiales</th>
                        <th>Cara</th>
                        <th>SkinCare</th>
                        <th>Ojos</th>
                    </tr>
                    <tr>
                        <th><img src="./images/dior_addictlipmaximer.PNG" alt="labiales"></th>
                        <th><img src="./images/rarebeauty_softpinchliquidcontour.avif" alt="cara"></th>
                        <th><img src="./images/benefit_pore.PNG" alt="skincare"></th>
                        <th><img src="./images/chanel_lelinerdechanel.PNG" alt="ojos"></th>
                    </tr>
            </table>
        </div>
    </div>
    <footer id="contact">
        <div class="newsletter-section text-white py-4">
            <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center">
                <div>
                    <h4 class="fw-bold">Contáctanos</h4>
                    <p class="m-0">Regístrate y recibe nuestras ofertas.</p>
                </div>

                <form class="d-flex mt-3 mt-md-0 w-100 w-md-50">
                    <input type="email" class="form-control me-2 bg-transparent text-white border-light"
                        placeholder="Ingresa tu correo electrónico..." required>
                    <button type="submit" class="btn btn-link text-white text-decoration-underline">Enviar</button>
                </form>
            </div>
        </div>
        <div class="footer-main text-white py-4">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <h5 class="fw-bold">Categorías</h5>
                        <ul class="list-unstyled">
                            <li><a href="#inicio" class="text-white text-decoration-none">Inicio</a></li>
                            <li><a href="./src/products.php" class="text-white text-decoration-none">Productos</a></li>
                            <li><a href="#contact" class="text-white text-decoration-none">Contacto</a></li>
                        </ul>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h5 class="fw-bold">Contacto</h5>
                        <p class="mb-1">📍 Dirección: Calle Roma 123, Ciudad de Mexico</p>
                        <p class="mb-1">📞 Teléfono: +52 446 310 6219</p>
                        <p class="mb-0">✉️ Email: marya.beaute@gmail.com</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
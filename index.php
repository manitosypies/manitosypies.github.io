<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manitos & Pies</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    

    <!-- NAVBAR -->
    <nav class="navbar">
        <div class="logo">
            <img src="img/logo.png" alt="Logo Eternal Beauty">
            <span>Manitos & Pies</span>
        </div>
        <div class="hamburger" id="hamburger">
        <span></span>
        <span></span>
        <span></span>
    </div>
        <ul class="nav-links" id="nav-links">
            <li><a href="#">Inicio</a></li>
            <li><a href="#services">Servicios</a></li>
            <li><a href="#promocion">Promociones</a></li>
            <li><a href="#acordeon">Preguntas</a></li>
            <li><a href="#galeria">Galería</a></li>
            <!-- Dropdown -->
        <li class="dropdown">
            <a href="#">Más &#9662;</a> <!-- ▼ flechita -->
            <ul class="dropdown-menu">
                <li><a href="./agenda/index.html">Agenda tu hora</a></li>
                <li><a href="serviceone.php">Servicio 1</a></li>
                <li><a href="servicetwo.php">Servicio 2</a></li>
                <li><a href="servicethree.php">Servicio 3</a></li>
            </ul>
        </li>
            <li><a href="#footer">Contacto</a></li>
        </ul>
    </nav>

    <!-- PORTADA -->
    <section class="hero">
        <div class="hero-content">
            <h1>Bienvenidos a Manitos & Pies</h1>
            <p>Realza tu belleza con nuestros exclusivos servicios</p>
           
        </div>
    </section>

  <!-- seccion tarjetas -->
    <section class="services reveal" id="services">
    <div class="service-info">
        <h2>Servicios de Manitos & Pies</h2>
        <p>Ofrecemos una variedad de servicios para realzar tu belleza. Cada tratamiento está diseñado con cuidado para ofrecerte los mejores resultados.</p>
    </div>
    <div class="service-cards" id="service-cards">
        <!-- Card 1 -->
        <div class="service-card reveal">
            <img src="img/service1.jpg" alt="Servicio 1">
            <h3>Maquillaje Profesional</h3>
            <p>Disfruta de un maquillaje impecable para cualquier ocasión, diseñado para resaltar tu belleza natural.</p>
            <a href="serviceone.php" class="toggle-details-btn">Ver más detalles</a>
        </div>
        <!-- Card 2 -->
        <div class="service-card reveal">
            <img src="img/service2.jpg" alt="Servicio 2">
            <h3>Manicura y Pedicura</h3>
            <p>Cuida de tus manos y pies con nuestros tratamientos profesionales para lograr unas uñas perfectas.</p>
            <a href="servicetwo.php" class="toggle-details-btn">Ver más detalles</a>
        </div>
        <!-- Card 3 -->
        <div class="service-card reveal">
            <img src="img/service3.jpg" alt="Servicio 3">
            <h3>Peinados y Estilizado</h3>
            <p>Transforma tu look con un peinado elegante, moderno o clásico, adaptado a tu estilo personal.</p>
            <a href="servicethree.php" class="toggle-details-btn">Ver más detalles</a>
        </div>
        <!-- Agrega más tarjetas aquí -->
        
    </div>
</section>
<!-- Seccion de promociones carrusel -->
<section class="promotions-section reveal" id="promocion">
    <h2>Promociones Especiales</h2>
    <div class="promotion-carousel">
        <button class="promo-btn left" id="prevPromo">&#10094;</button>
        
        <div class="promotion-slide active">
            <h3>🌟 2x1 en Manicura</h3>
            <p>Solo los martes. ¡Ven con una amiga y paga solo uno!</p>
        </div>

        <div class="promotion-slide">
            <h3>💅 Descuento del 20%</h3>
            <p>En tu primera visita. ¡Agenda tu cita ahora!</p>
        </div>

        <div class="promotion-slide">
            <h3>🎁 Regalo sorpresa</h3>
            <p>En tratamientos superiores a $50.000.</p>
        </div>

        <button class="promo-btn right" id="nextPromo">&#10095;</button>
    </div>
</section>

<!-- Acordeon de preguntas frecuentes -->
<div class="faq-section" id="acordeon">
    <h2>Preguntas Frecuentes</h2>
    <div class="faq-item">
        <button class="faq-button">¿Cómo puedo contactar con soporte?</button>
        <div class="faq-content">
            <p>Puede contactarnos a través del correo electrónico soporte@miempresa.com o llamarnos al número 123-456-789.</p>
        </div>
    </div>
    <div class="faq-item">
        <button class="faq-button">¿Cuál es el tiempo de entrega?</button>
        <div class="faq-content">
            <p>El tiempo de entrega depende de la ubicación, pero generalmente es entre 3 a 5 días hábiles.</p>
        </div>
    </div>
    <div class="faq-item">
        <button class="faq-button">¿Ofrecen garantía?</button>
        <div class="faq-content">
            <p>Sí, ofrecemos una garantía de 1 año para todos nuestros productos.</p>
        </div>
    </div>
</div>


    <!-- GALERÍA -->
    <section class="gallery reveal" id="galeria">
        <h1>Sección de galería</h1>
        <div class="gallery-grid">
            <?php
                $path = "img/galeria/";
                $images = glob($path . "*.{jpg,jpeg,png,gif}", GLOB_BRACE);
                $images = array_slice($images, 0, 18); // solo 24

                foreach ($images as $img) {
                    echo "<img src=\"$img\" alt=\"Galería\" class=\"img-click\">";
                }
            ?>
        </div>
    </section>

    <!-- LIGHTBOX -->
    <div id="lightbox" class="lightbox">
        <span class="close">&times;</span>
        <img class="lightbox-img" id="lightbox-img" src="">
        <div class="nav-buttons">
            <span class="prev">&#10094;</span>
            <span class="next">&#10095;</span>
        </div>
    </div>

    <!-- FOOTER -->
    <!-- Footer -->
    <footer id="footer">
    <div class="footer-content">
        <!-- Logo -->
        <!-- <div class="footer-logo">
            <img src="img/logo.png" alt="Eternal Beauty" class="logo-img">
        </div> -->

        <div class="footer-text">
            <p>Contacto: <a href="mailto:contacto@eternalbeauty.com">contacto@manitosypies.com</a></p>
            <p>&copy; 2025 Manitos & Pies. Todos los derechos reservados.</p>
        </div>
        
        <div class="footer-social">
            <p>Síguenos en:</p>
            <a href="https://www.instagram.com" target="_blank" class="social-icon instagram">Instagram</a>
            <a href="https://www.facebook.com" target="_blank" class="social-icon facebook">Facebook</a>
            <a href="https://www.tiktok.com" target="_blank" class="social-icon tiktok">WhatsApp</a>
        </div>

        <div class="footer-credits">
            <p>Desarrollado por <a href="https://www.consultoriadd.com" target="_blank" class="credit-link">Consultoría D&D</a></p>
        </div>
    </div>
</footer>


    <!-- Botón Volver Arriba -->
    <button id="scrollToTopBtn" title="Volver arriba">&#8679;</button>

    <script src="js/script.js"></script>
</body>
</html>



<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Servicio - Manitos & Pies</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/service.css">
</head>

<body>
<!-- Contenedor de Animación de Carga -->
<div class="loader"></div>
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
    <li><a href="index.php">Inicio</a></li>
    <li><a href="#footer">Contacto</a></li>
</ul>

    </nav>

    <!-- Sección de detalle del servicio -->
    <section class="service-detail-header">
        <img src="img/fondos/fondo2.jpg" alt="Maquillaje Profesional" class="service-image">
        <div class="service-title">
            <h1>Maquillaje Profesional</h1>
            <p>Transforma tu look con el toque de un experto</p>
        </div>
    </section>

    <section class="service-info">
    <div class="container" style="display: flex; align-items: center; gap: 2rem; flex-wrap: wrap;">
        <div class="service-description" style="flex: 1; min-width: 300px;">
            <h2>Detalles del Servicio</h2>
            <p>Disfruta de un maquillaje impecable para cualquier ocasión, diseñado para resaltar tu belleza natural. Nuestros maquilladores profesionales te ofrecen una experiencia única, adaptada a tu estilo personal y necesidades.</p>
            
            <h3>Lo que incluye:</h3>
            <ul>
                <li>Maquillaje completo (rostro, ojos, labios)</li>
                <li>Productos de alta calidad</li>
                <li>Asesoramiento personalizado para tu estilo</li>
            </ul>
        </div>

<!-- Contenedor de imagen -->
<div class="service-image" style="flex: 1; min-width: 300px;">
    <img src="img/galeria/foto19.jpg" alt="Servicio de maquillaje" class="img-click" style="width: 100%; height: auto; border-radius: 8px;">
</div>

<!-- Modal con la imagen ampliada -->
<div id="lightbox" class="lightbox">
    <span id="close" class="close">&times;</span>
    <img id="lightbox-img" src="" alt="Imagen ampliada">
</div>

</section>

    <!-- Sección de testimonios -->
    <section class="testimonials">
    <div class="container">
        <!-- Contenedor para la acción de reserva -->
        <div class="testimonials-right">
            <div class="service-action">
                <h3>Reserva tu cita</h3>
                <p>¡No esperes más! Reserva ahora tu cita para lucir espectacular.</p>
                <button class="btn-reserve">Reservar ahora</button>
            </div>
        </div>
        <!-- Contenedor para los testimonios -->
        <div class="testimonials-left">
            <h2>Lo que dicen nuestros clientes</h2>
            <div class="testimonial">
                <p>"El maquillaje que me hicieron fue impresionante. ¡Me sentí increíble en mi evento!"</p>
                <span>- Ana Gómez</span>
            </div>
            <div class="testimonial">
                <p>"Excelente servicio, me sentí como una verdadera estrella. ¡Recomendados al 100%!"</p>
                <span>- Laura Pérez</span>
            </div>
        </div>
    </div>
</section>


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
<script>
const hamburger = document.getElementById("hamburger");
const navLinks = document.getElementById("nav-links");

// Alterna el menú hamburguesa
hamburger.addEventListener("click", function () {
    navLinks.classList.toggle("active");
    hamburger.classList.toggle("active");
});

// Cierra el menú al hacer clic en un enlace (excepto el dropdown)
document.querySelectorAll("#nav-links > li > a").forEach(link => {
    link.addEventListener("click", () => {
        const parentLi = link.closest("li");
        if (!parentLi.classList.contains("dropdown")) {
            navLinks.classList.remove("active");
            hamburger.classList.remove("active");
        }
    });
});
</script>
    <!-- Botón Volver Arriba -->
    <button id="scrollToTopBtn" title="Volver arriba">&#8679;</button>
    <script src="js/script.js"></script>
</body>

</html>

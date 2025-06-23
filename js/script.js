// Esperar a que la página cargue completamente
window.addEventListener('load', () => {
    document.body.classList.add('page-load');
});

// Variables
let prevScrollPos = window.pageYOffset;  // Posición inicial del scroll
let navbar = document.querySelector('.navbar');
let scrollTimer;

// Función para manejar el desplazamiento
window.addEventListener('scroll', function() {
    let currentScrollPos = window.pageYOffset;

    // Si el usuario baja más de 100px, ocultamos el navbar
    if (prevScrollPos < currentScrollPos && currentScrollPos > 100) {
        navbar.style.top = "-80px"; // Oculta el navbar
    } else {
        navbar.style.top = "0"; // Muestra el navbar
    }

    prevScrollPos = currentScrollPos;

    // Si no hay desplazamiento durante un tiempo, mostrará el navbar
    clearTimeout(scrollTimer);
    scrollTimer = setTimeout(function() {
        navbar.style.top = "0"; // Después de cierto tiempo sin desplazarse, muestra el navbar
    }, 2000); // 2 segundos de espera sin movimiento
});

// Función para abrir el lightbox
const lightbox = document.getElementById('lightbox');
const lightboxImg = document.getElementById('lightbox-img');
const images = document.querySelectorAll('.img-click');
const closeBtn = document.querySelector('.close');
const prevBtn = document.querySelector('.prev');
const nextBtn = document.querySelector('.next');

let currentIndex = 0;

function openLightbox(index) {
    currentIndex = index;
    lightboxImg.src = images[currentIndex].src;
    lightbox.classList.add('show');
    lightbox.style.display = 'flex'; // Mostramos el lightbox como flex
}

// Eventos de las imágenes
images.forEach((img, index) => {
    img.addEventListener('click', () => {
        openLightbox(index);
    });
});

closeBtn.addEventListener('click', () => {
    lightbox.classList.remove('show');
    setTimeout(() => {
        lightbox.style.display = 'none'; // Ocultamos el lightbox con animación
    }, 300); // permite que se vea la animación de salida
});

// Cambiar imagen en el lightbox
function changeImage(index) {
    lightboxImg.style.opacity = 0; // Desaparece la imagen para transición

    setTimeout(() => {
        lightboxImg.src = images[index].src; // Actualizamos la imagen
        currentIndex = index;
        lightboxImg.style.opacity = 1; // Hacemos la imagen visible de nuevo
    }, 200);
}

prevBtn.addEventListener('click', () => {
    let newIndex = (currentIndex - 1 + images.length) % images.length;
    changeImage(newIndex);
});

nextBtn.addEventListener('click', () => {
    let newIndex = (currentIndex + 1) % images.length;
    changeImage(newIndex);
});


// Efecto de scroll en las imágenes de la galería
window.addEventListener('scroll', () => {
    document.querySelectorAll('.gallery-grid img').forEach(img => {
        const rect = img.getBoundingClientRect();
        if (rect.top < window.innerHeight - 100) {
            img.classList.add('visible');
        }
    });
});

// Scroll Reveal para .reveal
const revealElements = document.querySelectorAll('.reveal');

function revealOnScroll() {
    revealElements.forEach(el => {
        const rect = el.getBoundingClientRect();
        if (rect.top < window.innerHeight - 100) {
            el.classList.add('active');
        }
    });
}

window.addEventListener('scroll', revealOnScroll);
window.addEventListener('load', revealOnScroll); // para carga inicial

// Botón "volver arriba"
const scrollToTopBtn = document.getElementById("scrollToTopBtn");

window.addEventListener("scroll", () => {
    if (window.pageYOffset > 300) {
        scrollToTopBtn.classList.add("show");
    } else {
        scrollToTopBtn.classList.remove("show");
    }
});

scrollToTopBtn.addEventListener("click", () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
});


document.addEventListener("DOMContentLoaded", function () {
    const slides = document.querySelectorAll(".promotion-slide");
    const prevBtn = document.getElementById("prevPromo");
    const nextBtn = document.getElementById("nextPromo");

    let current = 0;

    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.classList.remove("active");
            if (i === index) slide.classList.add("active");
        });
    }

    prevBtn.addEventListener("click", () => {
        current = (current - 1 + slides.length) % slides.length;
        showSlide(current);
    });

    nextBtn.addEventListener("click", () => {
        current = (current + 1) % slides.length;
        showSlide(current);
    });

    showSlide(current); // Mostrar el primero
});

document.addEventListener("DOMContentLoaded", function() {
    const buttons = document.querySelectorAll(".faq-button");

    buttons.forEach(button => {
        button.addEventListener("click", function() {
            const content = this.nextElementSibling;

            // Cierra todos los demás contenidos
            document.querySelectorAll(".faq-content").forEach(item => {
                if (item !== content) {
                    item.classList.remove("open");
                }
            });

            // Alterna el actual
            content.classList.toggle("open");
        });
    });
});


window.addEventListener("load", function () {
    const preloader = document.getElementById("preloader");
    preloader.classList.add("fade-out");

    // Lo removemos del DOM después de la transición (opcional)
    setTimeout(() => {
        preloader.style.display = "none";
    }, 3000); // espera a que la animación termine
});


window.addEventListener("scroll", function () {
    const hero = document.querySelector(".hero");
    const scrolled = window.scrollY;
    hero.style.backgroundPositionY = `${scrolled * 0.5}px`;
});


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



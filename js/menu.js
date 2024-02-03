(function(){
    const openButton = document.querySelector('.nav_menu');
    const menu = document.querySelector('.nav_link');
    const closeMenu = document.querySelector('.nav_close');
    const navSalir = document.querySelector('.nav_salir', '.nav_salir1');
    const navSalir1 = document.querySelector('.nav_salir1');
    const navSalir2 = document.querySelector('.nav_salir2');
    const navSalir3 = document.querySelector('.nav_salir3');
    const navSalir4 = document.querySelector('.nav_salir4');

    openButton.addEventListener('click', ()=>{
        menu.classList.add('nav_link--show');
    });

    closeMenu.addEventListener('click', ()=>{
        menu.classList.remove('nav_link--show');
    });

    navSalir.addEventListener('click', ()=>{
        menu.classList.remove('nav_link--show');
    });

    navSalir1.addEventListener('click', ()=>{
        menu.classList.remove('nav_link--show');
    });

    navSalir2.addEventListener('click', ()=>{
        menu.classList.remove('nav_link--show');
    });

    navSalir3.addEventListener('click', ()=>{
        menu.classList.remove('nav_link--show');
    });

    navSalir4.addEventListener('click', ()=>{
        menu.classList.remove('nav_link--show');
    });


})();

const nav = document.querySelector('.nav');
window.addEventListener('scroll', function(){
    nav.classList.toggle('active', window.scrollY >0)
});


const fulImgBox = document.getElementById("fulImgBox"),
fulImg = document.getElementById("fulImg");

function openFulImg(reference){
    fulImgBox.style.display = "flex";
    fulImg.src = reference
}
function closeImg(){
    fulImgBox.style.display = "none";
}
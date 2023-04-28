
const menuburger = document.querySelector('.menuburger');
const navgauche = document.querySelector('.navgauche');

menuburger.addEventListener("click", () => {
  navgauche.classList.toggle("hidden");
})


function myFunction(x) {
    x.classList.toggle("fa-solid");
    x.classList.toggle("fa-regular");
    }

let img__slider = document.getElementsByClassName('img__slider');
console.log(img__slider);

let etape = 0;

let nbr__img = img__slider.length;

let precedent = document.querySelector('.precedent');
let suivant = document.querySelector('.suivant');

function enleverActiveImages() {
    for(let i = 0 ; i < nbr__img ; i++){
        img__slider[i].classList.remove('active');
    }
}

suivant.addEventListener('click', function() {
    etape++;
    if(etape >= nbr__img) {
        etape = 0;
    }
    enleverActiveImages();
    img__slider[etape].classList.add('active');
})

precedent.addEventListener('click', function () {
    etape--;
    if (etape < 0) {
        etape = nbr__img - 1;
    }
    enleverActiveImages();
    img__slider[etape].classList.add('active');
})

setInterval(function() {
    etape++;
    if(etape >= nbr__img) {
        etape = 0;
    }
    enleverActiveImages();
    img__slider[etape].classList.add('active');
}, 3000)
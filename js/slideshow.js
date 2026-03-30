let slideIndex = 0;
let slides = [];
let slideshowInterval = null;

function showSlide(index) {
    if (slides.length === 0) {
        return;
    }

    for (let i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }

    slides[index].style.display = "block";
}

function nextSlide() {
    if (slides.length === 0) {
        return;
    }

    slideIndex++;
    if (slideIndex >= slides.length) {
        slideIndex = 0;
    }

    showSlide(slideIndex);
}

function startSlideshow() {
    slides = document.querySelectorAll(".slide");

    if (slides.length === 0) {
        return;
    }

    slideIndex = 0;
    showSlide(slideIndex);

    if (slideshowInterval !== null) {
        clearInterval(slideshowInterval);
    }

    slideshowInterval = setInterval(nextSlide, 3000);
}

document.addEventListener("DOMContentLoaded", startSlideshow);
window.addEventListener("load", startSlideshow);
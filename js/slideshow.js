let slideIndex = 1;

function showSlides() {
    const slides = document.getElementsByClassName("home-slide");
    console.log("showSlides called, number of slides:", slides.length);
    
    for (let i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }

    slideIndex++;

    if (slideIndex > slides.length) {
        slideIndex = 1;
    }

    if (slides.length > 0) {
        slides[slideIndex - 1].style.display = "block";
        console.log("Displaying slide:", slideIndex);
    }
    setTimeout(showSlides, 3000);
}

// Function to display first slide immediately
function initSlideshow() {
    const slides = document.getElementsByClassName("home-slide");
    console.log("initSlideshow called, number of slides found:", slides.length);
    
    if (slides.length > 0) {
        slideIndex = 1;
        slides[0].style.display = "block";
        console.log("First slide displayed");
        setTimeout(showSlides, 3000);
    } else {
        console.error("No slides found! Check your HTML structure.");
    }
}

if (document.readyState === 'loading') {
    console.log("DOM still loading, waiting for DOMContentLoaded");
    document.addEventListener('DOMContentLoaded', initSlideshow);
} else {
    console.log("DOM already loaded, initializing slideshow");
    initSlideshow();
}

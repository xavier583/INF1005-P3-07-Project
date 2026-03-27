let slideIndex = 1;

function showSlides() {
    const slides = document.getElementsByClassName("slide");
    console.log("showSlides called, number of slides:", slides.length);
    
    // Hide all slides
    for (let i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }

    slideIndex++;

    // Loop back to first slide if we've gone past the last one
    if (slideIndex > slides.length) {
        slideIndex = 1;
    }

    // Show current slide
    if (slides.length > 0) {
        slides[slideIndex - 1].style.display = "block";
        console.log("Displaying slide:", slideIndex);
    }

    // Change slide every 3 seconds
    setTimeout(showSlides, 3000);
}

// Function to display first slide immediately
function initSlideshow() {
    const slides = document.getElementsByClassName("slide");
    console.log("initSlideshow called, number of slides found:", slides.length);
    
    if (slides.length > 0) {
        slideIndex = 1;
        slides[0].style.display = "block";
        console.log("First slide displayed");
        // Then start the rotation after 3 seconds
        setTimeout(showSlides, 3000);
    } else {
        console.error("No slides found! Check your HTML structure.");
    }
}

// Wait for DOM to be fully loaded before starting slideshow
if (document.readyState === 'loading') {
    console.log("DOM still loading, waiting for DOMContentLoaded");
    document.addEventListener('DOMContentLoaded', initSlideshow);
} else {
    console.log("DOM already loaded, initializing slideshow");
    initSlideshow();
}

document.addEventListener("DOMContentLoaded", function () {
    const video = document.getElementById("hero-video");
    const button = document.getElementById("video-control");

    if (!video || !button) {
        return;
    }

    button.addEventListener("click", function () {
        if (video.paused) {
            video.play();
            button.textContent = "❚❚";
            button.setAttribute("aria-label", "Pause Video");
        } else {
            video.pause();
            button.textContent = "▶";
            button.setAttribute("aria-label", "Play Video");
        }
    });
});
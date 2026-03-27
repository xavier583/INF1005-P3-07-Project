const video = document.getElementById("hero-video");
const controlBtn = document.getElementById("video-control");

if (video && controlBtn) {
    controlBtn.addEventListener("click", function () {
        if (video.paused) {
            video.play();
            controlBtn.textContent = "❚❚";
            controlBtn.setAttribute("aria-label", "Pause video");
        } else {
            video.pause();
            controlBtn.textContent = "▶";
            controlBtn.setAttribute("aria-label", "Play video");
        }
    });
}

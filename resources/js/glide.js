import Glide from '@glidejs/glide';

function initGlide() {
    var glideElements = document.querySelectorAll('.glide-1');

    // Loop through each element and initialize Glide
    glideElements.forEach(function (glideElement) {
        new Glide(glideElement, {
            type: 'carousel',
            perView: 5,
            autoplay: false,
            animationDuration: 1000,
            gap: 5,
            breakpoints: {
                640: {
                    perView: 2
                },
                480: {
                    perView: 2
                }
            }
        }).mount();
    });
}

setTimeout(() => {
    initGlide();
}, 100);
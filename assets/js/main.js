document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.querySelector('.menu-toggle');
    const nav = document.querySelector('.main-nav');
    if (toggle && nav) {
        toggle.addEventListener('click', () => {
            const expanded = toggle.getAttribute('aria-expanded') === 'true';
            toggle.setAttribute('aria-expanded', String(!expanded));
            nav.classList.toggle('is-open', !expanded);
        });
    }

    const mainImage = document.querySelector('#main-car-image');
    document.querySelectorAll('.thumbnail').forEach((thumbnail) => {
        thumbnail.addEventListener('click', () => {
            if (!mainImage) return;
            mainImage.src = thumbnail.dataset.image;
            document.querySelectorAll('.thumbnail').forEach((item) => item.classList.remove('selected'));
            thumbnail.classList.add('selected');
        });
    });
});

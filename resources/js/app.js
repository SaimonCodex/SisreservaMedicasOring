import './bootstrap';

window.addEventListener('DOMContentLoaded', () => {
    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, { rootMargin: '0px', threshold: 0.1 });

    document.querySelectorAll('.animate-on-scroll').forEach(el => observer.observe(el));

    document.querySelectorAll('[data-dismiss]').forEach(button => {
        button.addEventListener('click', () => {
            const target = document.getElementById(button.dataset.dismiss);
            if (target) {
                target.classList.add('opacity-0');
                setTimeout(() => target.remove(), 250);
            }
        });
    });
});
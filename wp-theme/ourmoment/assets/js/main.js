// Nav scroll shadow
const nav = document.getElementById('om-nav');
if (nav) {
  window.addEventListener('scroll', () => {
    nav.style.boxShadow = window.scrollY > 40
      ? '0 1px 8px rgba(58,47,42,.04)'
      : 'none';
  });
}

// Fade-in on scroll
const obs = new IntersectionObserver(entries => {
  entries.forEach(e => {
    if (e.isIntersecting) {
      e.target.classList.add('visible');
      obs.unobserve(e.target);
    }
  });
}, { threshold: 0.12 });

document.querySelectorAll('.om-fade').forEach(el => obs.observe(el));

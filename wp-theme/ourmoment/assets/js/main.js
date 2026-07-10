// Nav scroll shadow
const nav = document.getElementById('om-nav');
if (nav) {
  const onScroll = () => nav.classList.toggle('scrolled', window.scrollY > 40);
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();
}

// Mobile menu
const toggle = document.getElementById('om-nav-toggle');
const links = document.getElementById('om-nav-links');
if (toggle && links) {
  const setOpen = (open) => {
    links.classList.toggle('open', open);
    toggle.setAttribute('aria-expanded', String(open));
  };

  toggle.addEventListener('click', () => setOpen(!links.classList.contains('open')));

  // Close after tapping a link, and when leaving the mobile breakpoint
  links.addEventListener('click', (e) => {
    if (e.target.closest('a')) setOpen(false);
  });
  window.matchMedia('(min-width: 861px)').addEventListener('change', (e) => {
    if (e.matches) setOpen(false);
  });
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') setOpen(false);
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

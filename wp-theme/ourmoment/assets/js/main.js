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
  // Keep in sync with the nav breakpoint in style.css
  window.matchMedia('(min-width: 901px)').addEventListener('change', (e) => {
    if (e.matches) setOpen(false);
  });
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') setOpen(false);
  });
}

// Currency switcher: replace the native dropdown with our own list.
//
// The open state of a <select> is drawn by the operating system, not the page,
// so the highlight on the selected row cannot be styled — it arrives in the OS
// accent colour regardless of any CSS on `option`. The only way to control it
// is not to use it.
//
// The real <select> stays in the DOM and stays the source of truth: choosing
// from our list writes to it and dispatches `change`, so whatever WooPayments
// listens for still fires. If this script never runs, the native control is
// still there and still works.
document.querySelectorAll('.om-nav-currency select').forEach((select) => {
  const wrap = select.closest('.om-nav-currency');
  if (!wrap || wrap.dataset.omEnhanced) return;
  wrap.dataset.omEnhanced = '1';
  wrap.classList.add('om-currency-custom');

  const button = document.createElement('button');
  button.type = 'button';
  button.className = 'om-currency-btn';
  button.setAttribute('aria-haspopup', 'listbox');
  button.setAttribute('aria-expanded', 'false');
  button.setAttribute('aria-label', select.getAttribute('aria-label') || 'Change currency');

  const label = document.createElement('span');
  button.appendChild(label);

  const list = document.createElement('ul');
  list.className = 'om-currency-list';
  list.setAttribute('role', 'listbox');
  list.hidden = true;

  const options = [...select.options];
  const items = options.map((opt) => {
    const li = document.createElement('li');
    li.className = 'om-currency-option';
    li.setAttribute('role', 'option');
    li.tabIndex = -1;
    li.textContent = opt.textContent.trim();
    li.dataset.value = opt.value;
    list.appendChild(li);
    return li;
  });

  const syncLabel = () => {
    const current = options[select.selectedIndex];
    label.textContent = current ? current.textContent.trim() : '';
    items.forEach((li, i) => {
      li.setAttribute('aria-selected', String(i === select.selectedIndex));
    });
  };

  const setOpen = (open) => {
    list.hidden = !open;
    wrap.classList.toggle('open', open);
    button.setAttribute('aria-expanded', String(open));
    if (open) (items[select.selectedIndex] || items[0])?.focus();
  };

  const choose = (index) => {
    setOpen(false);
    button.focus();
    if (index === select.selectedIndex) return;
    select.selectedIndex = index;
    // WooPayments may listen for either, and dispatching both is harmless.
    select.dispatchEvent(new Event('input', { bubbles: true }));
    select.dispatchEvent(new Event('change', { bubbles: true }));
    syncLabel();
  };

  button.addEventListener('click', () => setOpen(list.hidden));

  items.forEach((li, i) => {
    li.addEventListener('click', () => choose(i));
    li.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); choose(i); }
      else if (e.key === 'ArrowDown') { e.preventDefault(); items[(i + 1) % items.length].focus(); }
      else if (e.key === 'ArrowUp') { e.preventDefault(); items[(i - 1 + items.length) % items.length].focus(); }
    });
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && !list.hidden) { setOpen(false); button.focus(); }
  });
  document.addEventListener('click', (e) => {
    if (!list.hidden && !wrap.contains(e.target)) setOpen(false);
  });

  // Keep the native control for assistive tech, out of the visual flow.
  select.classList.add('om-currency-native');
  wrap.appendChild(button);
  wrap.appendChild(list);
  syncLabel();
});

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

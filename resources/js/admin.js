/**
 * Admin enhancements JS (no frameworks; Bootstrap optional)
 */

document.addEventListener('DOMContentLoaded', () => {
  // Maintain sidebar scroll position between navigations
  const sidebar = document.querySelector('.sidebar');
  if (sidebar) {
    const key = 'admin_sidebar_scroll';
    sidebar.addEventListener('scroll', () => {
      localStorage.setItem(key, String(sidebar.scrollTop));
    });
    const saved = localStorage.getItem(key);
    if (saved) sidebar.scrollTop = parseInt(saved, 10) || 0;
  }

  // Auto-mark active menu by current path when route name highlighting is not enough
  const currentPath = window.location.pathname.replace(/\/$/, '');
  document.querySelectorAll('.sidebar .menu-link[href]').forEach((a) => {
    const href = a.getAttribute('href');
    if (!href) return;
    const normalized = href.replace(/\/$/, '');
    if (normalized && currentPath.startsWith(normalized)) {
      a.classList.add('active');
    }
  });

  // Smooth scroll to top for long pages
  const scrollBtn = document.createElement('button');
  scrollBtn.className = 'btn btn-admin position-fixed';
  scrollBtn.style.right = '1rem';
  scrollBtn.style.bottom = '1rem';
  scrollBtn.style.display = 'none';
  scrollBtn.innerHTML = '<i class="fas fa-arrow-up"></i>';
  document.body.appendChild(scrollBtn);
  window.addEventListener('scroll', () => {
    scrollBtn.style.display = window.scrollY > 400 ? 'inline-flex' : 'none';
  });
  scrollBtn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

  // Bootstrap tooltip init if available
  if (window.bootstrap) {
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach((el) => {
      try { new window.bootstrap.Tooltip(el); } catch (_) {}
    });
  }
});



(function () {
  function activateTab(link) {
    const container = link.closest('.locale-tabs');
    if (!container) return;

    container.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));

    container.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('show', 'active'));

    link.classList.add('active');

    const href = link.getAttribute('href') || link.getAttribute('data-bs-target');
    if (!href) return;

    const paneId = href.startsWith('#') ? href.slice(1) : href;
    const pane = container.querySelector('#' + CSS.escape(paneId));
    if (pane) {
      pane.classList.add('show', 'active');
    }
  }

  document.addEventListener('click', function (e) {
    const link = e.target.closest('.locale-tabs .nav-link');
    if (!link) return;
    e.preventDefault();
    activateTab(link);
  });

  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.locale-tabs').forEach(container => {
      const activeLink = container.querySelector('.nav-link.active');
      if (activeLink) activateTab(activeLink);
    });
  });
})();

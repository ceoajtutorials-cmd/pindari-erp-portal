// Pindari ERP Portal JS

(function () {
  // Sidebar toggle (mobile)
  var sidebarToggle = document.querySelector('.sidebar-toggle');
  var sidebar = document.querySelector('.sidebar');
  if (sidebarToggle && sidebar) {
    sidebarToggle.addEventListener('click', function () {
      sidebar.classList.toggle('open');
    });
  }

  // Close sidebar when clicking outside (mobile)
  document.addEventListener('click', function (e) {
    if (sidebar && sidebar.classList.contains('open')) {
      if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
        sidebar.classList.remove('open');
      }
    }
  });

  // Delete confirmation modals
  document.querySelectorAll('[data-confirm]').forEach(function (el) {
    el.addEventListener('click', function (e) {
      var msg = el.getAttribute('data-confirm');
      if (!confirm(msg)) {
        e.preventDefault();
      }
    });
  });

  // Auto-dismiss flash messages
  var flashes = document.querySelectorAll('.flash, .alert');
  flashes.forEach(function (el) {
    setTimeout(function () {
      el.style.transition = 'opacity 0.5s ease';
      el.style.opacity = '0';
      setTimeout(function () { el.remove(); }, 500);
    }, 5000);
  });

  // Animate progress bars on load
  document.querySelectorAll('.progress-fill').forEach(function (el) {
    var target = el.getAttribute('data-width');
    if (target) {
      setTimeout(function () { el.style.width = target + '%'; }, 100);
    }
  });

  // Animate bar chart
  document.querySelectorAll('.bar').forEach(function (el, i) {
    var targetH = el.getAttribute('data-height');
    el.style.height = '0px';
    setTimeout(function () {
      el.style.height = targetH + 'px';
    }, 100 + i * 80);
  });
})();

// Pindari Enterprises - Public Website JS

// Header scroll effect
(function () {
  var header = document.getElementById('siteHeader');
  if (header) {
    window.addEventListener('scroll', function () {
      if (window.scrollY > 10) {
        header.classList.add('scrolled');
      } else {
        header.classList.remove('scrolled');
      }
    });
  }

  // Mobile toggle
  var toggle = document.getElementById('mobileToggle');
  var nav = document.getElementById('mainNav');
  if (toggle && nav) {
    toggle.addEventListener('click', function () {
      toggle.classList.toggle('active');
      nav.classList.toggle('open');
    });
  }

  // Stat counter animation
  var statNumbers = document.querySelectorAll('.stat-number');
  if (statNumbers.length > 0) {
    var animated = false;
    function animateStats() {
      if (animated) return;
      var rect = statNumbers[0].getBoundingClientRect();
      if (rect.top < window.innerHeight && rect.bottom > 0) {
        animated = true;
        statNumbers.forEach(function (el) {
          var target = parseInt(el.dataset.value || el.textContent.replace(/\D/g, ''), 10);
          if (!target) return;
          var current = 0;
          var step = Math.ceil(target / 40);
          var suffix = el.dataset.suffix || '';
          var interval = setInterval(function () {
            current += step;
            if (current >= target) {
              current = target;
              clearInterval(interval);
            }
            el.textContent = current.toLocaleString() + suffix;
          }, 30);
        });
      }
    }
    window.addEventListener('scroll', animateStats);
    animateStats();
  }

  // Flash auto-dismiss
  var flash = document.querySelector('.flash');
  if (flash) {
    setTimeout(function () {
      flash.style.transition = 'opacity 0.5s ease';
      flash.style.opacity = '0';
      setTimeout(function () { flash.remove(); }, 500);
    }, 5000);
  }
})();

(function () {
  document.querySelectorAll('[data-toast]').forEach(function (toast) {
    var close = toast.querySelector('[data-toast-close]');
    var remove = function () { toast.remove(); };
    if (close) close.addEventListener('click', remove);
    setTimeout(remove, 4500);
  });

  var toggles = document.querySelectorAll('[data-toggle-password]');
  toggles.forEach(function (button) {
    button.addEventListener('click', function () {
      var target = document.getElementById(button.getAttribute('data-toggle-password'));
      if (!target) return;
      target.type = target.type === 'password' ? 'text' : 'password';
      button.setAttribute('aria-pressed', target.type === 'text' ? 'true' : 'false');
      button.setAttribute('aria-label', target.type === 'text' ? 'Hide password' : 'Show password');
      var eye = button.querySelector('[data-icon="eye"]');
      var eyeOff = button.querySelector('[data-icon="eye-off"]');
      if (eye && eyeOff) {
        eye.classList.toggle('hidden', target.type === 'text');
        eyeOff.classList.toggle('hidden', target.type !== 'text');
      }
    });
  });

  var sidebar = document.querySelector('[data-sidebar]');
  var openers = document.querySelectorAll('[data-sidebar-open]');
  var closers = document.querySelectorAll('[data-sidebar-close]');
  openers.forEach(function (button) {
    button.addEventListener('click', function () { if (sidebar) sidebar.classList.add('is-open'); });
  });
  closers.forEach(function (button) {
    button.addEventListener('click', function () { if (sidebar) sidebar.classList.remove('is-open'); });
  });
})();

(function () {
  'use strict';

  var SUBMIT_LOCK_MS = 12000;
  var NAV_LOCK_MS = 2500;

  function unlockForm(form) {
    if (!form) return;
    delete form.dataset.garbaliaSubmitting;
    form.querySelectorAll('button[type="submit"], input[type="submit"]').forEach(function (button) {
      if (button.dataset.garbaliaWasDisabled !== '1') button.disabled = false;
      delete button.dataset.garbaliaWasDisabled;
    });
  }

  document.addEventListener('submit', function (event) {
    var form = event.target;
    if (!form || event.defaultPrevented) return;

    if (form.dataset.garbaliaSubmitting === '1') {
      event.preventDefault();
      event.stopImmediatePropagation();
      return;
    }

    form.dataset.garbaliaSubmitting = '1';
    form.querySelectorAll('button[type="submit"], input[type="submit"]').forEach(function (button) {
      if (button.disabled) button.dataset.garbaliaWasDisabled = '1';
      button.disabled = true;
    });

    window.setTimeout(function () {
      unlockForm(form);
    }, SUBMIT_LOCK_MS);
  });

  document.addEventListener('click', function (event) {
    var link = event.target.closest && event.target.closest('a[href]');
    if (!link || link.target === '_blank' || link.hasAttribute('download')) return;
    if (link.dataset.garbaliaNavLock === '1') {
      event.preventDefault();
      return;
    }
    var href = link.getAttribute('href') || '';
    if (!href || href.charAt(0) === '#' || href.indexOf('javascript:') === 0) return;
    link.dataset.garbaliaNavLock = '1';
    window.setTimeout(function () {
      delete link.dataset.garbaliaNavLock;
    }, NAV_LOCK_MS);
  });

  window.addEventListener('pageshow', function () {
    document.querySelectorAll('form[data-garbalia-submitting="1"]').forEach(unlockForm);
    document.querySelectorAll('a[data-garbalia-nav-lock="1"]').forEach(function (link) {
      delete link.dataset.garbaliaNavLock;
    });
  });
})();

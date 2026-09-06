(function () {
  'use strict';

  var LOCK_MS = 12000;

  function unlock(form) {
    if (!form) return;
    delete form.dataset.garbaliaSubmitting;
    form.querySelectorAll('button[type="submit"],input[type="submit"]').forEach(function (button) {
      if (button.dataset.wasDisabled !== '1') button.disabled = false;
      delete button.dataset.wasDisabled;
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
    form.querySelectorAll('button[type="submit"],input[type="submit"]').forEach(function (button) {
      if (button.disabled) button.dataset.wasDisabled = '1';
      button.disabled = true;
    });

    window.setTimeout(function () { unlock(form); }, LOCK_MS);
  });

  window.addEventListener('pageshow', function () {
    document.querySelectorAll('form[data-garbalia-submitting="1"]').forEach(unlock);
  });
})();

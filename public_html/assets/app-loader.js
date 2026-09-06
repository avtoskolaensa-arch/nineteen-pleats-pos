(function () {
  'use strict';

  var LOCK_MS = 12000;
  var PRINT_TIMEOUT_MS = 15000;

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

  // Never let the two critical print POST requests leave the POS looking frozen
  // indefinitely if shared hosting stalls. Existing direct-print error handling
  // catches AbortError and restores the controls.
  if (window.fetch && window.AbortController && !window.__garbaliaFetchTimeoutInstalled) {
    window.__garbaliaFetchTimeoutInstalled = true;
    var nativeFetch = window.fetch.bind(window);
    window.fetch = function (input, init) {
      var url = typeof input === 'string' ? input : (input && input.url ? input.url : '');
      var isCriticalPrint = url.indexOf('/send_order_print.php') !== -1 || url.indexOf('/close_order_print.php') !== -1;
      if (!isCriticalPrint || (init && init.signal)) return nativeFetch(input, init);

      var controller = new AbortController();
      var options = Object.assign({}, init || {}, {signal: controller.signal});
      var timer = window.setTimeout(function () { controller.abort(); }, PRINT_TIMEOUT_MS);
      return nativeFetch(input, options).finally(function () { window.clearTimeout(timer); });
    };
  }
})();

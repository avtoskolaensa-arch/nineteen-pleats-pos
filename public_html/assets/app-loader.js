(function () {
  'use strict';

  var LOCK_MS = 12000;
  var ACTION_TIMEOUT_MS = 15000;

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

  // Critical AJAX actions should never leave the interface looking frozen forever
  // if shared hosting stalls. Their existing catch handlers restore the controls.
  if (window.fetch && window.AbortController && !window.__garbaliaFetchTimeoutInstalled) {
    window.__garbaliaFetchTimeoutInstalled = true;
    var nativeFetch = window.fetch.bind(window);
    window.fetch = function (input, init) {
      var url = typeof input === 'string' ? input : (input && input.url ? input.url : '');
      var isCriticalAction =
        url.indexOf('/send_order_print.php') !== -1 ||
        url.indexOf('/close_order_print.php') !== -1 ||
        url.indexOf('/cancel-table-order.php') !== -1;
      if (!isCriticalAction || (init && init.signal)) return nativeFetch(input, init);

      var controller = new AbortController();
      var options = Object.assign({}, init || {}, {signal: controller.signal});
      var timer = window.setTimeout(function () { controller.abort(); }, ACTION_TIMEOUT_MS);
      return nativeFetch(input, options).finally(function () { window.clearTimeout(timer); });
    };
  }

  // Ask an existing service worker to check for the newest POS build immediately.
  if ('serviceWorker' in navigator && navigator.serviceWorker.getRegistration) {
    navigator.serviceWorker.getRegistration('/').then(function (registration) {
      if (registration && registration.update) registration.update().catch(function () {});
    }).catch(function () {});
  }
})();

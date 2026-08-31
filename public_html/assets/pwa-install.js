(function () {
  let deferredInstallPrompt = null;

  function isStandalone() {
    return window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;
  }

  function injectPwaMetadata() {
    if (!document.querySelector('link[rel="manifest"]')) {
      const manifest = document.createElement('link');
      manifest.rel = 'manifest';
      manifest.href = '/manifest.webmanifest?v=3';
      document.head.appendChild(manifest);
    }

    if (!document.querySelector('meta[name="theme-color"]')) {
      const theme = document.createElement('meta');
      theme.name = 'theme-color';
      theme.content = '#2b1b10';
      document.head.appendChild(theme);
    }

    if (!document.querySelector('meta[name="apple-mobile-web-app-capable"]')) {
      const capable = document.createElement('meta');
      capable.name = 'apple-mobile-web-app-capable';
      capable.content = 'yes';
      document.head.appendChild(capable);
    }

    if (!document.querySelector('meta[name="apple-mobile-web-app-status-bar-style"]')) {
      const status = document.createElement('meta');
      status.name = 'apple-mobile-web-app-status-bar-style';
      status.content = 'black-translucent';
      document.head.appendChild(status);
    }
  }

  function injectStyles() {
    if (document.getElementById('garbalia-pwa-style')) return;
    const style = document.createElement('style');
    style.id = 'garbalia-pwa-style';
    style.textContent = `
      html,body{max-width:100%;overflow-x:hidden}
      .pwa-install-btn{display:inline-flex;align-items:center;justify-content:center;gap:7px;min-height:36px;padding:7px 11px;border:1px solid rgba(255,255,255,.22);border-radius:10px;background:rgba(255,255,255,.12);color:#fff;font:inherit;font-size:.79rem;font-weight:900;cursor:pointer;white-space:nowrap;transition:.16s ease}
      .pwa-install-btn:hover{background:#fff;color:#2b1b10;transform:translateY(-1px)}
      .pwa-install-btn:before{content:"↓";display:grid;place-items:center;width:18px;height:18px;border-radius:6px;background:rgba(255,255,255,.18);font-size:.78rem;line-height:1}
      .pwa-install-toast{position:fixed;right:18px;bottom:18px;z-index:10080;display:flex;align-items:center;gap:12px;width:min(390px,calc(100vw - 36px));padding:15px 16px;border:1px solid #ead6bd;border-radius:18px;background:linear-gradient(180deg,#fffaf2,#f5e7d5);box-shadow:0 18px 45px rgba(43,27,16,.24);color:#2b1b10;animation:garbaliaPwaIn .2s ease-out}
      .pwa-install-toast strong{display:block;font-size:.94rem}.pwa-install-toast span{display:block;margin-top:3px;color:#6d5140;font-size:.78rem;font-weight:750;line-height:1.35}.pwa-install-toast button{margin-left:auto;flex:0 0 auto;min-height:38px;padding:7px 11px;border:0;border-radius:11px;background:#2b1b10;color:#fff;font-weight:900;cursor:pointer}
      body.pwa-standalone{width:100%;min-height:100dvh!important;display:flex!important;flex-direction:column!important;overflow-x:hidden!important}
      body.pwa-standalone>.topbar{width:100%;flex:0 0 auto}
      body.pwa-standalone>main.wrap{width:100%;min-height:0!important;flex:1 1 auto!important}
      body.pwa-standalone>.app-footer{width:100%;flex:0 0 auto!important;margin-top:auto!important;padding-bottom:max(10px,env(safe-area-inset-bottom))!important}
      body.pwa-standalone .pwa-install-btn{display:none!important}
      @keyframes garbaliaPwaIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:none}}
      @media(max-width:980px){
        body.pwa-standalone>.topbar{align-items:stretch!important;padding:8px 10px!important;gap:7px!important}
        body.pwa-standalone>.topbar .brand{width:100%;justify-content:center!important}
        body.pwa-standalone>.topbar .brand-text{text-align:center}
        body.pwa-standalone>.topbar .nav{width:100%;display:grid!important;grid-template-columns:repeat(3,minmax(0,1fr));gap:4px!important;overflow:visible!important}
        body.pwa-standalone>.topbar .nav a,body.pwa-standalone>.topbar .nav button{width:100%!important;min-width:0!important;min-height:34px!important;padding:6px 5px!important;text-align:center!important;font-size:.76rem!important;line-height:1.15!important;white-space:normal!important}
        body.pwa-standalone>.app-footer{padding-left:12px!important;padding-right:12px!important}
        body.pwa-standalone .footer-inner{width:100%!important;gap:8px!important;padding:10px 0!important}
      }
      @media(max-width:560px){
        .pwa-install-btn{width:100%}.pwa-install-toast{right:10px;bottom:10px;width:calc(100vw - 20px);align-items:flex-start;flex-wrap:wrap}.pwa-install-toast button{width:100%;margin-left:0}
        body.pwa-standalone>.topbar .garbalia-mark{width:38px!important;height:30px!important;flex-basis:38px!important;border-radius:10px!important}
        body.pwa-standalone>.topbar .garbalia-bird{width:28px!important;height:21px!important}
        body.pwa-standalone>.topbar .brand-text small{display:none!important}
        body.pwa-standalone>.topbar .garbalia-word{font-size:.78rem!important}
        body.pwa-standalone>main.wrap{padding-left:10px!important;padding-right:10px!important}
        body.pwa-standalone>.app-footer .footer-brand{display:none!important}
        body.pwa-standalone>.app-footer .footer-inner{justify-content:center!important;align-items:center!important;text-align:center!important}
        body.pwa-standalone>.app-footer .footer-credit{justify-content:center!important;width:100%!important}
      }
      @media(display-mode:standalone){body{overscroll-behavior-y:none}.pwa-install-btn{display:none!important}}
    `;
    document.head.appendChild(style);
  }

  function removeInstallUi() {
    document.querySelectorAll('[data-pwa-install]').forEach(function (element) { element.remove(); });
  }

  function promptInstall() {
    if (!deferredInstallPrompt) return;
    deferredInstallPrompt.prompt();
    deferredInstallPrompt.userChoice.then(function () {
      deferredInstallPrompt = null;
      removeInstallUi();
    });
  }

  function addInstallButton() {
    if (!deferredInstallPrompt || isStandalone() || document.querySelector('[data-pwa-install]')) return;
    const nav = document.querySelector('.nav');
    if (!nav) return;

    const button = document.createElement('button');
    button.type = 'button';
    button.className = 'pwa-install-btn';
    button.setAttribute('data-pwa-install', '1');
    button.textContent = 'აპის დაყენება';
    button.addEventListener('click', promptInstall);

    const logout = nav.querySelector('a[href*="logout"]');
    if (logout) nav.insertBefore(button, logout);
    else nav.appendChild(button);
  }

  function showInstallToast() {
    if (!deferredInstallPrompt || isStandalone() || sessionStorage.getItem('garbalia-pwa-toast') === 'shown' || document.querySelector('.pwa-install-toast')) return;
    sessionStorage.setItem('garbalia-pwa-toast', 'shown');

    const toast = document.createElement('div');
    toast.className = 'pwa-install-toast';
    toast.setAttribute('data-pwa-install', '1');
    toast.innerHTML = '<div><strong>GARBALIA POS პროგრამად დააყენე</strong><span>გაიხსნება ცალკე ფანჯარაში — მისამართის ზოლისა და ბრაუზერის ტაბების გარეშე.</span></div><button type="button">დაყენება</button>';
    toast.querySelector('button').addEventListener('click', promptInstall);
    document.body.appendChild(toast);
    window.setTimeout(function () { if (toast.isConnected) toast.remove(); }, 12000);
  }

  function registerServiceWorker() {
    if (!('serviceWorker' in navigator) || location.protocol !== 'https:') return;
    navigator.serviceWorker.register('/service-worker.js?v=3', {scope: '/'}).catch(function () {
      // The POS remains fully usable in the browser if registration is unavailable.
    });
  }

  function start() {
    injectPwaMetadata();
    injectStyles();
    if (isStandalone()) document.body.classList.add('pwa-standalone');
    registerServiceWorker();
  }

  window.addEventListener('beforeinstallprompt', function (event) {
    event.preventDefault();
    deferredInstallPrompt = event;
    addInstallButton();
    window.setTimeout(showInstallToast, 800);
  });

  window.addEventListener('appinstalled', function () {
    deferredInstallPrompt = null;
    document.body.classList.add('pwa-standalone');
    removeInstallUi();
  });

  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', start);
  else start();
})();

(function () {
  'use strict';

  function isTablePage() {
    return /^\/table\/\d+\/?$/.test(window.location.pathname) ||
      new URLSearchParams(window.location.search).get('page') === 'table';
  }

  function injectStyles() {
    if (document.getElementById('garbalia-table-natural-flow')) return;

    const style = document.createElement('style');
    style.id = 'garbalia-table-natural-flow';
    style.textContent = `
      html body.app-shell .pos-grid,
      html body.app-shell.page-table .pos-grid{
        height:auto!important;
        max-height:none!important;
        overflow:visible!important;
        align-items:start!important;
      }
      html body.app-shell .pos-grid>.card:first-child,
      html body.app-shell.page-table .pos-grid>.card:first-child{
        height:auto!important;
        min-height:0!important;
        max-height:none!important;
        overflow:visible!important;
        overflow-x:visible!important;
        overflow-y:visible!important;
        scrollbar-width:none!important;
        overscroll-behavior:auto!important;
      }
      html body.app-shell .pos-grid>.card:first-child::-webkit-scrollbar,
      html body.app-shell.page-table .pos-grid>.card:first-child::-webkit-scrollbar{
        display:none!important;
        width:0!important;
        height:0!important;
      }
      html body.app-shell .pos-grid>.card:first-child .category-title,
      html body.app-shell.page-table .pos-grid>.card:first-child .category-title{
        position:static!important;
        inset:auto!important;
      }
      html body.app-shell main.wrap:has(.pos-grid){
        height:auto!important;
        min-height:0!important;
        max-height:none!important;
        overflow:visible!important;
      }
      @media(min-width:981px){
        html body.app-shell .pos-grid>.current-order-card,
        html body.app-shell.page-table .pos-grid>.current-order-card{
          position:sticky!important;
          top:74px!important;
          align-self:start!important;
          max-height:calc(100dvh - 90px)!important;
          overflow-y:auto!important;
          overflow-x:hidden!important;
        }
      }
      @media(max-width:980px){
        html body.app-shell .pos-grid>.current-order-card,
        html body.app-shell.page-table .pos-grid>.current-order-card{
          position:static!important;
          max-height:none!important;
          overflow:visible!important;
        }
      }
    `;
    document.head.appendChild(style);
  }

  function forceNaturalFlow() {
    if (!isTablePage()) return;
    injectStyles();

    const grid = document.querySelector('.pos-grid');
    const productsCard = grid && grid.querySelector(':scope > .card:first-child');
    if (grid) {
      grid.style.setProperty('height', 'auto', 'important');
      grid.style.setProperty('max-height', 'none', 'important');
      grid.style.setProperty('overflow', 'visible', 'important');
    }
    if (productsCard) {
      productsCard.style.setProperty('height', 'auto', 'important');
      productsCard.style.setProperty('min-height', '0', 'important');
      productsCard.style.setProperty('max-height', 'none', 'important');
      productsCard.style.setProperty('overflow', 'visible', 'important');
      productsCard.style.setProperty('overflow-x', 'visible', 'important');
      productsCard.style.setProperty('overflow-y', 'visible', 'important');
    }
  }

  function start() {
    if (!isTablePage()) return;
    forceNaturalFlow();
    window.requestAnimationFrame(forceNaturalFlow);
    window.setTimeout(forceNaturalFlow, 250);
    window.setTimeout(forceNaturalFlow, 900);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', start, {once:true});
  } else {
    start();
  }
})();

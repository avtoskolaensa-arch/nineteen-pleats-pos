(function () {
  function injectCompactDesktopStyles() {
    if (document.getElementById('garbalia-compact-desktop-style')) return;

    const style = document.createElement('style');
    style.id = 'garbalia-compact-desktop-style';
    style.textContent = `
      @media (min-width:981px) and (max-width:1500px), (min-width:981px) and (max-height:860px) {
        html{font-size:14px!important}
        body.app-shell{line-height:1.32!important}
        body.app-shell .topbar{min-height:58px!important;padding:7px 18px!important;gap:10px!important}
        body.app-shell .brand.garbalia-brand{gap:8px!important}
        body.app-shell .garbalia-mark{width:56px!important;height:34px!important;flex:0 0 56px!important}
        body.app-shell .garbalia-header-logo{width:56px!important;height:34px!important}
        body.app-shell .garbalia-word{font-size:.88rem!important;letter-spacing:.06em!important}
        body.app-shell .brand small{font-size:.66rem!important;margin-top:3px!important}
        body.app-shell .nav{gap:2px!important;flex-wrap:nowrap!important}
        body.app-shell .nav a{min-height:34px!important;padding:7px 8px!important;border-radius:9px!important;font-size:.79rem!important}

        body.app-shell .wrap{width:min(1360px,100%)!important;padding:14px 18px!important}
        body.app-shell .wrap>*{max-width:100%}
        body.app-shell .page-head{gap:8px!important;margin-bottom:12px!important}
        body.app-shell h1{font-size:1.85rem!important;line-height:1.08!important}
        body.app-shell h2{font-size:1.03rem!important;margin-bottom:10px!important}
        body.app-shell h3{font-size:.96rem!important;margin:12px 0 8px!important}
        body.app-shell .muted{font-size:.86rem!important}

        body.app-shell .card,
        body.app-shell .login-card,
        body.app-shell .receipt-card{padding:14px!important;border-radius:16px!important}
        body.app-shell .stack{gap:8px!important}
        body.app-shell label{gap:5px!important;font-size:.84rem!important}
        body.app-shell input,
        body.app-shell select{min-height:36px!important;padding:8px 9px!important;border-radius:10px!important;font-size:.86rem!important}
        body.app-shell .btn{min-height:36px!important;padding:7px 10px!important;border-radius:10px!important;font-size:.82rem!important}
        body.app-shell .pill{padding:6px 9px!important;font-size:.80rem!important}
        body.app-shell .live-date-pill{min-height:36px!important;min-width:148px!important;padding:7px 11px!important}
        body.app-shell .live-date-text{font-size:.84rem!important}

        body.app-shell .stats{grid-template-columns:repeat(6,minmax(0,1fr))!important;gap:8px!important;margin-bottom:12px!important}
        body.app-shell .stats div{padding:10px 11px!important;border-radius:14px!important}
        body.app-shell .stats span{font-size:.72rem!important;line-height:1.16!important}
        body.app-shell .stats strong{margin-top:4px!important;font-size:1.18rem!important;line-height:1.08!important}
        body.app-shell .day-cash-layout{grid-template-columns:minmax(280px,.82fr) minmax(0,1.18fr)!important;gap:12px!important}

        body.app-shell .tables-grid{grid-template-columns:repeat(5,minmax(0,1fr))!important;gap:10px!important}
        body.app-shell .table-card{min-height:104px!important;padding:13px!important;border-radius:17px!important}
        body.app-shell .table-card span{font-size:1.10rem!important;white-space:normal!important}
        body.app-shell .table-card strong{margin-top:8px!important;padding:5px 8px!important;font-size:.78rem!important;white-space:normal!important}

        body.app-shell .total-box{padding:8px 13px!important;border-radius:13px!important;font-size:1.35rem!important}
        body.app-shell .pos-grid{grid-template-columns:minmax(0,1.18fr) minmax(300px,.82fr)!important;gap:12px!important}
        body.app-shell .category-title{padding:7px 9px!important;font-size:.88rem!important;margin:12px 0 7px!important}
        body.app-shell .product-row{grid-template-columns:minmax(140px,1fr) 64px minmax(120px,.88fr) 92px!important;gap:6px!important;padding:7px 0!important}
        body.app-shell .product-name strong{font-size:.86rem!important}
        body.app-shell .product-name small{font-size:.74rem!important;margin-top:2px!important}
        body.app-shell .order-item{padding:10px!important;margin-bottom:8px!important;border-radius:14px!important;gap:8px!important}
        body.app-shell .actions{gap:7px!important;margin-top:12px!important}
        body.app-shell .close-form{grid-template-columns:minmax(150px,190px) minmax(170px,1fr)!important;gap:8px!important}
        body.app-shell .mixed-fields{gap:6px!important}
        body.app-shell .cancel-form{gap:6px!important}

        body.app-shell .two-col{grid-template-columns:minmax(260px,340px) minmax(0,1fr)!important;gap:12px!important}
        body.app-shell .reports{grid-template-columns:minmax(210px,280px) minmax(0,1fr)!important}
        body.app-shell .table-wrap{border-radius:11px!important}
        body.app-shell table{min-width:560px!important}
        body.app-shell th,
        body.app-shell td{padding:7px 8px!important;font-size:.80rem!important;line-height:1.22!important}
        body.app-shell .receipt-card pre{min-height:220px!important;padding:11px!important;border-radius:11px!important;font-size:.82rem!important;line-height:1.32!important}
        body.app-shell hr{margin:13px 0!important}

        body.app-shell .history-grid{grid-template-columns:repeat(5,minmax(115px,1fr))!important;gap:8px!important}
        body.app-shell .cashier-history-grid{grid-template-columns:1fr 1fr minmax(125px,.8fr) minmax(155px,1fr) auto!important;gap:8px!important}
        body.app-shell .history-actions,
        body.app-shell .cashier-history-actions{gap:6px!important;margin-top:8px!important}
        body.app-shell .history-detail-grid,
        body.app-shell .cashier-history-detail-grid{gap:7px!important}
        body.app-shell .history-detail-grid div,
        body.app-shell .cashier-history-detail-grid div{padding:8px!important;border-radius:11px!important}
        body.app-shell .cashier-history-detail{margin-bottom:0!important}

        body.app-shell .statistics-page{gap:11px!important}
        body.app-shell .stat-mini-grid{gap:8px!important}
        body.app-shell .stat-mini{padding:10px 11px!important;border-radius:13px!important}
        body.app-shell .statistics-two{gap:10px!important}
        body.app-shell .stat-card{padding:12px!important}
        body.app-shell .stats-range{gap:5px!important}
        body.app-shell .stats-range .btn{min-height:32px!important;padding:6px 8px!important;font-size:.76rem!important}
        body.app-shell .stat-table th,
        body.app-shell .stat-table td{padding:7px 8px!important;font-size:.76rem!important}

        body.app-shell.page-products .wrap{max-width:1360px!important}
        body.app-shell .product-top-panel{margin-bottom:11px!important;padding:11px 13px!important;border-radius:16px!important}
        body.app-shell .product-top-row{gap:10px!important}
        body.app-shell .product-top-icon{width:36px!important;height:36px!important;border-radius:11px!important;font-size:22px!important}
        body.app-shell .product-add-toggle{min-height:36px!important;padding:7px 11px!important;border-radius:10px!important}
        body.app-shell .product-add-holder{margin-top:9px!important}
        body.app-shell .product-add-card form,
        body.app-shell .product-add-card form.cost-product-form{grid-template-columns:minmax(170px,1.2fr) 110px 120px minmax(140px,.8fr) 92px 112px!important;gap:7px!important}
        body.app-shell .product-add-card .check{padding:8px 9px!important;border-radius:10px!important}
        body.app-shell.page-products tbody{gap:7px!important}
        body.app-shell.page-products tr{grid-template-columns:minmax(170px,1.25fr) minmax(95px,.70fr) minmax(175px,1fr) 76px 165px!important;gap:8px!important;padding:9px 10px!important;border-radius:13px!important}
        body.app-shell.page-products td:nth-child(1){font-size:.86rem!important}
        body.app-shell.page-products td:nth-child(2),
        body.app-shell.page-products td:nth-child(3){font-size:.80rem!important}
        body.app-shell.page-products td:nth-child(4){padding:5px 7px!important;font-size:.74rem!important}
        body.app-shell.page-products .btn.mini{min-height:32px!important;padding:6px 8px!important;font-size:.76rem!important}

        body.app-shell .garbalia-confirm-overlay,
        body.app-shell .unsent-overlay,
        body.app-shell .garbalia-cash-modal,
        body.app-shell .garbalia-close-modal{padding:10px!important}
        body.app-shell .garbalia-confirm-dialog,
        body.app-shell .unsent-dialog{width:min(560px,calc(100vw - 24px))!important;max-width:min(560px,calc(100vw - 24px))!important;padding:19px 17px 15px!important;border-radius:20px!important}
        body.app-shell .garbalia-cash-dialog,
        body.app-shell .garbalia-close-dialog{width:min(590px,calc(100vw - 24px))!important;max-width:min(590px,calc(100vw - 24px))!important;padding:19px 17px 15px!important;border-radius:20px!important}
        body.app-shell .garbalia-confirm-dialog h3,
        body.app-shell .unsent-dialog h3,
        body.app-shell .garbalia-cash-dialog h3,
        body.app-shell .garbalia-close-dialog h3{font-size:1.12rem!important;margin-bottom:6px!important}
        body.app-shell .garbalia-confirm-dialog p,
        body.app-shell .unsent-dialog p,
        body.app-shell .garbalia-cash-dialog p,
        body.app-shell .garbalia-close-dialog p{font-size:.82rem!important;margin-bottom:11px!important}
        body.app-shell .garbalia-confirm-info{gap:6px!important;margin-bottom:11px!important}
        body.app-shell .garbalia-confirm-info div,
        body.app-shell .garbalia-close-summary div,
        body.app-shell .garbalia-close-list div{padding:8px 9px!important;border-radius:11px!important;font-size:.80rem!important}
        body.app-shell .garbalia-confirm-actions,
        body.app-shell .unsent-actions,
        body.app-shell .garbalia-close-actions{gap:7px!important}

        body.app-shell .dual-print-page{gap:11px!important}
        body.app-shell .dual-print-card{max-width:680px!important;padding:13px!important}
        body.app-shell .dual-receipt-part{padding:13px!important}
        body.app-shell .dual-receipt-part h2{font-size:1rem!important;margin-bottom:8px!important}
        body.app-shell .dual-receipt-part pre{font-size:.80rem!important;line-height:1.32!important}

        body.app-shell .app-footer{padding:0 18px 10px!important}
        body.app-shell .footer-inner{width:min(1360px,100%)!important;padding:9px 0!important;gap:10px!important}
        body.app-shell .footer-logo-img{width:50px!important;height:30px!important}
        body.app-shell .footer-brand{gap:8px!important}
        body.app-shell .footer-brand strong{font-size:.78rem!important}
        body.app-shell .footer-brand small,
        body.app-shell .footer-credit{font-size:.68rem!important}
        body.app-shell .whatsapp-link{min-height:28px!important;padding:5px 10px!important;font-size:.72rem!important}
      }

      @media (min-width:981px) and (max-width:1180px) {
        body.app-shell .topbar{flex-wrap:wrap!important}
        body.app-shell .nav{flex-wrap:wrap!important}
        body.app-shell .stats{grid-template-columns:repeat(3,minmax(0,1fr))!important}
        body.app-shell .tables-grid{grid-template-columns:repeat(4,minmax(0,1fr))!important}
        body.app-shell .day-cash-layout{grid-template-columns:1fr 1fr!important}
        body.app-shell .history-grid{grid-template-columns:repeat(4,minmax(110px,1fr))!important}
        body.app-shell .cashier-history-grid{grid-template-columns:1fr 1fr 1fr!important}
        body.app-shell .cashier-history-grid .btn{grid-column:1/-1!important}
        body.app-shell .product-add-card form,
        body.app-shell .product-add-card form.cost-product-form{grid-template-columns:1fr 1fr 1fr!important}
        body.app-shell.page-products tr{grid-template-columns:minmax(0,1fr) 100px minmax(165px,.8fr) 72px 155px!important}
      }

      @media (min-width:981px) and (max-height:760px) {
        body.app-shell .wrap{padding-top:10px!important;padding-bottom:10px!important}
        body.app-shell .page-head{margin-bottom:9px!important}
        body.app-shell .stats{margin-bottom:9px!important}
        body.app-shell .stats div{padding-top:8px!important;padding-bottom:8px!important}
        body.app-shell .card{padding-top:11px!important;padding-bottom:11px!important}
        body.app-shell .app-footer{padding-bottom:5px!important}
        body.app-shell .footer-inner{padding:6px 0!important}
      }
    `;
    document.head.appendChild(style);
  }

  function addCashierHistoryAccess() {
    const nav = document.querySelector('.nav');
    if (nav && !nav.querySelector('a[href*="history"]')) {
      const link = document.createElement('a');
      link.href = '/history';
      link.textContent = 'ისტორია';
      link.className = 'cashier-history-link';
      const logout = nav.querySelector('a[href*="logout"]');
      if (logout) nav.insertBefore(link, logout); else nav.appendChild(link);
    }

    const params = new URLSearchParams(window.location.search);
    const orderId = params.get('order_id');
    const detail = document.querySelector('.history-detail');
    if (!orderId || !detail) return;

    let action = detail.querySelector('[data-reprint-order], a[href*="print_final"]');
    if (detail.textContent.indexOf('ნულით დახურული') !== -1) {
      if (action) action.remove();
      return;
    }

    if (!action) {
      action = document.createElement('a');
      const actions = detail.querySelector('.cashier-history-detail-actions');
      const head = detail.querySelector('.page-head');
      if (actions) actions.insertBefore(action, actions.firstChild);
      else if (head) head.appendChild(action);
      else return;
    }

    action.href = '/print_final?order_id=' + encodeURIComponent(orderId) + '&reprint=1';
    action.className = 'btn success cashier-reprint-button';
    action.setAttribute('data-reprint-order', '1');
    action.textContent = 'ქვითრის ბეჭდვა';
    action.title = 'საბოლოო ქვითრის ხელახლა დაბეჭდვა';
  }

  function runHistoryFixes() {
    injectCompactDesktopStyles();
    addCashierHistoryAccess();
    window.setTimeout(addCashierHistoryAccess, 150);
    window.setTimeout(addCashierHistoryAccess, 700);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', runHistoryFixes);
  } else {
    runHistoryFixes();
  }
})();

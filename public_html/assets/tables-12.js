(function () {
  'use strict';

  function injectLayoutStyles() {
    if (document.getElementById('garbalia-tables-layout-style')) return;

    const style = document.createElement('style');
    style.id = 'garbalia-tables-layout-style';
    style.textContent = `
      html body.app-shell main.wrap:has(.tables-grid){
        width:min(1480px,100%)!important;
        min-height:0!important;
        height:auto!important;
        display:flex!important;
        flex-direction:column!important;
        justify-content:flex-start!important;
        align-items:stretch!important;
        gap:10px!important;
        padding-top:18px!important;
        padding-bottom:28px!important;
        overflow:visible!important;
      }
      html body.app-shell main.wrap:has(.tables-grid)>.page-head{
        width:min(1380px,100%)!important;
        margin:0 auto 2px!important;
        flex:0 0 auto!important;
      }
      .garbalia-takeaway-section{
        width:min(1040px,calc(100% - 24px));
        height:auto!important;
        min-height:0!important;
        flex:0 0 auto!important;
        align-self:center!important;
        margin:0 auto 2px!important;
        padding:8px 9px 9px;
        border:1px solid rgba(150,88,24,.24);
        border-radius:17px;
        background:
          radial-gradient(circle at 88% -55%,rgba(255,255,255,.82),transparent 50%),
          linear-gradient(145deg,#fff5e2,#efd4a7);
        box-shadow:0 9px 22px rgba(91,52,18,.09),inset 0 1px 0 rgba(255,255,255,.94);
      }
      .garbalia-takeaway-head{
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:10px;
        margin:0 2px 7px;
      }
      .garbalia-takeaway-head strong{
        display:block;
        color:#4b2d16;
        font-size:.82rem;
        font-weight:950;
        letter-spacing:-.01em;
      }
      .garbalia-takeaway-head>span{
        display:inline-flex;
        align-items:center;
        min-height:23px;
        padding:3px 8px;
        border-radius:999px;
        background:rgba(127,72,19,.11);
        color:#86521e;
        font-size:.64rem;
        font-weight:900;
        white-space:nowrap;
      }
      .garbalia-takeaway-grid{
        display:grid;
        grid-template-columns:repeat(5,minmax(0,1fr));
        gap:8px;
        width:100%;
      }
      body.app-shell .garbalia-takeaway-grid .table-card,
      body.app-shell .garbalia-takeaway-grid .table-card.free{
        min-height:66px!important;
        height:auto!important;
        padding:8px 9px!important;
        border-width:1px!important;
        border-radius:14px!important;
        border-color:#c98635!important;
        background:
          radial-gradient(circle at 91% -28%,rgba(255,255,255,.88),transparent 46%),
          linear-gradient(145deg,#fff1cf,#e9bd6d)!important;
        box-shadow:0 7px 16px rgba(122,70,18,.12),inset 0 1px 0 rgba(255,255,255,.96)!important;
      }
      body.app-shell .garbalia-takeaway-grid .table-card:hover{
        transform:translateY(-2px)!important;
        box-shadow:0 11px 22px rgba(122,70,18,.16),inset 0 1px 0 rgba(255,255,255,.96)!important;
      }
      body.app-shell .garbalia-takeaway-grid .table-card.occupied{
        border-color:#b5473d!important;
        background:linear-gradient(145deg,#fff0e5,#f3b29e)!important;
      }
      body.app-shell .garbalia-takeaway-grid .table-card.pending{
        border-color:#d28c20!important;
        background:linear-gradient(145deg,#fff5d6,#efc268)!important;
      }
      body.app-shell .garbalia-takeaway-grid .table-card span{
        font-size:.92rem!important;
        line-height:1.05!important;
        white-space:nowrap!important;
      }
      body.app-shell .garbalia-takeaway-grid .table-card strong{
        margin-top:5px!important;
        padding:4px 7px!important;
        font-size:.67rem!important;
      }
      body.app-shell .garbalia-takeaway-grid .table-card span:before{
        content:"↗";
        display:inline-grid;
        place-items:center;
        width:21px;
        height:21px;
        margin-right:5px;
        border-radius:7px;
        background:rgba(86,47,13,.12);
        color:#6c3d14;
        font-size:.70rem;
        vertical-align:middle;
      }
      html body.app-shell main.wrap:has(.tables-grid)>.tables-grid{
        width:min(1380px,100%)!important;
        margin:0 auto!important;
        flex:0 0 auto!important;
        align-self:center!important;
      }
      @media (min-width:1250px) {
        html body.app-shell:has(.tables-grid) main.wrap .tables-grid {
          grid-template-columns:repeat(6,minmax(0,1fr))!important;
          gap:14px!important;
        }
        html body.app-shell:has(.tables-grid) main.wrap .table-card {
          min-height:clamp(122px,13.5vh,154px)!important;
          padding-left:13px!important;
          padding-right:13px!important;
        }
        html body.app-shell:has(.tables-grid) main.wrap .garbalia-takeaway-grid .table-card{
          min-height:66px!important;
          padding:8px 9px!important;
        }
      }
      @media (min-width:981px) and (max-width:1249px) {
        html body.app-shell:has(.tables-grid) main.wrap .tables-grid {
          grid-template-columns:repeat(4,minmax(0,1fr))!important;
        }
        .garbalia-takeaway-section{width:min(940px,calc(100% - 24px))}
      }
      @media (max-width:980px) {
        .garbalia-takeaway-grid{grid-template-columns:repeat(3,minmax(0,1fr))}
      }
      @media (max-width:640px) {
        html body.app-shell main.wrap:has(.tables-grid){gap:8px!important;padding-top:12px!important}
        .garbalia-takeaway-section{width:calc(100% - 16px);padding:8px;border-radius:15px;margin:0 auto!important}
        .garbalia-takeaway-grid{grid-template-columns:1fr 1fr;gap:7px}
        body.app-shell .garbalia-takeaway-grid .table-card{min-height:62px!important;padding:8px!important}
      }
      @media (max-width:360px) {
        .garbalia-takeaway-grid{grid-template-columns:1fr}
      }
    `;
    document.head.appendChild(style);
  }

  function organizeTakeawayCards() {
    const grid = document.querySelector('.tables-grid');
    if (!grid) return;

    const takeawayCards = Array.from(grid.querySelectorAll('.table-card')).filter(function (card) {
      const name = card.querySelector('span');
      return name && /^გატანა\s*\d+$/i.test(name.textContent.trim());
    });

    if (!takeawayCards.length) return;

    let section = document.querySelector('.garbalia-takeaway-section');
    if (!section) {
      section = document.createElement('section');
      section.className = 'garbalia-takeaway-section';
      section.innerHTML = '<div class="garbalia-takeaway-head"><strong>გატანის შეკვეთები</strong><span data-takeaway-count></span></div><div class="garbalia-takeaway-grid"></div>';
      grid.parentNode.insertBefore(section, grid);
    }

    const takeawayGrid = section.querySelector('.garbalia-takeaway-grid');
    takeawayCards.forEach(function (card) {
      card.classList.add('takeaway-card');
      takeawayGrid.appendChild(card);
    });
    const count = section.querySelector('[data-takeaway-count]');
    if (count) count.textContent = takeawayGrid.children.length + ' გატანა';
  }

  function start() {
    injectLayoutStyles();
    organizeTakeawayCards();
    window.setTimeout(organizeTakeawayCards, 250);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', start);
  } else {
    start();
  }
})();

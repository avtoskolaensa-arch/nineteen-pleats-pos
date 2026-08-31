<?php
header('Content-Type: text/css; charset=UTF-8');
header('Cache-Control: public, max-age=31536000, immutable');
readfile(__DIR__ . '/style.css');
?>

/* GARBALIA runtime popup refinements */
.garbalia-confirm-dialog{
  width:min(470px,calc(100vw - 40px))!important;
  max-height:calc(100vh - 42px)!important;
  overflow-y:auto!important;
  overflow-x:hidden!important;
  padding-bottom:22px!important;
}
.garbalia-confirm-info{
  max-height:min(48vh,420px)!important;
  overflow-y:auto!important;
  overflow-x:hidden!important;
  padding-right:2px!important;
}
.garbalia-confirm-info div{min-width:0!important}
.garbalia-confirm-info span,.garbalia-confirm-info strong{min-width:0!important;word-break:break-word!important;overflow-wrap:anywhere!important}
.garbalia-confirm-info strong{white-space:normal!important;text-align:right!important}
.garbalia-confirm-actions{position:sticky!important;bottom:0!important;z-index:2!important;margin-top:12px!important;padding-top:10px!important;background:linear-gradient(180deg,rgba(248,236,221,0),#f8ecdd 32%,#f8ecdd 100%)!important}
@media(max-width:560px){.garbalia-confirm-dialog{width:calc(100vw - 24px)!important;max-height:calc(100vh - 24px)!important;padding:24px 18px 18px!important}.garbalia-confirm-info{max-height:50vh!important}}

/* Premium centered tables screen */
@media(min-width:981px){
  body.app-shell:has(.tables-grid) main.wrap{width:min(1480px,100%)!important;flex:1 1 auto!important;display:flex!important;flex-direction:column!important;justify-content:center!important;padding:20px 28px!important}
  body.app-shell:has(.tables-grid) main.wrap .page-head{width:min(1380px,100%)!important;margin:0 auto 18px!important;align-items:center!important}
  body.app-shell:has(.tables-grid) main.wrap .page-head h1{font-size:2.05rem!important;letter-spacing:-.035em!important}
  body.app-shell:has(.tables-grid) main.wrap .live-date-pill{min-height:40px!important;padding:8px 14px!important;border:1px solid rgba(43,27,16,.11)!important;box-shadow:0 10px 24px rgba(43,27,16,.09)!important}
  body.app-shell:has(.tables-grid) main.wrap .tables-grid{width:min(1380px,100%)!important;margin:0 auto!important;gap:16px!important;align-items:stretch!important}
  body.app-shell:has(.tables-grid) main.wrap .table-card{min-height:clamp(128px,14.5vh,166px)!important;padding:18px 16px!important;align-items:center!important;justify-content:center!important;text-align:center!important;border-width:1px!important;border-radius:26px!important;box-shadow:0 14px 32px rgba(43,27,16,.10),inset 0 1px 0 rgba(255,255,255,.88)!important;isolation:isolate!important}
  body.app-shell:has(.tables-grid) main.wrap .table-card:before{background:linear-gradient(145deg,rgba(255,255,255,.72),rgba(255,255,255,.06) 58%,rgba(43,27,16,.025))!important}
  body.app-shell:has(.tables-grid) main.wrap .table-card:hover{transform:translateY(-4px) scale(1.012)!important;box-shadow:0 20px 40px rgba(43,27,16,.15),inset 0 1px 0 rgba(255,255,255,.92)!important}
  body.app-shell:has(.tables-grid) main.wrap .table-card span{width:100%!important;text-align:center!important;font-size:1.18rem!important;line-height:1.12!important;letter-spacing:-.02em!important}
  body.app-shell:has(.tables-grid) main.wrap .table-card strong{margin-top:11px!important;max-width:100%!important;justify-content:center!important;text-align:center!important;padding:7px 12px!important;border:1px solid rgba(43,27,16,.06)!important;background:rgba(255,255,255,.72)!important;box-shadow:0 5px 14px rgba(43,27,16,.06)!important;font-size:.82rem!important}
  body.app-shell:has(.tables-grid) main.wrap .table-card.free{background:linear-gradient(145deg,#f7fff3 0%,#e9fbe3 100%)!important;border-color:#62aa52!important}
  body.app-shell:has(.tables-grid) main.wrap .table-card.occupied{background:linear-gradient(145deg,#fff7f3 0%,#ffe8df 100%)!important;border-color:#cf4d3f!important}
  body.app-shell:has(.tables-grid) main.wrap .table-card.pending{background:linear-gradient(145deg,#fffaf0 0%,#ffedc5 100%)!important;border-color:#d89a27!important}
}
@media(min-width:1250px){body.app-shell:has(.tables-grid) main.wrap .tables-grid{grid-template-columns:repeat(5,minmax(0,1fr))!important}}
@media(min-width:981px) and (max-height:760px){body.app-shell:has(.tables-grid) main.wrap .table-card{min-height:112px!important}body.app-shell:has(.tables-grid) main.wrap .page-head{margin-bottom:12px!important}}

<?php readfile(__DIR__ . '/premium-ui.css'); ?>

/* Final specificity fixes for late runtime styles */
html body.app-shell.page-products .product-top-copy>div::before{display:none!important;content:none!important}
html body.app-shell.page-products main.wrap>.page-head{justify-content:center!important;text-align:center!important;margin-bottom:clamp(10px,1.5vw,16px)!important}
html body.app-shell.page-products main.wrap>.page-head h1{display:inline-flex!important;align-items:center!important;gap:12px!important}
html body.app-shell.page-products main.wrap>.page-head h1:before,
html body.app-shell.page-products main.wrap>.page-head h1:after{content:"";display:block;width:clamp(22px,3vw,48px);height:1px;background:linear-gradient(90deg,transparent,#c8a77f)}
html body.app-shell.page-products main.wrap>.page-head h1:after{background:linear-gradient(90deg,#c8a77f,transparent)}
html body.app-shell main.wrap:has(.garbalia-cash-actions-panel)>.page-head{padding-bottom:12px!important;border-bottom:1px solid rgba(43,27,16,.08)!important}

/* Workday opening: compact, centered and responsive */
html body.app-shell main.wrap:has(form input[name="action"][value="open_day"]){
  width:100%!important;
  max-width:none!important;
  min-height:calc(100dvh - 154px)!important;
  flex:1 1 auto!important;
  display:grid!important;
  place-items:center!important;
  padding:clamp(18px,4vw,56px)!important;
}
html body.app-shell main.wrap:has(form input[name="action"][value="open_day"])>.card.narrow{
  position:relative!important;
  overflow:hidden!important;
  width:min(620px,100%)!important;
  max-width:620px!important;
  margin:auto!important;
  padding:clamp(22px,3vw,32px)!important;
  border-radius:clamp(22px,2.4vw,30px)!important;
  border:1px solid rgba(149,105,64,.20)!important;
  background:
    radial-gradient(circle at 88% -20%,rgba(218,186,143,.25),transparent 42%),
    linear-gradient(155deg,rgba(255,253,249,.99),rgba(248,237,223,.97))!important;
  box-shadow:0 28px 68px rgba(43,27,16,.13),inset 0 1px 0 rgba(255,255,255,.92)!important;
  text-align:center!important;
}
html body.app-shell main.wrap:has(form input[name="action"][value="open_day"])>.card.narrow:after{
  content:"";
  position:absolute;
  right:-38px;
  top:-48px;
  width:210px;
  height:160px;
  background:url('/Logo.png?v=12') center/contain no-repeat;
  opacity:.035;
  filter:brightness(0);
  pointer-events:none;
}
html body.app-shell main.wrap:has(form input[name="action"][value="open_day"])>.card.narrow>*{
  position:relative!important;
  z-index:1!important;
}
html body.app-shell main.wrap:has(form input[name="action"][value="open_day"])>.card.narrow h1{
  margin:0 auto 9px!important;
  max-width:520px!important;
  text-align:center!important;
  font-size:clamp(1.55rem,2.5vw,2.05rem)!important;
  line-height:1.1!important;
  letter-spacing:-.035em!important;
}
html body.app-shell main.wrap:has(form input[name="action"][value="open_day"])>.card.narrow>.muted{
  max-width:500px!important;
  margin:0 auto clamp(17px,2.4vw,23px)!important;
  text-align:center!important;
  font-size:clamp(.82rem,1vw,.93rem)!important;
  line-height:1.5!important;
}
html body.app-shell main.wrap:has(form input[name="action"][value="open_day"]) form.stack{
  width:min(500px,100%)!important;
  margin:0 auto!important;
  gap:13px!important;
}
html body.app-shell main.wrap:has(form input[name="action"][value="open_day"]) form.stack label{
  gap:7px!important;
  text-align:center!important;
  font-size:.86rem!important;
}
html body.app-shell main.wrap:has(form input[name="action"][value="open_day"]) form.stack input{
  min-height:46px!important;
  padding:10px 13px!important;
  border-radius:13px!important;
  text-align:center!important;
  background:rgba(255,255,255,.94)!important;
  box-shadow:inset 0 1px 3px rgba(43,27,16,.04)!important;
}
html body.app-shell main.wrap:has(form input[name="action"][value="open_day"]) form.stack>.btn{
  width:min(300px,100%)!important;
  min-height:48px!important;
  margin:3px auto 0!important;
  border-radius:15px!important;
  background:linear-gradient(180deg,#2d8848,#23733d)!important;
  box-shadow:0 12px 24px rgba(36,115,60,.20),inset 0 1px 0 rgba(255,255,255,.16)!important;
}
@media(max-width:640px){
  html body.app-shell main.wrap:has(form input[name="action"][value="open_day"]){
    min-height:auto!important;
    padding:20px 12px 28px!important;
  }
  html body.app-shell main.wrap:has(form input[name="action"][value="open_day"])>.card.narrow{
    width:100%!important;
    padding:21px 15px!important;
    border-radius:22px!important;
  }
  html body.app-shell main.wrap:has(form input[name="action"][value="open_day"]) form.stack>.btn{
    width:100%!important;
  }
}
@media(min-width:641px) and (max-height:700px){
  html body.app-shell main.wrap:has(form input[name="action"][value="open_day"]){
    min-height:auto!important;
    padding-block:16px!important;
  }
  html body.app-shell main.wrap:has(form input[name="action"][value="open_day"])>.card.narrow{
    padding:20px 26px!important;
  }
}

/* Table order workspace: compact, balanced and responsive */
html body.app-shell.page-table main.wrap{
  width:min(1360px,100%)!important;
  padding:clamp(16px,2.3vw,30px) clamp(12px,2.8vw,34px) clamp(26px,4vw,46px)!important;
}
html body.app-shell.page-table main.wrap>.flash,
html body.app-shell.page-table main.wrap>.warn{
  width:min(1240px,100%)!important;
  margin:0 auto 14px!important;
  padding:10px 13px!important;
  border-radius:13px!important;
  font-size:.82rem!important;
  font-weight:800!important;
}
html body.app-shell.page-table main.wrap>.page-head{
  position:relative!important;
  width:min(1240px,100%)!important;
  min-height:78px!important;
  margin:0 auto 14px!important;
  padding:14px 16px!important;
  display:grid!important;
  grid-template-columns:minmax(0,1fr) auto auto!important;
  align-items:center!important;
  gap:12px!important;
  border:1px solid rgba(132,88,48,.16)!important;
  border-radius:22px!important;
  background:
    radial-gradient(circle at 82% -90%,rgba(218,186,143,.31),transparent 48%),
    linear-gradient(145deg,rgba(255,253,249,.98),rgba(247,235,220,.95))!important;
  box-shadow:0 16px 38px rgba(43,27,16,.085),inset 0 1px 0 rgba(255,255,255,.88)!important;
}
html body.app-shell.page-table main.wrap>.page-head h1{
  margin:0!important;
  font-size:clamp(1.55rem,2.2vw,2rem)!important;
  line-height:1.05!important;
  letter-spacing:-.04em!important;
}
html body.app-shell.page-table main.wrap>.page-head [data-open-receipt-number]{
  min-height:36px!important;
  padding:7px 12px!important;
  border:1px solid rgba(43,27,16,.08)!important;
  border-radius:999px!important;
  background:linear-gradient(180deg,#3b271a,#28180f)!important;
  color:#fff!important;
  font-size:.78rem!important;
  font-weight:950!important;
  box-shadow:0 8px 18px rgba(43,27,16,.13)!important;
  white-space:nowrap!important;
}
html body.app-shell.page-table main.wrap>.page-head .total-box{
  min-width:128px!important;
  min-height:54px!important;
  display:flex!important;
  align-items:center!important;
  justify-content:center!important;
  padding:10px 16px!important;
  border-radius:16px!important;
  background:linear-gradient(160deg,#392317,#25160e)!important;
  font-size:clamp(1.3rem,2vw,1.7rem)!important;
  line-height:1!important;
  box-shadow:0 12px 24px rgba(43,27,16,.16),inset 0 1px 0 rgba(255,255,255,.07)!important;
}
html body.app-shell.page-table .pos-grid{
  width:min(1240px,100%)!important;
  margin:0 auto!important;
  display:grid!important;
  grid-template-columns:minmax(0,1.17fr) minmax(380px,.83fr)!important;
  gap:14px!important;
  align-items:start!important;
}
html body.app-shell.page-table .pos-grid>.card{
  padding:15px 17px!important;
  border:1px solid rgba(132,88,48,.16)!important;
  border-radius:22px!important;
  background:linear-gradient(155deg,rgba(255,253,249,.98),rgba(249,239,226,.96))!important;
  box-shadow:0 18px 42px rgba(43,27,16,.085),inset 0 1px 0 rgba(255,255,255,.9)!important;
}
html body.app-shell.page-table .pos-grid>.card>h2:first-child{
  margin:0 0 10px!important;
  display:flex!important;
  align-items:center!important;
  justify-content:center!important;
  gap:10px!important;
  text-align:center!important;
  font-size:1rem!important;
  letter-spacing:-.015em!important;
}
html body.app-shell.page-table .pos-grid>.card>h2:first-child:before,
html body.app-shell.page-table .pos-grid>.card>h2:first-child:after{
  content:"";
  width:32px;
  height:1px;
  background:linear-gradient(90deg,transparent,#c6a47d);
}
html body.app-shell.page-table .pos-grid>.card>h2:first-child:after{
  background:linear-gradient(90deg,#c6a47d,transparent);
}
html body.app-shell.page-table .pos-grid>.card:first-child{
  max-height:calc(100dvh - 230px)!important;
  min-height:420px!important;
  overflow:auto!important;
  scrollbar-width:thin!important;
  scrollbar-color:#b79268 transparent!important;
}
html body.app-shell.page-table .pos-grid>.card:first-child::-webkit-scrollbar{width:7px!important}
html body.app-shell.page-table .pos-grid>.card:first-child::-webkit-scrollbar-thumb{background:#b79268!important;border-radius:999px!important}
html body.app-shell.page-table .category-title{
  position:sticky!important;
  top:-1px!important;
  z-index:3!important;
  margin:9px 0 2px!important;
  padding:7px 10px!important;
  border:1px solid rgba(132,88,48,.10)!important;
  border-radius:10px!important;
  background:linear-gradient(180deg,#ead8bf,#e3cdb0)!important;
  color:#47301f!important;
  font-size:.80rem!important;
  line-height:1.2!important;
  font-weight:950!important;
  box-shadow:0 5px 12px rgba(43,27,16,.05)!important;
}
html body.app-shell.page-table .product-row{
  grid-template-columns:minmax(165px,1fr) 62px minmax(125px,.82fr) 98px!important;
  gap:7px!important;
  min-height:54px!important;
  padding:6px 0!important;
  border-bottom:1px solid rgba(181,142,97,.28)!important;
  transition:background .14s ease,padding .14s ease!important;
}
html body.app-shell.page-table .product-row:hover{
  margin-inline:-5px!important;
  padding-inline:5px!important;
  border-radius:11px!important;
  background:rgba(255,255,255,.58)!important;
}
html body.app-shell.page-table .product-name strong{
  font-size:.84rem!important;
  line-height:1.18!important;
  font-weight:900!important;
}
html body.app-shell.page-table .product-name small{
  margin-top:2px!important;
  font-size:.68rem!important;
  line-height:1.15!important;
}
html body.app-shell.page-table .product-row input{
  min-height:37px!important;
  padding:7px 9px!important;
  border-radius:10px!important;
  font-size:.78rem!important;
  background:rgba(255,255,255,.94)!important;
}
html body.app-shell.page-table .product-row .qty-input{
  text-align:center!important;
  font-weight:900!important;
}
html body.app-shell.page-table .product-row .btn{
  min-height:37px!important;
  padding:7px 9px!important;
  border-radius:10px!important;
  font-size:.76rem!important;
  line-height:1.05!important;
  background:linear-gradient(180deg,#3a271b,#28180f)!important;
  box-shadow:0 6px 13px rgba(43,27,16,.12)!important;
  white-space:nowrap!important;
}
html body.app-shell.page-table .current-order-card{
  position:sticky!important;
  top:88px!important;
  max-height:calc(100dvh - 104px)!important;
  overflow:auto!important;
  scrollbar-width:thin!important;
  scrollbar-color:#b79268 transparent!important;
}
html body.app-shell.page-table .current-order-card::-webkit-scrollbar{width:7px!important}
html body.app-shell.page-table .current-order-card::-webkit-scrollbar-thumb{background:#b79268!important;border-radius:999px!important}
html body.app-shell.page-table .current-order-card>p.muted{
  margin:3px 0 0!important;
  padding:18px 12px!important;
  border:1px dashed #d5b48b!important;
  border-radius:15px!important;
  background:rgba(255,255,255,.55)!important;
  text-align:center!important;
  font-size:.79rem!important;
  font-weight:800!important;
}
html body.app-shell.page-table .order-item{
  gap:8px!important;
  margin:0 0 8px!important;
  padding:10px!important;
  border-radius:15px!important;
  border-color:rgba(181,142,97,.27)!important;
  background:linear-gradient(155deg,#fff,#fff9f0)!important;
  box-shadow:0 7px 16px rgba(43,27,16,.055)!important;
}
html body.app-shell.page-table .order-item>div:first-child{
  padding:0!important;
}
html body.app-shell.page-table .order-item strong{
  font-size:.82rem!important;
  line-height:1.18!important;
}
html body.app-shell.page-table .order-item small,
html body.app-shell.page-table .order-item em{
  margin-top:3px!important;
  font-size:.68rem!important;
  line-height:1.25!important;
}
html body.app-shell.page-table .cancel-form,
html body.app-shell.page-table .cancel-form.cancel-form-compact{
  grid-template-columns:68px minmax(120px,1fr) 96px!important;
  gap:6px!important;
}
html body.app-shell.page-table .cancel-form select,
html body.app-shell.page-table .cancel-form input,
html body.app-shell.page-table .cancel-form .btn,
html body.app-shell.page-table .cancel-form .muted{
  min-height:36px!important;
  padding:6px 8px!important;
  border-radius:10px!important;
  font-size:.70rem!important;
}
html body.app-shell.page-table .current-order-card .actions{
  margin:11px 0 0!important;
}
html body.app-shell.page-table .current-order-card .actions,
html body.app-shell.page-table .current-order-card .actions form,
html body.app-shell.page-table .current-order-card .actions .btn{
  width:100%!important;
}
html body.app-shell.page-table .current-order-card .actions .btn.primary{
  min-height:44px!important;
  border-radius:13px!important;
  background:linear-gradient(180deg,#2f68b7,#2357a5)!important;
  box-shadow:0 10px 20px rgba(35,87,165,.18)!important;
  font-size:.82rem!important;
}
html body.app-shell.page-table .current-order-card hr{
  margin:14px 0 12px!important;
  border-color:rgba(132,88,48,.14)!important;
}
html body.app-shell.page-table .current-order-card hr+h2{
  margin:0 0 10px!important;
  text-align:center!important;
  font-size:.95rem!important;
}
html body.app-shell.page-table .close-form,
html body.app-shell.page-table .zero-close-form{
  padding:11px!important;
  border:1px solid rgba(132,88,48,.14)!important;
  border-radius:15px!important;
  background:rgba(255,255,255,.57)!important;
}
html body.app-shell.page-table .close-form{
  grid-template-columns:minmax(145px,.75fr) minmax(170px,1.25fr)!important;
  gap:9px!important;
}
html body.app-shell.page-table .close-form label{
  gap:5px!important;
  font-size:.74rem!important;
}
html body.app-shell.page-table .close-form select,
html body.app-shell.page-table .close-form input,
html body.app-shell.page-table .close-form>.btn{
  min-height:41px!important;
  border-radius:11px!important;
  font-size:.78rem!important;
}
html body.app-shell.page-table .close-form>.btn{
  background:linear-gradient(180deg,#2f8a4b,#24733c)!important;
  box-shadow:0 9px 18px rgba(36,115,60,.17)!important;
}
html body.app-shell.page-table .zero-close-form{
  display:grid!important;
  gap:7px!important;
  text-align:center!important;
}
html body.app-shell.page-table .zero-close-form h3,
html body.app-shell.page-table .zero-close-form p{
  margin:0!important;
}

@media(max-width:1080px){
  html body.app-shell.page-table .pos-grid{
    grid-template-columns:1fr!important;
    width:min(880px,100%)!important;
  }
  html body.app-shell.page-table .pos-grid>.card:first-child{
    max-height:none!important;
    min-height:0!important;
    overflow:visible!important;
  }
  html body.app-shell.page-table .current-order-card{
    position:static!important;
    max-height:none!important;
    overflow:visible!important;
  }
}
@media(max-width:760px){
  html body.app-shell.page-table main.wrap{
    padding:14px 10px 28px!important;
  }
  html body.app-shell.page-table main.wrap>.page-head{
    grid-template-columns:1fr 1fr!important;
    padding:13px!important;
    text-align:center!important;
  }
  html body.app-shell.page-table main.wrap>.page-head h1{
    grid-column:1/-1!important;
    text-align:center!important;
  }
  html body.app-shell.page-table main.wrap>.page-head [data-open-receipt-number],
  html body.app-shell.page-table main.wrap>.page-head .total-box{
    width:100%!important;
    min-width:0!important;
  }
  html body.app-shell.page-table .product-row{
    grid-template-columns:minmax(0,1fr) 64px!important;
    gap:7px!important;
    padding:8px 0!important;
  }
  html body.app-shell.page-table .product-row input[name="comment"],
  html body.app-shell.page-table .product-row .btn{
    grid-column:1/-1!important;
    width:100%!important;
  }
  html body.app-shell.page-table .cancel-form,
  html body.app-shell.page-table .cancel-form.cancel-form-compact,
  html body.app-shell.page-table .close-form{
    grid-template-columns:1fr!important;
  }
}
@media(max-width:480px){
  html body.app-shell.page-table main.wrap>.page-head{
    grid-template-columns:1fr!important;
  }
  html body.app-shell.page-table main.wrap>.page-head h1,
  html body.app-shell.page-table main.wrap>.page-head [data-open-receipt-number],
  html body.app-shell.page-table main.wrap>.page-head .total-box{
    grid-column:1!important;
  }
  html body.app-shell.page-table .pos-grid>.card{
    padding:13px 12px!important;
    border-radius:18px!important;
  }
  html body.app-shell.page-table .product-row{
    grid-template-columns:minmax(0,1fr) 58px!important;
  }
}

<?php
require __DIR__ . '/includes/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require __DIR__ . '/includes/actions.php';
    handle_post_action();
}

if (is_logged_in()) {
    redirect_to('day');
}

header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
$flash = $GLOBALS['garbalia_flash_snapshot'] ?? null;
$logo = garbalia_mark_svg();
?>
<!doctype html>
<html lang="ka">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>GARBALIA POS</title>
<link rel="icon" type="image/png" href="/Logo.png">
<link rel="apple-touch-icon" href="/Logo.png">
<style>
:root{--bg:#f6efe4;--card:#fffaf2;--ink:#25160d;--muted:#7a6657;--line:#e5d1b6;--brown:#2b1b10;--blue:#2357a5;--red:#b43129;--shadow:0 18px 44px rgba(43,27,16,.12)}
*{box-sizing:border-box}html{min-height:100%;background:var(--bg)}body{margin:0;min-height:100vh;background:linear-gradient(180deg,#f7efe4 0%,#f3eadc 100%);color:var(--ink);font-family:system-ui,-apple-system,Segoe UI,Arial,sans-serif;display:flex;flex-direction:column}.topbar{display:flex;align-items:center;min-height:88px;padding:12px clamp(16px,4vw,34px);background:var(--brown);color:#fff;box-shadow:0 4px 18px rgba(0,0,0,.13)}.brand{display:flex;align-items:center;gap:14px;color:#fff;text-decoration:none}.mark{display:grid;place-items:center;width:54px;height:54px;color:#fff}.garbalia-bird{width:52px;height:38px;fill:none;stroke:currentColor;stroke-width:4.2;stroke-linecap:round;stroke-linejoin:round}.brand strong{display:block;font-size:1.05rem;font-weight:950;letter-spacing:.07em}.brand small{display:block;margin-top:4px;opacity:.72;font-size:.78rem}.stage{flex:1;display:grid;place-items:center;padding:28px 16px 44px}.login-card{width:min(430px,100%);background:rgba(255,250,242,.98);border:1px solid var(--line);border-radius:24px;padding:24px;box-shadow:var(--shadow);text-align:center}.login-card h1{margin:0 0 20px;font-size:clamp(1.7rem,5vw,2.15rem);line-height:1.1}.login-logo{display:grid;place-items:center;width:90px;height:72px;margin:0 auto 10px;color:#111}.login-logo .garbalia-bird{width:82px;height:58px}.stack{display:grid;gap:13px}label{display:grid;gap:7px;text-align:left;font-weight:800;font-size:.92rem}input{width:100%;min-height:46px;border:1px solid #c9ad88;border-radius:13px;background:#fff;padding:10px 12px;color:var(--ink);font:inherit;outline:none}input:focus{border-color:#7ea1d3;box-shadow:0 0 0 3px rgba(35,87,165,.11)}button{min-height:48px;border:0;border-radius:13px;background:var(--blue);color:#fff;font:inherit;font-weight:900;padding:10px 14px;cursor:pointer}.flash,.warn{width:min(430px,100%);margin:0 auto 12px;border-radius:14px;padding:11px 13px;font-weight:750}.flash{background:#fff3cd;border:1px solid #e7c96c}.warn{background:#ffe5e2;border:1px solid #ec9a92;color:#6a110b}@media(max-width:560px){.topbar{min-height:76px;padding:10px 14px}.mark{width:48px;height:48px}.garbalia-bird{width:44px;height:33px}.brand strong{font-size:.96rem}.brand small{font-size:.71rem}.stage{padding:20px 12px 34px}.login-card{padding:20px 16px;border-radius:20px}}
</style>
</head>
<body>
<header class="topbar"><div class="brand"><span class="mark"><?= $logo ?></span><span><strong>GARBALIA POS</strong><small>Restaurant Management System</small></span></div></header>
<main class="stage">
<div style="width:min(430px,100%)">
<?php if ($flash): ?><div class="<?= h($flash['type'] ?? 'flash') ?>"><?= h($flash['message'] ?? '') ?></div><?php endif; ?>
<section class="login-card">
<div class="login-logo"><?= $logo ?></div>
<h1>შესვლა</h1>
<form class="stack" method="post" action="/login">
<input type="hidden" name="action" value="login">
<label>მომხმარებელი<input name="username" autocomplete="username" required autofocus></label>
<label>პაროლი<input name="password" type="password" autocomplete="current-password" required></label>
<button type="submit">შესვლა</button>
</form>
</section>
</div>
</main>
</body>
</html>

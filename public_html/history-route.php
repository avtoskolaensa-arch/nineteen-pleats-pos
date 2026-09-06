<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/includes/business-day.php';

function garbalia_history_patch(string $source, bool $admin): string {
    $replace = static function (string $old, string $new, string $label) use (&$source): void {
        $count = 0;
        $source = str_replace($old, $new, $source, $count);
        if ($count === 0) error_log('GARBALIA history patch missed: ' . $label);
    };

    if ($admin) {
        $replace(
            "    \$today = date('Y-m-d');\n    \$monthStart = date('Y-m-01');",
            "    \$today = garbalia_business_date();\n    \$monthStart = garbalia_business_month_start(\$today);",
            'admin logical dates'
        );
        $replace(
            "    \$params = [\$from . ' 00:00:00', \$to . ' 23:59:59'];",
            "    [\$historyStartDateTime, \$historyEndDateTime] = garbalia_business_range(\$from, \$to);\n    \$params = [\$historyStartDateTime, \$historyEndDateTime];",
            'admin range'
        );
        $source = str_replace("date('Y-m-d', strtotime('-1 day'))", 'garbalia_business_date_shift(-1)', $source);
        $source = str_replace(
            'მხოლოდ ადმინისტრატორისთვის — დახურული მაგიდები, პროდუქტის ძებნა, მომხმარებელი და Excel ჩამოტვირთვა.',
            'მხოლოდ ადმინისტრატორისთვის — ოპერაციული დღე ითვლება 04:00-დან მომდევნო დღის 03:59-მდე.',
            $source
        );
    } else {
        $replace(
            "\$today = date('Y-m-d');\n\$limitFrom = date('Y-m-d', strtotime('-6 days'));",
            "\$today = garbalia_business_date();\n\$limitFrom = garbalia_business_date_shift(-6);",
            'cashier logical dates'
        );
        $replace(
            "\$params = [\$from . ' 00:00:00', \$to . ' 23:59:59'];",
            "[\$historyStartDateTime, \$historyEndDateTime] = garbalia_business_range(\$from, \$to);\n\$params = [\$historyStartDateTime, \$historyEndDateTime];",
            'cashier range'
        );
        $replace(
            "    \$stmt->execute([\$viewOrderId, \$limitFrom . ' 00:00:00', \$today . ' 23:59:59']);",
            "    [\$detailStartDateTime, \$detailEndDateTime] = garbalia_business_range(\$limitFrom, \$today);\n    \$stmt->execute([\$viewOrderId, \$detailStartDateTime, \$detailEndDateTime]);",
            'cashier detail range'
        );
        $source = str_replace("date('Y-m-d', strtotime('-1 day'))", 'garbalia_business_date_shift(-1)', $source);
        $source = str_replace(
            'მოლარის წვდომა — ბოლო 7 დღის ანგარიშების ნახვა და ქვითრის ხელახლა დაბეჭდვა.',
            'მოლარის წვდომა — დღე ითვლება 04:00-დან მომდევნო დღის 03:59-მდე.',
            $source
        );
    }

    // Closed and cancelled orders always set closed_at. Avoid COALESCE() on the
    // indexed date column so MySQL can use idx_orders_status_closed efficiently.
    $source = str_replace('COALESCE(o.closed_at, o.created_at)', 'o.closed_at', $source);
    $source = str_replace('COALESCE(o.closed_at,o.created_at)', 'o.closed_at', $source);
    return $source;
}

function garbalia_history_runtime(string $sourceFile, string $cacheFile, bool $admin): void {
    $freshness = max(
        (int)@filemtime($sourceFile),
        (int)@filemtime(__DIR__ . '/includes/business-day.php'),
        (int)@filemtime(__FILE__)
    );

    if (is_file($cacheFile) && (int)@filemtime($cacheFile) >= $freshness) {
        require $cacheFile;
        return;
    }

    $source = file_get_contents($sourceFile);
    if ($source === false) {
        http_response_code(500);
        exit('History source could not be loaded.');
    }
    $source = garbalia_history_patch($source, $admin);

    $tmp = $cacheFile . '.tmp-' . getmypid();
    $written = @file_put_contents($tmp, $source, LOCK_EX);
    if ($written !== false && @rename($tmp, $cacheFile)) {
        @chmod($cacheFile, 0640);
        if (function_exists('opcache_invalidate')) @opcache_invalidate($cacheFile, true);
        require $cacheFile;
        return;
    }
    @unlink($tmp);

    // Read-only hosting fallback: still works, only without the runtime-cache speedup.
    eval('?>' . $source);
}

$admin = (($_SESSION['user']['role'] ?? '') === 'admin');
if ($admin) {
    $_GET['page'] = 'history';
    garbalia_history_runtime(__DIR__ . '/index.php', __DIR__ . '/.runtime-history-admin.php', true);
} else {
    garbalia_history_runtime(__DIR__ . '/history.php', __DIR__ . '/.runtime-history-cashier.php', false);
}

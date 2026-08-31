<?php

function ensure_product_cost_schema(): void {
    // Installed by database/schema.sql. Item cost is copied explicitly on insert.
}

function product_unit_profit(array $product): float {
    return round((float)($product['price'] ?? 0) - (float)($product['cost'] ?? 0), 2);
}

function product_margin_percent(array $product): float {
    $price = (float)($product['price'] ?? 0);
    if ($price <= 0) return 0.0;
    return round(product_unit_profit($product) / $price * 100, 1);
}

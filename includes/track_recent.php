<?php
function track_recent($productName, $productLink) {
    $recent = isset($_COOKIE['recent_products'])
        ? json_decode($_COOKIE['recent_products'], true) : [];

    // remove if already present
    $recent = array_values(array_filter($recent, fn($p) => $p['name'] !== $productName));

    // add to front
    array_unshift($recent, ['name' => $productName, 'link' => $productLink]);

    // keep only last 5
    $recent = array_slice($recent, 0, 5);

    // 7 days, site-wide
    setcookie('recent_products', json_encode($recent), time() + 7*24*60*60, "/");
}

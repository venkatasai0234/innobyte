<?php
function track_most($productName) {
    $counts = isset($_COOKIE['product_counts'])
        ? json_decode($_COOKIE['product_counts'], true) : [];

    $counts[$productName] = ($counts[$productName] ?? 0) + 1;

    // 7 days, site-wide
    setcookie('product_counts', json_encode($counts), time() + 7*24*60*60, "/");
}

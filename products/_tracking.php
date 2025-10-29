<?php
/* ==========================================================
   InnoByte Product Tracking System
   Tracks:
   ✅ Recently Visited (last 5)
   ✅ Most Visited (top by visits)
   Works across the entire site.
   Now set to Pakistan Standard Time (Asia/Karachi)
========================================================== */

date_default_timezone_set('America/Los_Angeles'); // ✅ Set timezone to Pakistan Standard Time

/* ---------- Helper: Safely read a JSON cookie ---------- */
function _read_json_cookie($name) {
    if (empty($_COOKIE[$name])) return [];
    $decoded = json_decode($_COOKIE[$name], true);
    return (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) ? $decoded : [];
}

/* ---------- Helper: Write JSON cookie globally ---------- */
function _write_json_cookie($name, $value) {
    // Write cookie accessible across all folders for 7 days
    setcookie(
        $name,
        json_encode($value, JSON_UNESCAPED_SLASHES),
        time() + (7 * 24 * 60 * 60), // expires in 7 days
        "/",      // ✅ global path
        "",       // domain (default current)
        false,    // allow both http and https
        true      // HttpOnly
    );
}

/* ---------------------------------------------------------
   ✅ Track Recently Visited Products
--------------------------------------------------------- */
function track_recent($name, $link) {
    $recent = _read_json_cookie('recent_products');

    // If cookie missing or invalid
    if (!is_array($recent)) $recent = [];

    // Normalize old values
    $fixed = [];
    foreach ($recent as $item) {
        if (is_string($item)) {
            $fixed[] = [
                'name' => $item,
                'link' => $item,
                'ts'   => time()
            ];
        } elseif (is_array($item) && isset($item['name'])) {
            $fixed[] = [
                'name' => $item['name'],
                'link' => $item['link'] ?? '#',
                'ts'   => isset($item['ts']) ? (int)$item['ts'] : time()
            ];
        }
    }
    $recent = $fixed;

    // Remove duplicate entries for same product
    $recent = array_values(array_filter(
        $recent,
        fn($p) => ($p['name'] ?? '') !== $name
    ));

    // Add current product at the top with timestamp
    array_unshift($recent, [
        'name' => $name,
        'link' => $link,
        'ts'   => time() // exact second of visit (in PST)
    ]);

    // Keep only last 5 entries
    $recent = array_slice($recent, 0, 5);

    // Write cookie
    _write_json_cookie('recent_products', $recent);
}

/* ---------------------------------------------------------
   ✅ Track Most Visited Products
--------------------------------------------------------- */
function track_most($name, $link) {
    $counts = _read_json_cookie('product_counts');

    // Normalize old data
    foreach ($counts as $key => $val) {
        if (is_numeric($val)) {
            $counts[$key] = [
                'visits' => (int)$val,
                'link'   => $link,
                'last'   => time()
            ];
        }
    }

    // Initialize entry if it doesn’t exist
    if (!isset($counts[$name]) || !is_array($counts[$name])) {
        $counts[$name] = [
            'visits' => 0,
            'link'   => $link,
            'last'   => time()
        ];
    }

    // Increment visit count and record last-visit timestamp
    $counts[$name]['visits']++;
    $counts[$name]['link'] = $link;
    $counts[$name]['last'] = time(); // saved in PST

    // Write cookie
    _write_json_cookie('product_counts', $counts);
}

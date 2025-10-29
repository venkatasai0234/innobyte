<?php
// include tracking helpers (ensure _tracking.php is inside /products/)
require_once __DIR__ . '/_tracking.php';

// Product details
$productName = 'IoT Flow Sensor';
$productLink = 'product2.php';

// Track visits
track_recent($productName, $productLink);
track_most($productName, $productLink);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title><?= htmlspecialchars($productName) ?> — InnoByte</title>
  <link rel="icon" type="image/svg+xml" href="../logo.svg">
  <style>
    :root {
      --text:#0f172a;--text-strong:#020617;--muted:#475569;
      --accent:#3b82f6;--accent-2:#22c1a7;--radius:16px;
    }
    *{box-sizing:border-box;margin:0;padding:0}
    body{
      background:
        linear-gradient(rgba(255,255,255,0.7),rgba(255,255,255,0.7)),
        url('https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=1600&q=80'),
        url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1600&q=80');
      background-blend-mode:lighten,overlay,normal;
      background-size:cover;background-attachment:fixed;
      font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif;
      color:var(--text);line-height:1.6;
      animation:bgShift 60s ease-in-out infinite alternate;
      min-height:100vh;display:flex;flex-direction:column;align-items:center;padding:40px 20px;
    }
    @keyframes bgShift{0%{background-position:center top}100%{background-position:center bottom}}
    .card{
      background:rgba(255,255,255,0.65);
      border-radius:20px;
      backdrop-filter:blur(12px)saturate(160%);
      box-shadow:0 8px 32px rgba(0,0,0,0.2);
      padding:40px;
      max-width:700px;
      width:100%;
      text-align:center;
    }
    h1{color:var(--text-strong);margin-bottom:12px;}
    .price{color:var(--accent);font-weight:700;margin-bottom:14px;}
    p{color:var(--muted);margin-bottom:18px;}
    img{width:100%;max-width:700px;border-radius:16px;box-shadow:0 6px 18px rgba(0,0,0,0.15);margin-bottom:20px;}
    a.btn{
      display:inline-block;margin:6px;text-decoration:none;font-weight:700;color:#fff;
      padding:12px 22px;border-radius:999px;transition:all .3s ease;
    }
    .back-btn{background:linear-gradient(90deg,#3b82f6,#22c1a7);}
    .recent-btn{background:linear-gradient(90deg,#16a34a,#22c55e);}
    .most-btn{background:linear-gradient(90deg,#ef4444,#dc2626);}
    a.btn:hover{transform:translateY(-2px);box-shadow:0 4px 12px rgba(0,0,0,0.2);}
    footer{text-align:center;color:#475569;margin-top:40px;font-size:0.9rem;}
  </style>
</head>
<body>
  <div class="card">
    <h1><?= htmlspecialchars($productName) ?></h1>
    <p class="price">From $249</p>
    <p>
      High-precision IoT flow measurement device ideal for industrial and
      municipal water systems with wireless connectivity.
    </p>
    <img src="https://images.unsplash.com/photo-1616763355544-3fddbc2327ff?auto=format&fit=crop&w=1200&q=80" 
         alt="<?= htmlspecialchars($productName) ?>">

    <div>
      <a href="../products.html" class="btn back-btn">← Back to Products</a>
      <a href="recent.php" class="btn recent-btn">📋 Recently Visited</a>
      <a href="most.php" class="btn most-btn">🏆 Most Visited</a>
    </div>
  </div>

  <footer>© <?= date('Y') ?> InnoByte — Innovative, reliable web software.</footer>
</body>
</html>

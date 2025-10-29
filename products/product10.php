<?php
require_once __DIR__ . '/_tracking.php';

$productName = 'SLA Support Plan';
$productLink = 'product10.php';

// Track product visits
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
      --text: #0f172a;
      --muted: #475569;
      --accent: #3b82f6;
      --accent-2: #22c1a7;
      --highlight: #f97316;
      --radius: 16px;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    /* ===== Background ===== */
    body {
      background:
        linear-gradient(rgba(255,255,255,0.7), rgba(255,255,255,0.7)),
        url('https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=1600&q=80'),
        url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1600&q=80');
      background-blend-mode: lighten, overlay, normal;
      background-size: cover;
      background-attachment: fixed;
      color: var(--text);
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
      line-height: 1.6;
      animation: bgShift 60s ease-in-out infinite alternate;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    @keyframes bgShift {
      0% { background-position: center top; }
      100% { background-position: center bottom; }
    }

    main.container {
      flex: 1;
      max-width: 1000px;
      margin: 0 auto;
      padding: 32px 16px;
    }

    /* ===== Navbar ===== */
    .nav {
      width: 952px;
      height: 61px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 16px;
      margin: 0 auto;
      border-bottom: 1px solid #dbe4ef;
      background: rgba(255,255,255,0.85);
      backdrop-filter: blur(10px);
      border-radius: 0 0 14px 14px;
      box-shadow: 0 2px 14px rgba(0,0,0,0.06);
    }

    .logo {
      display: flex;
      align-items: center;
      gap: 10px;
      font-weight: 800;
      color: var(--text);
      text-decoration: none;
      font-size: 22px;
    }

    .logo svg { width: 28px; height: 28px; }

    .links a {
      color: var(--muted);
      text-decoration: none;
      padding: 8px 12px;
      border-radius: 999px;
      transition: all 0.3s ease;
    }

    .links a:hover,
    .links a.active {
      background: linear-gradient(90deg, var(--accent), var(--accent-2));
      color: white;
      box-shadow: 0 4px 10px rgba(34,193,195,0.3);
    }

    /* ===== Product Section ===== */
    .product-card {
      background: rgba(255,255,255,0.8);
      border-radius: var(--radius);
      backdrop-filter: blur(10px) saturate(160%);
      box-shadow: 0 10px 30px rgba(0,0,0,0.15);
      padding: 40px;
      margin-top: 60px;
      text-align: center;
    }

    .product-card h1 {
      background: linear-gradient(90deg, var(--accent), var(--highlight), var(--accent-2));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      font-size: 2rem;
      font-weight: 800;
      margin-bottom: 12px;
    }

    .price {
      color: var(--accent);
      font-weight: 700;
      margin-bottom: 10px;
    }

    .product-card p {
      color: var(--muted);
      margin-bottom: 16px;
      font-size: 1rem;
    }

    img {
      width: 100%;
      max-width: 700px;
      border-radius: var(--radius);
      box-shadow: 0 6px 18px rgba(0,0,0,0.15);
      margin: 20px 0;
    }

    .buttons {
      margin-top: 20px;
    }

    a.btn {
      text-decoration: none;
      font-weight: 700;
      color: #fff;
      padding: 12px 24px;
      border-radius: 999px;
      transition: all 0.3s ease;
      display: inline-block;
      margin: 6px;
    }

    .back-btn { background: linear-gradient(90deg, #3b82f6, #22c1a7); }
    .recent-btn { background: linear-gradient(90deg, #16a34a, #22c55e); }
    .most-btn { background: linear-gradient(90deg, #f97316, #ef4444); }

    a.btn:hover {
      transform: translateY(-3px);
      box-shadow: 0 4px 14px rgba(0,0,0,0.25);
    }

    footer {
      margin-top: 80px;
      padding: 24px;
      color: var(--muted);
      font-size: 14px;
      text-align: center;
      border-top: 1px solid #e2e8f0;
      background: rgba(255,255,255,0.85);
      backdrop-filter: blur(8px);
      border-radius: 12px;
      box-shadow: 0 -2px 12px rgba(0,0,0,0.05);
    }
  </style>
</head>

<body>
  <!-- NAVBAR -->
  <div class="nav">
    <a class="logo" href="../index.html">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" fill="none">
        <rect x="8" y="8" width="16" height="16" fill="#3b82f6" rx="3"/>
        <rect x="40" y="8" width="16" height="16" fill="#22c1a7" rx="3"/>
        <rect x="8" y="40" width="16" height="16" fill="#22c1a7" rx="3"/>
        <rect x="40" y="40" width="16" height="16" fill="#f97316" rx="3"/>
      </svg>
      <span>InnoByte</span>
    </a>
    <div class="links">
      <a href="../index.html">Home</a>
      <a href="../about.html">About</a>
      <a class="active" href="../products.html">Products/Services</a>
      <a href="../news.html">News</a>
      <a href="../contact.php">Contacts</a>
      <a href="../login.php">Admin Login</a>
    </div>
  </div>

  <!-- PRODUCT SECTION -->
  <main class="container">
    <section class="product-card">
      <h1><?= htmlspecialchars($productName) ?></h1>
      <p class="price">From $99/month</p>
      <p>Premium service plan offering 24/7 technical support, uptime monitoring, and SLA-backed incident response for enterprise customers.</p>

      <!-- ✅ Product image -->
      <img src="https://images.unsplash.com/photo-1551836022-d5d88e9218df?auto=format&fit=crop&w=1200&q=80"
           alt="<?= htmlspecialchars($productName) ?>">

      <div class="buttons">
        <a href="../products.html" class="btn back-btn">← Back to Products</a>
        <a href="recent.php" class="btn recent-btn">📋 Recently Visited</a>
        <a href="most.php" class="btn most-btn">🏆 Most Visited</a>
      </div>
    </section>
  </main>

  <footer>© <?= date('Y') ?> InnoByte — Innovative, reliable web software.</footer>
</body>
</html>

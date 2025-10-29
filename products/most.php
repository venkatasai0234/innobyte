<?php
// ✅ Set timezone to California, USA (Pacific Time)
date_default_timezone_set('America/Los_Angeles');

// --- Safely decode and normalize the "product_counts" cookie ---
$top = [];

if (!empty($_COOKIE['product_counts'])) {
    $decoded = json_decode($_COOKIE['product_counts'], true);

    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
        $cleaned = [];

        foreach ($decoded as $name => $data) {
            if (!is_string($name) || trim($name) === '') continue;

            // Convert legacy numeric-only values
            if (is_numeric($data)) {
                $cleaned[$name] = [
                    'visits' => (int)$data,
                    'link'   => '#',
                    'last'   => 0
                ];
            } elseif (is_array($data)) {
                $cleaned[$name] = [
                    'visits' => isset($data['visits']) ? (int)$data['visits'] : 0,
                    'link'   => $data['link'] ?? '#',
                    'last'   => isset($data['last']) ? (int)$data['last'] : 0
                ];
            }
        }

        // Sort products by visit count (descending)
        uasort($cleaned, fn($a, $b) => ($b['visits'] ?? 0) <=> ($a['visits'] ?? 0));

        // Keep top 5
        $top = array_slice($cleaned, 0, 5, true);
    }
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<title>Most Visited Products — InnoByte</title>
<link rel="icon" type="image/svg+xml" href="../logo.svg">

<style>
  :root {
    --text: #0f172a;
    --muted: #475569;
    --accent: #3b82f6;
    --accent-2: #22c1a7;
    --highlight: #f97316;
    --radius: 18px;
  }

  * { box-sizing: border-box; margin: 0; padding: 0; }

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

  /* ===== NAVBAR ===== */
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

  /* ===== MAIN CARD ===== */
  .card {
    background: rgba(255,255,255,0.85);
    border-radius: var(--radius);
    backdrop-filter: blur(12px) saturate(160%);
    box-shadow: 0 8px 32px rgba(0,0,0,0.15);
    border: 1px solid rgba(255,255,255,0.35);
    padding: 40px;
    max-width: 850px;
    width: 100%;
    margin: 60px auto 0 auto;
  }

  h1 {
    text-align: center;
    font-size: 2rem;
    background: linear-gradient(90deg, var(--accent), var(--highlight), var(--accent-2));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    font-weight: 800;
    margin-bottom: 8px;
  }

  h2 {
    text-align: center;
    color: var(--muted);
    font-weight: 500;
    margin-bottom: 30px;
  }

  /* ===== ITEM LIST ===== */
  .item {
    background: rgba(255,255,255,0.96);
    border-radius: 14px;
    padding: 18px 24px;
    margin-bottom: 18px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 4px 14px rgba(0,0,0,0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }

  .item:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 24px rgba(0,0,0,0.12);
  }

  .rank {
    background: linear-gradient(135deg, var(--accent), var(--highlight));
    color: #fff;
    font-weight: 700;
    border-radius: 50%;
    width: 38px;
    height: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 16px;
  }

  .info {
    flex: 1;
  }

  .info h3 {
    margin: 0;
    color: var(--text);
    font-size: 1.1rem;
  }

  .info p {
    color: var(--muted);
    font-size: 0.9rem;
    margin-top: 4px;
  }

  .actions {
    text-align: right;
  }

  .visits {
    background: var(--accent);
    color: #fff;
    border-radius: 999px;
    padding: 4px 12px;
    font-weight: 600;
    font-size: 0.9rem;
  }

  .visit-btn {
    text-decoration: none;
    background: linear-gradient(90deg, var(--highlight), var(--accent));
    color: #fff;
    padding: 6px 14px;
    border-radius: 999px;
    font-weight: 600;
    margin-left: 8px;
    transition: all 0.3s ease;
  }

  .visit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
  }

  .buttons {
    text-align: center;
    margin-top: 40px;
  }

  .btn {
    text-decoration: none;
    font-weight: 700;
    color: #fff;
    padding: 14px 28px;
    border-radius: 999px;
    margin: 0 10px;
    transition: all 0.3s ease;
  }

  .back-btn { background: linear-gradient(90deg, #3b82f6, #22c1a7); }
  .recent-btn { background: linear-gradient(90deg, #16a34a, #22c55e); }

  .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.25);
  }

  footer {
    text-align: center;
    color: var(--muted);
    margin-top: 50px;
    font-size: 0.9rem;
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
        <rect x="40" y="40" width="16" height="16" fill="#3b82f6" rx="3"/>
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

  <!-- MAIN CONTENT -->
  <main class="container">
    <div class="card">
      <h1>🏆 Most Visited Products</h1>
      <h2>Your Top 5 Most Viewed Products</h2>

      <?php if (!empty($top)): ?>
        <?php $rank = 1; foreach ($top as $name => $data): ?>
          <div class="item">
            <div class="rank">#<?= $rank ?></div>
            <div class="info">
              <h3><?= htmlspecialchars($name) ?></h3>
              <?php if (!empty($data['last'])): ?>
                <p>Last visited: <?= date('M j, Y g:i:s A', (int)$data['last']) ?></p>
              <?php endif; ?>
            </div>
            <div class="actions">
              <span class="visits"><?= $data['visits'] ?> <?= $data['visits'] == 1 ? 'visit' : 'visits' ?></span>
              <a class="visit-btn" href="<?= htmlspecialchars($data['link']) ?>">Visit Again</a>
            </div>
          </div>
        <?php $rank++; endforeach; ?>
      <?php else: ?>
        <p style="text-align:center;color:#475569;">No visit data yet. Visit some products first!</p>
      <?php endif; ?>

      <div class="buttons">
        <a href="../products.html" class="btn back-btn">← Back to All Products</a>
        <a href="recent.php" class="btn recent-btn">📋 Recently Visited</a>
      </div>
    </div>
  </main>

  <footer>© <?= date('Y') ?> InnoByte — Innovative, reliable web software.</footer>
</body>
</html>

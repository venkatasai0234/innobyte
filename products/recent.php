<?php
// ✅ Set timezone to California, USA (Pacific Time)
date_default_timezone_set('America/Los_Angeles');

// --- Safely decode and normalize the "recent_products" cookie ---
$recent = [];

if (!empty($_COOKIE['recent_products'])) {
    $decoded = json_decode($_COOKIE['recent_products'], true);
    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
        $cleaned = [];
        foreach ($decoded as $item) {
            if (is_string($item)) {
                $cleaned[] = [
                    'name' => ucfirst(pathinfo($item, PATHINFO_FILENAME)),
                    'link' => $item,
                    'ts'   => 0
                ];
            } elseif (is_array($item) && !empty($item['name'])) {
                $cleaned[] = [
                    'name' => $item['name'],
                    'link' => $item['link'] ?? '#',
                    'ts'   => isset($item['ts']) ? (int)$item['ts'] : 0
                ];
            }
        }
        $recent = $cleaned;
    }
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<title>Recently Visited Products — InnoByte</title>
<link rel="icon" type="image/svg+xml" href="../logo.svg">
<style>
:root{
  --text:#0f172a;--muted:#475569;--accent:#3b82f6;
  --accent-2:#22c1a7;--highlight:#f97316;--radius:16px;
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
  min-height:100vh;display:flex;flex-direction:column;
}
@keyframes bgShift{0%{background-position:center top}100%{background-position:center bottom}}
main.container{flex:1;max-width:1000px;margin:0 auto;padding:32px 16px}

/* ===== NAVBAR ===== */
.nav{width:952px;height:61px;display:flex;align-items:center;justify-content:space-between;
  padding:0 16px;margin:0 auto;border-bottom:1px solid #dbe4ef;
  background:rgba(255,255,255,0.85);backdrop-filter:blur(10px);
  border-radius:0 0 14px 14px;box-shadow:0 2px 14px rgba(0,0,0,0.06);}
.logo{display:flex;align-items:center;gap:10px;font-weight:800;
  color:#020617;text-decoration:none;font-size:22px;}
.logo svg{width:28px;height:28px}
.links a{color:var(--muted);text-decoration:none;padding:8px 12px;border-radius:999px;transition:.3s;}
.links a:hover,.links a.active{background:linear-gradient(90deg,var(--accent),var(--accent-2));color:#fff;box-shadow:0 4px 10px rgba(34,193,195,0.3)}

/* ===== CARD ===== */
.card{background:rgba(255,255,255,0.65);border-radius:20px;
  backdrop-filter:blur(12px)saturate(160%);box-shadow:0 8px 32px rgba(0,0,0,0.2);
  padding:40px;max-width:700px;width:100%;text-align:center;margin-top:60px;}
h1{font-size:1.8rem;font-weight:800;color:#020617;margin-bottom:25px;display:flex;
  align-items:center;justify-content:center;gap:10px;}
h1::before{content:"🧾";}
ul{list-style:none;padding:0;margin:0 auto 20px auto;max-width:500px;}
li{margin:15px 0;font-size:1.05rem;color:var(--accent-2);font-weight:600;}
li a{color:var(--accent-2);text-decoration:none;}
li a:hover{color:var(--accent);text-decoration:underline;}
.time{display:block;color:var(--muted);font-size:0.85rem;margin-top:4px;}

.buttons{text-align:center;margin-top:30px;}
.btn{text-decoration:none;font-weight:700;color:#fff;padding:14px 28px;border-radius:999px;
  transition:.3s;display:inline-block;margin:6px;}
.back-btn{background:linear-gradient(90deg,#f97316,#dc2626)}
.most-btn{background:linear-gradient(90deg,#22c1a7,#16a34a)}
.btn:hover{transform:translateY(-2px);box-shadow:0 4px 12px rgba(0,0,0,0.2)}

footer{text-align:center;color:var(--muted);margin-top:40px;font-size:0.9rem;}
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

  <!-- CONTENT -->
  <main class="container">
    <div class="card">
      <h1>Recently Visited Products</h1>

      <?php if (!empty($recent)): ?>
        <ul>
          <?php foreach ($recent as $p): ?>
            <li>
              <a href="<?= htmlspecialchars($p['link']) ?>">
                <?= htmlspecialchars($p['name']) ?>
              </a>
              <?php if (!empty($p['ts'])): ?>
                <span class="time">Visited: <?= date('M j, Y g:i:s A', (int)$p['ts']) ?></span>
              <?php endif; ?>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php else: ?>
        <p style="color:#475569;">You haven’t visited any products yet.</p>
      <?php endif; ?>

      <div class="buttons">
        <a href="../products.html" class="btn back-btn">← Back to All Products</a>
        <a href="most.php" class="btn most-btn">🏆 View Most Visited</a>
      </div>
    </div>
  </main>

  <footer>© <?= date('Y') ?> InnoByte — Innovative, reliable web software.</footer>
</body>
</html>

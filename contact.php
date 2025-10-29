<?php
function read_contacts(string $path): array {
    $contacts = [];

    if (!is_readable($path)) {
        return $contacts;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        $trim = trim($line);
        if ($trim === "" || substr($trim, 0, 1) === "#" || substr($trim, 0, 2) === "//") {
            continue;
        }

        $parts = explode("|", $trim);
        $parts = array_pad($parts, 4, "");

        $contacts[] = [
            "name"  => htmlspecialchars(trim($parts[0]), ENT_QUOTES, 'UTF-8'),
            "role"  => htmlspecialchars(trim($parts[1]), ENT_QUOTES, 'UTF-8'),
            "email" => htmlspecialchars(trim($parts[2]), ENT_QUOTES, 'UTF-8'),
            "phone" => htmlspecialchars(trim($parts[3]), ENT_QUOTES, 'UTF-8')
        ];
    }

    return $contacts;
}

$contacts = read_contacts(__DIR__ . "/data/contacts.txt");
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>InnoByte — Contacts</title>
  <link rel="icon" type="image/svg+xml" href="logo.svg">

  <style>
    /* ========= BRIGHT INNOVATIVE TECH THEME ========= */
    :root {
      --bg: #ffffff;
      --text: #0f172a;
      --text-strong: #020617;
      --muted: #475569;
      --accent: #3b82f6;
      --accent-2: #22c1a7;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    /* ========= BODY BACKGROUND + LAYOUT ========= */
    body {
      background:
        linear-gradient(rgba(255,255,255,0.7), rgba(255,255,255,0.7)),
        url('https://images.unsplash.com/photo-1505685296765-3a2736de412f?auto=format&fit=crop&w=1600&q=80'),
        url('https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=1600&q=80'),
        url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1600&q=80');
      background-blend-mode: lighten, overlay, overlay, normal;
      background-size: cover;
      background-attachment: fixed;
      background-position: center;
      color: var(--text);
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
      line-height: 1.6;
      animation: bgShift 60s ease-in-out infinite alternate;

      display: flex;
      flex-direction: column;
      min-height: 100vh; /* Footer stays at bottom */
    }

    @keyframes bgShift {
      0% { background-position: center top; }
      100% { background-position: center bottom; }
    }

    main.container {
      flex: 1;
      max-width: 1000px;
      margin: 0 auto;
      padding: 24px;
    }

    /* ========= NAVIGATION ========= */
    .nav {
  width: 952px;
  height: 61px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 16px;
  margin: 0 auto; /* Center horizontally */
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
      color: var(--text-strong);
      text-decoration: none;
    }

    .logo svg { width: 28px; height: 28px; }

    .links a {
      color: var(--muted);
      text-decoration: none;
      padding: 8px 12px;
      border-radius: 999px;
      transition: all .2s ease;
    }

    .links a:hover,
    .links a.active {
      background: linear-gradient(90deg, var(--accent), var(--accent-2));
      color: white;
    }

    /* ========= CONTACT SECTION ========= */
    h1 {
      font-size: clamp(28px, 4vw, 40px);
      color: var(--text-strong);
      margin-bottom: 12px;
      text-shadow: 0 1px 3px rgba(255,255,255,0.6);
      text-align: center;
    }

    .contact-note {
      font-size: 15px;
      color: var(--muted);
      margin-bottom: 20px;
      text-align: center;
    }

    .table-wrapper {
      background: rgba(255,255,255,0.6);
      border: 1px solid rgba(255,255,255,0.35);
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 8px 32px rgba(0,0,0,0.15);
      backdrop-filter: blur(10px);
    }

    table.table {
      width: 100%;
      border-collapse: collapse;
    }

    .table th, .table td {
      padding: 14px 16px;
      font-size: 15px;
      text-align: left;
    }

    .table th {
      background: linear-gradient(90deg, var(--accent), var(--accent-2));
      color: #ffffff;
      font-weight: 700;
    }

    .table td {
      background: rgba(255,255,255,0.4);
      color: var(--text);
    }

    .table tbody tr:hover {
      background: rgba(255,255,255,0.8);
    }

    .table a {
      color: var(--accent);
      text-decoration: none;
      font-weight: 600;
    }

    .table a:hover {
      color: var(--accent-2);
      text-decoration: underline;
    }

    .back-link-container {
      margin-top: 24px;
      text-align: center;
    }

    .back-link {
      color: white;
      background: linear-gradient(90deg, var(--accent), var(--accent-2));
      text-decoration: none;
      font-weight: 600;
      border: none;
      padding: 10px 20px;
      border-radius: 10px;
      display: inline-block;
      transition: all .2s ease;
      box-shadow: 0 4px 12px rgba(59,130,246,0.3);
    }

    .back-link:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(59,130,246,0.4);
    }

    /* ========= FOOTER ========= */
    footer {
      color: var(--muted);
      font-size: 14px;
      text-align: center;
      border-top: 1px solid #e2e8f0;
      background: rgba(255,255,255,0.85);
      backdrop-filter: blur(8px);
      border-radius: 12px 12px 0 0;
      box-shadow: 0 -2px 12px rgba(0,0,0,0.05);
      padding: 20px 0;
      margin-top: auto; /* 👈 Keeps footer pinned to bottom */
    }
  </style>
</head>

<body>
  <main class="container">
    <!-- Navigation -->
    <div class="nav">
      <a class="logo" href="index.html">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" fill="none" role="img" aria-label="InnoByte Logo">
          <rect x="8" y="8" width="16" height="16" fill="#3b82f6" rx="3"/>
          <rect x="40" y="8" width="16" height="16" fill="#22c1a7" rx="3"/>
          <rect x="8" y="40" width="16" height="16" fill="#22c1a7" rx="3"/>
          <rect x="40" y="40" width="16" height="16" fill="#3b82f6" rx="3"/>
        </svg>
        <span>InnoByte</span>
      </a>
      <div class="links">
        <a href="index.html">Home</a>
        <a href="about.html">About</a>
        <a href="products.html">Products/Services</a>
        <a href="news.html">News</a>
        <a class="active" href="contact.php">Contacts</a>
        <a href="login.php">Admin Login</a>
      </div>
    </div>

    <!-- Contact Section -->
    <h1>Company Contacts</h1>
    <p class="contact-note">Our contacts are dynamically loaded using PHP.</p>

    <?php if (empty($contacts)): ?>
      <div class="table-wrapper">
        <p class="contact-note">No contacts available. Please check <code>data/contacts.txt</code>.</p>
      </div>
    <?php else: ?>
      <div class="table-wrapper">
        <table class="table">
          <thead>
            <tr>
              <th>Name</th>
              <th>Role</th>
              <th>Email</th>
              <th>Phone</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($contacts as $c): ?>
              <tr>
                <td><?= $c["name"] ?></td>
                <td><?= $c["role"] ?></td>
                <td>
                  <?php if (!empty($c["email"])): ?>
                    <a href="mailto:<?= $c["email"] ?>"><?= $c["email"] ?></a>
                  <?php else: ?>
                    &mdash;
                  <?php endif; ?>
                </td>
                <td><?= !empty($c["phone"]) ? $c["phone"] : "&mdash;" ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>

    <div class="back-link-container">
      <a class="back-link" href="index.html">← Back to Home</a>
    </div>
  </main>

  <!-- Footer -->
  <footer>
    © 2025 InnoByte — Innovative, reliable web software.
  </footer>
</body>
</html>

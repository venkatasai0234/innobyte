<?php
require_once __DIR__ . '/includes/auth.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user = $_POST['username'] ?? '';
  $pass = $_POST['password'] ?? '';
  $csrf = $_POST[CSRF_FIELD] ?? '';
  if (!verify_csrf($csrf)) {
    $error = "Invalid CSRF token.";
  } elseif (login($user, $pass)) {
    $_SESSION['admin_logged_in'] = true;
    header('Location: ' . base_url('secure/users.php'));
    exit;
  } else {
    $error = "Invalid username or password.";
  }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>InnoByte — Admin Login</title>
  <link rel="icon" type="image/svg+xml" href="logo.svg">
  <style>
    :root {
      --bg: #ffffff;
      --text: #0f172a;
      --text-strong: #020617;
      --muted: #475569;
      --accent: #3b82f6;
      --accent-2: #22c1a7;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }

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
    }

    @keyframes bgShift {
      0% { background-position: center top; }
      100% { background-position: center bottom; }
    }

    .container {
      max-width: 960px;
      margin: 0 auto;
      padding: 24px;
    }

    .nav {
      width: 952px;
      height: 61px;
      margin: 0 auto;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 16px;
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
      transition: 0.2s;
    }

    .links a:hover,
    .links a.active {
      background: linear-gradient(90deg, var(--accent), var(--accent-2));
      color: white;
    }

    .login-wrapper {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 75vh;
    }

    .login-card {
      background: rgba(255,255,255,0.6);
      border: 1px solid rgba(255,255,255,0.35);
      border-radius: 16px;
      padding: 32px;
      width: 100%;
      max-width: 420px;
      backdrop-filter: blur(10px) saturate(160%);
      -webkit-backdrop-filter: blur(10px) saturate(160%);
      box-shadow: 0 8px 32px rgba(0,0,0,0.15);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .login-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 40px rgba(59,130,246,0.3);
    }

    h2 {
      text-align: center;
      margin-bottom: 24px;
      font-size: 24px;
      color: var(--text-strong);
      text-shadow: 0 1px 3px rgba(255,255,255,0.6);
    }

    label {
      display: block;
      margin-top: 16px;
      font-weight: 600;
      color: var(--text);
    }

    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 10px;
      border-radius: 8px;
      background: rgba(255,255,255,0.5);
      border: 1px solid rgba(59,130,246,0.3);
      margin-top: 6px;
      color: var(--text);
      outline: none;
      font-size: 15px;
    }

    input:focus {
      border-color: var(--accent);
      box-shadow: 0 0 8px rgba(59,130,246,0.3);
    }

    button {
      width: 100%;
      margin-top: 24px;
      padding: 12px;
      background: linear-gradient(90deg, var(--accent), var(--accent-2));
      color: white;
      font-weight: bold;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      box-shadow: 0 6px 12px rgba(106,166,255,.3);
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    button:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(106,166,255,.4);
    }

    .error {
      margin-top: 12px;
      background: rgba(255,0,0,0.1);
      color: #b91c1c;
      padding: 10px;
      border-radius: 8px;
      text-align: center;
      font-weight: 500;
    }

    .back-link {
      display: block;
      text-align: center;
      color: var(--muted);
      font-size: 14px;
      margin-top: 18px;
      text-decoration: none;
    }

    .back-link:hover {
      text-decoration: underline;
      color: var(--accent);
    }

    footer {
      text-align: center;
      color: var(--muted);
      font-size: 14px;
      padding: 24px;
      border-top: 1px solid #e2e8f0;
      background: rgba(255,255,255,0.85);
      backdrop-filter: blur(8px);
      border-radius: 12px;
      box-shadow: 0 -2px 12px rgba(0,0,0,0.05);
      margin-top: 40px;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="nav">
      <a class="logo" href="/index.html">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" fill="none">
          <rect x="8" y="8" width="16" height="16" fill="#3b82f6" rx="3"/>
          <rect x="40" y="8" width="16" height="16" fill="#22c1a7" rx="3"/>
          <rect x="8" y="40" width="16" height="16" fill="#22c1a7" rx="3"/>
          <rect x="40" y="40" width="16" height="16" fill="#3b82f6" rx="3"/>
        </svg>
        <span>InnoByte</span>
      </a>
      <div class="links">
        <a href="/index.html">Home</a>
        <a href="/about.html">About</a>
        <a href="/products.html">Products/Services</a>
        <a href="/news.html">News</a>
        <a href="/contact.php">Contacts</a>
      </div>
    </div>

    <div class="login-wrapper">
      <form class="login-card" method="post">
        <h2>Admin Login</h2>
        <?php if ($error): ?>
          <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <input type="hidden" name="<?= CSRF_FIELD ?>" value="<?= csrf_token() ?>">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required autofocus>
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Sign In</button>
        <a class="back-link" href="/index.html">← Back to Home</a>
      </form>
    </div>
  </div>

  <footer>
    © 2025 InnoByte — Innovative, reliable web software.
  </footer>
</body>
</html>

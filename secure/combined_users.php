<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();

/**
 * Helper: base_url()
 */
if (!function_exists('base_url')) {
  function base_url($path = '') {
    $root = 'https://innobyte.page.gd/';
    return $root . ltrim($path, '/');
  }
}

/**
 * Fetch local company users (Company A - InnoByte) from MySQL
 */
$host = "sql213.infinityfree.com";
$user = "if0_40063264";
$pass = "0xYzWJnLveiQf"; // Replace with your real InfinityFree control panel password
$dbname = "if0_40063264_usersdb";

$conn = new mysqli($host, $user, $pass, $dbname);
$localUsers = [];

if ($conn->connect_error) {
  die("Database connection failed: " . $conn->connect_error);
}

// Fetch data from your MySQL table
$sql = "SELECT name, role, email, phone FROM users";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $localUsers[] = $row;
  }
}

$conn->close();

/**
 * CURL or file_get_contents fallback to fetch partner companies’ users
 */
function fetchRemoteUsers($url) {
  if (function_exists('curl_init')) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $response = curl_exec($ch);
    curl_close($ch);
  } else {
    // fallback if CURL is disabled
    $response = @file_get_contents($url);
  }

  $data = json_decode($response, true);
  return is_array($data) ? $data : [];
}

// Partner company endpoints (JSON user lists)
$remoteUrls = [
    "https://innobyte.page.gd/secure/combined_users.php"
  "https://mithil-272.infinityfree.me/company/config/db.php", // Company B
  // Add another URL if you have Company C
];

// Combine local + remote users
$allUsers = $localUsers;

foreach ($remoteUrls as $url) {
  $remote = fetchRemoteUsers($url);
  foreach ($remote as $r) {
    $allUsers[] = [
      'name'  => $r['name'] ?? 'N/A',
      'role'  => $r['role'] ?? '—',
      'email' => $r['email'] ?? '',
      'phone' => $r['phone'] ?? '—',
    ];
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Combined User Directory | InnoByte</title>
  <link rel="icon" type="image/svg+xml" href="<?= base_url('logo.svg') ?>">
  <style>
    :root {
      --bg: #f9fafb;
      --card: #ffffff;
      --text: #0f172a;
      --text-strong: #020617;
      --muted: #64748b;
      --accent: #3b82f6;
      --accent-2: #22c1a7;
    }

    body {
      background: var(--bg);
      color: var(--text);
      font-family: system-ui, sans-serif;
      margin: 0;
    }

    .container {
      max-width: 960px;
      margin: 0 auto;
      padding: 24px;
    }

    .nav {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 16px 0;
      border-bottom: 1px solid #e2e8f0;
      background: rgba(255, 255, 255, 0.9);
      backdrop-filter: blur(8px);
      border-radius: 0 0 12px 12px;
      box-shadow: 0 2px 12px rgba(0, 0, 0, 0.03);
    }

    .logo {
      display: flex;
      align-items: center;
      gap: 10px;
      font-weight: 800;
      color: var(--text-strong);
      text-decoration: none;
    }

    .logo svg {
      width: 28px;
      height: 28px;
    }

    .links {
      display: flex;
      gap: 16px;
      align-items: center;
    }

    .links span {
      font-size: 14px;
      color: var(--muted);
    }

    .links a {
      color: var(--muted);
      text-decoration: none;
      padding: 8px 12px;
      border-radius: 999px;
      transition: 0.2s;
    }

    .links a:hover {
      background: linear-gradient(90deg, var(--accent), var(--accent-2));
      color: white;
    }

    h2 {
      font-size: 26px;
      margin: 24px 0 8px;
      color: var(--text-strong);
    }

    .notice {
      font-size: 14px;
      color: var(--muted);
      margin-bottom: 20px;
    }

    .table-wrap {
      overflow-x: auto;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background: var(--card);
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 6px 16px rgba(0, 0, 0, 0.05);
      min-width: 500px;
    }

    th, td {
      padding: 14px 20px;
      border-bottom: 1px solid #e2e8f0;
    }

    th {
      background: #f1f5f9;
      color: var(--accent);
      text-align: left;
      font-weight: 600;
    }

    tr:hover {
      background: #f9fbff;
    }

    .footer-btn {
      display: block;
      margin: 30px auto 0;
      padding: 12px 24px;
      border-radius: 10px;
      text-decoration: none;
      font-weight: bold;
      background: linear-gradient(180deg, var(--accent), var(--accent-2));
      color: #ffffff;
      box-shadow: 0 6px 12px rgba(106, 166, 255, .3);
      transition: all 0.2s ease;
      width: fit-content;
      text-align: center;
    }

    .footer-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(106, 166, 255, .4);
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="nav">
      <a class="logo" href="<?= base_url('index.php') ?>">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" fill="none">
          <rect x="8" y="8" width="16" height="16" fill="#3b82f6" rx="3"/>
          <rect x="40" y="8" width="16" height="16" fill="#22c1a7" rx="3"/>
          <rect x="8" y="40" width="16" height="16" fill="#22c1a7" rx="3"/>
          <rect x="40" y="40" width="16" height="16" fill="#3b82f6" rx="3"/>
        </svg>
        <span>InnoByte</span>
      </a>
      <div class="links">
        <span>Logged in as: <?= htmlspecialchars($_SESSION['admin_username'] ?? 'admin') ?></span>
        <a href="<?= base_url('index.html') ?>">Home</a>
        <a href="<?= base_url('logout.php') ?>">Logout</a>
      </div>
    </div>

    <h2>Combined Team Directory (All Companies)</h2>
    <p class="notice">Showing users from InnoByte (database) and partner companies (via CURL).</p>

    <div class="table-wrap">
      <table>
        <thead>
          <tr><th>Name</th><th>Role</th><th>Email</th><th>Phone</th></tr>
        </thead>
        <tbody>
          <?php foreach ($allUsers as $u): ?>
            <tr>
              <td><?= htmlspecialchars($u['name']) ?></td>
              <td><?= htmlspecialchars($u['role']) ?></td>
              <td>
                <?php if (!empty($u['email'])): ?>
                  <a href="mailto:<?= htmlspecialchars($u['email']) ?>" style="color: var(--accent); text-decoration: none;">
                    <?= htmlspecialchars($u['email']) ?>
                  </a>
                <?php endif; ?>
              </td>
              <td><?= htmlspecialchars($u['phone']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <a class="footer-btn" href="<?= base_url('logout.php') ?>">Logout</a>
  </div>
</body>
</html>

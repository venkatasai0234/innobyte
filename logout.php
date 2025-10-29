<?php
// Start session to access any active login
session_start();

// Clear all session variables
$_SESSION = [];

// Destroy the session itself
session_destroy();

// Also clear the session cookie from browser
if (ini_get("session.use_cookies")) {
  $params = session_get_cookie_params();
  setcookie(
    session_name(),
    '',                // blank value
    time() - 42000,     // expired
    $params["path"],
    $params["domain"],
    $params["secure"],
    $params["httponly"]
  );
}

// Redirect to homepage or login page
header("Location: index.html");
exit;

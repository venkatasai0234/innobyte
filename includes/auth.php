<?php
require_once __DIR__ . '/config.php';

if (!function_exists('str_starts_with')) {
  function str_starts_with($haystack, $needle) {
    return strpos($haystack, $needle) === 0;
  }
}

session_start();

function base_url($path = '') {
  return BASE_URL . '/' . ltrim($path, '/');
}

function csrf_token() {
  if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
  return $_SESSION['csrf'];
}

function verify_csrf($token) {
  return isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $token);
}

function login($user, $pass) {
  if ($user === ADMIN_USER) {
    if (USE_HASHED) {
      return password_verify($pass, ADMIN_PASS_HASH);
    }
    return $pass === ADMIN_PASS;
  }
  return false;
}

function is_logged_in() {
  return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function require_admin() {
  if (!is_logged_in()) {
    header('Location: ' . base_url('login.php'));
    exit;
  }
}

function logout() {
  session_destroy();
}


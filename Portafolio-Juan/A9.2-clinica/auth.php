<?php
session_start();

function is_logged_in(): bool {
  return isset($_SESSION['user']);
}

function require_login(): void {
  if (!is_logged_in()) {
    header('Location: /clinica2/login.php');
    exit;
  }
}

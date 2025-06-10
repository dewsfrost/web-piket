<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['user'])) {
  header("Location: login.html");
  exit;
}

$id = $_SESSION['user']['id'];

// Hapus user dari database
$stmt = $koneksi->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

// Hapus sesi login
session_destroy();

// Redirect ke login
echo "<script>alert('Akun Anda berhasil dihapus.'); window.location.href = 'login.html';</script>";
exit;
?>

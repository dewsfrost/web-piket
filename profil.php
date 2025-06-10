<?php
session_start();
require 'koneksi.php';

// Cek apakah user sudah login dengan session 'user'
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['user']['username'];

// Ambil data user dari database berdasarkan username session
$username_esc = mysqli_real_escape_string($koneksi, $username);
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username_esc' LIMIT 1");
$user = mysqli_fetch_assoc($query);

// Jika user tidak ditemukan, redirect ke login
if (!$user) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Profil Siswa</title>
  <link href="profil.css" rel="stylesheet" />
</head>
<body>
  <div class="container">
    <div class="sidebar">
      <h2>Piket kelas</h2>
      <nav>
        <a href="dashboard.php">Dashboard</a>
        <a href="profil.php" class="active">Profil</a>
        <a href="galeri.html">Galeri</a>
        <a href="jadwal.html">Jadwal</a>
      </nav>
    </div>

    <div class="main">
      <div class="topbar">
        <img src="logo_skensa.png" alt="Logo" class="logo" />
        <div class="top-links">
          <a href="#"><strong>Tentang kami</strong></a>
        </div>
      </div>

      <div class="profile-container">
        <div class="profile-card">
          <div class="profile-image">
            <img src="uploads/<?= htmlspecialchars($user['foto'] ?: 'default.png') ?>" alt="Foto Profil" />
          </div>
          <div class="profile-name"><?= htmlspecialchars($user['nama']) ?></div>
          <hr />
          <div class="profile-details">
            <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
            <p><strong>NIS:</strong> <?= htmlspecialchars($user['nis']) ?></p>
            <p><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
            <p><strong>Password:</strong> ********</p>
          </div>
          <div class="btn-group">
            <button class="logout" onclick="window.location.href='logout.php'">Logout</button>
            <button class="edit" onclick="window.location.href='edit_profil.php'">Edit Profil</button>
          </div>
        </div>
      </div>

      <footer class="footer">
        &copy; 2025 Piket Kelas. All rights reserved.
      </footer>
    </div>
  </div>
</body>
</html>

<?php
session_start();

// Cek apakah user sudah login dengan benar
if (!isset($_SESSION['user']['username'])) {
    header("Location: login.php");
    exit;
}

$role = $_SESSION['user']['role']; // Ambil role dari session
?>


<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - Piket Kelas</title>
  <link rel="stylesheet" href="dashboard.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Rubik:wght@600&display=swap" rel="stylesheet">
</head>
<body>
  <div class="container">
    <aside class="sidebar" style="position: relative;">
      <h2>Piket kelas</h2>
      <nav>
        <a href="dashboard.php">Dashboard</a>
        <a href="profil.php">Profil</a>
        <a href="galeri.html">Galeri</a>
        <a href="jadwal.html">Jadwal</a>
        <?php if ($role === 'admin'): ?>
          <a href="upload_dokumentasi.php">Upload Dokumentasi</a>
        <?php endif; ?>
      </nav>

      <!-- Tombol Hapus Akun -->
      <form method="POST" action="hapus_akun.php" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini? Tindakan ini tidak bisa dibatalkan.')" style="position: absolute; bottom: 20px; left: 10px; right: 10px;">
        <button type="submit" name="hapus" style="background: #ff4d4d; color: white; border: none; padding: 10px; width: 100%; border-radius: 5px; cursor: pointer;">
          Hapus Akun
        </button>
      </form>
    </aside>

    <div class="main">
      <div class="topbar">
        <img src="logo_skensa.png" alt="Logo" class="logo" />
        <div class="top-links">
          <a href="#"><strong>Tentang kami</strong></a>
        </div>
      </div>

      <section class="content">
        <div class="welcome">
          <h1>Selamat<br>datang di web<br>piket kelas</h1>
          <p>Silahkan cek jadwal piket terlebih dahulu</p>
        </div>
        <div class="images-container"> 
          <div class="img-circle circle-1"> 
            <img src="skensa.jpeg" alt="circle image">
          </div>
          <div class="img-circle small circle-2"> 
            <img src="foto kepsek.jpg" alt="circle image">
          </div>
          <div class="img-circle circle-3"> 
            <img src="foto skensa.jpg" alt="circle image">
          </div>
        </div>
      </section>

      <footer class="footer">
        <p>Copyright © 2025 webpiket.</p>
      </footer>
    </div>
  </div>
</body>
</html>

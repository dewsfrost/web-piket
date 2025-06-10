<?php
session_start();
require 'koneksi.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user'])) {
  header("Location: login.html");
  exit;
}

$user_id = $_SESSION['user']['id'];

$stmt = $koneksi->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
  echo "<script>alert('User tidak ditemukan'); window.location.href='login.html';</script>";
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Profil</title>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Rubik:wght@500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="profil.css">
  <link rel="stylesheet" href="edit_profil.css">
</head>
<body>
  <div class="container">
    <div class="sidebar">
      <h2>Piket kelas</h2>
      <nav>
        <a href="dashboard.php">Dashboard</a>
        <a href="profil.php">Profil</a>
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
          <h3>Edit Profil</h3>
          <form method="POST" action="update_profil.php" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $data['id'] ?>">

            <input type="text" name="nama" class="input-text" value="<?= htmlspecialchars($data['nama']) ?>" required>

            <p class="label">Foto Profil Saat Ini:</p>
            <div class="preview">
              <img src="uploads/<?= htmlspecialchars($data['foto']) ?>" alt="Foto Profil" onerror="this.src='foto-user.png';">
            </div>

            <input type="file" name="foto" accept="image/*" class="file-input">

            <div class="btn-group">
              <button type="submit" class="edit">Simpan Perubahan</button>
              <button type="button" class="logout" onclick="window.location.href='profil.php'">Batal</button>
            </div>
          </form>
        </div>
      </div>

      <div class="footer">
        &copy; 2025 Piket Kelas. All rights reserved.
      </div>
    </div>
  </div>
</body>
</html>

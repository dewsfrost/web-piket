<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'koneksi.php';

// Cek login dan role admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    die("Akses ditolak. Hanya admin yang dapat mengunggah dokumentasi.");
}

$uploadError = '';
$uploadSuccess = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        $fileTmpPath = $_FILES['gambar']['tmp_name'];
        $originalName = $_FILES['gambar']['name'];
        $fileSize = $_FILES['gambar']['size'];
        $fileExt = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        // Validasi ekstensi
        if (!in_array($fileExt, $allowedTypes)) {
            $uploadError = "Format file tidak didukung. Gunakan jpg, jpeg, png, atau gif.";
        }
        // Validasi ukuran max 5MB (5*1024*1024 bytes)
        elseif ($fileSize > 5 * 1024 * 1024) {
            $uploadError = "Ukuran file terlalu besar. Maksimal 5MB.";
        } else {
            // Buat nama file unik
            $newFileName = uniqid('img_', true) . '.' . $fileExt;
            $uploadDir = __DIR__ . '/uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $destPath = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                // Simpan ke DB
                $stmt = $koneksi->prepare("INSERT INTO dokumentasi (filename, uploaded_at) VALUES (?, NOW())");
                $stmt->bind_param("s", $newFileName);
                if ($stmt->execute()) {
                    $uploadSuccess = "Upload berhasil!";
                } else {
                    $uploadError = "Gagal menyimpan data ke database: " . $stmt->error;
                    unlink($destPath);
                }
                $stmt->close();
            } else {
                $uploadError = "Gagal memindahkan file.";
            }
        }
    } else {
        $uploadError = "File tidak ditemukan atau terjadi kesalahan saat upload.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Upload Dokumentasi - Piket Kelas</title>
<link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Rubik:wght@500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="upload.css" />
</head>
<body>
  <div class="container">
    <aside class="sidebar">
      <h2>Piket kelas</h2>
      <nav>
        <a href="dashboard.php">Dashboard</a>
        <a href="profil.php">Profil</a>
        <a href="galeri.html">Galeri</a>
        <a href="jadwal.html">Jadwal</a>
        <a href="upload_dokumentasi.php" class="active">Upload Dokumentasi</a>
      </nav>
    </aside>

    <div class="main">
      <div class="topbar">
        <img src="logo_skensa.png" alt="Logo" class="logo" />
        <div class="top-links">
          <a href="#"><strong>Tentang kami</strong></a>
        </div>
      </div>

      <section class="content">
        <?php if ($uploadError): ?>
          <div class="message error"><?= htmlspecialchars($uploadError) ?></div>
        <?php elseif ($uploadSuccess): ?>
          <div class="message success"><?= htmlspecialchars($uploadSuccess) ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
          <label for="gambar">Pilih Gambar (jpg, jpeg, png, gif, max 5MB):</label><br />
          <input type="file" name="gambar" id="gambar" accept=".jpg,.jpeg,.png,.gif" required><br />
          <button type="submit">Upload</button>
        </form>
      </section>

      <footer class="footer">
        &copy; 2025 Piket Kelas. All rights reserved.
      </footer>
    </div>
  </div>
</body>
</html>

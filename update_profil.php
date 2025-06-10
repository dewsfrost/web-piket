<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['user'])) {
  header("Location: login.html");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: edit_profil.php");
  exit;
}

$id   = (int)$_POST['id']; 
$nama = trim($_POST['nama']);


$stmt = $koneksi->prepare("SELECT foto FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$lama = $result->fetch_assoc();

if (!$lama) {
  echo "<script>alert('User tidak ditemukan'); window.location.href='profil.php';</script>";
  exit;
}

$foto_nama = $lama['foto'];


if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
  $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
  $file_tmp = $_FILES['foto']['tmp_name'];
  $file_name = $_FILES['foto']['name'];
  $file_size = $_FILES['foto']['size'];
  $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

  
  if (!in_array($ext, $allowed_types)) {
    echo "<script>alert('Format file tidak diperbolehkan. Hanya jpg, jpeg, png, gif.'); window.history.back();</script>";
    exit;
  }

  
  if ($file_size > 2 * 1024 * 1024) {
    echo "<script>alert('Ukuran file terlalu besar (max 2MB).'); window.history.back();</script>";
    exit;
  }

  
  $foto_nama_baru = uniqid('foto_', true) . '.' . $ext;

  
  if (move_uploaded_file($file_tmp, "uploads/" . $foto_nama_baru)) {
    if ($lama['foto'] !== 'foto-user.png' && file_exists("uploads/" . $lama['foto'])) {
      unlink("uploads/" . $lama['foto']);
    }
    $foto_nama = $foto_nama_baru;
  } else {
    echo "<script>alert('Gagal mengupload file foto'); window.history.back();</script>";
    exit;
  }
}


$stmt = $koneksi->prepare("UPDATE users SET nama = ?, foto = ? WHERE id = ?");
$stmt->bind_param("ssi", $nama, $foto_nama, $id);

if ($stmt->execute()) {
  $stmt = $koneksi->prepare("SELECT * FROM users WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $result = $stmt->get_result();
  $_SESSION['user'] = $result->fetch_assoc();

  echo "<script>alert('Profil berhasil diperbarui'); window.location.href='profil.php';</script>";
  exit;
} else {
  echo "<script>alert('Gagal update profil'); window.history.back();</script>";
  exit;
}
?>

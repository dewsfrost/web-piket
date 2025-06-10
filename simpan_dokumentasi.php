<<?php
require 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'] ?? '';
    $deskripsi = $_POST['deskripsi'] ?? '';

    
    if (empty($judul) || empty($deskripsi)) {
        echo "<script>alert('Judul dan deskripsi wajib diisi'); window.history.back();</script>";
        exit;
    }

    $gambar_nama = '';

    
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $allowedExt = ['jpg', 'jpeg', 'png', 'gif'];
        $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowedExt)) {
            echo "<script>alert('Ekstensi file tidak diizinkan'); window.history.back();</script>";
            exit;
        }

        $gambar_nama = uniqid() . '.' . $ext;
        $uploadDir = 'uploads/';

        if (!move_uploaded_file($_FILES['gambar']['tmp_name'], $uploadDir . $gambar_nama)) {
            echo "<script>alert('Gagal memindahkan file'); window.history.back();</script>";
            exit;
        }
    }

    
    $stmt = $koneksi->prepare("INSERT INTO dokumentasi (judul, deskripsi, gambar) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $judul, $deskripsi, $gambar_nama);

    if ($stmt->execute()) {
        echo "<script>alert('Dokumentasi berhasil diupload'); window.location.href='galeri.html';</script>";
    } else {
        echo "<script>alert('Gagal upload dokumentasi'); window.history.back();</script>";
    }
} else {
    
    header('Location: upload_dokumentasi.php');
    exit;
}

<?php
include 'koneksi.php';

$error = ''; // variabel penampung error

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = trim($_POST['nama'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $nis = trim($_POST['nis'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $ulang_password = $_POST['ulang_password'] ?? '';
    $role = $_POST['role'] ?? '';

    // Validasi password sama
    if ($password !== $ulang_password) {
        $error = "Password dan ulangi password tidak cocok.";
    }
    // Validasi role
    elseif (!in_array($role, ['admin', 'user'])) {
        $error = "Role tidak valid.";
    } else {
        // Cek username sudah ada
        $cekUser = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '".mysqli_real_escape_string($koneksi, $username)."'");
        if (mysqli_num_rows($cekUser) > 0) {
            $error = "Username sudah terdaftar.";
        } else {
            // Hash password
            $hashPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert ke database
            $query = "INSERT INTO users (nama, username, nis, email, password, role) VALUES (
                '".mysqli_real_escape_string($koneksi, $nama)."',
                '".mysqli_real_escape_string($koneksi, $username)."',
                '".mysqli_real_escape_string($koneksi, $nis)."',
                '".mysqli_real_escape_string($koneksi, $email)."',
                '$hashPassword',
                '".mysqli_real_escape_string($koneksi, $role)."')";
            $result = mysqli_query($koneksi, $query);

            if ($result) {
                header("Location: login.php");
                exit;
            } else {
                $error = "Pendaftaran gagal: " . mysqli_error($koneksi);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Register - Piket Kelas</title>
  <link rel="stylesheet" href="register.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
  <div class="box">
    <img src="logo_skensa.png" alt="Logo" class="logo">
    <h2>Register</h2>

    <?php if (!empty($error)): ?>
      <div class="error-message"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="" method="POST">
      <input type="text" name="nama" placeholder="Nama Lengkap" value="<?= htmlspecialchars($nama ?? '') ?>" required>
      <input type="text" name="username" placeholder="Username" value="<?= htmlspecialchars($username ?? '') ?>" required>
      <input type="text" name="nis" placeholder="NIS" value="<?= htmlspecialchars($nis ?? '') ?>" required>
      <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($email ?? '') ?>" required>

      <div class="password-container">
        <input type="password" name="password" id="reg-password" placeholder="Password" required>
        <i class="fa-solid fa-eye toggle" id="toggleRegPassword"></i>
      </div>

      <div class="password-container">
        <input type="password" name="ulang_password" id="reg-ulangi" placeholder="Ulangi Password" required>
        <i class="fa-solid fa-eye toggle" id="toggleRegUlangi"></i>
      </div>

      <label class="role-label">Daftar sebagai:</label>
      <select name="role" required>
        <option value="" disabled <?= empty($role) ? 'selected' : '' ?>>Pilih Role</option>
        <option value="user" <?= (isset($role) && $role === 'user') ? 'selected' : '' ?>>User</option>
        <option value="admin" <?= (isset($role) && $role === 'admin') ? 'selected' : '' ?>>Admin</option>
      </select>

      <input type="submit" value="Daftar">
    </form>

    <a href="login.php">Sudah punya akun? Login di sini</a>

  </div>

  <script>
    const toggleRegPassword = document.getElementById("toggleRegPassword");
    const regPassword = document.getElementById("reg-password");

    toggleRegPassword.addEventListener("click", function () {
      const type = regPassword.getAttribute("type") === "password" ? "text" : "password";
      regPassword.setAttribute("type", type);
      this.classList.toggle("fa-eye");
      this.classList.toggle("fa-eye-slash");
    });

    const toggleRegUlangi = document.getElementById("toggleRegUlangi");
    const regUlangi = document.getElementById("reg-ulangi");

    toggleRegUlangi.addEventListener("click", function () {
      const type = regUlangi.getAttribute("type") === "password" ? "text" : "password";
      regUlangi.setAttribute("type", type);
      this.classList.toggle("fa-eye");
      this.classList.toggle("fa-eye-slash");
    });
  </script>
</body>
</html>

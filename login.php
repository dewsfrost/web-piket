<?php
ob_start();
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'koneksi.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = "Username dan password harus diisi.";
    } else {
        $stmt = $koneksi->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                $_SESSION['user'] = [
                    'id'       => $user['id'],
                    'username' => $user['username'],
                    'nama'     => $user['nama'],
                    'role'     => $user['role'],
                    'email'    => $user['email'],
                    'foto'     => $user['foto'] ?? null
                ];
                header("Location: dashboard.php");
                exit;
            } else {
                $error = "Password salah.";
            }
        } else {
            $error = "Username tidak ditemukan.";
        }
        $stmt->close();
    }
}
ob_end_flush();
?>

<!-- HTML berikut sama seperti yang kamu punya -->

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Login - Piket Kelas</title>
    <link rel="stylesheet" href="login.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
  <div class="box">
    <img src="logo_skensa.png" alt="Logo" class="logo" />
    <h2>Login</h2>

    <?php if ($error): ?>
      <div class="error-message"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
      <input type="text" name="username" placeholder="Username" required autofocus />

      <div class="password-container">
        <input type="password" name="password" id="login-password" placeholder="Password" required />
        <i class="fa-solid fa-eye toggle" id="toggleLoginPassword"></i>
      </div>

      <input type="submit" value="Login" />
    </form>

    <a href="register.php">Belum punya akun? Daftar di sini</a>
  </div>

  <script>
    const toggleLoginPassword = document.getElementById("toggleLoginPassword");
    const loginPassword = document.getElementById("login-password");

    toggleLoginPassword.addEventListener("click", function () {
      const type = loginPassword.getAttribute("type") === "password" ? "text" : "password";
      loginPassword.setAttribute("type", type);
      this.classList.toggle("fa-eye");
      this.classList.toggle("fa-eye-slash");
    });
  </script>
</body>
</html>

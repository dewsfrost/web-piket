<?php
session_start();
session_unset();     // Menghapus semua variabel session
session_destroy();   // Mengakhiri session

// Redirect ke halaman login
header("Location: login.php");
exit;

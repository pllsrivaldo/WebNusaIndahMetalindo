<?php
session_start();

// Menghapus semua variabel session
session_unset();

// Menghancurkan session secara total
session_destroy();

// Memunculkan notifikasi dan mengarahkan kembali ke halaman login
echo '<script>
        alert("Anda telah berhasil logout!"); 
        window.location="login.php";
      </script>';
?>
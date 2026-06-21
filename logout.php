<?php
session_start();

// Hapus semua session
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Logout | BarTagX</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <script>
        Swal.fire({
            icon: 'success',
            title: 'Logout Berhasil',
            text: 'Sampai jumpa lagi 👋',
            showConfirmButton: false,
            timer: 1800
        }).then(() => {
            window.location.href = 'login.php';
        });
    </script>

</body>
</html>

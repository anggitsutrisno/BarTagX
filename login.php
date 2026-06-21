<?php
session_start();
include('config/config.php');
include('includes/log_activity.php');

/* =====================================================
   JIKA SUDAH LOGIN, LANGSUNG KE DASHBOARD
===================================================== */
if (isset($_SESSION['user'])) {
    header("Location: pages/dashboard/dashboard.php");
    exit;
}

$message = "";

/* =====================================================
   PROSES LOGIN
===================================================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Sanitasi input
    $email    = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $password = trim($_POST['password']);

    /* ================= VALIDASI INPUT ================= */
    if (!$email || $password === '') {
        $message = "Email atau password tidak valid.";

        logActivity(
            $conn,
            'LOGIN',
            'Input email atau password tidak valid',
            'FAILED'
        );

    } else {

        /* ================= AMBIL DATA USER ================= */
        $stmt = $conn->prepare("
            SELECT id, email, password, role, nama
            FROM users
            WHERE email = ?
            LIMIT 1
        ");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        /* ================= EMAIL TIDAK DITEMUKAN ================= */
        if ($result->num_rows !== 1) {

            $message = "Email tidak ditemukan!";

            logActivity(
                $conn,
                'LOGIN',
                'Email tidak ditemukan',
                'FAILED'
            );

        } else {

            $user = $result->fetch_assoc();
            $validPassword = false;

            /* =================================================
               CEK PASSWORD
            ================================================= */

            // 1️⃣ Password lama (plaintext)
            if ($password === $user['password']) {
                $validPassword = true;

                // Auto upgrade ke bcrypt
                if (!password_get_info($user['password'])['algo']) {
                    $hash = password_hash($password, PASSWORD_DEFAULT);
                    $upd = $conn->prepare("UPDATE users SET password=? WHERE id=?");
                    $upd->bind_param("si", $hash, $user['id']);
                    $upd->execute();
                }
            }

            // 2️⃣ Password bcrypt
            if (password_verify($password, $user['password'])) {
                $validPassword = true;
            }

            /* =================================================
               JIKA LOGIN BERHASIL
            ================================================= */
            if ($validPassword) {

                $_SESSION['user']       = $user['email'];
                $_SESSION['role']       = $user['role'];
                $_SESSION['nama']       = $user['nama'];
                $_SESSION['login_time'] = time();

                logActivity(
                    $conn,
                    'LOGIN',
                    'User berhasil login ke sistem',
                    'SUCCESS'
                );

                header("Location: pages/dashboard/dashboard.php?login=success");
                exit;

            } else {

                $message = "Password salah!";

                logActivity(
                    $conn,
                    'LOGIN',
                    'Password salah',
                    'FAILED'
                );
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login | BarTagX System</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        @keyframes slideLeft {
            0% { opacity: 0; transform: translateX(-60px); }
            100% { opacity: 1; transform: translateX(0); }
        }
        @keyframes slideRight {
            0% { opacity: 0; transform: translateX(60px); }
            100% { opacity: 1; transform: translateX(0); }
        }
        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        .animate-chip { animation: slideLeft 1s ease-out forwards; }
        .animate-text { animation: slideRight 1s ease-out 0.3s forwards; opacity: 0; }
        .animate-tagline { animation: fadeIn 1.2s ease-out 0.8s forwards; opacity: 0; }

        body {
            background: linear-gradient(to bottom right, #e0f2fe, #bfdbfe);
            font-family: 'Inter', sans-serif;
        }

        .card {
            background: #ffffff;
            border-radius: 1.5rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border: 1px solid #f3f4f6;
            max-width: 420px;
            margin: auto;
        }

        input:focus {
            outline: none;
            border-color: #0284c7;
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.3);
        }
    </style>
</head>

<body class="flex items-center justify-center min-h-screen px-4">

<div class="card w-full p-10 text-center">

    <!-- Logo -->
    <div class="flex items-center justify-center gap-3 mb-2">
        <img src="assets/img/logo_chip.png" class="w-24 animate-chip" alt="Logo Chip">
        <img src="assets/img/bartagx_sytem_clear.png" class="w-52 animate-text" alt="BarTagX">
    </div>

    <!-- Tagline -->
    <p class="text-gray-600 text-sm animate-tagline mb-6">
        Automation · Barcode · RFID Tracking
    </p>

    <?php if (!empty($message)): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Login Gagal',
            text: '<?= addslashes($message); ?>'
        });
    </script>
    <?php endif; ?>

    <!-- Form Login -->
    <form method="POST" class="space-y-5 mt-2">
        <div>
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" required
                   class="w-full mt-1 p-3 border rounded-xl">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Password</label>
            <input type="password" name="password" required
                   class="w-full mt-1 p-3 border rounded-xl">
        </div>

        <button type="submit"
                class="w-full bg-sky-700 hover:bg-sky-800 text-white py-3 rounded-xl font-semibold shadow-md">
            Masuk
        </button>
    </form>

    <p class="text-center text-gray-400 text-xs mt-8">
        &copy; <?= date('Y'); ?> BarTagX System<br>
        <span class="text-gray-500">PT Capella Dinamik Nusantara Batam</span>
    </p>

</div>

</body>
</html>

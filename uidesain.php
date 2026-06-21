<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Login | BarTagX System</title>
<script src="https://cdn.tailwindcss.com"></script>

<style>
/* ===============================
   MODE PERANCANGAN – LOGIN UI
================================ */

body{
    background:#DCEEFF;
    font-family: Inter, sans-serif;
}

/* CARD */
.login-card{
    background:#FFFFFF;
    border:1px dashed #CBD5E1;     /* dashed = masih perancangan */
    border-radius:16px;
    box-shadow:none;
}

/* LOGO & TITLE */
.logo-title{
    font-size:1.15rem;
    font-weight:700;
    color:#1E3A8A;
}
.logo-sub{
    font-size:0.7rem;
    color:#6B7280;
}

/* LABEL */
label{
    font-size:0.7rem;
    font-weight:600;
    color:#374151;
}

/* INPUT */
input{
    font-size:0.75rem;
    border-radius:8px;
}

/* BUTTON */
.btn-login{
    font-size:0.75rem;
    font-weight:600;
    background:#0369A1;
}

/* FOOTER */
.footer{
    font-size:0.65rem;
    color:#9CA3AF;
}
</style>
</head>

<body class="flex items-center justify-center min-h-screen">

<!-- LOGIN CARD -->
<div class="login-card w-[360px] p-8">

    <!-- LOGO -->
    <div class="flex flex-col items-center mb-6">
        <div class="text-blue-600 text-4xl mb-3">📡</div>
        <h1 class="logo-title">BarTagX <span class="text-gray-800">System</span></h1>
        <p class="logo-sub mt-1">
            Automation · Barcode · RFID Tracking
        </p>
    </div>

    <!-- FORM -->
    <form method="POST" action="login.php" class="space-y-4">

        <div>
            <label>Email</label>
            <input type="email"
                   name="email"
                   class="w-full border px-3 py-2 mt-1"
                   required>
        </div>

        <div>
            <label>Password</label>
            <input type="password"
                   name="password"
                   class="w-full border px-3 py-2 mt-1"
                   required>
        </div>

        <button type="submit"
                class="btn-login w-full text-white py-2 rounded-lg mt-2">
            Masuk
        </button>

    </form>

    <!-- FOOTER -->
    <p class="footer text-center mt-6">
        © 2026 BarTagX System<br>
        PT Capella Dinamik Nusantara Batam
    </p>

</div>

</body>
</html>

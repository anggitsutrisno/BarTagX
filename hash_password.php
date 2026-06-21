<?php
$password_plain = "12345"; // Ganti sesuai password lama
echo password_hash($password_plain, PASSWORD_BCRYPT);

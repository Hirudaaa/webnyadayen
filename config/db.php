<?php
$host = 'localhost';
$user = 'dayen';
$pass = 'manies';
$db   = 'dbdayen';

// Buat Koneksi
$conn = new mysqli($host, $user, $pass, $db);

// Cek Koneksi
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>

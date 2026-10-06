<?php
$host = 'sql205.infinityfree.com';
$username = 'if0_42301805';
$password = 'OmwSuzDlMpi';
$database = 'if0_42301805_sim_mahasiswa76';
$port = '3306';

// Membuka koneksi ke database
$koneksi = mysqli_connect($host, $username, $password, $database, $port);

// Cek koneksi (opsional untuk melihat jika ada error lanjutan)
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>
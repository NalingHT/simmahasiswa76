<?php session_start(); require_once 'koneksi.php'; require_once 'auth_check.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head><title>Rekap Mahasiswa</title><link rel="stylesheet" href="style.css"></head>
<body>
    <?php require_once 'topbar.php'; require_once 'sideleftbar.php'; ?>
    <div class="main-content">
        <div class="page-header" id="area-non-cetak">
            <div class="header-left">
                <p class="header-label">LAPORAN</p>
                <h1 class="page-title">Rekap Mahasiswa</h1>
                <p class="page-desc">Laporan rekapitulasi data mahasiswa terdaftar.</p>
            </div>
            <div class="header-right"><button onclick="window.print()" class="btn-add" style="border:none; cursor:pointer;">🖨️ Cetak Laporan</button></div>
        </div>
        <div class="card-table" id="area-cetak">
            <table class="custom-table">
                <thead><tr><th>NO.</th><th>NPM</th><th>NAMA LENGKAP</th><th>PROGRAM STUDI</th><th>L/P</th><th>STATUS</th></tr></thead>
                <tbody>
                    <?php $q = mysqli_query($koneksi, "SELECT m.*, p.nama_program_studi FROM mahasiswa m JOIN program_studi p ON m.id_program_studi = p.id_program_studi ORDER BY p.nama_program_studi, m.nama_mahasiswa ASC"); $no = 1; while($d = mysqli_fetch_array($q)){ ?>
                    <tr><td><?= $no++; ?></td><td><span class="badge-green"><?= $d['npm']; ?></span></td><td><b><?= $d['nama_mahasiswa']; ?></b></td><td><?= $d['nama_program_studi']; ?></td><td><?= $d['jenis_kelamin']; ?></td><td><?= $d['status_mahasiswa']; ?></td></tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <style>@media print { body * { visibility: hidden; } #area-cetak, #area-cetak * { visibility: visible; } #area-cetak { position: absolute; left: 0; top: 0; width: 100%; border:none; } }</style>
</body></html>
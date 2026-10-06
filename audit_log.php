<?php session_start(); require_once 'koneksi.php'; require_once 'auth_check.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head><title>Audit Log</title><link rel="stylesheet" href="style.css"></head>
<body>
    <?php require_once 'topbar.php'; require_once 'sideleftbar.php'; ?>
    <div class="main-content">
        <div class="page-header">
            <div class="header-left">
                <p class="header-label">SISTEM</p>
                <h1 class="page-title">Audit Log</h1>
                <p class="page-desc" style="color:#dc3545;">*Riwayat aktivitas sistem otomatis (Read-Only).</p>
            </div>
        </div>
        <div class="card-table">
            <table class="custom-table">
                <thead><tr><th>NO.</th><th>WAKTU (WIB)</th><th>PENGGUNA</th><th>AKSI</th><th>TARGET TABEL</th><th>IP ADDRESS</th></tr></thead>
                <tbody>
                    <?php $q = mysqli_query($koneksi, "SELECT a.*, p.username FROM audit_log a LEFT JOIN pengguna p ON a.id_pengguna = p.id_pengguna ORDER BY a.id_audit DESC LIMIT 100"); $no = 1; while($d = mysqli_fetch_array($q)){ ?>
                    <tr>
                        <td><?= $no++; ?></td><td><?= $d['waktu']; ?></td><td><b><?= htmlspecialchars($d['username'] ? $d['username'] : 'Sistem'); ?></b></td>
                        <td><span style="font-weight:bold; color:<?= $d['aksi']=='DELETE'?'#dc3545':($d['aksi']=='INSERT'?'#28a745':'#007bff') ?>"><?= $d['aksi']; ?></span></td>
                        <td><?= htmlspecialchars($d['tabel_nama']); ?></td><td><?= htmlspecialchars($d['ip_address']); ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body></html>
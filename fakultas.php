<?php
session_start(); require_once 'koneksi.php'; require_once 'auth_check.php';
$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : '';
if ($aksi == 'hapus') { mysqli_query($koneksi, "DELETE FROM fakultas WHERE id_fakultas='$_GET[id]'"); header("Location: fakultas.php"); exit; }
if (isset($_POST['simpan_tambah'])) { mysqli_query($koneksi, "INSERT INTO fakultas (id_universitas, kode_fakultas, nama_fakultas) VALUES (1, '$_POST[kode_fakultas]', '$_POST[nama_fakultas]')"); header("Location: fakultas.php"); exit; }
if (isset($_POST['simpan_edit'])) { mysqli_query($koneksi, "UPDATE fakultas SET kode_fakultas='$_POST[kode_fakultas]', nama_fakultas='$_POST[nama_fakultas]' WHERE id_fakultas='$_POST[id_fakultas]'"); header("Location: fakultas.php"); exit; }
?>
<!DOCTYPE html>
<html lang="id">
<head><title>Fakultas</title><link rel="stylesheet" href="style.css"></head>
<body>
    <?php require_once 'topbar.php'; require_once 'sideleftbar.php'; ?>
    <div class="main-content">
        <div class="page-header">
            <div class="header-left">
                <p class="header-label">DATA AKADEMIK</p>
                <h1 class="page-title">Fakultas</h1>
                <p class="page-desc">Kelola data fakultas yang ada di universitas.</p>
            </div>
            <?php if($aksi == '') { ?> <div class="header-right"><a href="?aksi=tambah" class="btn-add">+ Tambah Fakultas</a></div> <?php } ?>
        </div>
        <?php if ($aksi == 'tambah' || $aksi == 'edit') { 
            $is_edit = ($aksi == 'edit'); $d = $is_edit ? mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM fakultas WHERE id_fakultas='$_GET[id]'")) : null;
        ?>
            <div class="form-card" style="max-width: 500px;">
                <form method="POST">
                    <?php if($is_edit) echo '<input type="hidden" name="id_fakultas" value="'.$d['id_fakultas'].'">'; ?>
                    <div class="form-group"><label>KODE FAKULTAS</label><input type="text" name="kode_fakultas" class="form-control" value="<?= $is_edit ? htmlspecialchars($d['kode_fakultas']) : ''; ?>" required></div>
                    <div class="form-group"><label>NAMA FAKULTAS</label><input type="text" name="nama_fakultas" class="form-control" value="<?= $is_edit ? htmlspecialchars($d['nama_fakultas']) : ''; ?>" required></div>
                    <button type="submit" name="<?= $is_edit ? 'simpan_edit' : 'simpan_tambah'; ?>" class="btn-submit">Simpan</button>
                    <a href="fakultas.php" class="btn-cancel">Batal</a>
                </form>
            </div>
        <?php } else { ?>
            <div class="card-table">
                <table class="custom-table">
                    <thead><tr><th>NO.</th><th>KODE FAKULTAS</th><th>NAMA FAKULTAS</th><th>AKSI</th></tr></thead>
                    <tbody>
                        <?php $q = mysqli_query($koneksi, "SELECT * FROM fakultas ORDER BY id_fakultas DESC"); $no = 1; while($d = mysqli_fetch_array($q)){ ?>
                        <tr>
                            <td><?= $no++; ?></td><td><span class="badge-green"><?= $d['kode_fakultas']; ?></span></td><td><b><?= $d['nama_fakultas']; ?></b></td>
                            <td><a href="?aksi=edit&id=<?= $d['id_fakultas']; ?>" class="action-btn btn-outline-green">Edit</a> <a href="?aksi=hapus&id=<?= $d['id_fakultas']; ?>" class="action-btn btn-fill-red" onclick="return confirm('Hapus?');">Hapus</a></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } ?>
    </div>
</body></html>
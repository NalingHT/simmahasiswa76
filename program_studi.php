<?php
session_start(); require_once 'koneksi.php'; require_once 'auth_check.php';
$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : '';
if ($aksi == 'hapus') { mysqli_query($koneksi, "DELETE FROM program_studi WHERE id_program_studi='$_GET[id]'"); header("Location: program_studi.php"); exit; }
if (isset($_POST['simpan_tambah'])) { mysqli_query($koneksi, "INSERT INTO program_studi (id_fakultas, kode_program_studi, nama_program_studi, jenjang, status_aktif) VALUES ('$_POST[id_fakultas]', '$_POST[kode_program_studi]', '$_POST[nama_program_studi]', '$_POST[jenjang]', '$_POST[status_aktif]')"); header("Location: program_studi.php"); exit; }
if (isset($_POST['simpan_edit'])) { mysqli_query($koneksi, "UPDATE program_studi SET id_fakultas='$_POST[id_fakultas]', kode_program_studi='$_POST[kode_program_studi]', nama_program_studi='$_POST[nama_program_studi]', jenjang='$_POST[jenjang]', status_aktif='$_POST[status_aktif]' WHERE id_program_studi='$_POST[id_program_studi]'"); header("Location: program_studi.php"); exit; }
?>
<!DOCTYPE html>
<html lang="id">
<head><title>Program Studi</title><link rel="stylesheet" href="style.css"></head>
<body>
    <?php require_once 'topbar.php'; require_once 'sideleftbar.php'; ?>
    <div class="main-content">
        <div class="page-header">
            <div class="header-left">
                <p class="header-label">DATA AKADEMIK</p>
                <h1 class="page-title">Program Studi</h1>
                <p class="page-desc">Kelola program studi dan status aktivasinya.</p>
            </div>
            <?php if($aksi == '') { ?> <div class="header-right"><a href="?aksi=tambah" class="btn-add">+ Tambah Prodi</a></div> <?php } ?>
        </div>
        <?php if ($aksi == 'tambah' || $aksi == 'edit') { 
            $is_edit = ($aksi == 'edit'); $d = $is_edit ? mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM program_studi WHERE id_program_studi='$_GET[id]'")) : null;
        ?>
            <div class="form-card" style="max-width: 500px;">
                <form method="POST">
                    <?php if($is_edit) echo '<input type="hidden" name="id_program_studi" value="'.$d['id_program_studi'].'">'; ?>
                    <div class="form-group"><label>FAKULTAS</label><select name="id_fakultas" class="form-control" required><?php $qf = mysqli_query($koneksi, "SELECT * FROM fakultas"); while($f = mysqli_fetch_array($qf)) { $sel = ($is_edit && $f['id_fakultas'] == $d['id_fakultas']) ? 'selected' : ''; echo "<option value='{$f['id_fakultas']}' $sel>{$f['nama_fakultas']}</option>"; } ?></select></div>
                    <div class="form-group"><label>KODE PRODI</label><input type="text" name="kode_program_studi" class="form-control" value="<?= $is_edit ? htmlspecialchars($d['kode_program_studi']) : ''; ?>" required></div>
                    <div class="form-group"><label>NAMA PRODI</label><input type="text" name="nama_program_studi" class="form-control" value="<?= $is_edit ? htmlspecialchars($d['nama_program_studi']) : ''; ?>" required></div>
                    <div class="form-group"><label>JENJANG</label><select name="jenjang" class="form-control"><option value="D3" <?= ($is_edit && $d['jenjang']=='D3')?'selected':''; ?>>D3</option><option value="S1" <?= ($is_edit && $d['jenjang']=='S1')?'selected':''; ?>>S1</option><option value="S2" <?= ($is_edit && $d['jenjang']=='S2')?'selected':''; ?>>S2</option></select></div>
                    <div class="form-group"><label>STATUS AKTIF</label><select name="status_aktif" class="form-control"><option value="Aktif" <?= ($is_edit && $d['status_aktif']=='Aktif')?'selected':''; ?>>Aktif</option><option value="Tidak Aktif" <?= ($is_edit && $d['status_aktif']=='Tidak Aktif')?'selected':''; ?>>Tidak Aktif</option></select></div>
                    <button type="submit" name="<?= $is_edit ? 'simpan_edit' : 'simpan_tambah'; ?>" class="btn-submit">Simpan</button>
                    <a href="program_studi.php" class="btn-cancel">Batal</a>
                </form>
            </div>
        <?php } else { ?>
            <div class="card-table">
                <table class="custom-table">
                    <thead><tr><th>NO.</th><th>KODE PRODI</th><th>FAKULTAS</th><th>NAMA PRODI</th><th>JENJANG</th><th>STATUS</th><th>AKSI</th></tr></thead>
                    <tbody>
                        <?php $q = mysqli_query($koneksi, "SELECT p.*, f.nama_fakultas FROM program_studi p JOIN fakultas f ON p.id_fakultas = f.id_fakultas ORDER BY p.id_program_studi DESC"); $no = 1; while($d = mysqli_fetch_array($q)){ ?>
                        <tr>
                            <td><?= $no++; ?></td><td><span class="badge-green"><?= $d['kode_program_studi']; ?></span></td><td><?= $d['nama_fakultas']; ?></td>
                            <td><b><?= $d['nama_program_studi']; ?></b></td><td><?= $d['jenjang']; ?></td><td><?= $d['status_aktif']; ?></td>
                            <td><a href="?aksi=edit&id=<?= $d['id_program_studi']; ?>" class="action-btn btn-outline-green">Edit</a> <a href="?aksi=hapus&id=<?= $d['id_program_studi']; ?>" class="action-btn btn-fill-red" onclick="return confirm('Hapus?');">Hapus</a></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } ?>
    </div>
</body></html>
<?php
session_start(); require_once 'koneksi.php'; require_once 'auth_check.php';
$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : '';
if ($aksi == 'hapus') { mysqli_query($koneksi, "DELETE FROM mahasiswa WHERE id_mahasiswa='$_GET[id]'"); header("Location: mahasiswa.php"); exit; }
if (isset($_POST['simpan_tambah'])) { mysqli_query($koneksi, "INSERT INTO mahasiswa (id_program_studi, npm, nama_mahasiswa, jenis_kelamin, tempat_lahir, tanggal_lahir, tanggal_masuk, alamat, status_mahasiswa) VALUES ('$_POST[id_program_studi]', '$_POST[npm]', '$_POST[nama_mahasiswa]', '$_POST[jenis_kelamin]', '$_POST[tempat_lahir]', '$_POST[tanggal_lahir]', '$_POST[tanggal_masuk]', '$_POST[alamat]', '$_POST[status_mahasiswa]')"); header("Location: mahasiswa.php"); exit; }
if (isset($_POST['simpan_edit'])) { mysqli_query($koneksi, "UPDATE mahasiswa SET id_program_studi='$_POST[id_program_studi]', npm='$_POST[npm]', nama_mahasiswa='$_POST[nama_mahasiswa]', jenis_kelamin='$_POST[jenis_kelamin]', tempat_lahir='$_POST[tempat_lahir]', tanggal_lahir='$_POST[tanggal_lahir]', tanggal_masuk='$_POST[tanggal_masuk]', alamat='$_POST[alamat]', status_mahasiswa='$_POST[status_mahasiswa]' WHERE id_mahasiswa='$_POST[id_mahasiswa]'"); header("Location: mahasiswa.php"); exit; }
?>
<!DOCTYPE html>
<html lang="id">
<head><title>Data Mahasiswa</title><link rel="stylesheet" href="style.css"></head>
<body>
    <?php require_once 'topbar.php'; require_once 'sideleftbar.php'; ?>
    <div class="main-content">
        <div class="page-header">
            <div class="header-left">
                <p class="header-label">DATA AKADEMIK</p>
                <h1 class="page-title">Data Mahasiswa</h1>
                <p class="page-desc">Kelola biodata dan status kemahasiswaan.</p>
            </div>
            <?php if($aksi == '') { ?> <div class="header-right"><a href="?aksi=tambah" class="btn-add">+ Tambah Mahasiswa</a></div> <?php } ?>
        </div>
        <?php if ($aksi == 'tambah' || $aksi == 'edit') { 
            $is_edit = ($aksi == 'edit'); $d = $is_edit ? mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM mahasiswa WHERE id_mahasiswa='$_GET[id]'")) : null;
        ?>
            <div class="form-card">
                <form method="POST">
                    <?php if($is_edit) echo '<input type="hidden" name="id_mahasiswa" value="'.$d['id_mahasiswa'].'">'; ?>
                    <div style="display:flex; gap:20px;">
                        <div style="flex:1;">
                            <div class="form-group"><label>NPM</label><input type="text" name="npm" class="form-control" value="<?= $is_edit ? htmlspecialchars($d['npm']) : ''; ?>" required></div>
                            <div class="form-group"><label>NAMA LENGKAP</label><input type="text" name="nama_mahasiswa" class="form-control" value="<?= $is_edit ? htmlspecialchars($d['nama_mahasiswa']) : ''; ?>" required></div>
                            <div class="form-group"><label>PROGRAM STUDI</label><select name="id_program_studi" class="form-control" required><?php $qp = mysqli_query($koneksi, "SELECT * FROM program_studi"); while($p = mysqli_fetch_array($qp)) { $sel = ($is_edit && $p['id_program_studi'] == $d['id_program_studi']) ? 'selected' : ''; echo "<option value='{$p['id_program_studi']}' $sel>{$p['nama_program_studi']}</option>"; } ?></select></div>
                            <div class="form-group"><label>JENIS KELAMIN</label><select name="jenis_kelamin" class="form-control"><option value="L" <?= ($is_edit && $d['jenis_kelamin']=='L')?'selected':''; ?>>Laki-laki</option><option value="P" <?= ($is_edit && $d['jenis_kelamin']=='P')?'selected':''; ?>>Perempuan</option></select></div>
                        </div>
                        <div style="flex:1;">
                            <div class="form-group"><label>TEMPAT LAHIR</label><input type="text" name="tempat_lahir" class="form-control" value="<?= $is_edit ? htmlspecialchars($d['tempat_lahir']) : ''; ?>"></div>
                            <div class="form-group"><label>TANGGAL LAHIR</label><input type="date" name="tanggal_lahir" class="form-control" value="<?= $is_edit ? $d['tanggal_lahir'] : ''; ?>"></div>
                            <div class="form-group"><label>TANGGAL MASUK</label><input type="date" name="tanggal_masuk" class="form-control" value="<?= $is_edit ? $d['tanggal_masuk'] : ''; ?>" required></div>
                            <div class="form-group"><label>STATUS MAHASISWA</label><select name="status_mahasiswa" class="form-control"><?php $arr = ['Aktif','Cuti','Lulus','Mengundurkan Diri','Drop Out','Tidak Aktif']; foreach($arr as $s){ $sel = ($is_edit && $d['status_mahasiswa']==$s)?'selected':''; echo "<option value='$s' $sel>$s</option>"; } ?></select></div>
                        </div>
                    </div>
                    <div class="form-group"><label>ALAMAT</label><textarea name="alamat" class="form-control" rows="3"><?= $is_edit ? htmlspecialchars($d['alamat']) : ''; ?></textarea></div>
                    <button type="submit" name="<?= $is_edit ? 'simpan_edit' : 'simpan_tambah'; ?>" class="btn-submit">Simpan</button> <a href="mahasiswa.php" class="btn-cancel">Batal</a>
                </form>
            </div>
        <?php } else { ?>
            <div class="card-table">
                <table class="custom-table">
                    <thead><tr><th>NO.</th><th>NPM</th><th>NAMA LENGKAP</th><th>PRODI</th><th>L/P</th><th>STATUS</th><th>AKSI</th></tr></thead>
                    <tbody>
                        <?php $q = mysqli_query($koneksi, "SELECT m.*, p.nama_program_studi FROM mahasiswa m JOIN program_studi p ON m.id_program_studi = p.id_program_studi ORDER BY m.id_mahasiswa DESC"); $no = 1; while($d = mysqli_fetch_array($q)){ ?>
                        <tr>
                            <td><?= $no++; ?></td><td><span class="badge-green"><?= $d['npm']; ?></span></td><td><b><?= $d['nama_mahasiswa']; ?></b></td>
                            <td><?= $d['nama_program_studi']; ?></td><td><?= $d['jenis_kelamin']; ?></td><td><?= $d['status_mahasiswa']; ?></td>
                            <td><a href="?aksi=edit&id=<?= $d['id_mahasiswa']; ?>" class="action-btn btn-outline-green">Edit</a> <a href="?aksi=hapus&id=<?= $d['id_mahasiswa']; ?>" class="action-btn btn-fill-red" onclick="return confirm('Hapus?');">Hapus</a></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } ?>
    </div>
</body></html>
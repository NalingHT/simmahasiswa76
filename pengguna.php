<?php
session_start(); require_once 'koneksi.php'; require_once 'auth_check.php';
$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : '';
if ($aksi == 'hapus') { mysqli_query($koneksi, "DELETE FROM pengguna WHERE id_pengguna='$_GET[id]'"); header("Location: pengguna.php"); exit; }
if (isset($_POST['simpan_tambah'])) { $pass = password_hash($_POST['password'], PASSWORD_DEFAULT); mysqli_query($koneksi, "INSERT INTO pengguna (id_role, username, password_hash, nama_lengkap, email, status_aktif) VALUES ('$_POST[id_role]', '$_POST[username]', '$pass', '$_POST[nama_lengkap]', '$_POST[email]', '$_POST[status_aktif]')"); header("Location: pengguna.php"); exit; }
if (isset($_POST['simpan_edit'])) { 
    $q_upd = "UPDATE pengguna SET id_role='$_POST[id_role]', username='$_POST[username]', nama_lengkap='$_POST[nama_lengkap]', email='$_POST[email]', status_aktif='$_POST[status_aktif]'";
    if(!empty($_POST['password'])) { $pass = password_hash($_POST['password'], PASSWORD_DEFAULT); $q_upd .= ", password_hash='$pass'"; }
    $q_upd .= " WHERE id_pengguna='$_POST[id_pengguna]'"; mysqli_query($koneksi, $q_upd); header("Location: pengguna.php"); exit; 
}
?>
<!DOCTYPE html>
<html lang="id">
<head><title>Pengguna Sistem</title><link rel="stylesheet" href="style.css"></head>
<body>
    <?php require_once 'topbar.php'; require_once 'sideleftbar.php'; ?>
    <div class="main-content">
        <div class="page-header">
            <div class="header-left">
                <p class="header-label">MASTER DATA</p>
                <h1 class="page-title">Pengguna Sistem</h1>
                <p class="page-desc">Kelola akun dan hak akses login pengguna.</p>
            </div>
            <?php if($aksi == '') { ?> <div class="header-right"><a href="?aksi=tambah" class="btn-add">+ Tambah Pengguna</a></div> <?php } ?>
        </div>
        <?php if ($aksi == 'tambah' || $aksi == 'edit') { 
            $is_edit = ($aksi == 'edit'); $d = $is_edit ? mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM pengguna WHERE id_pengguna='$_GET[id]'")) : null;
        ?>
            <div class="form-card" style="max-width: 500px;">
                <form method="POST">
                    <?php if($is_edit) echo '<input type="hidden" name="id_pengguna" value="'.$d['id_pengguna'].'">'; ?>
                    <div class="form-group"><label>NAMA LENGKAP</label><input type="text" name="nama_lengkap" class="form-control" value="<?= $is_edit ? htmlspecialchars($d['nama_lengkap']) : ''; ?>" required></div>
                    <div class="form-group"><label>USERNAME</label><input type="text" name="username" class="form-control" value="<?= $is_edit ? htmlspecialchars($d['username']) : ''; ?>" required></div>
                    <div class="form-group"><label>EMAIL</label><input type="email" name="email" class="form-control" value="<?= $is_edit ? htmlspecialchars($d['email']) : ''; ?>"></div>
                    <div class="form-group"><label>PASSWORD <?= $is_edit ? '<span style="color:#7f8c8d;font-weight:normal;">(Kosongkan jika tidak diubah)</span>' : ''; ?></label><input type="password" name="password" class="form-control" <?= $is_edit ? '' : 'required'; ?>></div>
                    <div class="form-group"><label>ROLE</label><select name="id_role" class="form-control" required><?php $qr = mysqli_query($koneksi, "SELECT * FROM roles"); while($r = mysqli_fetch_array($qr)) { $sel = ($is_edit && $r['id_role'] == $d['id_role']) ? 'selected' : ''; echo "<option value='{$r['id_role']}' $sel>{$r['nama_role']}</option>"; } ?></select></div>
                    <div class="form-group"><label>STATUS AKTIF</label><select name="status_aktif" class="form-control"><option value="Aktif" <?= ($is_edit && $d['status_aktif']=='Aktif')?'selected':''; ?>>Aktif</option><option value="Tidak Aktif" <?= ($is_edit && $d['status_aktif']=='Tidak Aktif')?'selected':''; ?>>Tidak Aktif</option></select></div>
                    <button type="submit" name="<?= $is_edit ? 'simpan_edit' : 'simpan_tambah'; ?>" class="btn-submit">Simpan</button> <a href="pengguna.php" class="btn-cancel">Batal</a>
                </form>
            </div>
        <?php } else { ?>
            <div class="card-table">
                <table class="custom-table">
                    <thead><tr><th>NO.</th><th>USERNAME</th><th>NAMA LENGKAP</th><th>ROLE</th><th>STATUS</th><th>AKSI</th></tr></thead>
                    <tbody>
                        <?php $q = mysqli_query($koneksi, "SELECT p.*, r.nama_role FROM pengguna p JOIN roles r ON p.id_role = r.id_role ORDER BY p.id_pengguna DESC"); $no = 1; while($d = mysqli_fetch_array($q)){ ?>
                        <tr>
                            <td><?= $no++; ?></td><td><span class="badge-green"><?= $d['username']; ?></span></td><td><b><?= $d['nama_lengkap']; ?></b></td>
                            <td><?= $d['nama_role']; ?></td><td><?= $d['status_aktif']; ?></td>
                            <td>
                                <a href="?aksi=edit&id=<?= $d['id_pengguna']; ?>" class="action-btn btn-outline-green">Edit</a>
                                <?php if($d['username'] != 'Admin') { ?> <a href="?aksi=hapus&id=<?= $d['id_pengguna']; ?>" class="action-btn btn-fill-red" onclick="return confirm('Hapus?');">Hapus</a> <?php } ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } ?>
    </div>
</body></html>
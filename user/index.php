<?php
session_start();
include dirname(__DIR__) . '/koneksi.php';
include dirname(__DIR__) . '/auth.php';
requireRole('admin');

$judul = 'Tambah User';

// Guru yang belum punya user
$guru = mysqli_query($koneksi, "
    SELECT g.id, g.nama FROM dataguru g 
    LEFT JOIN users u ON u.id_guru = g.id 
    WHERE u.id IS NULL
");

// Siswa yang belum punya user
$siswa = mysqli_query($koneksi, "
    SELECT s.id, s.nama FROM datasiswa s 
    LEFT JOIN users u ON u.id_siswa = s.id 
    WHERE u.id IS NULL
");

include dirname(__DIR__) . '/layout/header.php';
?>

<h2>Tambah User</h2>

<form action="simpan.php" method="post">
    <div style="margin-bottom: 15px;">
        <label>Username:</label><br>
        <input type="text" name="username" required>
    </div>

    <div style="margin-bottom: 15px;">
        <label>Password:</label><br>
        <input type="password" name="password" required minlength="6">
    </div>

    <div style="margin-bottom: 15px;">
        <label>Nama Lengkap:</label><br>
        <input type="text" name="nama_lengkap" required>
    </div>

    <div style="margin-bottom: 15px;">
        <label>Role:</label><br>
        <select name="role" id="role" onchange="toggleLinkFields()" required>
            <option value="">-- Pilih Role --</option>
            <option value="admin">Admin</option>
            <option value="kepala">Kepala Sekolah</option>
            <option value="user">User (Guru/Siswa)</option>
        </select>
    </div>

    <div id="guru_field" style="display: none; margin-bottom: 15px;">
        <label>Link ke Guru:</label><br>
        <select name="id_guru">
            <option value="">-- Pilih Guru --</option>
            <?php while ($g = mysqli_fetch_assoc($guru)) : ?>
                <option value="<?= $g['id'] ?>"><?= htmlspecialchars($g['nama']) ?></option>
            <?php endwhile; ?>
        </select>
        <?php if (mysqli_num_rows($guru) == 0) : ?>
            <small style="color:red;">Semua guru sudah memiliki akun</small>
        <?php endif; ?>
    </div>

    <div id="siswa_field" style="display: none; margin-bottom: 15px;">
        <label>Link ke Siswa:</label><br>
        <select name="id_siswa">
            <option value="">-- Pilih Siswa --</option>
            <?php while ($s = mysqli_fetch_assoc($siswa)) : ?>
                <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['nama']) ?></option>
            <?php endwhile; ?>
        </select>
        <?php if (mysqli_num_rows($siswa) == 0) : ?>
            <small style="color:red;">Semua siswa sudah memiliki akun</small>
        <?php endif; ?>
    </div>

    <button class="btn btn-success" type="submit">Simpan</button>
    <a class="btn" href="tampil.php">Kembali</a>
</form>

<script>
function toggleLinkFields() {
    const role = document.getElementById('role').value;
    document.getElementById('guru_field').style.display = role === 'kepala' ? 'block' : 'none';
    document.getElementById('siswa_field').style.display = role === 'user' ? 'block' : 'none';
}
</script>

<?php include dirname(__DIR__) . '/layout/footer.php'; ?>
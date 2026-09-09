<?php
session_start();
include dirname(__DIR__) . '/koneksi.php';
include dirname(__DIR__) . '/auth.php';
requireRole('admin');

$judul = 'Edit User';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$data = mysqli_query($koneksi, "SELECT * FROM users WHERE id = $id");
$user = mysqli_fetch_assoc($data);

if (!$user) {
    header("location:tampil.php");
    exit;
}

// Guru yang belum punya user ATAU guru yang sudah link ke user ini
$guru = mysqli_query($koneksi, "
    SELECT g.id, g.nama FROM dataguru g 
    LEFT JOIN users u ON u.id_guru = g.id 
    WHERE u.id IS NULL OR u.id = $id
");

// Siswa yang belum punya user ATAU siswa yang sudah link ke user ini
$siswa = mysqli_query($koneksi, "
    SELECT s.id, s.nama FROM datasiswa s 
    LEFT JOIN users u ON u.id_siswa = s.id 
    WHERE u.id IS NULL OR u.id = $id
");

include dirname(__DIR__) . '/layout/header.php';
?>

<h2>Edit User</h2>

<form action="update.php" method="post">
    <input type="hidden" name="id" value="<?= $user['id'] ?>">

    <div style="margin-bottom: 15px;">
        <label>Username:</label><br>
        <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
    </div>

    <div style="margin-bottom: 15px;">
        <label>Password Baru (kosongkan jika tidak diubah):</label><br>
        <input type="password" name="password" minlength="6">
    </div>

    <div style="margin-bottom: 15px;">
        <label>Nama Lengkap:</label><br>
        <input type="text" name="nama_lengkap" value="<?= htmlspecialchars($user['nama_lengkap']) ?>" required>
    </div>

    <div style="margin-bottom: 15px;">
        <label>Role:</label><br>
        <select name="role" id="role" onchange="toggleLinkFields()" required>
            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
            <option value="kepala" <?= $user['role'] === 'kepala' ? 'selected' : '' ?>>Kepala Sekolah</option>
            <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User (Guru/Siswa)</option>
        </select>
    </div>

    <div id="guru_field" style="display: <?= $user['role'] === 'kepala' ? 'block' : 'none' ?>; margin-bottom: 15px;">
        <label>Link ke Guru:</label><br>
        <select name="id_guru">
            <option value="">-- Pilih Guru --</option>
            <?php while ($g = mysqli_fetch_assoc($guru)) : ?>
                <option value="<?= $g['id'] ?>" <?= $user['id_guru'] == $g['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($g['nama']) ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <div id="siswa_field" style="display: <?= $user['role'] === 'user' ? 'block' : 'none' ?>; margin-bottom: 15px;">
        <label>Link ke Siswa:</label><br>
        <select name="id_siswa">
            <option value="">-- Pilih Siswa --</option>
            <?php while ($s = mysqli_fetch_assoc($siswa)) : ?>
                <option value="<?= $s['id'] ?>" <?= $user['id_siswa'] == $s['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($s['nama']) ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <button class="btn" type="submit">Update</button>
    <a class="btn btn-danger" href="tampil.php">Batal</a>
</form>

<script>
function toggleLinkFields() {
    const role = document.getElementById('role').value;
    document.getElementById('guru_field').style.display = role === 'kepala' ? 'block' : 'none';
    document.getElementById('siswa_field').style.display = role === 'user' ? 'block' : 'none';
}
</script>

<?php include dirname(__DIR__) . '/layout/footer.php'; ?>
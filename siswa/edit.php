<?php
include dirname(__DIR__) . '/koneksi.php';
include dirname(__DIR__) . '/auth.php';
requireRole('admin');

$judul = 'Edit Data Siswa';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$data = mysqli_query($koneksi, "SELECT * FROM datasiswa WHERE id=$id");
$siswa = mysqli_fetch_assoc($data);

if (!$siswa) {
    header("location:tampil.php");
    exit;
}


include dirname(__DIR__) . '/layout/header.php';
?>

<h2>Edit Data Siswa</h2>

<form action="update.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $siswa['id']; ?>">
    <input type="hidden" name="foto_lama" value="<?= $siswa['foto']; ?>">

    <label>Nama:</label><br>
    <input type="text" name="nama" value="<?= htmlspecialchars($siswa['nama']); ?>" required><br>

<label>Kelas:</label><br>

<select name="kelas">
    <option value="">-- Pilih Kelas --</option>
    <option value="X">X</option>
    <option value="XI">XI</option>
    <option value="XII">XII</option>
</select><br>

    <label>Tanggal Lahir:</label><br>
    <input type="date" name="tanggal_lahir" value="<?= $siswa['tanggal_lahir']; ?>" required><br>

    <label>Alamat:</label><br>
    <input type="text" name="alamat" value="<?= htmlspecialchars($siswa['alamat']); ?>" required><br>

    <label>Status:</label><br>
    <select name="status" required>
        <option value="aktif" <?= $siswa['status'] === 'aktif' ? 'selected' : ''; ?>>Aktif</option>
        <option value="tidak aktif" <?= $siswa['status'] === 'tidak aktif' ? 'selected' : ''; ?>>Tidak Aktif</option>
    </select><br>

    <label>Foto Lama:</label><br>
    <img src="<?= BASE_URL; ?>/uploads/foto_siswa/<?= $siswa['foto']; ?>" width="100"><br><br>

    <label>Ganti Foto (opsional):</label><br>
    <input type="file" name="foto"><br>

    <button class="btn" type="submit">Update</button>
    <a class="btn btn-danger" href="tampil.php">Batal</a>
</form>

<?php include dirname(__DIR__) . '/layout/footer.php'; ?>
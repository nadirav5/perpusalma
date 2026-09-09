<?php
include dirname(__DIR__) . '/koneksi.php';
include dirname(__DIR__) . '/auth.php';
requireRole('admin');

$judul = 'Edit Data Ruangan';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$data = mysqli_query($koneksi, "SELECT * FROM dataruang WHERE id_ruang=$id");
$ruang = mysqli_fetch_assoc($data);

if (!$ruang) {
    header("location:tampil.php");
    exit;
}

include dirname(__DIR__) . '/layout/header.php';
?>

<h2>Edit Data Ruangan</h2>

<form action="update.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $ruang['id_ruang']; ?>">
    <input type="hidden" name="foto_lama" value="<?= $ruang['foto']; ?>">

    <label>Nama Ruangan:</label><br>
    <input type="text" name="nama_ruang" value="<?= htmlspecialchars($ruang['nama_ruang']); ?>" required><br>

    <label>Kapasitas:</label><br>
    <input type="number" name="kapasitas" value="<?= htmlspecialchars($ruang['kapasitas']); ?>" min="0" required><br>

    <label>Keterangan:</label><br>
    <textarea name="keterangan" rows="3" style="width:100%; max-width:400px; padding:8px; margin:5px 0 15px; border:1px solid #ccc; border-radius:4px;"><?= htmlspecialchars($ruang['keterangan']); ?></textarea><br>

    <label>Foto Lama:</label><br>
    <img src="<?= BASE_URL; ?>/uploads/foto_ruang/<?= $ruang['foto'] ?: 'default-ruang.jpg'; ?>" width="100"><br><br>

    <label>Ganti Foto (opsional):</label><br>
    <input type="file" name="foto" accept="image/*"><br>

    <button class="btn" type="submit">Update</button>
    <a class="btn btn-danger" href="tampil.php">Batal</a>
</form>

<?php include dirname(__DIR__) . '/layout/footer.php'; ?>
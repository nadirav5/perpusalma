<?php
include dirname(__DIR__) . '/koneksi.php';
include dirname(__DIR__) . '/auth.php';
requireRole('admin');

$judul = 'Edit Data Guru';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$data = mysqli_query($koneksi, "SELECT * FROM dataguru WHERE id=$id");
$guru = mysqli_fetch_assoc($data);

if (!$guru) {
    header("location:tampil.php");
    exit;
}

include dirname(__DIR__) . '/layout/header.php';
?>

<h2>Edit Data Guru</h2>

<form action="update.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $guru['id']; ?>">
    <input type="hidden" name="foto_lama" value="<?= $guru['foto']; ?>">

    <label>Nama:</label><br>
    <input type="text" name="nama" value="<?= htmlspecialchars($guru['nama']); ?>" required><br>

    <label>NIP:</label><br>
    <input type="text" name="nip" value="<?= htmlspecialchars($guru['nip']); ?>" required><br>

    <label>Mata Pelajaran:</label><br>
    <input type="text" name="mapel" value="<?= htmlspecialchars($guru['mapel']); ?>" required><br>

    <label>Alamat:</label><br>
    <input type="text" name="alamat" value="<?= htmlspecialchars($guru['alamat']); ?>" required><br>

    <label>Foto Lama:</label><br>
    <img src="<?= BASE_URL; ?>/uploads/foto_guru/<?= $guru['foto']; ?>" width="100"><br><br>

    <label>Ganti Foto (opsional):</label><br>
    <input type="file" name="foto"><br>

    <button class="btn" type="submit">Update</button>
    <a class="btn btn-danger" href="tampil.php">Batal</a>
</form>

<?php include dirname(__DIR__) . '/layout/footer.php'; ?>
<?php
include dirname(__DIR__) . '/koneksi.php';
include dirname(__DIR__) . '/auth.php';
requireRole('admin');

$judul = 'Tambah Data Ruangan';
include dirname(__DIR__) . '/layout/header.php';
?>

<h2>Tambah Data Ruangan</h2>

<form action="simpan.php" method="post" enctype="multipart/form-data">
    <label>Nama Ruangan:</label><br>
    <input type="text" name="nama_ruang" required><br>

    <label>Kapasitas:</label><br>
    <input type="number" name="kapasitas" min="0" required><br>

    <label>Keterangan:</label><br>
    <textarea name="keterangan" rows="3" style="width:100%; max-width:400px; padding:8px; margin:5px 0 15px; border:1px solid #ccc; border-radius:4px;"></textarea><br>

    <label>Foto Ruangan:</label><br>
    <input type="file" name="foto" accept="image/*"><br>

    <button class="btn" type="submit">Simpan</button>
    <a class="btn" href="tampil.php">Lihat Data</a>
</form>

<?php include dirname(__DIR__) . '/layout/footer.php'; ?>
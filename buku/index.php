<?php
include dirname(__DIR__) . '/koneksi.php';
include dirname(__DIR__) . '/auth.php';
requireRole('admin');

$judul = 'Tambah Data Buku';

include dirname(__DIR__) . '/layout/header.php';
?>

<h2>Tambah Data Buku</h2>

<form action="simpan.php" method="post" enctype="multipart/form-data">
    <label>Judul:</label><br>
    <input type="text" name="judul" required><br>

    <label>Penulis:</label><br>
    <input type="text" name="penulis" required><br>

    <label>Foto:</label><br>
    <input type="file" name="foto" required accept="image/*"><br>

    <label>Kategori:</label><br>
    <input type="text" name="kategori" required><br>

    <label>Stok:</label><br>
    <input type="number" name="stok" min="0" required><br>

    <button class="btn" type="submit">Simpan</button>
    <a class="btn" href="tampil.php">Lihat Data</a>
</form>

<?php include dirname(__DIR__) . '/layout/footer.php'; ?>
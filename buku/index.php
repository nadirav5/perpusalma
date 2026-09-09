<?php
include dirname(__DIR__) . '/koneksi.php';
include dirname(__DIR__) . '/auth.php';
requireRole('admin');

$judul = 'Tambah Data Buku';
$buku = mysqli_query($koneksi, "SELECT id_buku, judul FROM databuku ORDER BY judul");

include dirname(__DIR__) . '/layout/header.php';
?>

<h2>Tambah Data Buku</h2>

<form action="simpan.php" method="post" enctype="multipart/form-data">
    <label>Judul:</label><br>
    <input type="text" name="judul" required><br>

    <label>Foto:</label><br>
    <input type="file" name="foto" required><br>


    <label>Kategori:</label><br>
    <input type="varchar" name="kategori" required><br>

    <label>Stok:</label><br>
    <input type="text" name="stok" required><br>

    <button class="btn" type="submit">Simpan</button>
    <a class="btn" href="tampil.php">Lihat Data</a>
</form>

<?php include dirname(__DIR__) . '/layout/footer.php'; ?>
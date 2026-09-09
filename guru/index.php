<?php
include dirname(__DIR__) . '/koneksi.php';
include dirname(__DIR__) . '/auth.php';
requireRole('admin');

$judul = 'Tambah Data Guru';
include dirname(__DIR__) . '/layout/header.php';
?>

<h2>Tambah Data Guru</h2>

<form action="simpan.php" method="post" enctype="multipart/form-data">
    <label>Nama:</label><br>
    <input type="text" name="nama" required><br>

    <label>NIP:</label><br>
    <input type="text" name="nip" required><br>

    <label>Mata Pelajaran:</label><br>
    <input type="text" name="mapel" required><br>

    <label>Alamat:</label><br>
    <input type="text" name="alamat" required><br>

    <label>Foto:</label><br>
    <input type="file" name="foto" required><br>

    <button class="btn" type="submit">Simpan</button>
    <a class="btn" href="tampil.php">Lihat Data</a>
</form>

<?php include dirname(__DIR__) . '/layout/footer.php'; ?>
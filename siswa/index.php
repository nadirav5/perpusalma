<?php
include dirname(__DIR__) . '/koneksi.php';
include dirname(__DIR__) . '/auth.php';
requireRole('admin');

$judul = 'Tambah Data Siswa';
$kelas = mysqli_query($koneksi, "SELECT id_kelas, namakelas FROM datakelas ORDER BY namakelas");

include dirname(__DIR__) . '/layout/header.php';
?>

<h2>Tambah Data Siswa</h2>

<form action="simpan.php" method="post" enctype="multipart/form-data">
    <label>Nama:</label><br>
    <input type="text" name="nama" required><br>

    <label>Kelas:</label><br>
    <select name="id_kelas">
        <option value="">-- Pilih Kelas (opsional) --</option>
        <?php while ($k = mysqli_fetch_assoc($kelas)) : ?>
            <option value="<?= $k['id_kelas']; ?>"><?= htmlspecialchars($k['namakelas']); ?></option>
        <?php endwhile; ?>
    </select><br>

    <label>Tanggal Lahir:</label><br>
    <input type="date" name="tanggal_lahir" required><br>

    <label>Alamat:</label><br>
    <input type="text" name="alamat" required><br>

    <label>Status:</label><br>
    <select name="status" required>
        <option value="aktif">Aktif</option>
        <option value="tidak aktif">Tidak Aktif</option>
    </select><br>

    <label>Foto:</label><br>
    <input type="file" name="foto" required><br>

    <button class="btn" type="submit">Simpan</button>
    <a class="btn" href="tampil.php">Lihat Data</a>
</form>

<?php include dirname(__DIR__) . '/layout/footer.php'; ?>
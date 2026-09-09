<?php
include dirname(__DIR__) . '/koneksi.php';
include dirname(__DIR__) . '/auth.php';
requireRole('admin');

$judul = 'Tambah Data Kelas';

// Guru yang BELUM menjadi wali kelas di kelas lain
$id_guru = mysqli_query($koneksi, "
    SELECT g.id, g.nama 
    FROM dataguru g 
    WHERE g.id NOT IN (SELECT id_guru FROM datakelas WHERE id_guru IS NOT NULL)
");

// Siswa untuk ketua kelas - KOSONG pada tambah baru (kelas belum ada)
$siswa = mysqli_query($koneksi, "SELECT id, nama FROM datasiswa");
$ruang = mysqli_query($koneksi, "SELECT id_ruang, nama_ruang FROM dataruang");

include dirname(__DIR__) . '/layout/header.php';
?>

<h2>Tambah Data Kelas</h2>

<form action="simpan.php" method="post">
    <label>Nama Kelas:</label><br>
    <input type="text" name="namakelas" required><br>

    <label>Tingkat:</label><br>
    <select name="tingkat" required>
        <option value="">-- Pilih Tingkat --</option>
        <option value="X">Tingkat X</option>
        <option value="XI">Tingkat XI</option>
        <option value="XII">Tingkat XII</option>
    </select><br>

    <label>Tahun Ajaran:</label><br>
    <input type="text" name="tahunajaran" placeholder="Contoh: 2024/2025" required><br>

    <label>Wali Kelas (Guru):</label><br>
    <select name="idguru">
        <option value="">-- Pilih Wali Kelas --</option>
        <?php while ($g = mysqli_fetch_assoc($id_guru)) : ?>
            <option value="<?= $g['id']; ?>"><?= htmlspecialchars($g['nama']); ?></option>
        <?php endwhile; ?>
    </select>
    <?php if (mysqli_num_rows($id_guru) == 0) : ?>
        <small style="color:red;">Tidak ada guru yang tersedia (semua sudah menjadi wali kelas)</small>
    <?php endif; ?>
    <br>

    <label>Ketua Kelas (Siswa):</label><br>
<select name="id_siswa">
    <option value="">-- Pilih Ketua Kelas --</option>
    <?php while($s = mysqli_fetch_assoc($siswa)): ?>
        <option value="<?= $s['id']; ?>"><?= $s['nama']; ?></option>
    <?php endwhile; ?>
</select><br>

    <label>Ruangan:</label><br>
    <select name="idruang">
        <option value="">-- Pilih Ruangan --</option>
        <?php while ($r = mysqli_fetch_assoc($ruang)) : ?>
            <option value="<?= $r['id_ruang']; ?>"><?= htmlspecialchars($r['nama_ruang']); ?></option>
        <?php endwhile; ?>
    </select><br>

    <button class="btn" type="submit">Simpan</button>
    <a class="btn" href="tampil.php">Lihat Data</a>
</form>

<?php include dirname(__DIR__) . '/layout/footer.php'; ?>
<?php
include dirname(__DIR__) . '/koneksi.php';
include dirname(__DIR__) . '/auth.php';
requireRole('admin');

$judul = 'Edit Data Kelas';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$data = mysqli_query($koneksi, "SELECT * FROM datakelas WHERE id_kelas=$id");
$kelas = mysqli_fetch_assoc($data);

if (!$kelas) {
    header("location:tampil.php");
    exit;
}

// Guru yang BELUM menjadi wali kelas di kelas LAIN (kecuali guru yang sudah jadi wali kelas ini)
$guru = mysqli_query($koneksi, "
    SELECT g.id, g.nama 
    FROM dataguru g 
    WHERE g.id NOT IN (
        SELECT idguru FROM datakelas WHERE idguru IS NOT NULL AND id_kelas != $id
    )
");

// Siswa yang terdaftar di KELAS INI saja
$siswa = mysqli_query($koneksi, "SELECT id, nama FROM datasiswa WHERE id_kelas = $id ORDER BY nama");
$ruang = mysqli_query($koneksi, "SELECT id_ruang, nama_ruang FROM dataruang");

include dirname(__DIR__) . '/layout/header.php';
?>

<h2>Edit Data Kelas</h2>

<form action="update.php" method="post">
    <input type="hidden" name="id" value="<?= $kelas['id_kelas']; ?>">

    <label>Nama Kelas:</label><br>
    <input type="text" name="namakelas" value="<?= htmlspecialchars($kelas['namakelas']); ?>" required><br>

    <label>Tingkat:</label><br>
    <select name="tingkat" required>
        <option value="X" <?= $kelas['tingkat'] == 'X' ? 'selected' : ''; ?>>X</option>
        <option value="XI" <?= $kelas['tingkat'] == 'XI' ? 'selected' : ''; ?>>XI</option>
        <option value="XII" <?= $kelas['tingkat'] == 'XII' ? 'selected' : ''; ?>>XII</option>
    </select><br>

    <label>Tahun Ajaran:</label><br>
    <input type="text" name="tahunajaran" value="<?= htmlspecialchars($kelas['tahunajaran']); ?>" placeholder="Contoh: 2024/2025" required><br>

    <label>Wali Kelas (Guru):</label><br>
    <select name="idguru">
        <option value="">-- Pilih Wali Kelas --</option>
        <?php while ($g = mysqli_fetch_assoc($guru)) : ?>
            <option value="<?= $g['id']; ?>" <?= $kelas['idguru'] == $g['id'] ? 'selected' : ''; ?>>
                <?= htmlspecialchars($g['nama']); ?>
            </option>
        <?php endwhile; ?>
    </select>
    <?php if (mysqli_num_rows($guru) == 0 && $kelas['idguru']) : ?>
        <!-- Tampilkan guru saat ini meski tidak di query -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const select = document.querySelector('select[name="idguru"]');
                const currentId = <?= $kelas['idguru']; ?>;
                // Guru saat ini sudah terpilih via selected attribute di atas
            });
        </script>
    <?php endif; ?>
    <br>

    <label>Ketua Kelas (Siswa):</label><br>
    <select name="idsiswa">
        <option value="">-- Pilih Ketua Kelas --</option>
        <?php 
        mysqli_data_seek($siswa, 0);
        while ($s = mysqli_fetch_assoc($siswa)) : ?>
            <option value="<?= $s['id']; ?>" <?= $kelas['idsiswa'] == $s['id'] ? 'selected' : ''; ?>>
                <?= htmlspecialchars($s['nama']); ?>
            </option>
        <?php endwhile; ?>
    </select>
    <?php if (mysqli_num_rows($siswa) == 0) : ?>
        <small style="color:red;">Belum ada siswa di kelas ini</small>
    <?php endif; ?>
    <br>

    <label>Ruangan:</label><br>
    <select name="idruang">
        <option value="">-- Pilih Ruangan --</option>
        <?php while ($r = mysqli_fetch_assoc($ruang)) : ?>
            <option value="<?= $r['id_ruang']; ?>" <?= $kelas['idruang'] == $r['id_ruang'] ? 'selected' : ''; ?>>
                <?= htmlspecialchars($r['nama_ruang']); ?>
            </option>
        <?php endwhile; ?>
    </select><br>

    <button class="btn" type="submit">Update</button>
    <a class="btn btn-danger" href="tampil.php">Batal</a>
</form>

<?php include dirname(__DIR__) . '/layout/footer.php'; ?>
<?php
include dirname(__DIR__) . '/koneksi.php';
include dirname(__DIR__) . '/auth.php';
requireRole(['admin', 'kepala', 'user']);

$judul = 'Detail Siswa';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$query = "SELECT s.*, k.namakelas, k.tingkat, k.tahunajaran, g.nama as wali_kelas, r.nama_ruang
          FROM datasiswa s
          LEFT JOIN datakelas k ON s.id_kelas = k.id_kelas
          LEFT JOIN dataguru g ON k.idguru = g.id
          LEFT JOIN dataruang r ON k.idruang = r.id_ruang
          WHERE s.id = $id";
$data = mysqli_query($koneksi, $query);
$siswa = mysqli_fetch_assoc($data);

if (!$siswa) {
    header("location:tampil.php");
    exit;
}

include dirname(__DIR__) . '/layout/header.php';
?>

<h2>Detail Siswa</h2>

<div style="display: flex; gap: 30px; flex-wrap: wrap; margin-bottom: 20px;">
    <div style="flex: 1; min-width: 200px; text-align: center;">
        <img src="<?= BASE_URL; ?>/uploads/foto_siswa/<?= $siswa['foto'] ?>" width="200" style="border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,.1);">
    </div>
    <div style="flex: 2; min-width: 300px;">
        <table style="width:100%; border-collapse:collapse;">
            <tr>
                <td style="padding:10px; font-weight:bold; width:180px; background:#f8f9fa;">Nama</td>
                <td style="padding:10px;"><?= htmlspecialchars($siswa['nama']) ?></td>
            </tr>
            <tr>
                <td style="padding:10px; font-weight:bold; background:#f8f9fa;">Kelas</td>
                <td style="padding:10px;"><?= htmlspecialchars($siswa['namakelas'] ?? '-') ?> (<?= htmlspecialchars($siswa['tingkat'] ?? '-') ?>)</td>
            </tr>
            <tr>
                <td style="padding:10px; font-weight:bold; background:#f8f9fa;">Tahun Ajaran</td>
                <td style="padding:10px;"><?= htmlspecialchars($siswa['tahunajaran'] ?? '-') ?></td>
            </tr>
            <tr>
                <td style="padding:10px; font-weight:bold; background:#f8f9fa;">Wali Kelas</td>
                <td style="padding:10px;"><?= htmlspecialchars($siswa['wali_kelas'] ?? '-') ?></td>
            </tr>
            <tr>
                <td style="padding:10px; font-weight:bold; background:#f8f9fa;">Ruangan</td>
                <td style="padding:10px;"><?= htmlspecialchars($siswa['nama_ruang'] ?? '-') ?></td>
            </tr>
            <tr>
                <td style="padding:10px; font-weight:bold; background:#f8f9fa;">Tanggal Lahir</td>
                <td style="padding:10px;"><?= htmlspecialchars($siswa['tanggal_lahir']) ?></td>
            </tr>
            <tr>
                <td style="padding:10px; font-weight:bold; background:#f8f9fa;">Alamat</td>
                <td style="padding:10px;"><?= htmlspecialchars($siswa['alamat']) ?></td>
            </tr>
            <tr>
                <td style="padding:10px; font-weight:bold; background:#f8f9fa;">Status</td>
                <td style="padding:10px;">
                    <span style="padding: 5px 12px; border-radius: 4px; background: <?= $siswa['status'] === 'aktif' ? '#27ae60' : '#e74c3c'; ?>; color: white;">
                        <?= htmlspecialchars($siswa['status']) ?>
                    </span>
                </td>
            </tr>
        </table>
    </div>
</div>

<div style="display: flex; gap: 10px;">
    <?php if (hasRole('admin')): ?>
    <a class="btn" href="edit.php?id=<?= $siswa['id'] ?>">Edit</a>
    <a class="btn btn-danger" href="hapus.php?id=<?= $siswa['id'] ?>" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
    <?php endif; ?>
    <a class="btn" href="tampil.php" style="background:#95a5a6;">Kembali</a>
</div>

<?php include dirname(__DIR__) . '/layout/footer.php'; ?>
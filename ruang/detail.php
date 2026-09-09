<?php
include dirname(__DIR__) . '/koneksi.php';
include dirname(__DIR__) . '/auth.php';
requireRole(['admin', 'kepala', 'user']);

$judul = 'Detail Ruangan';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$query = "SELECT r.*, k.namakelas, k.tingkat, g.nama as wali_kelas
          FROM dataruang r
          LEFT JOIN datakelas k ON r.id_ruang = k.idruang
          LEFT JOIN dataguru g ON k.idguru = g.id
          WHERE r.id_ruang = $id";
$data = mysqli_query($koneksi, $query);
$ruang = mysqli_fetch_assoc($data);

if (!$ruang) {
    header("location:tampil.php");
    exit;
}

include dirname(__DIR__) . '/layout/header.php';
?>

<h2>Detail Ruangan</h2>

<div style="display: flex; gap: 30px; flex-wrap: wrap; margin-bottom: 20px;">
    <div style="flex: 1; min-width: 250px; text-align: center;">
        <img src="<?= BASE_URL; ?>/uploads/foto_ruang/<?= $ruang['foto'] ?: 'default-ruang.jpg' ?>" width="300" style="border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,.1);">
    </div>
    <div style="flex: 2; min-width: 300px;">
        <table style="width:100%; border-collapse:collapse;">
            <tr>
                <td style="padding:10px; font-weight:bold; width:180px; background:#f8f9fa;">Nama Ruangan</td>
                <td style="padding:10px;"><?= htmlspecialchars($ruang['nama_ruang']) ?></td>
            </tr>
            <tr>
                <td style="padding:10px; font-weight:bold; background:#f8f9fa;">Kapasitas</td>
                <td style="padding:10px;"><?= $ruang['kapasitas'] ?> orang</td>
            </tr>
            <tr>
                <td style="padding:10px; font-weight:bold; background:#f8f9fa;">Keterangan</td>
                <td style="padding:10px;"><?= htmlspecialchars($ruang['keterangan'] ?? '-') ?></td>
            </tr>
            <tr>
                <td style="padding:10px; font-weight:bold; background:#f8f9fa;">Kelas yang Menggunakan</td>
                <td style="padding:10px;">
                    <?php if ($ruang['namakelas']) : ?>
                        <?= htmlspecialchars($ruang['namakelas']) ?> (<?= htmlspecialchars($ruang['tingkat']) ?>) - Wali: <?= htmlspecialchars($ruang['wali_kelas'] ?? '-') ?>
                    <?php else : ?>
                        <span style="color:#e74c3c;">Belum digunakan</span>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
    </div>
</div>

<div style="display: flex; gap: 10px;">
    <?php if (hasRole('admin')): ?>
    <a class="btn" href="edit.php?id=<?= $ruang['id_ruang'] ?>">Edit</a>
    <a class="btn btn-danger" href="hapus.php?id=<?= $ruang['id_ruang'] ?>" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
    <?php endif; ?>
    <a class="btn" href="tampil.php" style="background:#95a5a6;">Kembali</a>
</div>

<?php include dirname(__DIR__) . '/layout/footer.php'; ?>
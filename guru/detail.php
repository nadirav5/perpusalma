<?php
include dirname(__DIR__) . '/koneksi.php';
include dirname(__DIR__) . '/auth.php';
requireRole(['admin', 'kepala', 'user']);

$judul = 'Detail Guru';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$query = "SELECT g.*, k.namakelas 
          FROM dataguru g
          LEFT JOIN datakelas k ON g.id = k.idguru
          WHERE g.id = $id";
$data = mysqli_query($koneksi, $query);
$guru = mysqli_fetch_assoc($data);

if (!$guru) {
    header("location:tampil.php");
    exit;
}

include dirname(__DIR__) . '/layout/header.php';
?>

<h2>Detail Guru</h2>

<div style="display: flex; gap: 30px; flex-wrap: wrap; margin-bottom: 20px;">
    <div style="flex: 1; min-width: 200px; text-align: center;">
        <img src="<?= BASE_URL; ?>/uploads/foto_guru/<?= $guru['foto'] ?>" width="200" style="border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,.1);">
    </div>
    <div style="flex: 2; min-width: 300px;">
        <table style="width:100%; border-collapse:collapse;">
            <tr>
                <td style="padding:10px; font-weight:bold; width:180px; background:#f8f9fa;">Nama</td>
                <td style="padding:10px;"><?= htmlspecialchars($guru['nama']) ?></td>
            </tr>
            <tr>
                <td style="padding:10px; font-weight:bold; background:#f8f9fa;">NIP</td>
                <td style="padding:10px;"><?= htmlspecialchars($guru['nip']) ?></td>
            </tr>
            <tr>
                <td style="padding:10px; font-weight:bold; background:#f8f9fa;">Mata Pelajaran</td>
                <td style="padding:10px;"><?= htmlspecialchars($guru['mapel']) ?></td>
            </tr>
            <tr>
                <td style="padding:10px; font-weight:bold; background:#f8f9fa;">Alamat</td>
                <td style="padding:10px;"><?= htmlspecialchars($guru['alamat']) ?></td>
            </tr>
            <tr>
                <td style="padding:10px; font-weight:bold; background:#f8f9fa;">Wali Kelas</td>
                <td style="padding:10px;"><?= htmlspecialchars($guru['namakelas'] ?? '-') ?></td>
            </tr>
        </table>
    </div>
</div>

<div style="display: flex; gap: 10px;">
    <?php if (hasRole('admin')): ?>
    <a class="btn" href="edit.php?id=<?= $guru['id'] ?>">Edit</a>
    <a class="btn btn-danger" href="hapus.php?id=<?= $guru['id'] ?>" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
    <?php endif; ?>
    <a class="btn" href="tampil.php" style="background:#95a5a6;">Kembali</a>
</div>

<?php include dirname(__DIR__) . '/layout/footer.php'; ?>
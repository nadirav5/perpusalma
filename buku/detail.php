<?php
include dirname(__DIR__) . '/koneksi.php';
include dirname(__DIR__) . '/auth.php';
requireRole(['admin', 'kepala', 'user']);

$judul = 'Detail Buku';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$query = "SELECT r.*, id_buku, judul, penulis, kategori, stok, foto
          FROM databuku
          LEFT JOIN datakelas k ON r.id_ruang = k.idruang
          LEFT JOIN dataguru g ON k.idguru = g.id
          WHERE r.id_ruang = $id";
$data = mysqli_query($koneksi, $query);
$buku = mysqli_fetch_assoc($data);

if (!$buku) {
    header("location:tampil.php");
    exit;
}

include dirname(__DIR__) . '/layout/header.php';
?>

<h2>Detail Buku</h2>

<div style="display: flex; gap: 30px; flex-wrap: wrap; margin-bottom: 20px;">
    <div style="flex: 1; min-width: 250px; text-align: center;">
        <img src="<?= BASE_URL; ?>/uploads/foto_ruang/<?= $buku['foto'] ?: 'default-ruang.jpg' ?>" width="300" style="border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,.1);">
    </div>
    <div style="flex: 2; min-width: 300px;">
        <table style="width:100%; border-collapse:collapse;">
            <tr>
                <td style="padding:10px; font-weight:bold; width:180px; background:#f8f9fa;">Data Buku</td>
                <td style="padding:10px;"><?= htmlspecialchars($buku['judul']) ?></td>
            </tr>
            <tr>
                <td style="padding:10px; font-weight:bold; width:180px; background:#f8f9fa;">Foto Buku</td>
                <td style="padding:10px;"><?= htmlspecialchars($buku['foto']) ?></td>
            </tr>
            <tr>
                <td style="padding:10px; font-weight:bold; background:#f8f9fa;">Penulis</td>
                <td style="padding:10px;"><?= $buku['penulis'] ?> </td>
            </tr>
            <tr>
                <td style="padding:10px; font-weight:bold; background:#f8f9fa;">Kategori</td>
                <td style="padding:10px;"><?= htmlspecialchars($buku['kategori'] ?? '-') ?></td>
            </tr>
            <tr>
                <td style="padding:10px; font-weight:bold; background:#f8f9fa;">Stok</td>
                <td style="padding:10px;"><?= htmlspecialchars($buku['stok'] ?? '-') ?></td>
</tr>
            
        </table>
    </div>
</div>

<div style="display: flex; gap: 10px;">
    <?php if (hasRole('admin')): ?>
    <a class="btn" href="edit.php?id=<?= $buku['id_buku'] ?>">Edit</a>
    <a class="btn btn-danger" href="hapus.php?id=<?= $buku['id_buku'] ?>" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
    <?php endif; ?>
    <a class="btn" href="tampil.php" style="background:#95a5a6;">Kembali</a>
</div>

<?php include dirname(__DIR__) . '/layout/footer.php'; ?>
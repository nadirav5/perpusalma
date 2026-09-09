<?php
include dirname(__DIR__) . '/koneksi.php';
include dirname(__DIR__) . '/auth.php';
requireRole(['admin', 'kepala']);

$judul = 'Data Siswa';

$perPage = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$search = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : '';
$filter_kelas = isset($_GET['kelas']) ? (int)$_GET['kelas'] : 0;
$filter_status = isset($_GET['status']) ? mysqli_real_escape_string($koneksi, $_GET['status']) : '';

$where = "WHERE 1=1";
if ($search) {
    $where .= " AND (s.nama LIKE '%$search%' OR s.alamat LIKE '%$search%')";
}
if ($filter_kelas) { 
    $where .= " AND s.kelas = '$filter_kelas'"; 
}
if ($filter_status) {
    $where .= " AND s.status = '$filter_status'";
}

$countQuery = "SELECT COUNT(*) as total FROM datasiswa s $where";
$total = mysqli_fetch_assoc(mysqli_query($koneksi, $countQuery))['total'];
$totalPages = ceil($total / $perPage);
$offset = ($page - 1) * $perPage;

$query = "SELECT s.*
          FROM datasiswa s
          $where
          ORDER BY s.nama
          LIMIT $perPage OFFSET $offset";
$data = mysqli_query($koneksi, $query);

$kelasList = ['X', 'XI', 'XII'];
include dirname(__DIR__) . '/layout/header.php';
?>

<h2>Data Siswa</h2>

<form method="GET" style="margin-bottom: 20px; display: flex; gap: 10px; flex-wrap: wrap; align-items: end;">
    <div style="flex: 1; min-width: 100px;">
        <label>Cari Nama/Alamat:</label><br>
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Ketik untuk mencari...">
    </div>
    <div style="min-width: 150px;">
        <label>Filter Kelas:</label><br>
        <select name="kelas">
            <option value="0">-- Semua Kelas --</option>
            <?php foreach ($kelasList as $kls) : ?>
    <option value="<?= $kls ?>" <?= $filter_kelas == $kls ? 'selected' : '' ?>>
        <?= htmlspecialchars($kls) ?>
    </option>
<?php endforeach; ?>
        </select>
    </div>
    <div style="min-width: 120px;">
        <label>Filter Status:</label><br>
        <select name="status">
            <option value="">-- Semua --</option>
            <option value="aktif" <?= $filter_status === 'aktif' ? 'selected' : '' ?>>Aktif</option>
            <option value="tidak aktif" <?= $filter_status === 'tidak aktif' ? 'selected' : '' ?>>Tidak Aktif</option>
        </select>
    </div>
    <?php if (hasRole('admin')): ?>
    <div style="display: flex; flex-direction: column;">
        <label style="visibility: hidden;">Aksi</label>
        <div style="display: flex; gap: 5px;">
            <button class="btn" type="submit">Filter</button>
            <a class="btn" href="tampil.php" style="background:#95a5a6;">Reset</a>
            <a class="btn btn-success" href="index.php">Tambah Data</a>
        </div>
    </div>
    <?php else: ?>
    <div style="display: flex; flex-direction: column;">
        <label style="visibility: hidden;">Aksi</label>
        <div style="display: flex; gap: 5px;">
            <button class="btn" type="submit">Filter</button>
            <a class="btn" href="tampil.php" style="background:#95a5a6;">Reset</a>
        </div>
    </div>
    <?php endif; ?>
</form>

<table>
    <tr>
        <th>No</th>
        <th>Foto</th>
        <th>Nama</th>
        <th>Kelas</th>
        <th>Status</th>
        <th>Aksi</th>
    </tr>

    <?php
    $no = $offset + 1;
    if (mysqli_num_rows($data) === 0) : ?>
        <tr>
            <td colspan="6" style="text-align:center; padding:20px;">Tidak ada data</td>
        </tr>
    <?php else: ?>
        <?php while ($row = mysqli_fetch_assoc($data)) : ?>
            <tr>
                <td><?= $no++; ?></td>
                <td>
                    <img src="<?= BASE_URL; ?>/uploads/foto_siswa/<?= $row['foto'] ?>" width="60" style="border-radius:4px;">
                </td>
                <td><?= htmlspecialchars($row['nama']) ?></td>
                <td><?= htmlspecialchars($row['kelas'] ?? '-') ?></td>
                <td>
                    <span style="padding: 3px 8px; border-radius: 3px; background: <?= $row['status'] === 'aktif' ? '#27ae60' : '#e74c3c'; ?>; color: white; font-size:12px;">
                        <?= htmlspecialchars($row['status']) ?>
                    </span>
                </td>
                <td>
                    <a class="btn" href="detail.php?id=<?= $row['id'] ?>" style="padding:5px 10px; font-size:12px;">Detail</a>
                    <?php if (hasRole('admin')): ?>
                    <a class="btn" href="edit.php?id=<?= $row['id'] ?>" style="padding:5px 10px; font-size:12px;">Edit</a>
                    <a class="btn btn-danger" href="hapus.php?id=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus?')" style="padding:5px 10px; font-size:12px;">Hapus</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php endif; ?>
</table>

<?php if ($totalPages > 1) : ?>
<div style="margin-top: 20px; display: flex; justify-content: center; gap: 5px; flex-wrap: wrap;">
    <?php if ($page > 1) : ?>
        <a class="btn" href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>&kelas=<?= $filter_kelas ?>&status=<?= urlencode($filter_status) ?>">« Prev</a>
    <?php endif; ?>
    <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++) : ?>
        <a class="btn" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&kelas=<?= $filter_kelas ?>&status=<?= urlencode($filter_status) ?>" style="<?= $i == $page ? 'background:#2c3e50;' : '' ?>">
            <?= $i ?>
        </a>
    <?php endfor; ?>
    <?php if ($page < $totalPages) : ?>
        <a class="btn" href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>&kelas=<?= $filter_kelas ?>&status=<?= urlencode($filter_status) ?>">Next »</a>
    <?php endif; ?>
</div>
<p style="text-align:center; margin-top:10px; color:#7f8c8d;">Total: <?= $total ?> data | Halaman <?= $page ?> dari <?= $totalPages ?></p>
<?php endif; ?>

<?php include dirname(__DIR__) . '/layout/footer.php'; ?>
<?php
include dirname(__DIR__) . '/koneksi.php';
include dirname(__DIR__) . '/auth.php';
requireRole(['admin', 'kepala']);

$judul = 'Data Guru';

$perPage = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$search = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : '';
$filter_mapel = isset($_GET['mapel']) ? mysqli_real_escape_string($koneksi, $_GET['mapel']) : '';

$where = "WHERE 1=1";
if ($search) {
    $where .= " AND (nama LIKE '%$search%' OR nip LIKE '%$search%' OR alamat LIKE '%$search%')";
}
if ($filter_mapel) {
    $where .= " AND mapel = '$filter_mapel'";
}

$countQuery = "SELECT COUNT(*) as total FROM dataguru $where";
$total = mysqli_fetch_assoc(mysqli_query($koneksi, $countQuery))['total'];
$totalPages = ceil($total / $perPage);
$offset = ($page - 1) * $perPage;

$query = "SELECT * FROM dataguru $where ORDER BY nama LIMIT $perPage OFFSET $offset";
$data = mysqli_query($koneksi, $query);

$mapelList = mysqli_query($koneksi, "SELECT DISTINCT mapel FROM dataguru ORDER BY mapel");

include dirname(__DIR__) . '/layout/header.php';
?>

<h2>Data Guru</h2>

<form method="GET" style="margin-bottom: 20px; display: flex; gap: 10px; flex-wrap: wrap; align-items: end;">
    <div style="flex: 1; min-width: 200px;">
        <label>Cari Nama/NIP/Alamat:</label><br>
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Ketik untuk mencari...">
    </div>
    <div style="min-width: 150px;">
        <label>Filter Mapel:</label><br>
        <select name="mapel">
            <option value="">-- Semua Mapel --</option>
            <?php while ($m = mysqli_fetch_assoc($mapelList)) : ?>
                <option value="<?= htmlspecialchars($m['mapel']) ?>" <?= $filter_mapel === $m['mapel'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($m['mapel']) ?>
                </option>
            <?php endwhile; ?>
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
        <th>NIP</th>
        <th>Mapel</th>
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
                    <img src="<?= BASE_URL; ?>/uploads/foto_guru/<?= $row['foto'] ?>" width="60" style="border-radius:4px;">
                </td>
                <td><?= htmlspecialchars($row['nama']) ?></td>
                <td><?= htmlspecialchars($row['nip']) ?></td>
                <td><?= htmlspecialchars($row['mapel']) ?></td>
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
        <a class="btn" href="?page=<?= $page-1 ?>&search=<?= urlencode($search) ?>&mapel=<?= urlencode($filter_mapel) ?>">« Prev</a>
    <?php endif; ?>
    <?php for ($i = max(1, $page-2); $i <= min($totalPages, $page+2); $i++) : ?>
        <a class="btn" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&mapel=<?= urlencode($filter_mapel) ?>" style="<?= $i == $page ? 'background:#2c3e50;' : '' ?>">
            <?= $i ?>
        </a>
    <?php endfor; ?>
    <?php if ($page < $totalPages) : ?>
        <a class="btn" href="?page=<?= $page+1 ?>&search=<?= urlencode($search) ?>&mapel=<?= urlencode($filter_mapel) ?>">Next »</a>
    <?php endif; ?>
</div>
<p style="text-align:center; margin-top:10px; color:#7f8c8d;">Total: <?= $total ?> data | Halaman <?= $page ?> dari <?= $totalPages ?></p>
<?php endif; ?>

<?php include dirname(__DIR__) . '/layout/footer.php'; ?>
<?php
session_start();
include dirname(__DIR__) . '/koneksi.php';
include dirname(__DIR__) . '/auth.php';
requireRole('admin');

$judul = 'Kelola User';

$perPage = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$search = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : '';
$filter_role = isset($_GET['role']) ? mysqli_real_escape_string($koneksi, $_GET['role']) : '';

$where = "WHERE 1=1";
if ($search) {
    $where .= " AND (username LIKE '%$search%' OR nama_lengkap LIKE '%$search%')";
}
if ($filter_role) {
    $where .= " AND role = '$filter_role'";
}

$countQuery = "SELECT COUNT(*) as total FROM users $where";
$total = mysqli_fetch_assoc(mysqli_query($koneksi, $countQuery))['total'];
$totalPages = ceil($total / $perPage);
$offset = ($page - 1) * $perPage;

$query = "SELECT * FROM users $where ORDER BY created_at DESC LIMIT $perPage OFFSET $offset";
$data = mysqli_query($koneksi, $query);

include dirname(__DIR__) . '/layout/header.php';
?>

<h2>Kelola User</h2>

<form method="GET" style="margin-bottom: 20px; display: flex; gap: 10px; flex-wrap: wrap; align-items: end;">
    <div style="flex: 1; min-width: 200px;">
        <label>Cari Username/Nama:</label><br>
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Ketik untuk mencari...">
    </div>
    <div style="min-width: 150px;">
        <label>Filter Role:</label><br>
        <select name="role">
            <option value="">-- Semua Role --</option>
            <option value="admin" <?= $filter_role === 'admin' ? 'selected' : '' ?>>Admin</option>
            <option value="kepala" <?= $filter_role === 'kepala' ? 'selected' : '' ?>>Kepala Sekolah</option>
            <option value="user" <?= $filter_role === 'user' ? 'selected' : '' ?>>User (Guru/Siswa)</option>
        </select>
    </div>
    <div style="display: flex; flex-direction: column;">
        <label style="visibility: hidden;">Aksi</label>
        <div style="display: flex; gap: 5px;">
            <button class="btn" type="submit">Filter</button>
            <a class="btn" href="tampil.php" style="background:#95a5a6;">Reset</a>
            <a class="btn btn-success" href="index.php">Tambah User</a>
        </div>
    </div>
</form>

<table>
    <tr>
        <th>No</th>
        <th>Username</th>
        <th>Nama Lengkap</th>
        <th>Role</th>
        <th>Linked</th>
        <th>Dibuat</th>
        <th>Aksi</th>
    </tr>

    <?php
    $no = $offset + 1;
    if (mysqli_num_rows($data) === 0) : ?>
        <tr>
            <td colspan="7" style="text-align:center; padding:20px;">Tidak ada data</td>
        </tr>
    <?php else: ?>
        <?php while ($row = mysqli_fetch_assoc($data)) : ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                <td>
                    <?php
                    $roleLabels = [
                        'admin' => ['label' => 'Admin', 'color' => '#e74c3c'],
                        'kepala' => ['label' => 'Kepala Sekolah', 'color' => '#f39c12'],
                        'user' => ['label' => 'User', 'color' => '#27ae60']
                    ];
                    $rl = $roleLabels[$row['role']] ?? ['label' => ucfirst($row['role']), 'color' => '#95a5a6'];
                    ?>
                    <span style="padding: 3px 8px; border-radius: 3px; background: <?= $rl['color'] ?>; color: white; font-size:12px;">
                        <?= $rl['label'] ?>
                    </span>
                </td>
                <td>
                    <?php if ($row['id_guru']): ?>
                        <span style="font-size:12px;">👨‍🏫 Guru ID: <?= $row['id_guru'] ?></span>
                    <?php elseif ($row['id_siswa']): ?>
                        <span style="font-size:12px;">👨‍🎓 Siswa ID: <?= $row['id_siswa'] ?></span>
                    <?php else: ?>
                        <span style="font-size:12px; color:#95a5a6;">-</span>
                    <?php endif; ?>
                </td>
                <td><?= date('d/m/Y H:i', strtotime($row['created_at'])) ?></td>
                <td>
                    <a class="btn" href="edit.php?id=<?= $row['id'] ?>" style="padding:5px 10px; font-size:12px;">Edit</a>
                    <?php if ($row['id'] !== $_SESSION['user_id']): ?>
                        <a class="btn btn-danger" href="hapus.php?id=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus user ini?')" style="padding:5px 10px; font-size:12px;">Hapus</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php endif; ?>
</table>

<?php if ($totalPages > 1) : ?>
    <div style="margin-top: 20px; display: flex; justify-content: center; gap: 5px; flex-wrap: wrap;">
        <?php if ($page > 1) : ?>
            <a class="btn" href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>&role=<?= urlencode($filter_role) ?>">« Prev</a>
        <?php endif; ?>
        <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++) : ?>
            <a class="btn" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&role=<?= urlencode($filter_role) ?>" style="<?= $i == $page ? 'background:#2c3e50;' : '' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>
        <?php if ($page < $totalPages) : ?>
            <a class="btn" href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>&role=<?= urlencode($filter_role) ?>">Next »</a>
        <?php endif; ?>
    </div>
    <p style="text-align:center; margin-top:10px; color:#7f8c8d;">Total: <?= $total ?> data | Halaman <?= $page ?> dari <?= $totalPages ?></p>
<?php endif; ?>

<?php include dirname(__DIR__) . '/layout/footer.php'; ?>
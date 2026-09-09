<?php
include dirname(__DIR__) . '/koneksi.php';
include dirname(__DIR__) . '/auth.php';
requireRole(['admin', 'kepala', 'user']);

$judul = 'Detail Kelas';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$query = "SELECT k.*, 
                 g.nama AS nama_guru, g.nip, g.mapel, g.alamat AS alamat_guru, g.foto AS foto_guru,
                 s.nama AS nama_siswa, s.tanggal_lahir, s.alamat AS alamat_siswa, s.foto AS foto_siswa,
                 r.nama_ruang, r.kapasitas, r.keterangan,
                 (SELECT COUNT(*) FROM datasiswa WHERE id_kelas = k.id_kelas) AS anggota
          FROM datakelas k
          LEFT JOIN dataguru g ON k.idguru = g.id
          LEFT JOIN datasiswa s ON k.idsiswa = s.id
          LEFT JOIN dataruang r ON k.idruang = r.id_ruang
          WHERE k.id_kelas = $id";
$data = mysqli_query($koneksi, $query);
$kelas = mysqli_fetch_assoc($data);

if (!$kelas) {
    header("location:tampil.php");
    exit;
}

// Ambil daftar siswa di kelas ini
$siswaKelas = mysqli_query($koneksi, "SELECT * FROM datasiswa WHERE id_kelas = $id ORDER BY nama");

include dirname(__DIR__) . '/layout/header.php';
?>

<h2>Detail Kelas: <?= htmlspecialchars($kelas['namakelas']) ?></h2>

<div style="margin-bottom: 20px;">
    <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 20px;">
        <div style="flex: 1; min-width: 250px; background:#f8f9fa; padding:15px; border-radius:6px;">
            <h4 style="margin-bottom:10px; color:#2c3e50; border-bottom:1px solid #ddd; padding-bottom:5px;">Info Kelas</h4>
            <table style="width:100%; border-collapse:collapse;">
                <tr><td style="padding:5px; font-weight:bold; width:120px;">Nama Kelas</td><td style="padding:5px;"><?= htmlspecialchars($kelas['namakelas']) ?></td></tr>
                <tr><td style="padding:5px; font-weight:bold;">Tingkat</td><td style="padding:5px;"><?= htmlspecialchars($kelas['tingkat']) ?></td></tr>
                <tr><td style="padding:5px; font-weight:bold;">Tahun Ajaran</td><td style="padding:5px;"><?= htmlspecialchars($kelas['tahunajaran']) ?></td></tr>
                <tr><td style="padding:5px; font-weight:bold;">Jumlah Anggota</td><td style="padding:5px; font-weight:bold; font-size:16px; color:#3498db;"><?= $kelas['anggota'] ?></td></tr>
            </table>
        </div>
        <div style="flex: 1; min-width: 250px; background:#f8f9fa; padding:15px; border-radius:6px;">
            <h4 style="margin-bottom:10px; color:#2c3e50; border-bottom:1px solid #ddd; padding-bottom:5px;">Ruangan</h4>
            <table style="width:100%; border-collapse:collapse;">
                <tr><td style="padding:5px; font-weight:bold; width:120px;">Nama Ruang</td><td style="padding:5px;"><?= htmlspecialchars($kelas['nama_ruang'] ?? '-') ?></td></tr>
                <tr><td style="padding:5px; font-weight:bold;">Kapasitas</td><td style="padding:5px;"><?= $kelas['kapasitas'] ?? '-' ?> orang</td></tr>
                <tr><td style="padding:5px; font-weight:bold;">Keterangan</td><td style="padding:5px;"><?= htmlspecialchars($kelas['keterangan'] ?? '-') ?></td></tr>
            </table>
        </div>
    </div>

    <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 20px;">
        <div style="flex: 1; min-width: 250px; background:#f8f9fa; padding:15px; border-radius:6px;">
            <h4 style="margin-bottom:10px; color:#2c3e50; border-bottom:1px solid #ddd; padding-bottom:5px;">Wali Kelas</h4>
            <div style="display:flex; align-items:center; gap:15px;">
                <img src="<?= BASE_URL; ?>/uploads/foto_guru/<?= $kelas['foto_guru'] ?? 'man-avatar-icon-free-vector.jpg' ?>" width="80" style="border-radius:4px;">
                <div>
                    <div style="font-weight:bold; font-size:16px;"><?= htmlspecialchars($kelas['nama_guru'] ?? '-') ?></div>
                    <div style="font-size:13px; color:#7f8c8d;">NIP: <?= htmlspecialchars($kelas['nip'] ?? '-') ?></div>
                    <div style="font-size:13px; color:#7f8c8d;">Mapel: <?= htmlspecialchars($kelas['mapel'] ?? '-') ?></div>
                </div>
            </div>
        </div>
        <div style="flex: 1; min-width: 250px; background:#f8f9fa; padding:15px; border-radius:6px;">
            <h4 style="margin-bottom:10px; color:#2c3e50; border-bottom:1px solid #ddd; padding-bottom:5px;">Ketua Kelas</h4>
            <div style="display:flex; align-items:center; gap:15px;">
                <img src="<?= BASE_URL; ?>/uploads/foto_siswa/<?= $kelas['foto_siswa'] ?? 'avatar-siswa-lk.jpg' ?>" width="80" style="border-radius:4px;">
                <div>
                    <div style="font-weight:bold; font-size:16px;"><?= htmlspecialchars($kelas['nama_siswa'] ?? '-') ?></div>
                    <div style="font-size:13px; color:#7f8c8d;">Tgl Lahir: <?= htmlspecialchars($kelas['tanggal_lahir'] ?? '-') ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<h3 style="margin-bottom:15px; color:#2c3e50;">Daftar Siswa (<?= $kelas['anggota'] ?> orang)</h3>
<table>
    <tr>
        <th>No</th>
        <th>Foto</th>
        <th>Nama</th>
        <th>Tgl Lahir</th>
        <th>Alamat</th>
        <th>Status</th>
    </tr>
    <?php 
    $no = 1;
    if (mysqli_num_rows($siswaKelas) === 0) : ?>
        <tr>
            <td colspan="6" style="text-align:center; padding:20px;">Belum ada siswa di kelas ini</td>
        </tr>
    <?php else: ?>
        <?php while ($s = mysqli_fetch_assoc($siswaKelas)) : ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><img src="<?= BASE_URL; ?>/uploads/foto_siswa/<?= $s['foto'] ?>" width="50" style="border-radius:4px;"></td>
                <td><?= htmlspecialchars($s['nama']) ?></td>
                <td><?= htmlspecialchars($s['tanggal_lahir']) ?></td>
                <td><?= htmlspecialchars($s['alamat']) ?></td>
                <td>
                    <span style="padding: 3px 8px; border-radius: 3px; background: <?= $s['status'] === 'aktif' ? '#27ae60' : '#e74c3c'; ?>; color: white; font-size:11px;">
                        <?= htmlspecialchars($s['status']) ?>
                    </span>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php endif; ?>
</table>

<div style="margin-top:20px; display:flex; gap:10px;">
    <?php if (hasRole('admin')): ?>
    <a class="btn" href="edit.php?id=<?= $kelas['id_kelas'] ?>">Edit</a>
    <a class="btn btn-danger" href="hapus.php?id=<?= $kelas['id_kelas'] ?>" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
    <?php endif; ?>
    <a class="btn" href="tampil.php" style="background:#95a5a6;">Kembali</a>
</div>

<?php include dirname(__DIR__) . '/layout/footer.php'; ?>
<?php
include __DIR__ . '/koneksi.php';
include __DIR__ . '/auth.php';
requireLogin();

$judul = 'Dashboard';

$jml_siswa = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM datasiswa"))['total'];
$jml_guru  = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM dataguru"))['total'];
$jml_kelas = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM datakelas"))['total'];
$jml_ruang = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM dataruang"))['total'];
$jml_buku  = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM databuku"))['total'];

include BASE_PATH . '/layout/header.php';
?>

<h2>Dashboard</h2>

<p>Selamat datang, <strong><?= htmlspecialchars($_SESSION['nama_lengkap']) ?></strong>! Anda login sebagai <span style="text-transform: capitalize;"><?= $_SESSION['role'] ?></span>.</p>
<br>

<div style="display: flex; gap: 20px; flex-wrap: wrap;">
    <div style="flex: 1; min-width: 200px; background: #3498db; color: #fff; padding: 20px; border-radius: 6px;">
        <h3 style="margin-bottom: 5px;"><?= $jml_siswa; ?></h3>
        <p>Data Siswa</p>
        <br>
        <a class="btn" href="<?= BASE_URL; ?>/siswa/tampil.php" style="background:#fff; color:#3498db;">Lihat</a>
    </div>
    <div style="flex: 1; min-width: 200px; background: #e67e22; color: #fff; padding: 20px; border-radius: 6px;">
        <h3 style="margin-bottom: 5px;"><?= $jml_guru; ?></h3>
        <p>Data Guru</p>
        <br>
        <a class="btn" href="<?= BASE_URL; ?>/guru/tampil.php" style="background:#fff; color:#e67e22;">Lihat</a>
    </div>
    <div style="flex: 1; min-width: 200px; background: #9b59b6; color: #fff; padding: 20px; border-radius: 6px;">
        <h3 style="margin-bottom: 5px;"><?= $jml_kelas; ?></h3>
        <p>Data Kelas</p>
        <br>
        <a class="btn" href="<?= BASE_URL; ?>/kelas/tampil.php" style="background:#fff; color:#9b59b6;">Lihat</a>
    </div>
    <div style="flex: 1; min-width: 200px; background: #1abc9c; color: #fff; padding: 20px; border-radius: 6px;">
        <h3 style="margin-bottom: 5px;"><?= $jml_ruang; ?></h3>
        <p>Data Ruangan</p>
        <br>
        <a class="btn" href="<?= BASE_URL; ?>/ruang/tampil.php" style="background:#fff; color:#1abc9c;">Lihat</a>
    </div>

<div style="flex: 1; min-width: 200px; background: #9b59b6; color: #fff; padding: 20px; border-radius: 6px;">
        <h3 style="margin-bottom: 5px;"><?= $jml_buku; ?></h3>
        <p>Data Buku</p>
        <br>
        <a class="btn" href="<?= BASE_URL; ?>/buku/tampil.php" style="background:#fff; color:#9b59b6;">Lihat</a>
    </div>

</div>

<?php include BASE_PATH . '/layout/footer.php'; ?>
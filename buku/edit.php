<?php
include dirname(__DIR__) . '/koneksi.php';
include dirname(__DIR__) . '/auth.php';

requireRole('admin');

$judul = 'Edit Data Buku';

/* Ambil ID buku */
$id_buku = isset($_GET['id_buku']) ? (int)$_GET['id_buku'] : 0;

/* Ambil data buku */
$data = mysqli_query(
    $koneksi,
    "SELECT * FROM databuku WHERE id_buku = $id_buku"
);

$buku = mysqli_fetch_assoc($data);

/* Jika data tidak ditemukan */
if (!$buku) {
    header("Location: tampil.php");
    exit;
}

include dirname(__DIR__) . '/layout/header.php';
?>

<h2>Edit Data Buku</h2>

<form action="update.php" method="post" enctype="multipart/form-data">

    <!-- ID BUKU -->
    <input type="hidden" name="id_buku" value="<?= $buku['id_buku']; ?>">

    <!-- FOTO LAMA -->
    <input type="hidden" name="foto_lama" value="<?= htmlspecialchars($buku['foto']); ?>">

    <label>Judul:</label><br>
    <input
        type="text"
        name="judul"
        value="<?= htmlspecialchars($buku['judul']); ?>"
        required
    >
    <br><br>

    <label>Foto Lama:</label><br>

    <?php if (!empty($buku['foto'])): ?>
        <img
            src="<?= BASE_URL; ?>/uploads/foto_buku/<?= htmlspecialchars($buku['foto']); ?>"
            width="100"
        >
    <?php else: ?>
        <p>Tidak ada foto</p>
    <?php endif; ?>

    <br><br>

    <label>Ganti Foto (opsional):</label><br>
    <input
        type="file"
        name="foto"
        accept=".jpg,.jpeg,.png,.webp"
    >

    <br><br>

    <label>Penulis:</label><br>
    <input
        type="text"
        name="penulis"
        value="<?= htmlspecialchars($buku['penulis']); ?>"
        required
    >

    <br><br>

    <label>Kategori:</label><br>
    <input
        type="text"
        name="kategori"
        value="<?= htmlspecialchars($buku['kategori']); ?>"
        required
    >

    <br><br>

    <label>Stok:</label><br>
    <input
        type="number"
        name="stok"
        value="<?= htmlspecialchars($buku['stok']); ?>"
        min="0"
        required
    >

    <br><br>

    <button class="btn" type="submit">Update</button>

    <a class="btn btn-danger" href="tampil.php">Batal</a>

</form>

<?php include dirname(__DIR__) . '/layout/footer.php'; ?>
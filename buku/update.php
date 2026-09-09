<?php
include dirname(__DIR__) . '/koneksi.php';
include dirname(__DIR__) . '/auth.php';

requireRole('admin');

/* =========================
   AMBIL DATA DARI FORM
========================= */

$id_buku = isset($_POST['id_buku'])
    ? (int)$_POST['id_buku']
    : 0;

$judul = isset($_POST['judul'])
    ? mysqli_real_escape_string($koneksi, trim($_POST['judul']))
    : '';

$foto_lama = isset($_POST['foto_lama'])
    ? mysqli_real_escape_string($koneksi, $_POST['foto_lama'])
    : '';

$penulis = isset($_POST['penulis'])
    ? mysqli_real_escape_string($koneksi, trim($_POST['penulis']))
    : '';

$kategori = isset($_POST['kategori'])
    ? mysqli_real_escape_string($koneksi, trim($_POST['kategori']))
    : '';

$stok = isset($_POST['stok'])
    ? (int)$_POST['stok']
    : 0;


/* =========================
   CEK ID
========================= */

if ($id_buku <= 0) {
    die("ID buku tidak valid.");
}


/* =========================
   CEK FOTO BARU
========================= */

$foto_baru = '';

if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {

    $namaFoto = $_FILES['foto']['name'];
    $tmpFoto = $_FILES['foto']['tmp_name'];

    $ekstensi = strtolower(
        pathinfo($namaFoto, PATHINFO_EXTENSION)
    );

    $ekstensiAllowed = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($ekstensi, $ekstensiAllowed)) {
        die("Format foto tidak diperbolehkan.");
    }

    /* Buat nama foto baru */
    $foto_baru = time() . '_' . basename($namaFoto);

    $folder = BASE_PATH . '/uploads/foto_buku/';

    /* Buat folder jika belum ada */
    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    /* Upload foto baru */
    if (!move_uploaded_file($tmpFoto, $folder . $foto_baru)) {
        die("Foto gagal diupload.");
    }

    /* Hapus foto lama */
    if (!empty($foto_lama) &&
        file_exists($folder . $foto_lama)) {

        unlink($folder . $foto_lama);
    }


    /* =========================
       UPDATE DENGAN FOTO BARU
    ========================= */

    $query = "UPDATE databuku SET
                judul='$judul',
                foto='$foto_baru',
                penulis='$penulis',
                kategori='$kategori',
                stok=$stok
              WHERE id_buku=$id_buku";

} else {

    /* =========================
       UPDATE TANPA GANTI FOTO
    ========================= */

    $query = "UPDATE databuku SET
                judul='$judul',
                penulis='$penulis',
                kategori='$kategori',
                stok=$stok
              WHERE id_buku=$id_buku";
}


/* =========================
   JALANKAN QUERY
========================= */

$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Gagal mengupdate data buku: " . mysqli_error($koneksi));
}


/* =========================
   KEMBALI KE DATA BUKU
========================= */

header("Location: tampil.php");
exit;
?>
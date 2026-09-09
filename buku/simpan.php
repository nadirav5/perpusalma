<?php

include dirname(__DIR__) . '/koneksi.php';
include dirname(__DIR__) . '/auth.php';

requireRole('admin');


/* =========================
   AMBIL DATA FORM
========================= */

$judul = isset($_POST['judul'])
    ? mysqli_real_escape_string($koneksi, trim($_POST['judul']))
    : '';

$penulis = isset($_POST['penulis'])
    ? mysqli_real_escape_string($koneksi, trim($_POST['penulis']))
    : '';

$kategori = isset($_POST['kategori'])
    ? mysqli_real_escape_string($koneksi, trim($_POST['kategori']))
    : '';

$stok = isset($_POST['stok'])
    ? (int) $_POST['stok']
    : 0;


/* =========================
   CEK JUDUL
========================= */

if ($judul === '') {
    die('Judul buku wajib diisi.');
}


/* =========================
   FOTO
========================= */

$foto = '';

if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {

    $namaFoto = $_FILES['foto']['name'];
    $tmpFoto = $_FILES['foto']['tmp_name'];

    $ekstensi = strtolower(
        pathinfo($namaFoto, PATHINFO_EXTENSION)
    );

    $ekstensiAllowed = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($ekstensi, $ekstensiAllowed)) {
        die('Format foto tidak diperbolehkan.');
    }

    /* Buat nama file unik */
    $foto = time() . '_' . basename($namaFoto);

    $folder = BASE_PATH . '/uploads/foto_buku/';

    /* Pastikan folder ada */
    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    move_uploaded_file(
        $tmpFoto,
        $folder . $foto
    );
}


/* =========================
   SIMPAN KE DATABASE
========================= */

$query = "INSERT INTO databuku
          (judul, foto, penulis, kategori, stok)
          VALUES
          ('$judul', '$foto', '$penulis', '$kategori', $stok)";


$result = mysqli_query($koneksi, $query);


/* =========================
   CEK HASIL
========================= */

if (!$result) {
    die("Gagal menyimpan data buku: " . mysqli_error($koneksi));
}


/* =========================
   KEMBALI KE DATA BUKU
========================= */

header("Location: tampil.php");
exit;

?>
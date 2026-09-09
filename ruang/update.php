<?php
include dirname(__DIR__) . '/koneksi.php';
include dirname(__DIR__) . '/auth.php';
requireRole('admin');

$id         = (int)$_POST['id'];
$nama_ruang = mysqli_real_escape_string($koneksi, $_POST['nama_ruang']);
$kapasitas  = (int)$_POST['kapasitas'];
$keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan']);
$foto_lama  = mysqli_real_escape_string($koneksi, $_POST['foto_lama']);

$foto_baru = $_FILES['foto']['name'];
$tmp       = $_FILES['foto']['tmp_name'];

if ($foto_baru != "") {
    if ($foto_lama != "" && $foto_lama != 'default-ruang.jpg' && file_exists(BASE_PATH . '/uploads/foto_ruang/' . $foto_lama)) {
        unlink(BASE_PATH . '/uploads/foto_ruang/' . $foto_lama);
    }

    move_uploaded_file($tmp, BASE_PATH . '/uploads/foto_ruang/' . $foto_baru);

    $query = "UPDATE dataruang SET
              nama_ruang='$nama_ruang',
              kapasitas='$kapasitas',
              keterangan='$keterangan',
              foto='$foto_baru'
              WHERE id_ruang=$id";
} else {
    $query = "UPDATE dataruang SET
              nama_ruang='$nama_ruang',
              kapasitas='$kapasitas',
              keterangan='$keterangan'
              WHERE id_ruang=$id";
}

mysqli_query($koneksi, $query);
header("location:tampil.php");
<?php
include dirname(__DIR__) . '/koneksi.php';
include dirname(__DIR__) . '/auth.php';
requireRole('admin');

$nama_ruang = mysqli_real_escape_string($koneksi, $_POST['nama_ruang']);
$kapasitas  = (int)$_POST['kapasitas'];
$keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan']);

$foto = $_FILES['foto']['name'];
$tmp  = $_FILES['foto']['tmp_name'];

if ($foto != "") {
    move_uploaded_file($tmp, BASE_PATH . '/uploads/foto_ruang/' . $foto);
} else {
    $foto = 'default-ruang.jpg';
}

$query = "INSERT INTO dataruang (nama_ruang, kapasitas, keterangan, foto) VALUES ('$nama_ruang', '$kapasitas', '$keterangan', '$foto')";
mysqli_query($koneksi, $query);

header("location:tampil.php");
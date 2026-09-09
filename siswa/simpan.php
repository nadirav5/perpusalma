<?php
include dirname(__DIR__) . '/koneksi.php';
include dirname(__DIR__) . '/auth.php';
requireRole('admin');

$nama      = mysqli_real_escape_string($koneksi, $_POST['nama']);
$id_kelas  = $_POST['id_kelas'] ? (int)$_POST['id_kelas'] : 'NULL';
$tanggal_lahir = mysqli_real_escape_string($koneksi, $_POST['tanggal_lahir']);
$alamat    = mysqli_real_escape_string($koneksi, $_POST['alamat']);
$status    = mysqli_real_escape_string($koneksi, $_POST['status']);

$foto = $_FILES['foto']['name'];
$tmp  = $_FILES['foto']['tmp_name'];

move_uploaded_file($tmp, BASE_PATH . '/uploads/foto_siswa/' . $foto);

// Column order: id, nama, status, tanggal_lahir, alamat, foto, id_kelas
$query = "INSERT INTO datasiswa VALUES (NULL, '$nama', '$status', '$tanggal_lahir', '$alamat', '$foto', $id_kelas)";
mysqli_query($koneksi, $query);

header("location:tampil.php");
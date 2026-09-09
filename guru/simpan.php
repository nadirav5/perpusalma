<?php
include dirname(__DIR__) . '/koneksi.php';
include dirname(__DIR__) . '/auth.php';
requireRole('admin');

$nama   = mysqli_real_escape_string($koneksi, $_POST['nama']);
$nip    = mysqli_real_escape_string($koneksi, $_POST['nip']);
$mapel  = mysqli_real_escape_string($koneksi, $_POST['mapel']);
$alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);

$foto = $_FILES['foto']['name'];
$tmp  = $_FILES['foto']['tmp_name'];

move_uploaded_file($tmp, BASE_PATH . '/uploads/foto_guru/' . $foto);

$query = "INSERT INTO dataguru VALUES (NULL, '$nama', '$nip', '$mapel', '$alamat', '$foto')";
mysqli_query($koneksi, $query);

header("location:tampil.php");
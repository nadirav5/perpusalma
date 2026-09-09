<?php
include dirname(__DIR__) . '/koneksi.php';
include dirname(__DIR__) . '/auth.php';
requireRole('admin');

$id        = (int)$_POST['id'];
$nama      = mysqli_real_escape_string($koneksi, $_POST['nama']);
$nip       = mysqli_real_escape_string($koneksi, $_POST['nip']);
$mapel     = mysqli_real_escape_string($koneksi, $_POST['mapel']);
$alamat    = mysqli_real_escape_string($koneksi, $_POST['alamat']);
$foto_lama = mysqli_real_escape_string($koneksi, $_POST['foto_lama']);

$foto_baru = $_FILES['foto']['name'];
$tmp       = $_FILES['foto']['tmp_name'];

if ($foto_baru != "") {
    if (file_exists(BASE_PATH . '/uploads/foto_guru/' . $foto_lama)) {
        unlink(BASE_PATH . '/uploads/foto_guru/' . $foto_lama);
    }

    move_uploaded_file($tmp, BASE_PATH . '/uploads/foto_guru/' . $foto_baru);

    $query = "UPDATE dataguru SET
              nama='$nama',
              nip='$nip',
              mapel='$mapel',
              alamat='$alamat',
              foto='$foto_baru'
              WHERE id='$id'";
} else {
    $query = "UPDATE dataguru SET
              nama='$nama',
              nip='$nip',
              mapel='$mapel',
              alamat='$alamat'
              WHERE id='$id'";
}

mysqli_query($koneksi, $query);
header("location:tampil.php");
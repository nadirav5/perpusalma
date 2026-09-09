<?php
include dirname(__DIR__) . '/koneksi.php';
include dirname(__DIR__) . '/auth.php';
requireRole('admin');

$id         = (int)$_POST['id'];
$nama       = mysqli_real_escape_string($koneksi, $_POST['nama']);
$kelas   = $_POST['kelas'] ? (int)$_POST['kelas'] : 'NULL';
$tanggal_lahir = mysqli_real_escape_string($koneksi, $_POST['tanggal_lahir']);
$alamat     = mysqli_real_escape_string($koneksi, $_POST['alamat']);
$status     = mysqli_real_escape_string($koneksi, $_POST['status']);
$foto_lama  = mysqli_real_escape_string($koneksi, $_POST['foto_lama']);

$foto_baru = $_FILES['foto']['name'];
$tmp       = $_FILES['foto']['tmp_name'];

if ($foto_baru != "") {
    if (file_exists(BASE_PATH . '/uploads/foto_siswa/' . $foto_lama)) {
        unlink(BASE_PATH . '/uploads/foto_siswa/' . $foto_lama);
    }

    move_uploaded_file($tmp, BASE_PATH . '/uploads/foto_siswa/' . $foto_baru);

    $query = "UPDATE datasiswa SET
              nama='$nama',
              status='$status',
              tanggal_lahir='$tanggal_lahir',
              alamat='$alamat',
              foto='$foto_baru',
              kelas=$kelas
              WHERE ='$id'";
} else {
    $query = "UPDATE datasiswa SET
              nama='$nama',
              status='$status',
              tanggal_lahir='$tanggal_lahir',
              alamat='$alamat',
              kelas=$kelas
              WHERE id='$id'";
}

mysqli_query($koneksi, $query);
header("location:tampil.php");
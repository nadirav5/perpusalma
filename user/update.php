<?php
session_start();
include dirname(__DIR__) . '/koneksi.php';
include dirname(__DIR__) . '/auth.php';
requireRole('admin');

$id = (int)$_POST['id'];
$username = mysqli_real_escape_string($koneksi, $_POST['username']);
$nama_lengkap = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
$role = mysqli_real_escape_string($koneksi, $_POST['role']);
$id_guru = $_POST['id_guru'] ? (int)$_POST['id_guru'] : 'NULL';
$id_siswa = $_POST['id_siswa'] ? (int)$_POST['id_siswa'] : 'NULL';

if ($_POST['password']) {
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $query = "UPDATE users SET 
              username='$username', 
              password='$password', 
              nama_lengkap='$nama_lengkap', 
              role='$role', 
              id_guru=$id_guru, 
              id_siswa=$id_siswa 
              WHERE id=$id";
} else {
    $query = "UPDATE users SET 
              username='$username', 
              nama_lengkap='$nama_lengkap', 
              role='$role', 
              id_guru=$id_guru, 
              id_siswa=$id_siswa 
              WHERE id=$id";
}

mysqli_query($koneksi, $query);
header("location:tampil.php");
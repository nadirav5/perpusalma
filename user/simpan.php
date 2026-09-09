<?php
session_start();
include dirname(__DIR__) . '/koneksi.php';
include dirname(__DIR__) . '/auth.php';
requireRole('admin');

$username = mysqli_real_escape_string($koneksi, $_POST['username']);
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$nama_lengkap = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
$role = mysqli_real_escape_string($koneksi, $_POST['role']);
$id_guru = $_POST['id_guru'] ? (int)$_POST['id_guru'] : 'NULL';
$id_siswa = $_POST['id_siswa'] ? (int)$_POST['id_siswa'] : 'NULL';

$query = "INSERT INTO users (username, password, nama_lengkap, role, id_guru, id_siswa) 
          VALUES ('$username', '$password', '$nama_lengkap', '$role', $id_guru, $id_siswa)";

mysqli_query($koneksi, $query);

header("location:tampil.php");
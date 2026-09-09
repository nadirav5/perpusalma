<?php
include dirname(__DIR__) . '/koneksi.php';
include dirname(__DIR__) . '/auth.php';
requireRole('admin');

$nama_kelas  = mysqli_real_escape_string($koneksi, $_POST['nama_kelas']);
$tingkat    = mysqli_real_escape_string($koneksi, $_POST['tingkat']);
$tahun_ajaran = mysqli_real_escape_string($koneksi, $_POST['tahun_ajaran']);
$id_guru     = $_POST['id_guru'] ? (int)$_POST['id_guru'] : 'NULL';
$id_siswa    = $_POST['id_siswa'] ? (int)$_POST['id_siswa'] : 'NULL';
$id_ruang    = $_POST['id_ruang'] ? (int)$_POST['id_ruang'] : 'NULL';

$query = "INSERT INTO datakelas (nama_kelas, tingkat, tahun_ajaran, id_guru, id_siswa, id_ruang) 
          VALUES ('$nama_kelas', '$tingkat', '$tahun_ajaran', $id_guru, $id_siswa, $id_ruang)";

mysqli_query($koneksi, $query);

header("location:tampil.php");
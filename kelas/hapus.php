<?php
include dirname(__DIR__) . '/koneksi.php';
include dirname(__DIR__) . '/auth.php';
requireRole('admin');

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Set id_kelas to NULL in datasiswa before deleting the class
mysqli_query($koneksi, "UPDATE datasiswa SET id_kelas = NULL WHERE id_kelas = $id");

mysqli_query($koneksi, "DELETE FROM datakelas WHERE id_kelas=$id");

header("location:tampil.php");
<?php
include dirname(__DIR__) . '/koneksi.php';
include dirname(__DIR__) . '/auth.php';
requireRole('admin');

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$data = mysqli_query($koneksi, "SELECT foto FROM dataruang WHERE id_ruang=$id");
$ruang = mysqli_fetch_assoc($data);

if ($ruang) {
    if ($ruang['foto'] != "" && $ruang['foto'] != 'default-ruang.jpg' && file_exists(BASE_PATH . '/uploads/foto_ruang/' . $ruang['foto'])) {
        unlink(BASE_PATH . '/uploads/foto_ruang/' . $ruang['foto']);
    }

    mysqli_query($koneksi, "DELETE FROM dataruang WHERE id_ruang=$id");
}

header("location:tampil.php");
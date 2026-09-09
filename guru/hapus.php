<?php
include dirname(__DIR__) . '/koneksi.php';
include dirname(__DIR__) . '/auth.php';
requireRole('admin');

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$data = mysqli_query($koneksi, "SELECT * FROM dataguru WHERE id=$id");
$guru = mysqli_fetch_assoc($data);

if ($guru) {
    if ($guru['foto'] != "" && file_exists(BASE_PATH . '/uploads/foto_guru/' . $guru['foto'])) {
        unlink(BASE_PATH . '/uploads/foto_guru/' . $guru['foto']);
    }

    mysqli_query($koneksi, "DELETE FROM dataguru WHERE id=$id");
}

header("location:tampil.php");
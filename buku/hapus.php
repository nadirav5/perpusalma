<?php
include dirname(__DIR__) . '/koneksi.php';
include dirname(__DIR__) . '/auth.php';
requireRole('admin');

$id = isset($_GET['id_buku']) ? (int)$_GET['id_buku'] : 0;

$data = mysqli_query($koneksi, "SELECT * FROM databuku WHERE id_buku=$id");
$buku = mysqli_fetch_assoc($data);

if ($buku) {
    if ($buku['foto'] != "" && file_exists(BASE_PATH . '/uploads/foto_buku/' . $buku['foto'])) {
        unlink(BASE_PATH . '/uploads/foto_buku/' . $buku['foto']);
    }

    mysqli_query($koneksi, "DELETE FROM databuku WHERE id_buku=$id");
}

header("location:tampil.php");
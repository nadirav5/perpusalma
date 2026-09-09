<?php
session_start();
include dirname(__DIR__) . '/koneksi.php';
include dirname(__DIR__) . '/auth.php';
requireRole('admin');

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Prevent deleting own account
if ($id === $_SESSION['user_id']) {
    header("location:tampil.php");
    exit;
}

mysqli_query($koneksi, "DELETE FROM users WHERE id=$id");

header("location:tampil.php");
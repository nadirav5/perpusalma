<?php
$koneksi = mysqli_connect("localhost", "root", "", "praktikum11");

if (!$koneksi) {
    die("Koneksi database gagal");
}

if (!defined('BASE_PATH')) define('BASE_PATH', __DIR__);
if (!defined('BASE_URL')) define('BASE_URL', '/praktikum11');

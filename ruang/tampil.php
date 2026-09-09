<?php
include dirname(__DIR__) . '/koneksi.php';
include dirname(__DIR__) . '/auth.php';

requireRole(['admin', 'kepala']);

$judul = 'Data Ruangan';

$perPage = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;

$search = isset($_GET['search'])
    ? mysqli_real_escape_string($koneksi, $_GET['search'])
    : '';

$where = "WHERE 1=1";

if ($search) {
    $where .= " AND (
        nama_ruang LIKE '%$search%'
        OR keterangan LIKE '%$search%'
    )";
}

/* =========================
   HITUNG TOTAL DATA
========================= */
$countQuery = "SELECT COUNT(*) AS total 
               FROM dataruang 
               $where";

$countResult = mysqli_query($koneksi, $countQuery);

$total = mysqli_fetch_assoc($countResult)['total'];

$totalPages = ceil($total / $perPage);

$offset = ($page - 1) * $perPage;

/* =========================
   AMBIL DATA RUANGAN
========================= */
$query = "SELECT *
          FROM dataruang
          $where
          ORDER BY nama_ruang
          LIMIT $perPage OFFSET $offset";

$data = mysqli_query($koneksi, $query);

if (!$data) {
    die("Query gagal: " . mysqli_error($koneksi));
}

include dirname(__DIR__) . '/layout/header.php';
?>

<h2>Data Ruangan</h2>

<!-- =========================
     FORM PENCARIAN
========================= -->

<form method="GET"
      style="margin-bottom:20px; display:flex; gap:10px; flex-wrap:wrap; align-items:end;">

    <div style="flex:1; min-width:200px;">
        <label>Cari Nama/Keterangan:</label><br>

        <input
            type="text"
            name="search"
            value="<?= htmlspecialchars($search) ?>"
            placeholder="Ketik untuk mencari..."
        >
    </div>

    <?php if (hasRole('admin')): ?>

        <div style="display:flex; flex-direction:column;">

            <label style="visibility:hidden;">Aksi</label>

            <div style="display:flex; gap:5px;">

                <button class="btn" type="submit">
                    Filter
                </button>

                <a
                    class="btn"
                    href="tampil.php"
                    style="background:#95a5a6;"
                >
                    Reset
                </a>

                <a
                    class="btn btn-success"
                    href="index.php"
                >
                    Tambah Data
                </a>

            </div>
        </div>

    <?php else: ?>

        <div style="display:flex; flex-direction:column;">

            <label style="visibility:hidden;">Aksi</label>

            <div style="display:flex; gap:5px;">

                <button class="btn" type="submit">
                    Filter
                </button>

                <a
                    class="btn"
                    href="tampil.php"
                    style="background:#95a5a6;"
                >
                    Reset
                </a>

            </div>
        </div>

    <?php endif; ?>

</form>


<!-- =========================
     TABEL DATA RUANGAN
========================= -->

<table>

    <tr>
        <th>No</th>
        <th>Foto</th>
        <th>Nama Ruangan</th>
        <th>Kapasitas</th>
        <th>Keterangan</th>
        <th>Aksi</th>
    </tr>


    <?php

    $no = $offset + 1;

    if (mysqli_num_rows($data) === 0):

    ?>

        <tr>

            <td
                colspan="6"
                style="text-align:center; padding:20px;"
            >
                Tidak ada data
            </td>

        </tr>

    <?php else: ?>

        <?php while ($row = mysqli_fetch_assoc($data)): ?>

            <tr>

                <!-- NOMOR -->
                <td>
                    <?= $no++; ?>
                </td>


                <!-- FOTO -->
                <td>

                    <img
                        src="<?= BASE_URL; ?>/uploads/foto_ruang/<?= htmlspecialchars($row['foto'] ?: 'default-ruang.jpg') ?>"
                        width="60"
                        style="border-radius:4px;"
                        onerror="this.src='<?= BASE_URL; ?>/uploads/foto_ruang/default-ruang.jpg';"
                    >

                </td>


                <!-- NAMA RUANGAN -->
                <td>
                    <?= htmlspecialchars($row['nama_ruang']) ?>
                </td>


                <!-- KAPASITAS -->
                <td>
                    <?= htmlspecialchars($row['kapasitas']) ?> orang
                </td>


                <!-- KETERANGAN -->
                <td>
                    <?= htmlspecialchars($row['keterangan']) ?>
                </td>


                <!-- AKSI -->
                <td>

                    <!-- DETAIL -->
                    <a
                        class="btn"
                        href="detail.php?id=<?= $row['id_ruang'] ?>"
                        style="padding:5px 10px; font-size:12px;"
                    >
                        Detail
                    </a>


                    <?php if (hasRole('admin')): ?>

                        <!-- EDIT -->
                        <a
                            class="btn"
                            href="edit.php?id=<?= $row['id_ruang'] ?>"
                            style="padding:5px 10px; font-size:12px;"
                        >
                            Edit
                        </a>


                        <!-- HAPUS -->
                        <a
                            class="btn btn-danger"
                            href="hapus.php?id=<?= $row['id_ruang'] ?>"
                            onclick="return confirm('Yakin ingin menghapus?')"
                            style="padding:5px 10px; font-size:12px;"
                        >
                            Hapus
                        </a>

                    <?php endif; ?>

                </td>

            </tr>

        <?php endwhile; ?>

    <?php endif; ?>

</table>


<!-- =========================
     PAGINATION
========================= -->

<?php if ($totalPages > 1): ?>

    <div
        style="
            margin-top:20px;
            display:flex;
            justify-content:center;
            gap:5px;
            flex-wrap:wrap;
        "
    >

        <?php if ($page > 1): ?>

            <a
                class="btn"
                href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>"
            >
                « Prev
            </a>

        <?php endif; ?>


        <?php for (
            $i = max(1, $page - 2);
            $i <= min($totalPages, $page + 2);
            $i++
        ): ?>

            <a
                class="btn"
                href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"
                style="<?= $i == $page ? 'background:#2c3e50;' : '' ?>"
            >
                <?= $i ?>
            </a>

        <?php endfor; ?>


        <?php if ($page < $totalPages): ?>

            <a
                class="btn"
                href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>"
            >
                Next »
            </a>

        <?php endif; ?>

    </div>


    <p
        style="
            text-align:center;
            margin-top:10px;
            color:#7f8c8d;
        "
    >
        Total: <?= $total ?> data |
        Halaman <?= $page ?> dari <?= $totalPages ?>
    </p>

<?php endif; ?>


<?php include dirname(__DIR__) . '/layout/footer.php'; ?>
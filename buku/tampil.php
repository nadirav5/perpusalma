<?php
include dirname(__DIR__) . '/koneksi.php';
include dirname(__DIR__) . '/auth.php';

requireRole(['admin', 'kepala']);

$judul = 'Data Buku';

$perPage = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;

$search = isset($_GET['search'])
    ? mysqli_real_escape_string($koneksi, trim($_GET['search']))
    : '';

$filter_penulis = isset($_GET['penulis'])
    ? mysqli_real_escape_string($koneksi, trim($_GET['penulis']))
    : '';

$filter_kategori = isset($_GET['kategori'])
    ? mysqli_real_escape_string($koneksi, trim($_GET['kategori']))
    : '';


/* =========================
   WHERE
========================= */

$where = "WHERE 1=1";

if ($search) {
    $where .= " AND (
        s.judul LIKE '%$search%'
        OR s.penulis LIKE '%$search%'
        OR s.kategori LIKE '%$search%'
    )";
}

if ($filter_penulis) {
    $where .= " AND s.penulis = '$filter_penulis'";
}

if ($filter_kategori) {
    $where .= " AND s.kategori = '$filter_kategori'";
}


/* =========================
   DATA FILTER PENULIS
========================= */

$penulisQuery = mysqli_query(
    $koneksi,
    "SELECT DISTINCT penulis
     FROM databuku
     WHERE penulis IS NOT NULL
     AND penulis != ''
     ORDER BY penulis"
);

if (!$penulisQuery) {
    die("Query Penulis Error: " . mysqli_error($koneksi));
}


/* =========================
   DATA FILTER KATEGORI
========================= */

$kategoriQuery = mysqli_query(
    $koneksi,
    "SELECT DISTINCT kategori
     FROM databuku
     WHERE kategori IS NOT NULL
     AND kategori != ''
     ORDER BY kategori"
);

if (!$kategoriQuery) {
    die("Query Kategori Error: " . mysqli_error($koneksi));
}


/* =========================
   HITUNG TOTAL DATA
========================= */

$countQuery = "SELECT COUNT(*) AS total
               FROM databuku s
               $where";

$countResult = mysqli_query($koneksi, $countQuery);

if (!$countResult) {
    die("Count Query Error: " . mysqli_error($koneksi));
}

$total = mysqli_fetch_assoc($countResult)['total'];

$totalPages = ceil($total / $perPage);

$offset = ($page - 1) * $perPage;


/* =========================
   AMBIL DATA BUKU
========================= */

$query = "SELECT
            s.id_buku,
            s.judul,
            s.foto,
            s.penulis,
            s.kategori,
            s.stok
          FROM databuku s
          $where
          ORDER BY s.judul
          LIMIT $perPage OFFSET $offset";

$data = mysqli_query($koneksi, $query);

if (!$data) {
    die("Query Error: " . mysqli_error($koneksi));
}


include dirname(__DIR__) . '/layout/header.php';
?>

<h2>Data Buku</h2>


<!-- =========================
     FORM PENCARIAN
========================= -->

<form method="GET"
      style="margin-bottom:20px; display:flex; gap:10px; flex-wrap:wrap; align-items:end;">

    <div style="flex:1; min-width:200px;">

        <label>Cari Buku:</label><br>

        <input
            type="text"
            name="search"
            value="<?= htmlspecialchars($search) ?>"
            placeholder="Cari judul, penulis, atau kategori..."
        >

    </div>


    <!-- FILTER PENULIS -->

    <div style="min-width:150px;">

        <label>Filter Penulis:</label><br>

        <select name="penulis">

            <option value="">-- Semua Penulis --</option>

            <?php while ($p = mysqli_fetch_assoc($penulisQuery)): ?>

                <option
                    value="<?= htmlspecialchars($p['penulis']) ?>"
                    <?= $filter_penulis === $p['penulis'] ? 'selected' : '' ?>
                >
                    <?= htmlspecialchars($p['penulis']) ?>
                </option>

            <?php endwhile; ?>

        </select>

    </div>


    <!-- FILTER KATEGORI -->

    <div style="min-width:150px;">

        <label>Filter Kategori:</label><br>

        <select name="kategori">

            <option value="">-- Semua Kategori --</option>

            <?php while ($k = mysqli_fetch_assoc($kategoriQuery)): ?>

                <option
                    value="<?= htmlspecialchars($k['kategori']) ?>"
                    <?= $filter_kategori === $k['kategori'] ? 'selected' : '' ?>
                >
                    <?= htmlspecialchars($k['kategori']) ?>
                </option>

            <?php endwhile; ?>

        </select>

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
     TABEL DATA BUKU
========================= -->

<table>

    <tr>
        <th>No</th>
        <th>Foto</th>
        <th>Judul</th>
        <th>Penulis</th>
        <th>Kategori</th>
        <th>Stok</th>
        <th>Aksi</th>
    </tr>


    <?php

    $no = $offset + 1;

    if (mysqli_num_rows($data) === 0):

    ?>

        <tr>

            <td
                colspan="7"
                style="text-align:center; padding:20px;"
            >
                Tidak ada data
            </td>

        </tr>

    <?php else: ?>

        <?php while ($row = mysqli_fetch_assoc($data)): ?>

            <tr>

                <!-- NO -->

                <td>
                    <?= $no++; ?>
                </td>


                <!-- FOTO -->

                <td>

                    <img
                        src="<?= BASE_URL; ?>/uploads/foto_buku/<?= htmlspecialchars($row['foto'] ?: 'default-buku.jpg') ?>"
                        width="60"
                        height="70"
                        style="object-fit:cover; border-radius:4px;"
                        onerror="this.src='<?= BASE_URL; ?>/uploads/foto_buku/default-buku.jpg';"
                    >

                </td>


                <!-- JUDUL -->

                <td>
                    <?= htmlspecialchars($row['judul']) ?>
                </td>


                <!-- PENULIS -->

                <td>
                    <?= htmlspecialchars($row['penulis'] ?? '-') ?>
                </td>


                <!-- KATEGORI -->

                <td>
                    <?= htmlspecialchars($row['kategori'] ?? '-') ?>
                </td>


                <!-- STOK -->

                <td>
                    <?= htmlspecialchars($row['stok'] ?? '0') ?>
                </td>


                <!-- AKSI -->

                <td>

                    <a
                        class="btn"
                        href="detail.php?id_buku=<?= $row['id_buku'] ?>"
                        style="padding:5px 10px; font-size:12px;"
                    >
                        Detail
                    </a>


                    <?php if (hasRole('admin')): ?>

                        <a
                            class="btn"
                            href="edit.php?id_buku=<?= $row['id_buku'] ?>"
                            style="padding:5px 10px; font-size:12px;"
                        >
                            Edit
                        </a>


                        <a
                            class="btn btn-danger"
                            href="hapus.php?id_buku=<?= $row['id_buku'] ?>"
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
                href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>&penulis=<?= urlencode($filter_penulis) ?>&kategori=<?= urlencode($filter_kategori) ?>"
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
                href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&penulis=<?= urlencode($filter_penulis) ?>&kategori=<?= urlencode($filter_kategori) ?>"
                style="<?= $i == $page ? 'background:#2c3e50;' : '' ?>"
            >
                <?= $i ?>
            </a>

        <?php endfor; ?>


        <?php if ($page < $totalPages): ?>

            <a
                class="btn"
                href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>&penulis=<?= urlencode($filter_penulis) ?>&kategori=<?= urlencode($filter_kategori) ?>"
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
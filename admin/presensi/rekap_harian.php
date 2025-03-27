<?php
session_start();
ob_start();
if (!isset($_SESSION["login"])) {
    header("Location: ../../auth/login.php?belum_login");
} else if ($_SESSION["role"] != 'admin') {
    header("Location: ../../auth/login.php?tolak_akses");
}

$judul = 'Rekap Presensi Harian';
include('../layout/header.php');
include_once('../../config.php');

$limit = 10; // Jumlah data per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

if (empty($_GET['tanggal_dari'])) {
    $tanggal_hari_ini = date('Y-m-d');
    $query_count = "SELECT COUNT(*) as total FROM presensi WHERE tanggal_masuk = '$tanggal_hari_ini'";
    $query_data = "SELECT presensi.*, pegawai.nama, pegawai.lokasi_presensi 
                   FROM presensi 
                   JOIN pegawai ON presensi.id_pegawai = pegawai.id 
                   WHERE tanggal_masuk = '$tanggal_hari_ini' 
                   ORDER BY tanggal_masuk DESC 
                   LIMIT $limit OFFSET $offset";
} else {
    $tanggal_dari = $_GET['tanggal_dari'];
    $tanggal_sampai = $_GET['tanggal_sampai'];
    $query_count = "SELECT COUNT(*) as total FROM presensi WHERE tanggal_masuk BETWEEN '$tanggal_dari' AND '$tanggal_sampai'";
    $query_data = "SELECT presensi.*, pegawai.nama, pegawai.lokasi_presensi 
                   FROM presensi 
                   JOIN pegawai ON presensi.id_pegawai = pegawai.id 
                   WHERE tanggal_masuk BETWEEN '$tanggal_dari' AND '$tanggal_sampai' 
                   ORDER BY tanggal_masuk DESC 
                   LIMIT $limit OFFSET $offset";
}

$result = mysqli_query($connection, $query_data);
$count_result = mysqli_query($connection, $query_count);
$total_data = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_data / $limit);

?>

<div class="page-body">
    <div class="container-xl">

        <!-- Navigasi berdasarkan Role -->
        <div class="alert alert-info">
            <strong>Anda login sebagai:</strong>
            <?php if ($_SESSION["role"] == 'admin') : ?>
            <span class="badge bg-success text-white">Administrator</span>
            <p class="mt-2">Anda memiliki akses penuh untuk melihat data rekap presensi harian dari setiap pegawai</p>
            <?php else : ?>
            <span class="badge bg-warning">Pegawai</span>
            <p class="mt-2">Anda hanya dapat melihat data pegawai tanpa bisa mengedit atau menghapusnya.</p>
            <?php endif; ?>
        </div>

        <div class="row">
            <div class="col-md-2">
                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal"
                    data-bs-target="#exampleModal">
                    Export Excel
                </button>
            </div>

            <div class="col-md-10">
                <form method="GET">
                    <div class="input-group">
                        <input type="date" class="form-control" name="tanggal_dari"
                            value="<?= $_GET['tanggal_dari'] ?? '' ?>">
                        <input type="date" class="form-control" name="tanggal_sampai"
                            value="<?= $_GET['tanggal_sampai'] ?? '' ?>">
                        <button type="submit" class="btn btn-primary">Tampilkan</button>
                    </div>
                </form>
            </div>
        </div>

        <?php if (empty($_GET['tanggal_dari'])) : ?>
        <span>Rekap Presensi Tanggal: <?= date('d F Y') ?></span>
        <?php else: ?>
        <span>Rekap Presensi Tanggal:
            <?= date('d F Y', strtotime($_GET['tanggal_dari'])) . ' sampai ' . date('d F Y', strtotime($_GET['tanggal_sampai'])) ?>
        </span>
        <?php endif; ?>

        <table class="table table-bordered mt-2">
            <tr class="text-center">
                <th>No.</th>
                <th>Nama</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Jam Pulang</th>
                <th>Total Jam</th>
                <th>Total Terlambat</th>
            </tr>

            <?php if(mysqli_num_rows($result) === 0) { ?>
            <tr>
                <td colspan="7">Data rekap presensi masih kosong.</td>
            </tr>
            <?php } else { ?>

            <?php $no = $offset + 1;
            while ($rekap = mysqli_fetch_array($result)):

                // Menghitung total jam kerja
                $jam_tanggal_masuk = strtotime($rekap['tanggal_masuk'] . ' ' . $rekap['jam_masuk']);
                $jam_tanggal_keluar = strtotime($rekap['tanggal_keluar'] . ' ' . $rekap['jam_keluar']);

                $selisih = $jam_tanggal_keluar - $jam_tanggal_masuk;
                $total_jam_kerja = floor($selisih / 3600);
                $selisih_menit_kerja = floor(($selisih % 3600) / 60);

                // Menghitung total jam terlambat
                $lokasi_presensi = $rekap['lokasi_presensi'];
                $lokasi_query = mysqli_query($connection, "SELECT jam_masuk FROM lokasi_presensi WHERE nama_lokasi = '$lokasi_presensi'");
                $jam_masuk_kantor = mysqli_fetch_assoc($lokasi_query)['jam_masuk'];

                $jam_masuk = strtotime($rekap['jam_masuk']);
                $jam_masuk_kantor = strtotime($jam_masuk_kantor);

                $terlambat = $jam_masuk - $jam_masuk_kantor;
                $total_jam_terlambat = floor($terlambat / 3600);
                $selisih_menit_terlambat = floor(($terlambat % 3600) / 60);
            ?>

            <tr>
                <td><?= $no++ ?></td>
                <td><?= $rekap['nama'] ?></td>
                <td><?= date('d F Y', strtotime($rekap['tanggal_masuk'])) ?></td>
                <td class="text-center"><?= $rekap['jam_masuk'] ?></td>
                <td class="text-center"><?= $rekap['jam_keluar'] ?></td>
                <td class="text-center">
                    <?= ($rekap['tanggal_keluar'] == '0000-00-00') ? '0 Jam 0 Menit' : $total_jam_kerja . ' Jam ' . $selisih_menit_kerja . ' Menit' ?>
                </td>
                <td class="text-center">
                    <?= ($total_jam_terlambat < 0) ? '<span class="badge bg-success text-white">On Time</span>' : $total_jam_terlambat . ' Jam ' . $selisih_menit_terlambat . ' Menit' ?>
                </td>
            </tr>
            <?php endwhile; ?>
            <?php } ?>

        </table>

        <!-- Navigasi Pagination -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page - 1 ?>">Previous</a>
                </li>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                </li>
                <?php endfor; ?>

                <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page + 1 ?>">Next</a>
                </li>
            </ul>
        </nav>

    </div>
</div>

<div class="modal" id="exampleModal" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export Excel Recap Presensi Harian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="<?= base_url('admin/presensi/rekap_harian_excel.php') ?>">
                <div class="modal-body">

                    <div class="mb-3">
                        <label for="">Tanggal Awal</label>
                        <input type="date" class="form-control" name="tanggal_dari">
                    </div>

                    <div class="mb-3">
                        <label for="">Tanggal Akhir</label>
                        <input type="date" class="form-control" name="tanggal_sampai">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Export</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include('../layout/footer.php'); ?>
<?php
session_start();
ob_start();
if (!isset($_SESSION["login"])) {
    header("Location: ../../auth/login.php?belum_login");
} else if ($_SESSION["role"] != 'pegawai') {
    header("Location: ../../auth/login.php?tolak_akses");
}

$judul = 'Rekap Presensi';
include('../layout/header.php');
include_once('../../config.php');

$base_url = "http://" . $_SERVER['HTTP_HOST'] . "/presensi";

$id = $_SESSION['id'];
$tanggal_hari_ini = date('Y-m-d'); // simpan tanggal hari ini sekali saja

if (empty($_GET['tanggal_dari'])) {
    $query_count = "SELECT COUNT(*) as total FROM presensi WHERE id_pegawai = '$id' AND tanggal_masuk = '$tanggal_hari_ini'";
} else {
    $tanggal_dari = $_GET['tanggal_dari'];
    $tanggal_sampai = $_GET['tanggal_sampai'];
    $query_count = "SELECT COUNT(*) as total FROM presensi WHERE id_pegawai = '$id' AND tanggal_masuk BETWEEN '$tanggal_dari' AND '$tanggal_sampai'";
}

$count_result = mysqli_query($connection, $query_count);
$total_data = mysqli_fetch_assoc($count_result)['total'];

$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$total_pages = ceil($total_data / $limit);

if (empty($_GET['tanggal_dari'])) {
    $result = mysqli_query($connection, "SELECT * FROM presensi WHERE id_pegawai = '$id' AND tanggal_masuk = '$tanggal_hari_ini' ORDER BY tanggal_masuk DESC LIMIT $limit OFFSET $offset");
} else {
    $result = mysqli_query($connection, "SELECT * FROM presensi WHERE id_pegawai = '$id' AND tanggal_masuk BETWEEN '$tanggal_dari' AND '$tanggal_sampai' ORDER BY tanggal_masuk DESC LIMIT $limit OFFSET $offset");
}

// lokasi presensi
$lokasi_presensi = $_SESSION['lokasi_presensi'];
$lokasi = mysqli_query($connection, "SELECT * FROM lokasi_presensi WHERE nama_lokasi = '$lokasi_presensi'");

while ($lokasi_result = mysqli_fetch_array($lokasi)) :
    $jam_masuk_kantor = date('H:i:s',strtotime($lokasi_result['jam_masuk']));
endwhile;


?>

<div class="page-body">
    <div class="container-xl">

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

        <?php if (empty($_GET['tanggal_dari'])): ?>
        <span>Rekap Presensi Tanggal: <?= date('d F Y') ?></span>
        <?php else: ?>
        <span>Rekap Presensi Tanggal:
            <?= date('d F Y', strtotime($_GET['tanggal_dari'])) . ' sampai ' . date('d F Y', strtotime($_GET['tanggal_sampai'])) ?>
        </span>
        <?php endif; ?>

        <table class="table table-bordered">
            <tr class="text-center">
                <th>No.</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Jam Pulang</th>
                <th>Total Jam</th>
                <th>Total Terlambat</th>
                <th colspan="2">Bukti Kehadiran</th>
            </tr>

            <?php if(mysqli_num_rows($result) === 0) {?>
            <tr>
                <td colspan="6">Data rekap presensi masih kosong.</td>
            </tr>
            <?php } else { ?>

            <?php $no = 1;
            while ($rekap = mysqli_fetch_array($result)):

                // menghitung total jam kerja
                $jam_tanggal_masuk = date('Y-m-d H:i:s', strtotime($rekap['tanggal_masuk'] . ' ' . $rekap['jam_masuk']));
                $jam_tanggal_keluar = date('Y-m-d H:i:s', strtotime($rekap['tanggal_keluar'] . ' ' . $rekap['jam_keluar']));

                $timestamp_masuk = strtotime($jam_tanggal_masuk);
                $timestamp_keluar = strtotime($jam_tanggal_keluar);

                $selisih = $timestamp_keluar - $timestamp_masuk;

                $total_jam_kerja = floor($selisih / 3600);
                $selisih -= $total_jam_kerja * 3600;
                $selisih_menit_kerja = floor($selisih / 60);

                // menghitung total jam terlambat
                $jam_masuk = date('H:i:s', strtotime($rekap['jam_masuk']));
                $timestamp_jam_masuk_real = strtotime($jam_masuk);
                $timestamp_jam_masuk_kantor = strtotime($jam_masuk_kantor);

                $terlambat = $timestamp_jam_masuk_real - $timestamp_jam_masuk_kantor;
                $total_jam_terlambat = floor($terlambat / 3600);
                $terlambat -= $total_jam_terlambat * 3600;
                $selisih_menit_terlambat = floor($terlambat / 60);


                ?>

            <tr>
                <td><?= $no++ ?></td>
                <td><?= date('d F Y', strtotime($rekap['tanggal_masuk'])) ?></td>
                <td class="text-center"><?= $rekap['jam_masuk'] ?></td>
                <td class="text-center"><?= $rekap['jam_keluar'] ?></td>
                <td class="text-center">
                    <?php if ($rekap['tanggal_keluar'] == '0000-00-00'): ?>
                    <span>0 Jam 0 Menit</span>
                    <?php else: ?>
                    <?= $total_jam_kerja . ' Jam ' . $selisih_menit_kerja . ' Menit' ?>
                    <?php endif; ?>

                </td>
                <td class="text-center">
                    <?php if ($total_jam_terlambat < 0): ?>
                    <span class="badge bg-success text-white">On Time</span>
                    <?php else: ?>
                    <?= $total_jam_terlambat . ' Jam ' . $selisih_menit_terlambat . ' Menit' ?>
                    <?php endif; ?>
                </td>

                <td class="text-center">
                    <?php if (!empty($rekap['foto_masuk'])): ?>
                    <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#fotoModal"
                        data-foto="<?= base_url('pegawai/presensi/foto/' . $rekap['foto_masuk']) ?>" data-jenis="masuk">
                        Lihat Foto Masuk
                    </a>
                    <?php else: ?>
                    <span class="text-muted">Tidak ada foto</span>
                    <?php endif; ?>
                </td>
                <td class="text-center">
                    <?php if (!empty($rekap['foto_keluar'])): ?>
                    <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#fotoModal"
                        data-foto="<?= base_url('pegawai/presensi/foto/' . $rekap['foto_keluar']) ?>"
                        data-jenis="keluar">
                        Lihat Foto Keluar
                    </a>
                    <?php else: ?>
                    <span class="text-muted">Tidak ada foto</span>
                    <?php endif; ?>
                </td>

            </tr>
            <?php endwhile; ?>
            <?php } ?>

        </table>

        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                    <a class="page-link"
                        href="?page=<?= $page - 1 ?>&tanggal_dari=<?= $_GET['tanggal_dari'] ?? '' ?>&tanggal_sampai=<?= $_GET['tanggal_sampai'] ?? '' ?>">Previous</a>
                </li>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                    <a class="page-link"
                        href="?page=<?= $i ?>&tanggal_dari=<?= $_GET['tanggal_dari'] ?? '' ?>&tanggal_sampai=<?= $_GET['tanggal_sampai'] ?? '' ?>"><?= $i ?></a>
                </li>
                <?php endfor; ?>

                <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                    <a class="page-link"
                        href="?page=<?= $page + 1 ?>&tanggal_dari=<?= $_GET['tanggal_dari'] ?? '' ?>&tanggal_sampai=<?= $_GET['tanggal_sampai'] ?? '' ?>">Next</a>
                </li>
            </ul>
        </nav>

        <!-- Kode Modal untuk Lihat Foto -->
        <div class="modal fade" id="fotoModal" tabindex="-1" aria-labelledby="fotoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="fotoModalLabel">Lihat Foto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img src="" id="fotoView" class="img-fluid" alt="Foto" />
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="modal" id="exampleModal" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export Excel Recap Presensi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="exportForm" method="POST" action="<?= base_url('pegawai/presensi/rekap_excel.php') ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="">Tanggal Awal</label>
                        <input type="date" class="form-control" name="tanggal_dari" required>
                    </div>

                    <div class="mb-3">
                        <label for="">Tanggal Akhir</label>
                        <input type="date" class="form-control" name="tanggal_sampai" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Export</button>
                </div>
            </form>

        </div>
    </div>
</div>

<?php include('../layout/footer.php');  ?>
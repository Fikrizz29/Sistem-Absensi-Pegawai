<?php
ob_start(); 
session_start();
if (!isset($_SESSION["login"])) {
    header("Location: ../../auth/login.php?belum_login");
    exit;
} elseif ($_SESSION["role"] != 'admin') {
    header("Location: ../../auth/login.php?tolak_akses");
    exit;
}

$judul = "Data Ketidakhadiran";
include('../layout/header.php');
require_once('../../config.php');

// Ambil tanggal hari ini dalam format YYYY-MM-DD
$tanggal_hari_ini = date('Y-m-d');

// Inisialisasi variabel filter tanggal
$tanggal_dari = $_GET['tanggal_dari'] ?? $tanggal_hari_ini;
$tanggal_sampai = $_GET['tanggal_sampai'] ?? $tanggal_hari_ini;

// Pagination setup
$limit = 10; // Jumlah data per halaman
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Hitung total data sesuai filter
$total_query = "
    SELECT COUNT(*) as total 
    FROM ketidakhadiran 
    WHERE tanggal BETWEEN '$tanggal_dari' AND '$tanggal_sampai'
";
$total_result = mysqli_query($connection, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_data = $total_row['total'];
$total_pages = ceil($total_data / $limit);

// Query utama dengan pagination
$query = "
    SELECT ketidakhadiran.*, pegawai.nama 
    FROM ketidakhadiran 
    JOIN pegawai ON ketidakhadiran.id_pegawai = pegawai.id 
    WHERE tanggal BETWEEN '$tanggal_dari' AND '$tanggal_sampai' 
    ORDER BY ketidakhadiran.id DESC 
    LIMIT $start, $limit
";
$result = mysqli_query($connection, $query);
?>

<div class="page-body">
    <div class="container-xl">

        <!-- Navigasi berdasarkan Role -->
        <div class="alert alert-info">
            <strong>Anda login sebagai:</strong>
            <?php if ($_SESSION["role"] == 'admin') : ?>
            <span class="badge bg-success text-white">Administrator</span>
            <p class="mt-2">Anda memiliki akses penuh untuk mengubah status pengajuan ketidakhadiran</p>
            <?php else : ?>
            <span class="badge bg-warning">Pegawai</span>
            <p class="mt-2">Anda hanya dapat melihat data pegawai tanpa bisa mengedit atau menghapusnya.</p>
            <?php endif; ?>
        </div>

        <div class="row">
            <div class="col-md-">
                <form method="GET">
                    <div class="input-group">
                        <input type="date" class="form-control" name="tanggal_dari"
                            value="<?= htmlspecialchars($tanggal_dari) ?>">
                        <input type="date" class="form-control" name="tanggal_sampai"
                            value="<?= htmlspecialchars($tanggal_sampai) ?>">
                        <button type="submit" class="btn btn-primary">Tampilkan</button>
                    </div>
                </form>
            </div>
        </div>

        <table class="table table-bordered mt-2">
            <tr class="text-center">
                <th>No.</th>
                <th>Nama</th>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Deskripsi</th>
                <th>File</th>
                <th>Status Pengajuan</th>
            </tr>

            <?php if (mysqli_num_rows($result) === 0) { ?>
            <tr>
                <td colspan="7">Tidak ada data ketidakhadiran</td>
            </tr>
            <?php } else { ?>
            <?php $no = $start + 1; 
            while ($data = mysqli_fetch_array($result)) : ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($data['nama']) ?></td>
                <td><?= date('d F Y', strtotime($data['tanggal'])) ?></td>
                <td><?= htmlspecialchars($data['keterangan']) ?></td>
                <td><?= htmlspecialchars($data['deskripsi']) ?></td>
                <td class="text-center">
                    <a target="_blank" href="<?= base_url('assets/file_ketidakhadiran/'.$data['file']) ?>"
                        class="badge badge-pill bg-primary">Download</a>
                </td>
                <td class="text-center">
                    <?php if ($data['status_pengajuan'] == 'PENDING') : ?>
                    <a class="badge badge-pill bg-warning"
                        href="<?= base_url('admin/data_ketidakhadiran/detail.php?id='.$data['id']) ?>">PENDING</a>
                    <?php elseif ($data['status_pengajuan'] == 'REJECTED') : ?>
                    <a class="badge badge-pill bg-danger"
                        href="<?= base_url('admin/data_ketidakhadiran/detail.php?id='.$data['id']) ?>">REJECTED</a>
                    <?php else : ?>
                    <a class="badge badge-pill bg-success"
                        href="<?= base_url('admin/data_ketidakhadiran/detail.php?id='.$data['id']) ?>">APPROVED</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
            <?php } ?>

        </table>

        <!-- Navigasi Pagination -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center mt-8">
                <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                    <a class="page-link"
                        href="?tanggal_dari=<?= $tanggal_dari ?>&tanggal_sampai=<?= $tanggal_sampai ?>&page=<?= $page - 1 ?>">Previous</a>
                </li>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                    <a class="page-link"
                        href="?tanggal_dari=<?= $tanggal_dari ?>&tanggal_sampai=<?= $tanggal_sampai ?>&page=<?= $i ?>"><?= $i ?></a>
                </li>
                <?php endfor; ?>

                <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                    <a class="page-link"
                        href="?tanggal_dari=<?= $tanggal_dari ?>&tanggal_sampai=<?= $tanggal_sampai ?>&page=<?= $page + 1 ?>">Next</a>
                </li>
            </ul>
        </nav>

    </div>
</div>

<?php include('../layout/footer.php'); ?>
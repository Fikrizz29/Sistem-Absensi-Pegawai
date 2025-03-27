<?php 
ob_start(); 
session_start();
if(!isset($_SESSION["login"])){
    header("Location: ../../auth/login.php?belum_login");
    exit;
}else if($_SESSION["role"] != 'admin' && $_SESSION["role"] != 'pegawai'){
    header("Location: ../../auth/login.php?tolak_akses");
    exit;
}

$judul = "Data Pegawai"; 
include('../layout/header.php');
require_once('../../config.php');

// Konfigurasi Pagination
$limit = 10; // Jumlah data per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Filter Berdasarkan Role
$filter_role = isset($_GET['role']) ? $_GET['role'] : '';
$where_clause = "";
if ($filter_role == 'admin' || $filter_role == 'pegawai') {
    $where_clause = "WHERE users.role = '$filter_role'";
}

// Hitung total data pegawai
$total_result = mysqli_query($connection, "SELECT COUNT(*) AS total FROM users JOIN pegawai ON users.id_pegawai = pegawai.id $where_clause");
$total_row = mysqli_fetch_assoc($total_result);
$total_data = $total_row['total'];
$total_pages = ceil($total_data / $limit);

// Ambil data pegawai sesuai filter, limit & offset
$result = mysqli_query($connection, "SELECT users.id_pegawai, users.username, users.password, users.status, users.role, pegawai.* 
FROM users JOIN pegawai ON users.id_pegawai = pegawai.id 
$where_clause 
LIMIT $limit OFFSET $offset");

?>

<div class="page-body">
    <div class="container-xl">

        <!-- Navigasi berdasarkan Role -->
        <div class="alert alert-info">
            <strong>Anda login sebagai:</strong>
            <?php if ($_SESSION["role"] == 'admin') : ?>
            <span class="badge bg-success text-white">Administrator</span>
            <p class="mt-2">Anda memiliki akses penuh untuk mengelola data pegawai, termasuk menambah, mengedit, dan
                menghapus data.</p>
            <?php else : ?>
            <span class="badge bg-warning">Pegawai</span>
            <p class="mt-2">Anda hanya dapat melihat data pegawai tanpa bisa mengedit atau menghapusnya.</p>
            <?php endif; ?>
        </div>

        <!-- Row untuk menyusun tombol secara sejajar -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <!-- Tombol Tambah Data (Hanya untuk Admin) -->
            <?php if ($_SESSION["role"] == 'admin') : ?>
            <a href="<?= base_url('admin/data_pegawai/tambah.php') ?>" class="btn btn-primary">
                <span class="text"><i class="fa-solid fa-circle-plus"></i> Tambah Data</span>
            </a>
            <?php endif; ?>

            <!-- Filter Berdasarkan Role (Di Ujung Kanan) -->
            <div>
                <a href="?role=admin" class="btn btn-success <?= ($filter_role == 'admin') ? 'active' : '' ?>">Tampilkan
                    Admin</a>
                <a href="?role=pegawai"
                    class="btn btn-warning <?= ($filter_role == 'pegawai') ? 'active' : '' ?>">Tampilkan Pegawai</a>
                <a href="?" class="btn btn-secondary <?= ($filter_role == '') ? 'active' : '' ?>">Tampilkan Semua</a>
            </div>
        </div>

        <!-- Tabel Data Pegawai -->
        <table class="table table-bordered mt-3">
            <tr class="text-center">
                <th>No</th>
                <th>NIP</th>
                <th>Nama</th>
                <th>Username</th>
                <th>Jabatan</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>

            <?php if (mysqli_num_rows($result) === 0) { ?>
            <tr>
                <td colspan="7">Data Kosong, silahkan tambahkan data baru</td>
            </tr>
            <?php } else { ?>
            <?php $no = $offset + 1;
            while($pegawai = mysqli_fetch_array($result)) :?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $pegawai['nip'] ?></td>
                <td><?= $pegawai['nama'] ?></td>
                <td><?= $pegawai['username'] ?></td>
                <td><?= $pegawai['jabatan'] ?></td>
                <td><?= $pegawai['role'] ?></td>
                <td class="text-center">
                    <a href="<?= base_url('admin/data_pegawai/detail.php?id=' . $pegawai['id']) ?>"
                        class="badge badge-pill bg-primary">Detail</a>

                    <?php if ($_SESSION["role"] == 'admin') : ?>
                    <a href="<?= base_url('admin/data_pegawai/edit.php?id=' . $pegawai['id']) ?>"
                        class="badge badge-pill bg-warning">Edit</a>

                    <a href="<?= base_url('admin/data_pegawai/hapus.php?id=' . $pegawai['id']) ?>"
                        class="badge badge-pill bg-danger tombol-hapus">Hapus</a>
                    <?php endif; ?>
                </td>
            </tr>

            <?php endwhile; ?>
            <?php } ?>
        </table>

        <!-- Navigasi Pagination -->
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?role=<?= $filter_role ?>&page=<?= $page - 1 ?>">Previous</a>
                </li>

                <?php for($i = 1; $i <= $total_pages; $i++) : ?>
                <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                    <a class="page-link" href="?role=<?= $filter_role ?>&page=<?= $i ?>"><?= $i ?></a>
                </li>
                <?php endfor; ?>

                <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?role=<?= $filter_role ?>&page=<?= $page + 1 ?>">Next</a>
                </li>
            </ul>
        </nav>

    </div>
</div>

<?php include('../layout/footer.php'); ?>
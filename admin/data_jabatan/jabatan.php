<?php 
session_start();

if(!isset($_SESSION["login"])){
    header("Location: ../../auth/login.php?belum_login");
}else if($_SESSION["role"] != 'admin'){
    header("Location: ../../auth/login.php?tolak_akses");
}

$judul = "Data Jabatan"; 
include('../layout/header.php');
require_once('../../config.php');

$result = mysqli_query($connection, "SELECT * FROM jabatan ORDER BY id DESC");
?>
<!-- Page body -->
<div class="page-body">
    <div class="container-xl">

        <!-- Navigasi berdasarkan Role -->
        <div class="alert alert-info">
            <strong>Anda login sebagai:</strong>
            <?php if ($_SESSION["role"] == 'admin') : ?>
            <span class="badge bg-success text-white">Administrator</span>
            <p class="mt-2">Anda memiliki akses penuh untuk mengelola data jabatan, termasuk menambah, mengedit, dan
                menghapus data.</p>
            <?php else : ?>
            <span class="badge bg-warning">Pegawai</span>
            <p class="mt-2">Anda hanya dapat melihat data pegawai tanpa bisa mengedit atau menghapusnya.</p>
            <?php endif; ?>
        </div> <a href="<?= base_url('admin/data_jabatan/tambah.php') ?>" class="btn btn-primary"><span class="text"><i
                    class="fa-solid fa-circle-plus"></i> Tambah
                Data</span>
        </a>
        <div class="row row-deck row-cards mt-2">
            <table class="table table-bordered">
                <tr class="text-center">
                    <th>No.</th>
                    <th>Nama Jabatan</th>
                    <th>Aksi</th>
                    </trcla>

                    <?php if(mysqli_num_rows($result) === 0) : ?>
                <tr>
                    <td colspan="3">Data masih kosong, silahkan tambahkan data baru</td>
                </tr>
                <?php else : ?>
                <?php $no = 1;
                while ($jabatan = mysqli_fetch_array($result))
                    : ?>

                <tr>
                    <td> <?= $no++ ?> </td>
                    <td> <?= $jabatan['jabatan'] ?> </td>
                    <td class="text-center">
                        <a href="<?= base_url('admin/data_jabatan/edit.php?id=' . $jabatan['id']) ?>"
                            class="badge bg-primary badge-pill">Edit</a>
                        <a href="<?= base_url('admin/data_jabatan/hapus.php?id=' . $jabatan['id']) ?>"
                            class="badge bg-danger badge-pill tombol-hapus">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php endif; ?>

            </table>

        </div>
    </div>
</div>
<?php include('../layout/footer.php');  ?>
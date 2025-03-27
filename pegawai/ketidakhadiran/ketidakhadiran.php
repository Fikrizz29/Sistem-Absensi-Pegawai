<?php
ob_start();
session_start();
if(!isset($_SESSION["login"])){
    header("Location: ../../auth/login.php?belum_login");
}else if($_SESSION["role"] != 'pegawai'){
    header("Location: ../../auth/login.php?tolak_akses");
}

$judul = 'Ketidakhadiran';
include('../layout/header.php');  
include_once('../../config.php');

$id = $_SESSION['id'];
$result = mysqli_query($connection, "SELECT * FROM ketidakhadiran WHERE id_pegawai = '$id' ORDER BY id DESC");


?>

<!-- Page body -->
<div class="page-body">
    <div class="container-xl">

        <a href="<?= base_url('pegawai/ketidakhadiran/pengajuan_ketidakhadiran.php')?>" class="btn btn-primary"><span
                class="text"><i class="fa-solid fa-circle-plus"></i> Tambah
                Data</span></a>

        <table class="table table-bordered mt-2">
            <tr class="text-center">
                <th>No.</th>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Deskripsi</th>
                <th>File</th>
                <th>Status Pengajuan</th>
                <th>Aksi</th>
            </tr>

            <?php if (mysqli_num_rows($result) === 0) { ?>
            <tr>
                <td colspan="7">Data ketidakhadiran masih kosong</td>
            </tr>
            <?php } else { ?>
            <?php $no = 1; 
            while ($data = mysqli_fetch_array($result)) : ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= date('d F Y', strtotime($data['tanggal'])) ?></td>
                <td><?= $data['keterangan'] ?></td>
                <td><?= $data['deskripsi'] ?></td>
                <td class="text-center">
                    <a target="_blank" href="<?= base_url('assets/file_ketidakhadiran/'.$data['file']) ?>"
                        class="badge badge-pill bg-primary">Download</a>
                </td>
                <td class="text-center">
                    <?php if ($data['status_pengajuan'] == 'APPROVED') : ?>
                    <span class="badge badge-pill bg-success text-white"><?= $data['status_pengajuan'] ?></span>
                    <?php elseif ($data['status_pengajuan'] == 'PENDING') : ?>
                    <span class="badge badge-pill bg-warning text-white"><?= $data['status_pengajuan'] ?></span>
                    <?php elseif ($data['status_pengajuan'] == 'REJECTED') : ?>
                    <span class="badge badge-pill bg-danger text-white"><?= $data['status_pengajuan'] ?></span>
                    <?php endif; ?>
                </td>
                <td class="text-center">
                    <a href="edit.php?id=<?= $data['id'] ?>" class=" badge badge-pill bg-success">Update</a>
                    <a href="hapus.php?id=<?= $data['id'] ?>"
                        class=" badge badge-pill bg-danger tombol-hapus">Delete</a>
                </td>

            </tr>

            <?php endwhile; ?>
            <?php } ?>

        </table>

    </div>
</div>

<?php include('../layout/footer.php');  ?>
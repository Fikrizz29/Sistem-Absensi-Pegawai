<?php 
session_start();

if(!isset($_SESSION["login"])){
    header("Location: ../../auth/login.php?belum_login");
}else if($_SESSION["role"] != 'admin'){
    header("Location: ../../auth/login.php?tolak_akses");
}

$judul = "Data Lokasi Presensi"; 
include('../layout/header.php');
require_once('../../config.php');

$result = mysqli_query($connection, "SELECT * FROM lokasi_presensi ORDER BY id DESC");
?>

<div class="page-body">
    <div class="container-xl">

        <!-- Navigasi berdasarkan Role -->
        <div class="alert alert-info">
            <strong>Anda login sebagai:</strong>
            <?php if ($_SESSION["role"] == 'admin') : ?>
            <span class="badge bg-success text-white">Administrator</span>
            <p class="mt-2">Anda memiliki akses penuh untuk mengelola data lokasi presensi, termasuk menambah, mengedit,
                dan
                menghapus data.</p>
            <?php else : ?>
            <span class="badge bg-warning">Pegawai</span>
            <p class="mt-2">Anda hanya dapat melihat data pegawai tanpa bisa mengedit atau menghapusnya.</p>
            <?php endif; ?>
        </div>

        <a href="<?= base_url('admin/data_lokasi_presensi/tambah.php') ?>" class="btn btn-primary"><span class="text"><i
                    class="fa-solid fa-circle-plus"></i> Tambah
                Data</span>
            <a />

            <table class="table table-bordered mt-3">
                <tr class="text-center">
                    <th>No</th>
                    <th>Nama Lokasi</th>
                    <th>Tipe Lokasi</th>
                    <th>Latitude/Longitude</th>
                    <th>Radius</th>
                    <th>Aksi</th>
                </tr>

                <?php if (mysqli_num_rows($result) === 0) { ?>
                <tr>
                    <td colspan="6">Data Kosong, silahkan tambahkan data baru</td>
                </tr>
                <?php } else { ?>
                <?php $no = 1; 
            while($lokasi = mysqli_fetch_array($result)) :?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $lokasi['nama_lokasi'] ?></td>
                    <td><?= $lokasi['alamat_lokasi'] ?></td>
                    <td><?= $lokasi['latitude'] . '/' . $lokasi['longitude'] ?></td>
                    <td><?= $lokasi['radius'] ?></td>
                    <td class="text-center">
                        <a href="<?= base_url('admin/data_lokasi_presensi/detail.php?id=' . $lokasi['id']) ?>"
                            class="badge badge-pill bg-primary">Detail</a>

                        <a href="<?= base_url('admin/data_lokasi_presensi/edit.php?id=' . $lokasi['id']) ?>"
                            class="badge badge-pill bg-primary">Edit</a>

                        <a href="<?= base_url('admin/data_lokasi_presensi/hapus.php?id=' . $lokasi['id']) ?>"
                            class="badge badge-pill bg-danger tombol-hapus">Hapus</a>
                    </td>
                </tr>

                <?php endwhile; ?>

                <?php } ?>
            </table>

    </div>
</div>

<?php include('../layout/footer.php');  ?>
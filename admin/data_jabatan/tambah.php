<?php 
session_start();
ob_start();
if(!isset($_SESSION["login"])){
    header("Location: ../../auth/login.php?belum_login");
}else if($_SESSION["role"] != 'admin'){
    header("Location: ../../auth/login.php?tolak_akses");
}

$judul = "Tambah Data Jabatan"; 
include('../layout/header.php');
require_once('../../config.php');

if (isset($_POST['submit'])) {
    $jabatan = htmlspecialchars($_POST['jabatan']);

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($jabatan)) {
            $pesan_kesalahan = "Nama Jabatan wajib diisi";
        }

        if (!empty($pesan_kesalahan)) {
            $_SESSION['validasi'] = $pesan_kesalahan;
        }else{
            $result = mysqli_query($connection, "INSERT INTO jabatan(jabatan) VALUES('$jabatan')");
            $_SESSION['berhasil'] = "Data berhasil disimpan";
            header("location: jabatan.php");
            exit();
        }
    }
}

?>
<!-- Page body -->
<div class="page-body">
    <div class="container-xl">

        <div class="card col-md-6">
            <div class="card-body">

                <form action="<?= base_url('admin/data_jabatan/tambah.php') ?>" method="POST">

                    <div class="mb-3">
                        <label for="">Nama Jabatan</label>
                        <input type="text" class="form-control" name="jabatan">
                    </div>

                    <button type="sumbit" name="submit" class="btn btn-primary">Simpan</button>
                    <a href="jabatan.php" class="btn btn-danger">Batal</a>
                </form>

            </div>
        </div>

    </div>
</div>
<?php include('../layout/footer.php');  ?>
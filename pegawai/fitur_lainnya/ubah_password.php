<?php 
ob_start(); 
session_start();
if(!isset($_SESSION["login"])){
    header("Location: ../../auth/login.php?belum_login");
}else if($_SESSION["role"] != 'pegawai'){
    header("Location: ../../auth/login.php?tolak_akses");
}

$judul = "Ubah Password"; 
include('../layout/header.php');
require_once('../../config.php');



if(isset($_POST['update'])) {
    $id = $_SESSION['id'];
    $password_baru = password_hash($_POST['password_baru'], PASSWORD_DEFAULT);
    $ulangi_password_baru = password_hash($_POST['ulangi_password_baru'], PASSWORD_DEFAULT);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if(empty($_POST['password_baru'])) {
            $pesan_kesalahan[] = "Password baru wajib diisi";
        }
        if(empty($_POST['ulangi_password_baru'])) {
            $pesan_kesalahan[] = "Ulangi password baru wajib diisi";
        }
       
        if($_POST['password_baru'] != $_POST['ulangi_password_baru']) {
            $pesan_kesalahan[] = "Password tidak cocok";
        }
        
        if(!empty($pesan_kesalahan)) {
            $_SESSION['validasi'] = implode("<br>", $pesan_kesalahan);
        }else{
            $pegawai = mysqli_query($connection, "UPDATE users SET
                password = '$password_baru'
            WHERE id_pegawai = $id");

            $_SESSION['berhasil'] = "Password berhasil diubah";
            header("Location: ../home/home.php");
            exit;
        }
    }
}

?>

<div class="page-body">
    <div class="container-xl">

        <form method="POST" action="">

            <div class="card col-md-6">
                <div class="card-body">
                    <div class="mb-3">
                        <label for="">Password Baru</label>
                        <input type="password" name="password_baru" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="">Ulangi Password Baru</label>
                        <input type="password" name="ulangi_password_baru" class="form-control">
                    </div>

                    <input type="hidden" name="id" value="<?= $_SESSION['id']; ?>">

                    <button type="submit" class="btn btn-primary" name="update">Update</button>
                </div>
            </div>
        </form>

    </div>
</div>


<?php include('../layout/footer.php');  ?>
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
                        <div class="input-group input-group-flat">
                            <input type="password" class="form-control" name="password_baru" placeholder="Password"
                                autocomplete="off">
                            <span class="input-group-text">
                                <a href="#" class="link-secondary" title="Show password" data-bs-toggle="tooltip">
                                    <!-- Download SVG icon from http://tabler-icons.io/i/eye -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                        <path
                                            d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                    </svg>
                                </a>
                            </span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="">Ulangi Password Baru</label>
                        <div class="input-group input-group-flat">
                            <input type="password" class="form-control" name="ulangi_password_baru"
                                placeholder="Password" autocomplete="off">
                            <span class="input-group-text">
                                <a href="#" class="link-secondary" title="Show password" data-bs-toggle="tooltip">
                                    <!-- Download SVG icon from http://tabler-icons.io/i/eye -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                        <path
                                            d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                    </svg>
                                </a>
                            </span>
                        </div>
                    </div>

                    <input type="hidden" name="id" value="<?= $_SESSION['id']; ?>">

                    <button type="submit" class="btn btn-primary" name="update">Update</button>
                    <a href="../home/home.php" class="btn btn-danger">Batal</a>
                </div>
            </div>
        </form>

    </div>
</div>

<!-- Showpw -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const togglePasswordLinks = document.querySelectorAll('.link-secondary');

    togglePasswordLinks.forEach(function(togglePassword) {
        togglePassword.addEventListener('click', function(e) {
            e.preventDefault();
            const passwordInput = togglePassword.closest('.input-group').querySelector('input');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' :
                'password';
            passwordInput.setAttribute('type', type);
        });
    });
});
</script>


<?php include('../layout/footer.php');  ?>
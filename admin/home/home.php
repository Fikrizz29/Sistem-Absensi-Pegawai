<?php
ob_start();
session_start();
if(!isset($_SESSION["login"])){
    header("Location: ../../auth/login.php?belum_login");
}else if($_SESSION["role"] != 'admin'){
    header("Location: ../../auth/login.php?tolak_akses");
}

$judul = "Home";
include('../layout/header.php');
$pegawai = mysqli_query($connection, "SELECT pegawai.*, users.status FROM pegawai JOIN users ON pegawai.id = users.id_pegawai WHERE status = 'Aktif'");
$total_pegawai_aktif = mysqli_num_rows($pegawai);


?>
<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <div class="row row-deck row-cards">
            <div class="col-12">
                <div class="row row-cards">
                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="bg-primary text-white avatar">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="icon icon-tabler icons-tabler-outline icon-tabler-user">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                                <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium">
                                            Total Pegawai Aktif
                                        </div>
                                        <div class="text-secondary">
                                            <?= $total_pegawai_aktif . ' Pegawai' ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="bg-green text-white avatar">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="icon icon-tabler icons-tabler-outline icon-tabler-user-check">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                                <path d="M6 21v-2a4 4 0 0 1 4 -4h4" />
                                                <path d="M15 19l2 2l4 -4" />
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <?php
                                        $tanggal_hari_ini = date('Y-m-d');
                                        $pegawai_hadir_query = mysqli_query($connection, "SELECT COUNT(DISTINCT id_pegawai) AS total_hadir FROM presensi WHERE tanggal_masuk = '$tanggal_hari_ini'");
                                        $data_hadir = mysqli_fetch_assoc($pegawai_hadir_query);
                                        $total_pegawai_hadir = $data_hadir['total_hadir'];
                                        ?>
                                        <div class="font-weight-medium">
                                            Total Pegawai Hadir
                                        </div>
                                        <div class="text-secondary">
                                            <?= $total_pegawai_hadir . ' Pegawai' ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="bg-facebook text-white avatar">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="icon icon-tabler icons-tabler-outline icon-tabler-user-question">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                                <path d="M6 21v-2a4 4 0 0 1 4 -4h3.5" />
                                                <path d="M19 22v.01" />
                                                <path
                                                    d="M19 19a2.003 2.003 0 0 0 .914 -3.782a1.98 1.98 0 0 0 -2.414 .483" />
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="col">

                                        <?php
                                        $tanggal_hari_ini = date('Y-m-d');
                                        $pegawai_tidak_hadir_query = mysqli_query($connection, "SELECT COUNT(DISTINCT id_pegawai) AS total_tidak_hadir FROM ketidakhadiran WHERE tanggal = '$tanggal_hari_ini'");
                                        $data_tidak_hadir = mysqli_fetch_assoc($pegawai_tidak_hadir_query);
                                        $total_pegawai_tidak_hadir = $data_tidak_hadir['total_tidak_hadir'];
                                        ?>
                                        <div class="font-weight-medium">
                                            Total Ketidakhadiran
                                        </div>
                                        <div class="text-secondary">
                                            <?= $total_pegawai_tidak_hadir .' Pegawai' ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div> <!-- End row -->
            </div> <!-- End col-12 -->
        </div> <!-- End row-deck -->
    </div> <!-- End container-xl -->
</div> <!-- End page-body -->
<?php include('../layout/footer.php');  ?>
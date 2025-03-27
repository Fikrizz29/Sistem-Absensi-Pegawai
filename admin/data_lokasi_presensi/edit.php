<?php 
session_start();
ob_start();
if(!isset($_SESSION["login"])){
    header("Location: ../../auth/login.php?belum_login");
}else if($_SESSION["role"] != 'admin'){
    header("Location: ../../auth/login.php?tolak_akses");
}

$judul = "Edit Data Lokasi Presensi"; 
include('../layout/header.php');
require_once('../../config.php');

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nama_lokasi = htmlspecialchars($_POST['nama_lokasi']);
    $alamat_lokasi = htmlspecialchars($_POST['alamat_lokasi']);
    $tipe_lokasi = htmlspecialchars($_POST['tipe_lokasi']);
    $latitude = htmlspecialchars($_POST['latitude']);
    $longitude = htmlspecialchars($_POST['longitude']);
    $radius = htmlspecialchars($_POST['radius']);
    $zona_waktu = htmlspecialchars($_POST['zona_waktu']);
    $jam_masuk = htmlspecialchars($_POST['jam_masuk']);
    $jam_pulang = htmlspecialchars($_POST['jam_pulang']);

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        if(empty($nama_lokasi)) {
            $pesan_kesalahan[] = "Nama lokasi wajib diisi";
        }
        if(empty($alamat_lokasi)) {
            $pesan_kesalahan[] = "Alamat lokasi wajib diisi";
        }
        if(empty($tipe_lokasi)) {
            $pesan_kesalahan[] = "Tipe lokasi wajib diisi";
        }
        if(empty($latitude)) {
            $pesan_kesalahan[] = "Latitude wajib diisi";
        }
        if(empty($longitude)) {
            $pesan_kesalahan[] = "Longitude wajib diisi";
        }
        if(empty($radius)) {
            $pesan_kesalahan[] = "Radius wajib diisi";
        }
        if(empty($zona_waktu)) {
            $pesan_kesalahan[] = "Zona waktu wajib diisi";
        }
        if(empty($jam_masuk)) {
            $pesan_kesalahan[] = "Jam masuk wajib diisi";
        }
        if(empty($jam_pulang)) {
            $pesan_kesalahan[] = "Jam pulang wajib diisi";
        }

        if (!empty($pesan_kesalahan)) {
            $_SESSION['validasi'] = implode("<br>", $pesan_kesalahan);
        }else{
            $result = mysqli_query($connection, "UPDATE lokasi_presensi SET 
            nama_lokasi='$nama_lokasi', 
            alamat_lokasi='$alamat_lokasi', 
            tipe_lokasi='$tipe_lokasi', 
            latitude='$latitude', 
            longitude='$longitude', 
            radius='$radius', 
            zona_waktu='$zona_waktu', 
            jam_masuk='$jam_masuk', 
            jam_pulang='$jam_pulang'
            WHERE id=$id");
            $_SESSION['berhasil'] = "Data berhasil diupdate";
            header("location: lokasi_presensi.php");
            exit();
        }
    }
}

// $id = $_GET['id'];
$id = isset($_GET['id']) ? $_GET['id'] : $_POST['id'];
$result = mysqli_query($connection, "SELECT * FROM lokasi_presensi WHERE id=$id ");

while ($lokasi = mysqli_fetch_array($result)) {
    $nama_lokasi = $lokasi['nama_lokasi'];
    $alamat_lokasi = $lokasi['alamat_lokasi'];
    $tipe_lokasi = $lokasi['tipe_lokasi'];
    $latitude = $lokasi['latitude'];
    $longitude = $lokasi['longitude'];
    $radius = $lokasi['radius'];
    $zona_waktu = $lokasi['zona_waktu'];
    $jam_masuk = $lokasi['jam_masuk'];
    $jam_pulang = $lokasi['jam_pulang'];
    
}

?>
<!-- Page body -->
<div class="page-body">
    <div class="container-xl">

        <div class="card col-md-6">
            <div class="card-body">

                <form action="<?= base_url('admin/data_lokasi_presensi/edit.php') ?>" method="POST">

                    <div class="mb-3">
                        <label for="">Nama Lokasi</label>
                        <input type="text" class="form-control" name="nama_lokasi" value="<?= $nama_lokasi ?>">
                    </div>

                    <div class="mb-3">
                        <label for="">Alamat Lokasi</label>
                        <input type="text" class="form-control" name="alamat_lokasi" value="<?= $alamat_lokasi ?>">
                    </div>

                    <div class="mb-3">
                        <label for="">Tipe Lokasi</label>
                        <select name="tipe_lokasi" class="form-control">
                            <option value="">--Pilih Tipe Lokasi--</option>
                            <option <?php if ($tipe_lokasi == 'Pusat') {
                                echo 'selected'; } ?> value="Pusat">Pusat</option>

                            <option <?php if ($tipe_lokasi == 'Cabang') {
                                echo 'selected'; } ?> value="Cabang">Cabang</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="">Latitude</label>
                        <input type="text" class="form-control" name="latitude" value="<?= $latitude ?>">
                    </div>

                    <div class="mb-3">
                        <label for="">Longitude</label>
                        <input type="text" class="form-control" name="longitude" value="<?= $longitude ?>">
                    </div>

                    <div class="mb-3">
                        <label for="">Radius</label>
                        <input type="text" class="form-control" name="radius" value="<?= $radius ?>">
                    </div>

                    <div class="mb-3">
                        <label for="">Zona Waktu</label>
                        <select name="zona_waktu" class="form-control">
                            <option value="">--Pilih Zona Waktu--</option>
                            <option <?php if ($zona_waktu == 'WIB') {
                                echo 'selected'; } ?> value="WIB">WIB</option>

                            <option <?php if ($zona_waktu == 'WITA') {
                                echo 'selected'; } ?> value="WITA">WITA</option>

                            <option <?php if ($zona_waktu == 'WIT') {
                                echo 'selected'; } ?> value="WIT">WIT</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="">Jam Masuk</label>
                        <input type="time" class="form-control" name="jam_masuk" value="<?= $jam_masuk ?>">
                    </div>

                    <div class="mb-3">
                        <label for="">Jam Pulang</label>
                        <input type="time" class="form-control" name="jam_pulang" value="<?= $jam_pulang ?>">
                    </div>

                    <input type="hidden" value="<?= $id ?>" name="id">
                    <button type=" sumbit" name="update" class="btn btn-primary">Update</button>
                    <a href="lokasi_presensi.php" class="btn btn-danger">Batal</a>
                </form>

            </div>
        </div>

    </div>
</div>
<?php include('../layout/footer.php');  ?>
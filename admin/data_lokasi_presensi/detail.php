<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"
    integrity="sha512-dQIiHSl2hr3NWKKLycPndtpbh5iaHLo6MwrXm7F0FM5e+kL2U16oE9uIwPHUl6fQBeCthiEuV/rzP3MiAB8Vfw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<?php 
session_start();

if(!isset($_SESSION["login"])){
    header("Location: ../../auth/login.php?belum_login");
}else if($_SESSION["role"] != 'admin'){
    header("Location: ../../auth/login.php?tolak_akses");
}

$judul = "Detail Lokasi Presensi"; 
include('../layout/header.php');
require_once('../../config.php');

$id = $_GET['id'];
$result = mysqli_query($connection, "SELECT * FROM lokasi_presensi WHERE id=$id");

$lokasi = mysqli_fetch_assoc($result);
$latitude_kantor = floatval($lokasi['latitude']);
$longitude_kantor = floatval($lokasi['longitude']);
?>

<div class="page-body">
    <div class="container-xl">

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <td>Nama Lokasi</td>
                                <td>: <?= $lokasi['nama_lokasi'] ?></td>
                            </tr>
                            <tr>
                                <td>Alamat Lokasi</td>
                                <td>: <?= $lokasi['alamat_lokasi'] ?></td>
                            </tr>
                            <tr>
                                <td>Tipe Lokasi</td>
                                <td>: <?= $lokasi['tipe_lokasi'] ?></td>
                            </tr>
                            <tr>
                                <td>Latitude</td>
                                <td>: <?= $lokasi['latitude'] ?></td>
                            </tr>
                            <tr>
                                <td>Longitude</td>
                                <td>: <?= $lokasi['longitude'] ?></td>
                            </tr>
                            <tr>
                                <td>Radius (Meter)</td>
                                <td>: <?= $lokasi['radius'] ?></td>
                            </tr>
                            <tr>
                                <td>Zona Waktu</td>
                                <td>: <?= $lokasi['zona_waktu'] ?></td>
                            </tr>
                            <tr>
                                <td>Jam Masuk</td>
                                <td>: <?= $lokasi['jam_masuk'] ?></td>
                            </tr>
                            <tr>
                                <td>Jam Pulang</td>
                                <td>: <?= $lokasi['jam_pulang'] ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div id="map" style="width: 100%; height: 405px;"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // map leaflet js
    let latitude_ktr = <?= $latitude_kantor ?>;
    let longitude_ktr = <?= $longitude_kantor ?>;

    let map = L.map('map').setView([latitude_ktr, longitude_ktr], 13);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    var marker = L.marker([latitude_ktr, longitude_ktr]).addTo(map)
        .bindPopup("Lokasi Kantor").openPopup();
});
</script>

<?php include('../layout/footer.php');  ?>
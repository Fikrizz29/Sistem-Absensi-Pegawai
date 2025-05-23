<footer class="footer footer-transparent d-print-none">
    <div class="container-xl">
        <div class="row text-center align-items-center flex-row-reverse">
            <div class="col-lg-auto ms-lg-auto">
                <ul class="list-inline list-inline-dots mb-0">
                </ul>
            </div>
            <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                <ul class="list-inline list-inline-dots mb-0">
                    <li class="list-inline-item">
                        Copyright &copy; 2025
                        <a href="" class="link-secondary">ElevenTwelfth</a>
                        All rights reserved.
                    </li>
                </ul>
            </div>
        </div>
    </div>
</footer>
<!-- Libs JS -->
<script src="<?= base_url('assets/libs/apexcharts/dist/apexcharts.min.js?1692870487') ?>" defer></script>
<script src="<?= base_url('assets/libs/jsvectormap/dist/js/jsvectormap.min.js?1692870487') ?>" defer></script>
<script src="<?= base_url('assets/libs/jsvectormap/dist/maps/world.js?1692870487') ?>" defer></script>
<script src="<?= base_url('assets/libs/jsvectormap/dist/maps/world-merc.js?1692870487') ?>" defer></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

<!-- Tabler Core -->
<script src="<?= base_url('assets/js/tabler.min.js?1692870487') ?>" defer></script>
<script src="<?= base_url('assets/js/demo.min.js?1692870487') ?>" defer></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- sweetalert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-o9N1j8RE6fD3v2QK5z5k7f5p9e9e9e9e9e9e9e9e9=" crossorigin=""></script>


<!-- Menambahkan Script Modal pada footer.php -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    $('#fotoModal').on('show.bs.modal', function(event) {
        var link = $(event.relatedTarget); // Elemen <a> yang diklik
        var fotoUrl = link.data('foto');
        var jenis = link.data('jenis');
        var modal = $(this);

        modal.find('#fotoView').attr('src', fotoUrl);

        if (jenis === 'masuk') {
            modal.find('#fotoModalLabel').text('Foto Presensi Masuk');
        } else if (jenis === 'keluar') {
            modal.find('#fotoModalLabel').text('Foto Presensi Keluar');
        } else {
            modal.find('#fotoModalLabel').text('Lihat Foto');
        }
    });
});
</script>


<script>
document.addEventListener("DOMContentLoaded", function() {
    // Tangkap semua form dengan ID "exportForm"
    const exportForms = document.querySelectorAll("#exportForm");

    exportForms.forEach(function(form) {
        form.addEventListener("submit", function(e) {
            const tanggalDari = form.querySelector('input[name="tanggal_dari"]');
            const tanggalSampai = form.querySelector('input[name="tanggal_sampai"]');
            const bulan = form.querySelector('select[name="filter_bulan"]');
            const tahun = form.querySelector('select[name="filter_tahun"]');

            // Jika form punya input tanggal (berarti form export berdasarkan tanggal)
            if (tanggalDari && tanggalSampai) {
                if (!tanggalDari.value || !tanggalSampai.value) {
                    e.preventDefault();
                    alert("Tanggal Awal dan Tanggal Akhir wajib diisi!");
                    return;
                }
            }

            // Jika form punya input bulan dan tahun (berarti form export bulanan)
            if (bulan && tahun) {
                if (!bulan.value || !tahun.value) {
                    e.preventDefault();
                    alert("Bulan dan Tahun wajib dipilih!");
                    return;
                }
            }
        });
    });
});
</script>


<!-- alert validasi -->
<?php if (isset($_SESSION['validasi'])) : ?>

<script>
const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
    }
});
Toast.fire({
    icon: "error",
    title: "<?= $_SESSION['validasi'] ?>"
});
</script>
<?php unset($_SESSION['validasi']); ?>
<?php endif; ?>

<!-- alert berhasil -->
<?php if (isset($_SESSION['berhasil'])) : ?>

<script>
const Berhasil = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
    }
});
Berhasil.fire({
    icon: "success",
    title: "<?= $_SESSION['berhasil'] ?>"
});
</script>
<?php unset($_SESSION['berhasil']); ?>
<?php endif; ?>

<!-- alert konfirmasi dihapus -->
<script>
$('.tombol-hapus').on('click', function() {
    var getlink = $(this).attr('href');
    Swal.fire({
        title: "Yakin hapus?",
        text: "Data yang sudah dihapus tidak bisa dikembalikan",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, hapus"
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = getlink
        }
    })
    return false;
});
</script>

</body>

</html>
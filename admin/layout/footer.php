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
<!-- sweetalert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
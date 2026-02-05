<!-- JAVASCRIPT -->
<script src="<?= asset('backend_assets') ?>/libs/swiper/swiper-bundle.min.js"></script>
<script src="<?= asset('backend_assets') ?>/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= asset('backend_assets') ?>/libs/simplebar/simplebar.min.js"></script>
<script src="<?= asset('backend_assets') ?>/js/scroll-top.init.js"></script>
<script src="<?= asset('backend_assets') ?>/libs/sweetalert2/sweetalert2.min.js"></script>
<script src="<?= asset('backend_assets') ?>/js/jquery.min.js"></script>
<script>

const ToastMixin = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 2500,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
    }
});

    function showToast(type,message)
    {
        ToastMixin.fire({
                icon: type,
                title: message
            });
    }
   
</script>
@include('backend.alert')
</body>
</html>
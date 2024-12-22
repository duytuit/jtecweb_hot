<!-- Plugins JS File -->
<script src="{{ asset('public/assets/common/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('public/assets/common/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- Sweet Alert -->
<script src="{{ asset('public/assets/backend/js/sweetalert2/dist/sweetalert2.min.js') }}"></script>
<script src="{{ asset('public/assets/backend/js/sweetalert2/sweet-alert.init.js') }}"></script>
<!-- Main JS File -->
<script src="{{ asset('public/assets/frontend/js/frontend-main.js') }}"></script>
<script src="{{ asset('public/assets/frontend/js/socket.io.min.js') }}"></script>
<script src="{{ asset('public/assets/frontend/js/jquery-code-scanner.js') }}"></script>
<script src="{{ asset('public/assets/frontend/js/html5-qrcode.min.js') }}"></script>

<script>
$.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
</script>


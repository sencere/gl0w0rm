@if ($flash = session('message'))
    <div class="alert alert-success message" role="alert">{{ $flash }}</div>
@endif

<script>
window.onload = function() {
    $(document).ready(function() {
        $('.message').fadeOut(5000);
    });
};
</script>

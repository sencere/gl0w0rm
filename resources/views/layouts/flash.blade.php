@if ($flash = session('message'))
    <div class="alert alert-success message" role="alert">{{ $flash }}</div>
    <script>
        function fade(element) {
            var op = 1;  // initial opacity
            var timer = setInterval(function () {
                if (op <= 0.1){
                    clearInterval(timer);
                    element.style.display = 'none';
                }
                element.style.opacity = op;
                element.style.filter = 'alpha(opacity=' + op * 100 + ")";
                op -= op * 0.1;
            }, 250);
        }

        var messageElement = document.querySelector('.message');
        fade(messageElement);
    </script>
@endif


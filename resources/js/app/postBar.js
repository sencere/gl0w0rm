function PostBar() {
    $votes = $('#votes');
    $upFill = $('#thumbsup-fill');
    $upEmpty = $('#thumbsup-empty');
    $downFill = $('#thumbsdown-fill');
    $downEmpty = $('#thumbsdown-empty');
    $upCounter = $('#up-counter');
    $downCounter = $('#down-counter');
    token = $('meta[name="csrf-token"]').attr('content');
    postId = $('#landgrass').data('id');
    url = '/posts/' + postId + '/votes';

    function requestAdd(type) {
        $.ajax({
            type: "POST",
            url: url,
            data: {
                '_token':token,
                'type': type
            },
            success: function() {},
            dataType: 'json'
        });
    }

    function requestRemove(type) {
        $.ajax({
            type: "DELETE",
            url: url,
            data: {
                '_token':token,
                'type': type
            },
            success: function() {},
            dataType: 'json'
        });
    }

    function requestAddUp() {
        requestAdd('up');
    }

    function requestAddDown() {
        requestAdd('down');
    }

    function requestRemoveUp() {
        requestRemove('up');
    }

    function requestRemoveDown() {
        requestRemove('down');
    }

    function changeCounter(state) {
        var upperCount = parseInt($upCounter.html());
        var downCount = parseInt($downCounter.html());

        if ($votes.hasClass('up')) {
            $upCounter.html(++upperCount);
            if (state === 'down') {
                $downCounter.html(--downCount);
            }
            requestAddUp();
        } else if ($votes.hasClass('down')) {
            $downCounter.html(++downCount);
            if (state === 'up') {
                $upCounter.html(--upperCount);
            }
            requestAddDown();
        } else {
            if (state === 'up') {
                $upCounter.html(--upperCount);
                requestRemoveUp();
            } else if (state === 'down') {
                $upCounter.html(--upperCount);
                requestRemoveDown();
            }
        }
    }

    function displayUp() {
        displayEmpty();
        $upFill.removeClass('d-none');
        $downEmpty.removeClass('d-none');

        if ($votes.hasClass('up')) {
            $votes.removeClass('up');
        } else {
            $votes.addClass('up');
        }
    }

    function displayDown() {
        displayEmpty();
        $downFill.removeClass('d-none');
        $upEmpty.removeClass('d-none');

        if ($votes.hasClass('down')) {
            $votes.removeClass('down');
        } else {
            $votes.addClass('down');
        }
    }

    function displayEmpty() {
        $upFill.addClass('d-none');
        $upEmpty.addClass('d-none');
        $downFill.addClass('d-none');
        $downEmpty.addClass('d-none');
        changeStatus('');
    }

    function init() {
        $upEmpty.removeClass('d-none');
        $downEmpty.removeClass('d-none');
        changeStatus('');
    }

    function changeStatus(changeStatusTo) {
        $votes.removeClass('up');
        $votes.removeClass('down');
        $votes.addClass(changeStatusTo);
    }

    if ($votes.hasClass('up') || $votes.hasClass('down')) {
        if ($votes.hasClass('up')) {
            displayUp();
        }

        if ($votes.hasClass('down')) {
            displayDown();
        }
    } else {
        displayEmpty();
        init();
    }

    $upFill.on('click', function(){
        displayEmpty();
        init();
        changeCounter('up');
    });

    $upEmpty.on('click', function(){
        buttonState = $votes.hasClass('down') ? 'down' : 'up';
        displayUp();
        changeCounter(buttonState);
    });

    $downFill.on('click', function(){
        displayEmpty();
        init();
        changeCounter('down');
    });

    $downEmpty.on('click', function(){
        buttonState = $votes.hasClass('down') ? 'down' : 'up';
        displayDown();
        changeCounter(buttonState);
    });
}

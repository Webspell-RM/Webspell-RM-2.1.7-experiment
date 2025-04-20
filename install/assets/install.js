$(document).ready(function () {
    function updateProgress() {
        $.get('progress.php', function (data) {
            let percent = data.progress || 0;
            $('#progressBar').css('width', percent + '%').text(percent + '%');
            if (percent < 100) setTimeout(updateProgress, 500);
        });
    }

    updateProgress();
});
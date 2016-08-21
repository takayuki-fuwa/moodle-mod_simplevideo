$('#btn_fullscreen').on('click', function(event) {
    document.getElementsByTagName('video')[0].webkitEnterFullscreen();
    if (!!document.getElementsByTagName('video')[0].requestFullScreen) {
        document.getElementsByTagName('video')[0].requestFullScreen();
    } else if (!!document.getElementsByTagName('video')[0].webkitRequestFullScreen) {
        document.getElementsByTagName('video')[0].webkitRequestFullScreen();
    } else if (!!document.getElementsByTagName('video')[0].webkitEnterFullscreen) {
        document.getElementsByTagName('video')[0].webkitEnterFullscreen();
    }
});
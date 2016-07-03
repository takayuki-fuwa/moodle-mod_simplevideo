videojs.options.flash.swf = "js/video-js.swf";

var video = videojs("simplevideo_player", {
    controls: true,
    autoplay: true,
    preload: 'auto',
    plugins: {}
});

videojs(video, {html5: {
    hls: {
        withCredentials: false
    }
}});

videojs(video, {flash: {
    hls: {
        withCredentials: false
    }
}});
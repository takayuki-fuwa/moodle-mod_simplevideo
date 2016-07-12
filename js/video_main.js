videojs.options.flash.swf = "js/video-js.swf";

var player = videojs("simplevideo_player", {
    controls: true,
    autoplay: true,
    preload: 'auto',
    plugins: {}
});

player.play();
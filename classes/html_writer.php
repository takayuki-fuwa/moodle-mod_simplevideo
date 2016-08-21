<?php

namespace mod_simplevideo;

class html_writer extends \html_writer
{
    /**
     * <video>タグでプレーヤー部分を出力させる。
     *
     * @param \moodle_url $url
     * @param array $params
     * @return string $html
     */
    public static function video($url, $params, $enable_autoload, $enable_controls)
    {
        preg_match('/(.*)(?:\.([^.]+$))/', $url->get_path(), $preg_url);

        if ($preg_url) {
            switch ($preg_url[2]) {
                case "rtmp" :
                    $video_type = "rtmp/mp4";
                    break;
                case "mp4" :
                    $video_type = "video/mp4";
                    break;
                case "avi" :
                    $video_type = "video/x-msvideo";
                    break;
                case "m3u8" :
                    $video_type = "application/x-mpegURL";
                    break;
                default :
                    $video_type = "video/mp4";
                    break;
            }
        } else {
            $video_type = null;
        }

        $html = html_writer::start_tag("video", $params);
        $html .= html_writer::empty_tag("source", ["src" => $url, "type" => $video_type]);
        $html .= html_writer::tag("p", "動画をこの画面で再生する場合、Javascriptを有効にして下さい。", ["class" => "vjs-no-js"]);
        $html .= html_writer::end_tag("video");

        $enable_autoload = (int)$enable_autoload == 1 ? "true" : "false";
        $enable_controls = (int)$enable_controls == 1 ? "true" : "false";

        $js = <<<JS
videojs.options.flash.swf = "js/video-js.swf";
//videojs.flashls({swfUrl: "js/video-js.swf"});

videojs.options.flash.swf = "video-js.swf";
var player = videojs("simplevideo_player", {
    controls: $enable_controls,
    autoplay: $enable_autoload,
    preload: 'auto',
    loop: false,
    "width": 640,
    "height": 480,
    plugins: {},
    flash : {
        hls: {
            withCredentials: false
        }
    },
    html5 : {
        hls: {
            withCredentials: false
        }
    }
});

player.src({
    src: "$url",
    type: 'application/x-mpegURL',
    withCredentials: false
});



JS;
        $html .= html_writer::script($js, null);

        return $html;
    }
}
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
    public static function video($url, $params)
    {
        preg_match('/(.*)(?:\.([^.]+$))/', $url->get_path(), $preg_url);

        if ($preg_url) {
            switch ($url->get_scheme()) {
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

        return $html;
    }
}
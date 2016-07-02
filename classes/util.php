<?php
/**
 * Created by PhpStorm.
 * User: yue_2
 * Date: 2016/06/29
 * Time: 23:30
 */

namespace mod_simplevideo;

class util
{

    /**
     * 活動インスタンスを取得する。
     *
     * @param int $url_id
     * @return mixed
     */
    public static function getInstance($url_id)
    {
        global $DB;
        return $DB->get_record("urlvideo", ["id" => $url_id]);
    }

    /**
     * ユーザーエージェントを取得する。
     *
     * @param void
     * @return string
     */
    public static function getUserAgent()
    {
        $ua = $_SERVER['HTTP_USER_AGENT'];

        if ((strpos($ua, 'Android') !== false)) {
            return "Android";
        } elseif ((strpos($ua, 'iPad') !== false || (strpos($ua, 'iPhone') !== false))) {
            return "iOS";
        } else {
            return "PC";
        }
    }

    /**
     * インスタンスに紐付く動画URLを取得する。
     *
     * @param $instance
     * @return mixed
     */
    public static function getVideoUrls($instance)
    {
        global $DB;
        return $DB->get_record("simplevideo_videos", ["instanceid" => $instance->id]);
    }
}
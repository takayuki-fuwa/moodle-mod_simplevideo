<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Prints a particular instance of simplevideo
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod_simplevideo
 * @copyright  2016 Takayuki Fuwa
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_simplevideo;

global $DB, $PAGE, $OUTPUT;

require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once(dirname(__FILE__) . '/lib.php');

$id = optional_param('id', 0, PARAM_INT);
$n = optional_param('n', 0, PARAM_INT);

if ($id) {
    $cm = get_coursemodule_from_id('simplevideo', $id, 0, false, MUST_EXIST);
    $course = $DB->get_record('course', ['id' => $cm->course], '*', MUST_EXIST);
    $instance = $DB->get_record('simplevideo', ['id' => $cm->instance], '*', MUST_EXIST);
} else if ($n) {
    $instance = $DB->get_record('simplevideo', ['id' => $n], '*', MUST_EXIST);
    $course = $DB->get_record('course', ['id' => $instance->course], '*', MUST_EXIST);
    $cm = get_coursemodule_from_instance('simplevideo', $instance->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);

// アクセスログを記録
$event = event\course_module_viewed::create([
    'objectid' => $PAGE->cm->instance,
    'context' => $PAGE->context,
]);
$event->add_record_snapshot('course', $PAGE->course);
$event->add_record_snapshot($PAGE->cm->modname, $instance);
$event->trigger();

$PAGE->set_url('/mod/simplevideo/view.php', ['id' => $cm->id]);
$PAGE->set_title(format_string($instance->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->requires->css(new \moodle_url("css/video-js.min.css"));
$PAGE->requires->js(new \moodle_url("js/video.min.js"), true);
$PAGE->requires->js(new \moodle_url("js/videojs-contrib-media-sources.min.js"), true);
$PAGE->requires->js(new \moodle_url("js/videojs-contrib-hls.min.js"), true);

echo $OUTPUT->header();

echo html_writer::tag("h1", $instance->name);

echo html_writer::start_div("container");

echo html_writer::start_div("row");

//説明文を表示させる(設定されている場合のみ)
if ($instance->intro) {
    echo html_writer::start_div("span4 well");
    echo html_writer::tag("p", $instance->intro);
    //.col-md-4
    echo html_writer::end_div();
}

//動画プレーヤーを表示させる。
$videotag_params = [
    "id" => "simplevideo_player",
    "class" => "video-js vjs-default-skin vjs-big-play-centered",
    "data-setup" => '{
        "playbackRates": [0.5, 1, 1.5, 2]
    }'
];
echo html_writer::start_div("span7 well");
echo html_writer::video(new \moodle_url($instance->url), $videotag_params, $instance->enable_autoload, $instance->enable_controler);
echo html_writer::end_div();

//.row
echo html_writer::end_div();
//.container
echo html_writer::end_div();


echo $OUTPUT->footer();
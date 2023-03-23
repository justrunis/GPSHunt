<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Prints an instance of mod_gpshunt.
 *
 * @package     mod_gpshunt
 * @copyright   2023 Justinas Runevicius <justinas.runevicius@distance.ktu.lt>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__.'/../../config.php');
require_once(__DIR__.'/lib.php');
require_once("$CFG->dirroot/mod/gpshunt/lib.php");

// Course module id.
$id = optional_param('id', 0, PARAM_INT);

// Activity instance id.
$g = optional_param('g', 0, PARAM_INT);

if ($id) {
    $cm = get_coursemodule_from_id('gpshunt', $id, 0, false, MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $moduleInstance = $DB->get_record('gpshunt', array('id' => $cm->instance), '*', MUST_EXIST);
} else {
    $moduleInstance = $DB->get_record('gpshunt', array('id' => $g), '*', MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $moduleInstance->course), '*', MUST_EXIST);
    $cm = get_coursemodule_from_instance('gpshunt', $moduleInstance->id, $course->id, false, MUST_EXIST);
}

require_login($course, true, $cm);

if (!is_siteadmin()) {
    redirect(new moodle_url('/mod/gpshunt/play.php', array('id' => $cm->id)));
}

$modulecontext = context_module::instance($cm->id);

$event = \mod_gpshunt\event\course_module_viewed::create(array(
    'objectid' => $moduleInstance->id,
    'context' => $modulecontext
));
$event->add_record_snapshot('course', $course);
$event->add_record_snapshot('gpshunt', $moduleInstance);
$event->trigger();

$PAGE->set_url('/mod/gpshunt/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($moduleInstance->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($modulecontext);

echo $OUTPUT->header();

$latitude = 0;
$longitude = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $latitude = $_POST["latitude"] ?? 0;
    $longitude = $_POST["longitude"] ?? 0;
}
if (isset($_POST['latitudeCoords']) && isset($_POST['longitudeCoords'])) {
    // Get the submitted answers.
    $answerLatitude = $_POST["latitudeCoords"] ?? $latitude;
    $answerLongitude = $_POST["longitudeCoords"] ?? $longitude;

    // Update the coordinates fields in the gpshunt table.
    $update = new stdClass();
    $update->id = $moduleInstance->id;
    $update->latitude = $answerLatitude;
    $update->longitude = $answerLongitude;
    $DB->update_record('gpshunt', $update);
    $moduleInstance = get_moduleinstance($id, $g);
}

display_admin_map_form($moduleInstance, $cm);
display_precision_submit_form($moduleInstance);
display_admin_map($moduleInstance);

echo $OUTPUT->footer();

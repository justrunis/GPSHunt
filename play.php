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
require_once($CFG->libdir . '/gradelib.php');

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

if(!has_user_located_correctly($DB, $USER, $moduleInstance)){
    $latitude = 0;
    $longitude = 0;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $latitude = $_POST["userLatitudeCoords"] ?? 0;
        $longitude = $_POST["userLongitudeCoords"] ?? 0;

    }
    if (isset($_POST['userLatitudeCoords']) && isset($_POST['userLongitudeCoords'])) {
        // Get the submitted answers.
        $userLatitude = $_POST["userLatitudeCoords"];
        $userLongitude = $_POST["userLongitudeCoords"];


        $correctLatitude = $moduleInstance->latitude;
        $correctLongitude = $moduleInstance->longitude;

        $iscorrect = is_player_in_correct_location($correctLongitude, $correctLatitude, $userLongitude,
            $userLatitude, $maxDistance = 15);

        // Update the answer field in the gpshunt table.
        $update = new stdClass();

        //$update->id = $moduleInstance->id;
        $update->userid = $USER->id;
        $update->timecreated = time();
        $update->gpshuntid = $moduleInstance->id;
        $update->latitude = $userLatitude;
        $update->longitude = $userLongitude;

        if($iscorrect){
            // location is correct
            $update->correctanswer = 1;
            $DB->insert_record('gpshunt_user_locations', $update);
            $moduleInstance = get_moduleinstance($id, $g);




            $item = array(
                'itemname' => 'GPS Hunt',
                'gradetype' => GRADE_TYPE_VALUE,
                'grademax' => 100,
                'grademin' => 0
            );

            $itemid = grade_update('mod_gpshunt', $PAGE->course->id, 'mod', 'gpshunt', $moduleInstance->id, 0, null, $item);

            $grade = array(
                'userid' => $USER->id,
                'rawgrade' => 10
            );

            $grade_item = grade_update('mod_assign', $PAGE->course->id, 'mod', 'assign', $moduleInstance->id, $USER->id, $grade);



            // Redirect the user to the same page after the form has been submitted and answer is correct.
            reload_page();
        }
        else{
            // location is not correct
            $update->correctanswer = 0;
            $DB->insert_record('gpshunt_user_locations', $update);
            $moduleInstance = get_moduleinstance($id, $g);

            $_SESSION['message'] = get_string('incorrectlocation', 'mod_gpshunt');
            if (isset($_SESSION['message'])) {
                echo '<div class="alert alert-danger">' . $_SESSION['message'] . '</div>';
                unset($_SESSION['message']);
            }
        }
    }
    display_user_map_form($PAGE);
}
else{
    echo '<div class="alert alert-success">' . "Correct location press back to go back" . '</div>';
    create_button_back_to_course($PAGE->course->id);
}

echo $OUTPUT->footer();
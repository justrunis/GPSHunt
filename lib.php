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
 * Library of interface functions and constants.
 *
 * @package     mod_gpshunt
 * @copyright   2023 Justinas Runevicius <justinas.runevicius@distance.ktu.lt>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Return if the plugin supports $feature.
 *
 * @param string $feature Constant representing the feature.
 * @return true | null True if the feature is supported, null otherwise.
 */
function gpshunt_supports($feature) {
    switch ($feature) {
        case FEATURE_MOD_INTRO:
        case FEATURE_GRADE_HAS_GRADE:
            return true;
        default:
            return null;
    }
}

/**
 * Saves a new instance of the mod_gpshunt into the database.
 *
 * Given an object containing all the necessary data, (defined by the form
 * in mod_form.php) this function will create a new instance and return the id
 * number of the instance.
 *
 * @param object $moduleinstance An object from the form.
 * @param mod_gpshunt_mod_form $mform The form.
 * @return int The id of the newly inserted record.
 */
function gpshunt_add_instance($moduleinstance, $mform = null) {
    global $DB;

    $moduleinstance->timecreated = time();

    $id = $DB->insert_record('gpshunt', $moduleinstance);

    return $id;
}

/**
 * Updates an instance of the mod_gpshunt in the database.
 *
 * Given an object containing all the necessary data (defined in mod_form.php),
 * this function will update an existing instance with new data.
 *
 * @param object $moduleinstance An object from the form in mod_form.php.
 * @param mod_gpshunt_mod_form $mform The form.
 * @return bool True if successful, false otherwise.
 */
function gpshunt_update_instance($moduleinstance, $mform = null) {
    global $DB;

    $moduleinstance->timemodified = time();
    $moduleinstance->id = $moduleinstance->instance;

    return $DB->update_record('gpshunt', $moduleinstance);
}

/**
 * Removes an instance of the mod_gpshunt from the database.
 *
 * @param int $id Id of the module instance.
 * @return bool True if successful, false on failure.
 */
function gpshunt_delete_instance($id) {
    global $DB;

    $exists = $DB->get_record('gpshunt', array('id' => $id));
    if (!$exists) {
        return false;
    }

    $DB->delete_records('gpshunt', array('id' => $id));

    return true;
}

/**
 * Is a given scale used by the instance of mod_gpshunt?
 *
 * This function returns if a scale is being used by one mod_gpshunt
 * if it has support for grading and scales.
 *
 * @param int $moduleinstanceid ID of an instance of this module.
 * @param int $scaleid ID of the scale.
 * @return bool True if the scale is used by the given mod_gpshunt instance.
 */
function gpshunt_scale_used($moduleinstanceid, $scaleid) {
    global $DB;

    if ($scaleid && $DB->record_exists('gpshunt', array('id' => $moduleinstanceid, 'grade' => -$scaleid))) {
        return true;
    } else {
        return false;
    }
}

/**
 * Checks if scale is being used by any instance of mod_gpshunt.
 *
 * This is used to find out if scale used anywhere.
 *
 * @param int $scaleid ID of the scale.
 * @return bool True if the scale is used by any mod_gpshunt instance.
 */
function gpshunt_scale_used_anywhere($scaleid) {
    global $DB;

    if ($scaleid && $DB->record_exists('gpshunt', array('grade' => -$scaleid))) {
        return true;
    } else {
        return false;
    }
}

/**
 * Creates or updates grade item for the given mod_gpshunt instance.
 *
 * Needed by {@see grade_update_mod_grades()}.
 *
 * @param stdClass $moduleinstance Instance object with extra cmidnumber and modname property.
 * @param bool $reset Reset grades in the gradebook.
 * @return void.
 */
function gpshunt_grade_item_update($moduleinstance, $reset=false) {
    global $CFG;
    require_once($CFG->libdir.'/gradelib.php');

    $item = array();
    $item['itemname'] = clean_param($moduleinstance->name, PARAM_NOTAGS);
    $item['gradetype'] = GRADE_TYPE_VALUE;

    if ($moduleinstance->grade > 0) {
        $item['gradetype'] = GRADE_TYPE_VALUE;
        $item['grademax']  = $moduleinstance->grade;
        $item['grademin']  = 0;
    } else if ($moduleinstance->grade < 0) {
        $item['gradetype'] = GRADE_TYPE_SCALE;
        $item['scaleid']   = -$moduleinstance->grade;
    } else {
        $item['gradetype'] = GRADE_TYPE_NONE;
    }
    if ($reset) {
        $item['reset'] = true;
    }

    grade_update('/mod/gpshunt', $moduleinstance->course, 'mod', 'mod_gpshunt', $moduleinstance->id, 0, null, $item);
}

/**
 * Delete grade item for given mod_gpshunt instance.
 *
 * @param stdClass $moduleinstance Instance object.
 * @return grade_item.
 */
function gpshunt_grade_item_delete($moduleinstance) {
    global $CFG;
    require_once($CFG->libdir.'/gradelib.php');

    return grade_update('/mod/gpshunt', $moduleinstance->course, 'mod', 'gpshunt',
                        $moduleinstance->id, 0, null, array('deleted' => 1));
}

/**
 * Update mod_gpshunt grades in the gradebook.
 *
 * Needed by {@see grade_update_mod_grades()}.
 *
 * @param stdClass $moduleinstance Instance object with extra cmidnumber and modname property.
 * @param int $userid Update grade of specific user only, 0 means all participants.
 */
function gpshunt_update_grades($moduleinstance, $userid = 0) {
    global $CFG, $DB;
    require_once($CFG->libdir.'/gradelib.php');

    // Populate array of grade objects indexed by userid.
    $grades = array();
    grade_update('/mod/gpshunt', $moduleinstance->course, 'mod', 'mod_gpshunt', $moduleinstance->id, 0, $grades);
}

function display_admin_map_form($moduleInstance, $cm){
    ?>
    <form method="post" action="">
        <div id="map" style="width: 800px; height: 500px;"></div>
        <br>
        <input class="btn btn-dark" type="submit" value="<?php echo get_string('refreshlocation', 'mod_gpshunt'); ?>">
        <?php button_to_play($cm); ?>
        <input id="latitudeCoords" type="hidden" value="" name="latitudeCoords">
        <input id="longitudeCoords" type="hidden" value="" name="longitudeCoords">
        <table id="coordinates-table">
            <tr>
                <td><?php echo get_string('latitude', 'mod_gpshunt'); ?></td>
                <td id="latitude"><?php echo $moduleInstance->latitude; ?></td>
            </tr>
            <tr>
                <td><?php echo get_string('longitude', 'mod_gpshunt'); ?></td>
                <td id="longitude"><?php echo $moduleInstance->longitude; ?></td>
            </tr>
            <tr>
        </table>
    </form>
    <?php
}

function display_admin_map($moduleInstance){
    ?>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />

    <!-- Leaflet JavaScript -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>


    <script>
        var coords = {
            longitude:'<?php echo $moduleInstance->longitude?>',
            latitude:'<?php echo $moduleInstance->latitude?>',
        }
    </script>
    <script src="JavaScript/admindisplaymap.js"></script>
    <?php
}

function display_user_map_form($PAGE){
    ?>
    <form method="post" action="">
        <div id="mapid" style="width: 800px; height: 500px;"></div>
        <input id="userLatitudeCoords" type="hidden" value="" name="userLatitudeCoords">
        <input id="userLongitudeCoords" type="hidden" value="" name="userLongitudeCoords">
        <!-- Leaflet CSS -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
        <!-- Leaflet JavaScript -->
        <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
        <script src="JavaScript/userdisplaymap.js"></script>
        <br>
        <input class="btn btn-dark" type="submit" name="submit" value="Submit location">
        <?php     create_button_back_to_course($PAGE->course->id); ?>
    </form>
    <?php
}

function button_to_play($cm) {
    global $PAGE;

    $url = new moodle_url('/mod/gpshunt/play.php', array('id' => $PAGE->cm->id));
    $link_attributes = array(
        'href' => $url->out(),
        'class' => 'btn btn-dark',
    );

    echo html_writer::start_tag('a', $link_attributes);
    echo get_string('play', 'mod_gpshunt');
    echo html_writer::end_tag('a');
}

function get_moduleinstance($id, $g){
    global $DB;
    if ($id) {
        $cm = get_coursemodule_from_id('gpshunt', $id, 0, false, MUST_EXIST);
        $moduleinstance = $DB->get_record('gpshunt', array('id' => $cm->instance), '*', MUST_EXIST);
    } else {
        $moduleinstance = $DB->get_record('gpshunt', array('id' => $g), '*', MUST_EXIST);
    }
    return $moduleinstance;
}

function is_player_in_correct_location($correctLongitude, $correctLatitude, $playersLongitude, $playersLatitude, $maxDistance = 15) {
    // check if input values are valid numbers
    if (!is_numeric($correctLongitude) || !is_numeric($correctLatitude) ||
        !is_numeric($playersLongitude) || !is_numeric($playersLatitude) ||
        !is_numeric($maxDistance)) {
        throw new InvalidArgumentException(get_string('invalidinputnumeric', 'mod_gpshunt'));
    }

    // check if input values are within valid range
    if ($correctLongitude < -180 || $correctLongitude > 180 ||
        $correctLatitude < -90 || $correctLatitude > 90 ||
        $playersLongitude < -180 || $playersLongitude > 180 ||
        $playersLatitude < -90 || $playersLatitude > 90 ||
        $maxDistance <= 0) {
        throw new InvalidArgumentException(get_string('invalidinputdegrees', 'mod_gpshunt'));
    }

    $earthRadius = 6371000; // in meters
    $lat1 = deg2rad($correctLatitude);
    $lon1 = deg2rad($correctLongitude);
    $lat2 = deg2rad($playersLatitude);
    $lon2 = deg2rad($playersLongitude);
    // Haversine formula
    $deltaLat = $lat2 - $lat1;
    $deltaLon = $lon2 - $lon1;
    $a = sin($deltaLat/2) * sin($deltaLat/2) + cos($lat1) * cos($lat2) * sin($deltaLon/2) * sin($deltaLon/2);
    $c = 2 * atan2(sqrt($a), sqrt(1-$a));
    $distance = $earthRadius * $c;

    return $distance <= $maxDistance;
}


function has_user_located_correctly($DB, $USER, $moduleinstance){
    $records = $DB->get_records('gpshunt_user_locations', array('userid' => $USER->id));

    foreach ($records as $record) {
        if($record->correctanswer == 1 && $moduleinstance->id == $record->gpshuntid){
            return true;
        }
    }
    return false;
}

function create_button_back_to_course($courseid) {
    global $CFG;

    $url = new moodle_url('/course/view.php', array('id' => $courseid));
    $link_attributes = array(
        'href' => $url->out(),
        'class' => 'btn btn-dark',
    );

    echo html_writer::start_tag('a', $link_attributes);
    echo get_string('back', 'mod_qrhunt');
    echo html_writer::end_tag('a');
}

function write_gpshunt_user_grade($moduleInstance, $USER, $PAGE, $rawgrade){
    $item = array(
        'itemname' => $moduleInstance->name,
        'gradetype' => GRADE_TYPE_VALUE,
        'grademax' => 100,
        'grademin' => 0
    );

    $grade = array(
        'userid' => $USER->id,
        'rawgrade' => $rawgrade,
        'dategraded' => (new DateTime())->getTimestamp(),
        'datesubmitted' => (new DateTime())->getTimestamp(),
    );
    $grades = [$USER->id => (object)$grade];
    $itemid = grade_update('mod_gpshunt', $PAGE->course->id, 'mod', 'gpshunt', $moduleInstance->id, 0, $grades, $item);
}
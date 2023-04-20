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
 * Plugin strings are defined here.
 *
 * @package     mod_gpshunt
 * @category    string
 * @copyright   2023 Justinas Runevicius <justinas.runevicius@distance.ktu.lt>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'GPS Hunt';
$string['modulenameplural'] = 'GPS HUNT';
$string['modulename'] = 'GPS Hunt';
$string['gpshuntname'] = 'GPS Hunt name';
$string['gpshuntname_help'] = 'Type a name for this GPS hunt activity';
$string['gpshuntsettings'] = 'GPS Hunt settings';
$string['gpshuntfieldset'] = 'GPS Hunt field set';
$string['pluginadministration'] = 'Plugin administration';


$string['modulenameicon'] = '<img src="'.$CFG->wwwroot.'/mod/gpshunt/pix/icon.svg" class="icon" alt="GPS icon" />';

$string['play'] = 'Start game';
$string['setlocation'] = 'Set GPS hunt location';
$string['backtostart'] = 'Back to start';
$string['backtoview'] = 'Back to view';
$string['refreshlocation'] = 'Set location';
$string['locationsuccess'] = 'Location has been successfully changed';
$string['locationerror'] = 'Location cannot be changed';
$string['invalidcoordinates'] = 'Invalid coordinates: cannot update database with (0,0)';

$string['latitude'] = 'Latitude';
$string['longitude'] = 'Longitude';

$string['locationprecision'] = 'Location precision (m)';
$string['updateprecision'] = 'Update precision';
$string['precisionsuccess'] = 'Precision has been successfully changed';
$string['updateprecision_help'] = 'Enter a precision distance that determines how far the user can be from the original location in meters';

$string['submitlocationheading'] = 'Submit your location for GPS hunt game';
$string['submitlocation'] = 'Submit location';
$string['incorrectlocation'] = 'Incorrect location please try again';
$string['nolocationset'] = 'No location is set';
$string['correctlocation'] = 'Correct location press back to go back';
$string['longitude'] = 'Longitude';

$string['invalidinputnumeric'] = 'Invalid input. Longitude and latitude values must be numeric.';
$string['invalidinputdegrees'] = 'Invalid input. Longitude must be between -180 and 180 degrees, latitude must be 
between -90 and 90 degrees, and max distance must be greater than 0 meters.';
$string['invalidinputparameters'] = 'Invalid input parameter(s).';
$string['invalidgradevalue'] = 'Invalid grade value. Grade must be a number between 0 and 100.';
$string['gradingerror'] = 'Error updating grade.';

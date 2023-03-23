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
$string['gpshuntprecision'] = 'Location precision';
$string['gpshuntprecision_help'] = 'Enter how far a person can be from the original marker in meters';


$string['modulenameicon'] = '<img src="'.$CFG->wwwroot.'/mod/gpshunt/pix/icon.svg" class="icon" alt="GPS icon" />';

$string['play'] = 'Start game';
$string['refreshlocation'] = 'Set location';

$string['latitude'] = 'Latitude';
$string['longitude'] = 'Longitude';

$string['incorrectlocation'] = 'Incorrect location';
$string['correctlocation'] = 'Correct location press back to go back';
$string['longitude'] = 'Longitude';

$string['invalidinputnumeric'] = 'Invalid input. Longitude and latitude values must be numeric.';
$string['invalidinputdegrees'] = 'Invalid input. Longitude must be between -180 and 180 degrees, latitude must be between -90 and 90 degrees, and max distance must be greater than 0 meters.';
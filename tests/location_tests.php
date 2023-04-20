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

define('CLI_SCRIPT', true);
require(__DIR__.'/../../../config.php');
global $CFG;
require_once("$CFG->dirroot/mod/gpshunt/lib.php");

use PHPUnit\Framework\TestCase;

// command to run these tests vendor/bin/phpunit tests/location_tests.php
class location_tests extends TestCase
{
    public function testIsPlayerInCorrectLocation()
    {
        $correctLongitude = 100.0;
        $correctLatitude = 50.0;
        $playersLongitude = 100.0;
        $playersLatitude = 50.0;
        $maxDistance = 15.0;

        $result = is_player_in_correct_location($correctLongitude, $correctLatitude, $playersLongitude, $playersLatitude, $maxDistance);

        $this->assertTrue($result);
    }

    public function testIsPlayerNotInCorrectLocation()
    {
        $correctLongitude = 100.0;
        $correctLatitude = 50.0;
        $playersLongitude = 102.0;
        $playersLatitude = 50.0;
        $maxDistance = 15.0;

        $result = is_player_in_correct_location($correctLongitude, $correctLatitude, $playersLongitude, $playersLatitude, $maxDistance);

        $this->assertFalse($result);
    }

    public function testInvalidInputNumeric()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(get_string('invalidinputnumeric', 'mod_gpshunt'));

        is_player_in_correct_location('invalid', 'input', 'values', 'here');
    }

    public function testInvalidInputDegrees()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(get_string('invalidinputdegrees', 'mod_gpshunt'));

        is_player_in_correct_location(200, 95, 200, 95, -5);
    }

    public function testUserNotLocatedCorrectly()
    {
        $DB = $this->getMockBuilder(stdClass::class)
            ->setMethods(['get_records'])
            ->getMock();

        $USER = new stdClass();
        $USER->id = 1;

        $moduleinstance = new stdClass();
        $moduleinstance->id = 2;

        $records = [
            (object) ['userid' => 1, 'correctanswer' => 0, 'gpshuntid' => 2]
        ];

        $DB->expects($this->once())
            ->method('get_records')
            ->with('gpshunt_user_locations', ['userid' => $USER->id])
            ->willReturn($records);

        $this->assertFalse(has_user_located_correctly($DB, $USER, $moduleinstance));
    }

    public function testNoRecordsFound()
    {
        $DB = $this->getMockBuilder(stdClass::class)
            ->setMethods(['get_records'])
            ->getMock();

        $USER = new stdClass();
        $USER->id = 1;

        $moduleinstance = new stdClass();
        $moduleinstance->id = 2;

        $DB->expects($this->once())
            ->method('get_records')
            ->with('gpshunt_user_locations', ['userid' => $USER->id])
            ->willReturn([]);

        $this->assertFalse(has_user_located_correctly($DB, $USER, $moduleinstance));
    }


}

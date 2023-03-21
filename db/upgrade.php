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
 * Plugin upgrade steps are defined here.
 *
 * @package     mod_gpshunt
 * @category    upgrade
 * @copyright   2023 Justinas Runevicius <justinas.runevicius@distance.ktu.lt>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Execute mod_gpshunt upgrade from the given old version.
 *
 * @param int $oldversion
 * @return bool
 */
function xmldb_gpshunt_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2023032010) {

        // Define field latitude to be added to gpshunt.
        $table = new xmldb_table('gpshunt');
        $field1 = new xmldb_field('latitude', XMLDB_TYPE_NUMBER, '10, 8', null, null, null, null, 'introformat');
        $field2 = new xmldb_field('longitude', XMLDB_TYPE_NUMBER, '10, 8', null, null, null, null, 'latitude');

        // Conditionally launch add field latitude.
        if (!$dbman->field_exists($table, $field1)) {
            $dbman->add_field($table, $field1);
        }

        // Conditionally launch add field longitude.
        if (!$dbman->field_exists($table, $field2)) {
            $dbman->add_field($table, $field2);
        }

        // Gpshunt savepoint reached.
        upgrade_mod_savepoint(true, 2023032010, 'gpshunt');
    }

    if ($oldversion < 2023032012) {

        // Changing nullability of field latitude on table gpshunt to not null.
        $table = new xmldb_table('gpshunt');
        $field1 = new xmldb_field('latitude', XMLDB_TYPE_NUMBER, '10, 8', null, XMLDB_NOTNULL, null, '0', 'introformat');

        $field2 = new xmldb_field('longitude', XMLDB_TYPE_NUMBER, '10, 8', null, XMLDB_NOTNULL, null, '0', 'latitude');

        // Launch change of nullability for field latitude.
        $dbman->change_field_notnull($table, $field1);
        $dbman->change_field_notnull($table, $field2);

        // Launch change of default for field latitude.
        $dbman->change_field_default($table, $field1);
        $dbman->change_field_default($table, $field2);

        // Gpshunt savepoint reached.
        upgrade_mod_savepoint(true, 2023032012, 'gpshunt');
    }

    if ($oldversion < 2023032018) {

        // Changing precision of field latitude on table gpshunt to (11, 8).
        $table = new xmldb_table('gpshunt');
        $field1 = new xmldb_field('latitude', XMLDB_TYPE_NUMBER, '11, 8', null, XMLDB_NOTNULL, null, '0', 'introformat');
        $field2 = new xmldb_field('longitude', XMLDB_TYPE_NUMBER, '11, 8', null, XMLDB_NOTNULL, null, '0', 'latitude');

        $dbman->change_field_precision($table, $field1);
        $dbman->change_field_precision($table, $field2);

        // Gpshunt savepoint reached.
        upgrade_mod_savepoint(true, 2023032018, 'gpshunt');
    }

    if ($oldversion < 2023032022) {

        // Define table gpshunt_user_locations to be created.
        $table = new xmldb_table('gpshunt_user_locations');

        // Adding fields to table gpshunt_user_locations.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('gpshuntid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('latitude', XMLDB_TYPE_NUMBER, '11, 8', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('longitude', XMLDB_TYPE_NUMBER, '11, 8', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('correctanswer', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0');

        // Adding keys to table gpshunt_user_locations.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Conditionally launch create table for gpshunt_user_locations.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Gpshunt savepoint reached.
        upgrade_mod_savepoint(true, 2023032022, 'gpshunt');
    }

    return true;
}

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

$string['pluginname'] = 'GPS medžioklė';
$string['modulenameplural'] = 'GPS MEDŽIOKLĖ';
$string['modulename'] = 'GPS medžioklė';
$string['gpshuntname'] = 'GPS medžioklės pavadinimas';
$string['gpshuntname_help'] = 'Įveskite šios GPS medžioklės veiklos pavadinimą';
$string['gpshuntsettings'] = 'GPS medžioklės nustatymai';
$string['gpshuntfieldset'] = 'GPS medžioklės laukai';
$string['pluginadministration'] = 'Įskeipio administratorius';


$string['modulenameicon'] = '<img src="'.$CFG->wwwroot.'/mod/gpshunt/pix/icon.svg" class="icon" alt="GPS icon" />';

$string['play'] = 'Pradėti žaidimą';
$string['setlocation'] = 'Nustatyti GPS medžioklės vietą';
$string['backtostart'] = 'Atgal į pradžią';
$string['backtoview'] = 'Atgal į redagavimą';
$string['refreshlocation'] = 'Nustatyti vietą';
$string['locationsuccess'] = 'Vieta sėkmingai pakeista';
$string['locationerror'] = 'Vietos negalima pakeisti';
$string['invalidcoordinates'] = 'Netinkamos koordinates: negalima atnaujinti koordinačių (0,0)';

$string['latitude'] = 'Platuma';
$string['longitude'] = 'Ilguma';

$string['locationprecision'] = 'Vietos tikslumas (m)';
$string['updateprecision'] = 'Atnaujinti tikslumą';
$string['precisionsuccess'] = 'Tikslumas buvo sėkmingai pakeistas';
$string['updateprecision_help'] = 'Įveskite tikslumo atstumą, kuris nustato, kiek toli vartotojas gali būti nuo pradinės vietos';

$string['submitlocationheading'] = 'Pateikite savo vietą GPS medžioklės žaidimui';
$string['submitlocation'] = 'Pateikti vietą';
$string['incorrectlocation'] = 'Neteisinga vieta, bandykite dar kartą';
$string['nolocationset'] = 'Vieta nenustatyta';
$string['correctlocation'] = 'Teisinga vieta, norėdami grįžti atgal, paspauskite atgal į pradžią';

$string['invalidinputnumeric'] = 'Neteisinga įvestis. Ilgumos ir platumos reikšmės turi būti skaitinės.';
$string['invalidinputdegrees'] = 'Neteisinga įvestis. Ilguma turi būti nuo -180 iki 180 laipsnių, platuma turi būti 
nuo -90 iki 90 laipsnių, o maksimalus atstumas turi būti didesnis nei 0 metrų.';
$string['invalidinputparameters'] = 'Netinkamas (-i) įvesties parametras (-ai).';
$string['invalidgradevalue'] = 'Neteisinga pažymio reikšmė. Įvertinimas turi būti skaičius nuo 0 iki 100.';
$string['gradingerror'] = 'Klaida atnaujinant pažymį.';

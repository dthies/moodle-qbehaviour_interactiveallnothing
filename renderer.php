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
 * Renderer for outputting parts of a question belonging to adaptive
 * all or nothing behaviour.
 *
 * @package    qbehaviour
 * @subpackage adaptiveallnothing
 * @copyright  2015 Daniel Thies <dthies@ccal.edu>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

require_once(dirname(__FILE__) . '/../adaptive/renderer.php');

class qbehaviour_adaptiveallnothing_renderer extends qbehaviour_adaptive_renderer {
    protected function grading_details(qbehaviour_adaptive_mark_details $details, question_display_options $options) {
        $mark = $details->get_formatted_marks($options->markdp);
        if ($mark['raw'] !== $mark['max']) {
            $mark['raw'] = 0;
        }
        return get_string('gradingdetails', 'qbehaviour_adaptive', $mark);
    }

}

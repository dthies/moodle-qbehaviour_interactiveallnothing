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
 * Question behaviour for interactive mode with no partial credit.
 *
 * @package    qbehaviour
 * @subpackage interactiveallnothing
 * @copyright  2015 onward Daniel Thies <dethies@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once(dirname(__FILE__) . '/../interactive/behaviour.php');

/**
 * Question behaviour for interactive mode (all-or-nothing).
 *
 * This is same as interactive except no credit is given for partially correct
 * responses.
 *
 * @copyright  2015 onward Daniel Thies <dethies@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qbehaviour_interactiveallnothing extends qbehaviour_interactive {
    protected function adjusted_fraction($fraction, $prevtries) {
        return question_state::graded_state_for_fraction($fraction) ==
                question_state::$gradedright ? $fraction - $this->question->penalty * $prevtries: 0;
    }

    public function get_state_string($showcorrectness) {
        if ($this->qa->get_state()->is_partially_correct()) {
            return question_state::$gradedwrong->default_string($showcorrectness);
        }
        return $this->qa->get_state()->default_string($showcorrectness);
    }

    public function get_interactive_marks() {
        $gradedstep = $this->get_graded_step();

        // If not partially correct fall back to parent.
        if (empty($gradedstep) ||
                question_state::graded_state_for_fraction($gradedstep->get_behaviour_var('_rawfraction'))
                     == question_state::$gradedright) {
            return parent::get_interactive_marks();
        }

        // Set state to wrong answer since it is partially correct.
        $state = question_state::$gradedwrong;

        // Prepare the grading details.
        $details = $this->interactive_mark_details_from_step($gradedstep, $state, $this->qa->get_max_mark(), $this->question->penalty);

        // Override raw score to show no credit.
        $details->rawmark = 0;

        $details->improvable = $this->is_state_improvable($this->qa->get_state());
        return $details;
    }

}

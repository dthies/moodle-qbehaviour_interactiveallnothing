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
 * Question behaviour for adaptive mode with no partial credit.
 *
 * @package    qbehaviour
 * @subpackage adaptiveallnothing
 * @copyright  2015 Daneil Thies <dthies@ccal.edu>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once(dirname(__FILE__) . '/../adaptive/behaviour.php');

class qbehaviour_adaptiveallnothing extends qbehaviour_adaptive {
    protected function adjusted_fraction($fraction, $prevtries) {
        return $fraction == 1 ? $fraction - $this->question->penalty * $prevtries: 0;
    }

    public function get_state_string($showcorrectness) {
        if ($this->qa->get_state()->is_partially_correct()) {
            return question_state::$gradedwrong->default_string($showcorrectness);
        }
        return $this->qa->get_state()->default_string($showcorrectness);
    }

    public function get_adaptive_marks() {
        // If not partially correct fall back to parent.
        if ($this->qa->get_state()->is_commented() || !$this->qa->get_state()->is_partially_correct()) {
            return parent::get_adaptive_marks();
        }

        // Set state to wrong answer since it is partially correct.
        $state = question_state::$gradedwrong;
        $gradedstep = $this->get_graded_step();

        // Prepare the grading details.
        $details = $this->adaptive_mark_details_from_step($gradedstep, $state, $this->qa->get_max_mark(), $this->question->penalty);
        $details->improvable = $this->is_state_improvable($this->qa->get_state());
        return $details;
    }

}

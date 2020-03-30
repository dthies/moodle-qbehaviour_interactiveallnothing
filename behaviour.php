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
    public function get_state_string($showcorrectness) {
        if ($this->qa->get_state()->is_partially_correct()) {
            return question_state::$gradedwrong->default_string($showcorrectness);
        }
        return $this->qa->get_state()->default_string($showcorrectness);
    }

    protected function adjust_fraction($fraction, question_attempt_pending_step $pendingstep) {
        $state = $pendingstep->get_state();
        if ($state == question_state::$gradedright) {
            return parent::adjust_fraction($fraction, $pendingstep);
        } else {
            return 0;
        }
    }
}

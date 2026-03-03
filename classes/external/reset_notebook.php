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

namespace mod_jupyter\external;

defined('MOODLE_INTERNAL') || die();

use context_module;
use mod_jupyter\jupyterhub;
use external_function_parameters;
use external_value;
use invalid_parameter_exception;

/**
 * Jupyter web service class for resetting the notebook.
 *
 * @package     mod_jupyter
 * @copyright   KIB3 StuPro SS2022 Development Team of the University of Stuttgart
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class reset_notebook extends \external_api {
    /**
     * Returns description of method parameters.
     * @return external_function_parameters
     */
    public static function execute_parameters() {
        return new external_function_parameters([
            'user' => new external_value(PARAM_USERNAME, VALUE_REQUIRED, 'unique user id'),
            'contextid' => new external_value(PARAM_INT, VALUE_REQUIRED, 'module context id'),
            'courseid' => new external_value(PARAM_INT, VALUE_REQUIRED, 'module course id'),
            'instanceid' => new external_value(PARAM_INT, VALUE_REQUIRED, 'module instance id'),
            'autograded' => new external_value(PARAM_BOOL, VALUE_REQUIRED, '1 if assignment is auto-graded, 0 otherwise'),
        ]);
    }

    /**
     * Rename any naming collisions and reupload the default notebook.
     *
     * @param string $user current user's username
     * @param int $contextid contextid of activity instance
     * @param int $courseid course id
     * @param int $instanceid activity instance id
     * @param int $autograded
     * @return string
     */
    public static function execute(string $user, int $contextid, int $courseid, int $instanceid, int $autograded) {
        global $USER;

        $params = self::validate_parameters(self::execute_parameters(), [
            'user' => $user,
            'contextid' => $contextid,
            'courseid' => $courseid,
            'instanceid' => $instanceid,
            'autograded' => $autograded,
        ]);
        $cm = get_coursemodule_from_instance('jupyter', $params['instanceid'], $params['courseid'], false, MUST_EXIST);
        $context = context_module::instance($cm->id);
        self::validate_context($context);
        require_capability('mod/jupyter:view', $context);

        if ($params['contextid'] !== $context->id) {
            throw new invalid_parameter_exception('The context id does not match the requested activity.');
        }

        $currentuser = mb_strtolower($USER->username, 'UTF-8');
        if ($params['user'] !== $currentuser) {
            throw new invalid_parameter_exception('The user parameter must match the current Moodle user.');
        }

        jupyterhub::reset_notebook(
            $params['user'],
            $params['contextid'],
            $params['courseid'],
            $params['instanceid'],
            (int)$params['autograded']
        );
        return 'notebook reset';
    }

    /**
     * Returns description of return values.
     * @return external_value
     */
    public static function execute_returns() {
        return new external_value(PARAM_TEXT, VALUE_REQUIRED, 'test value');
    }
}

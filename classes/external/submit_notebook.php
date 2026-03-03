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

require_once("$CFG->dirroot/mod/jupyter/lib.php");

use context_module;
use invalid_parameter_exception;
use mod_jupyter\gradeservice;
use external_function_parameters;
use external_value;
use external_multiple_structure;
use external_single_structure;
use stdClass;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;

/**
 * Jupyter web service class for submitting a notebook for autograding.
 *
 * @package     mod_jupyter
 * @copyright   KIB3 StuPro SS2022 Development Team of the University of Stuttgart
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class submit_notebook extends \external_api {
    /**
     * Returns description of method parameters.
     * @return external_function_parameters
     */
    public static function execute_parameters() {
        return new external_function_parameters([
            'user' => new external_value(PARAM_USERNAME, VALUE_REQUIRED, 'unique user id'),
            'courseid' => new external_value(PARAM_INT, VALUE_REQUIRED, 'module course id'),
            'instanceid' => new external_value(PARAM_INT, VALUE_REQUIRED, 'module instance id'),
            'filename' => new external_value(PARAM_FILE, VALUE_REQUIRED, 'notebook file name'),
            'token' => new external_value(PARAM_RAW, VALUE_REQUIRED, 'Gradeservice JWT'),
        ]);
    }

    /**
     * Get notebookfile from notebook server and send it to autograding.
     *
     * @param string $user user name of the student that submitted the file
     * @param int $courseid ID of the Moodle course
     * @param int $instanceid ID of the activity instance
     * @param string $filename name of the submitted notebook file
     * @param string $token Gradeservice authorization JWT
     * @return array $points Response array of graded question results
     */
    public static function execute(string $user, int $courseid, int $instanceid, string $filename, string $token) : array {
        global $DB, $USER;

        $params = self::validate_parameters(self::execute_parameters(), [
            'user' => $user,
            'courseid' => $courseid,
            'instanceid' => $instanceid,
            'filename' => $filename,
            'token' => $token,
        ]);
        $cm = get_coursemodule_from_instance('jupyter', $params['instanceid'], $params['courseid'], false, MUST_EXIST);
        $context = context_module::instance($cm->id);
        self::validate_context($context);
        require_capability('mod/jupyter:view', $context);

        $currentuser = mb_strtolower($USER->username, 'UTF-8');
        if ($params['user'] !== $currentuser) {
            throw new invalid_parameter_exception('The user parameter must match the current Moodle user.');
        }

        $points = [];
        $questions = [];

        try {
            gradeservice::submit_assignment(
                $params['user'],
                $params['courseid'],
                $params['instanceid'],
                $params['filename'],
                $params['token']
            );
            $questions = $DB->get_records(
                'jupyter_questions_points',
                ['jupyter' => $params['instanceid'], 'userid' => $USER->id],
                ''
            );
        } catch (ConnectException $e) {
            $error = new stdClass;
            $error->errormessage = get_string('gradeservice_submit_connect_err', 'jupyter');

            $error->question = 0;
            $error->reached = 0;
            $error->max = 0;
            $error->error = true;
            array_push($points, $error);

        } catch (RequestException $e) {
            $error = new stdClass;

            if ($e->getCode() == 408) {
                $error->errormessage = get_string('gradeservice_submit_timeout', 'jupyter');
            } else {
                $error->errormessage = get_string('gradeservice_submit_resp_err', 'jupyter');
            }

            $error->question = 0;
            $error->reached = 0;
            $error->max = 0;
            $error->error = true;
            array_push($points, $error);
        }

        foreach ($questions as $question) {
            $point = new stdClass;
            $point->question = $question->questionnr;
            $point->reached = floatval($question->points);
            $point->max = floatval(
                $DB->get_record(
                    'jupyter_questions',
                    ['jupyter' => $params['instanceid'], 'questionnr' => $question->questionnr],
                    'maxpoints', MUST_EXIST)->maxpoints
            );
            array_push($points, $point);
        }

        return $points;
    }

    /**
     * Returns description of return values.
     * @return external_multiple_structure
     */
    public static function execute_returns() {
        return new external_multiple_structure(
            new external_single_structure([
            'question' => new external_value(PARAM_INT, 'question number in notebook'),
            'reached' => new external_value(PARAM_FLOAT, 'reached points in question after grading'),
            'max' => new external_value(PARAM_FLOAT, 'maximum reachable points in question'),
            'error' => new external_value(PARAM_BOOL, VALUE_OPTIONAL, 'if an error occured'),
            'errormessage' => new external_value(PARAM_TEXT, VALUE_OPTIONAL, 'what error occured'),
            ]));
    }
}

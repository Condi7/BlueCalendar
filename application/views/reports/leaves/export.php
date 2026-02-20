<?php
/**
 * This view exports into a Spreadsheet file the native report listing the approved leave requests of employees attached to an entity.
 * This report is launched by the user from the view reports/leaves.
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 */

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setTitle(mb_strimwidth(lang('reports_export_leaves_title'), 0, 28, "..."));  //Maximum 31 characters allowed in sheet title.

$month = $this->input->get("month") === FALSE ? 0 : $this->input->get("month");
$year = $this->input->get("year") === FALSE ? 0 : $this->input->get("year");
$entity = $this->input->get("entity") === FALSE ? 0 : $this->input->get("entity");
$children = filter_var($this->input->get("children"), FILTER_VALIDATE_BOOLEAN);
$requests = filter_var($this->input->get("requests"), FILTER_VALIDATE_BOOLEAN);

//Compute facts about dates and the selected month
if ($month == 0) {
    $start = sprintf('%d-01-01', $year);
    $end = sprintf('%d-12-31', $year);
    $total_days = date("z", mktime(0,0,0,12,31,$year)) + 1;
} else {
    $start = sprintf('%d-%02d-01', $year, $month);
    $lastDay = date("t", strtotime($start));    //last day of selected month
    $end = sprintf('%d-%02d-%02d', $year, $month, $lastDay);
    $total_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
}

$types = $this->types_model->getTypes();
$overtimeTypeName = NULL;
foreach ($types as $type) {
    if ((int) $type['id'] === 0) {
        $overtimeTypeName = $type['name'];
        break;
    }
}
$leave_requests = array();

//Iterate on all employees of the entity
$users = $this->organization_model->allEmployees($entity, $children);
$result = array();
foreach ($users as $user) {
    $result[$user->id]['identifier'] = $user->identifier;
    $result[$user->id]['firstname'] = $user->firstname;
    $result[$user->id]['lastname'] = $user->lastname;
    $date = new DateTime($user->datehired);
    $result[$user->id]['datehired'] = $date->format(lang('global_date_format'));
    $result[$user->id]['department'] = $user->department;
    $result[$user->id]['position'] = $user->position;
    $result[$user->id]['contract'] = $user->contract;
    $daily_hours = $this->leaves_model->getDailyHours($user->id, $user->contract_id);
    $non_working_days = $this->dayoffs_model->lengthDaysOffBetweenDates($user->contract_id, $start, $end);
    $opened_days = $total_days - $non_working_days;
    $opened_hours = round($opened_days * $daily_hours, 3);

    //If the user has selected All months
    if ($month == 0) {
        $leave_duration = 0;
        for ($ii = 1; $ii <13; $ii++) {
            $linear = $this->leaves_model->linear($user->id, $ii, $year, FALSE, FALSE, TRUE, FALSE);
            $leaves_detail = $this->leaves_model->monthlyLeavesByType($linear);
            $absenceDays = $this->leaves_model->monthlyLeavesDuration($linear);
            if (!is_null($overtimeTypeName) && array_key_exists($overtimeTypeName, $leaves_detail)) {
                $absenceDays -= (float) $leaves_detail[$overtimeTypeName];
                if ($absenceDays < 0) {
                    $absenceDays = 0;
                }
            }
            $leave_duration += $this->leaves_model->convertDaysToHours(
                $absenceDays,
                $user->id,
                $user->contract_id
            );
            //Init type columns
            foreach ($types as $type) {
                if (array_key_exists($type['name'], $leaves_detail)) {
                    if (!array_key_exists($type['name'], $result[$user->id])) {
                        $result[$user->id][$type['name']] = 0;
                    }
                    $result[$user->id][$type['name']] +=
                            $this->leaves_model->convertDaysToHours(
                                $leaves_detail[$type['name']],
                                $user->id,
                                $user->contract_id
                            );
                } else {
                    $result[$user->id][$type['name']] = '';
                }
            }
        }
        if ($requests) $leave_requests[$user->id] = $this->leaves_model->getAcceptedLeavesBetweenDates($user->id, $start, $end);
        $work_duration = round($opened_hours - $leave_duration, 3);
    } else {
        $linear = $this->leaves_model->linear($user->id, $month, $year, FALSE, FALSE, TRUE, FALSE);
        $leaves_detail = $this->leaves_model->monthlyLeavesByType($linear);
        $absenceDays = $this->leaves_model->monthlyLeavesDuration($linear);
        if (!is_null($overtimeTypeName) && array_key_exists($overtimeTypeName, $leaves_detail)) {
            $absenceDays -= (float) $leaves_detail[$overtimeTypeName];
            if ($absenceDays < 0) {
                $absenceDays = 0;
            }
        }
        $leave_duration = $this->leaves_model->convertDaysToHours(
            $absenceDays,
            $user->id,
            $user->contract_id
        );
        $work_duration = round($opened_hours - $leave_duration, 3);
        if ($requests) $leave_requests[$user->id] = $this->leaves_model->getAcceptedLeavesBetweenDates($user->id, $start, $end);
        //Init type columns
        foreach ($types as $type) {
            if (array_key_exists($type['name'], $leaves_detail)) {
                $result[$user->id][$type['name']] = $this->leaves_model->convertDaysToHours(
                    $leaves_detail[$type['name']],
                    $user->id,
                    $user->contract_id
                );
            } else {
                $result[$user->id][$type['name']] = '';
            }
        }
    }
    foreach ($types as $type) {
        if ($result[$user->id][$type['name']] !== '') {
            $result[$user->id][$type['name']] = formatLeaveDurationHours($result[$user->id][$type['name']]);
        }
    }
    $result[$user->id]['leave_duration'] = formatLeaveDurationHours($leave_duration);
    $result[$user->id]['work_duration'] = formatLeaveDurationHours($work_duration);
}

$max = 0;
$line = 2;
$i18n = array("identifier", "firstname", "lastname", "datehired", "department", "position", "contract");
$hours_labels = array(
    'leave_duration' => lang('reports_leaves_col_leave_duration'),
    'work_duration' => lang('reports_leaves_col_work_duration')
);
foreach ($result as $user_id => $row) {
    $index = 1;
    foreach ($row as $key => $value) {
        if ($line == 2) {
            $colidx = columnName($index) . '1';
            if (in_array($key, $i18n)) {
                $sheet->setCellValue($colidx, lang($key));
            } else if (array_key_exists($key, $hours_labels)) {
                $sheet->setCellValue($colidx, $hours_labels[$key]);
            } else {
                $sheet->setCellValue($colidx, $key);
            }
            $max++;
        }
        $colidx = columnName($index) . $line;
        $sheet->setCellValue($colidx, $value);
        $index++;
    }
    $line++;
    //Display a nested table listing the leave requests
    if ($requests) {
        if (count($leave_requests[$user_id])) {
            $sheet->setCellValue('A' . $line, lang('leaves_index_thead_start_date'));
            $sheet->setCellValue('B' . $line, lang('leaves_index_thead_end_date'));
            $sheet->setCellValue('C' . $line, lang('leaves_index_thead_type'));
            $sheet->setCellValue('D' . $line, lang('reports_leaves_col_duration_hours'));
            $sheet->getStyle('A' . $line . ':D' . $line)->getFont()->setBold(true);
            $sheet->getStyle('A' . $line . ':D' . $line)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $line++;
            //Iterate on leave requests
            foreach ($leave_requests[$user_id] as $request) {
                $date = new DateTime($request['startdate']);
                $startdate = $date->format(lang('global_date_format'));
                $date = new DateTime($request['enddate']);
                $enddate = $date->format(lang('global_date_format'));
                $sheet->setCellValue('A' . $line, $startdate . ' (' . leaveTimeLabel($request['startdatetype']) . ')');
                $sheet->setCellValue('B' . $line, $enddate . ' (' . leaveTimeLabel($request['enddatetype']) . ')');
                $sheet->setCellValue('C' . $line, $request['type']);
                $sheet->setCellValue('D' . $line, formatLeaveDurationHours($request['duration']));
                $line++;
            }
        } else {
            $sheet->setCellValue('A' . $line, "----");
            $line++;
        }
    }
}

$colidx = columnName($max) . '1';
$sheet->getStyle('A1:' . $colidx)->getFont()->setBold(true);
$sheet->getStyle('A1:' . $colidx)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

//Autofit
for ($ii=1; $ii <$max; $ii++) {
    $col = columnName($ii);
    $sheet->getColumnDimension($col)->setAutoSize(TRUE);
}

writeSpreadsheet($spreadsheet, 'leave_requests_'. $month . '_' . $year);

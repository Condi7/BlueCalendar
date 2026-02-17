<?php
/**
 * This view displays a yearly calendar of the leave taken by a user (can be displayed by HR or manager)
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.4.3
 */

$isCurrentYear = (int)date('Y') === (int)$year;
$currentMonth = (int)date('m');
$currentDay = (int)date('d');
?>

<h2><?php echo lang('calendar_year_title');?>&nbsp;<span class="muted">(<?php echo $employee_name;?>)</span>&nbsp;<?php echo $help;?></h2>

<div class="row-fluid">
    <div class="span4">
        <span class="label"><?php echo lang('Planned');?></span>&nbsp;
        <span class="label label-success"><?php echo lang('Accepted');?></span>&nbsp;
        <span class="label label-warning"><?php echo lang('Requested');?></span>&nbsp;
        <span class="label label-important" style="background-color: #ff0000;"><?php echo lang('Rejected');?></span>
    </div>
    <div class="span4">
        <a href="<?php echo base_url();?>calendar/year/export/<?php echo $employee_id;?>/<?php echo ($year);?>" class="btn btn-primary"><i class="mdi mdi-download"></i>&nbsp;<?php echo lang('calendar_year_button_export');?></a>
    </div>
    <div class="span4">
        <div class="pull-right">
            <a href="<?php echo base_url();?>calendar/year/<?php echo $employee_id;?>/<?php echo (intval($year) - 1);?>" class="btn btn-primary"><i class="mdi mdi-chevron-left"></i>&nbsp;<?php echo (intval($year) - 1);?></a>
            <b><?php echo $year;?></b>
            <a href="<?php echo base_url();?>calendar/year/<?php echo $employee_id;?>/<?php echo (intval($year) + 1);?>" class="btn btn-primary"><?php echo (intval($year) + 1);?>&nbsp;<i class="mdi mdi-chevron-right"></i></a>
        </div>
    </div>
</div>

<div class="row-fluid">

</div>

<div class="row-fluid"><div class="span12">&nbsp;</div></div>

<div class="row-fluid">
    <div class="span12">
<table class="table table-bordered table-condensed">
    <thead>
        <tr>
            <td>&nbsp;</td>
            <?php for ($ii = 1; $ii <=31; $ii++) {
                    echo '<td'.($ii === $currentDay ?' class="currentday-bg"':'').'>' . $ii . '</td>';
                }?>
        </tr>
    </thead>
  <tbody>
  <?php

  $statusToClass = function ($status) {
      switch ((int)$status) {
          case 1: return 'planned';
          case 2: return 'requested';
          case 3: return 'accepted';
          case 4:
          case 5:
          case 6: return 'rejected';
          default: return '';
      }
  };

  $getHalfDayClass = function ($display, $status, $isMorning) use ($statusToClass) {
      $display = (int)$display;
      if ($isMorning) {
          if ($display === 4 || $display === 5) return 'dayoff';
          if ($display === 1 || $display === 2) return $statusToClass($status);
          return '';
      }

      if ($display === 4 || $display === 6) return 'dayoff';
      if ($display === 1 || $display === 3) return $statusToClass($status);
      return '';
  };

  $monthNumber = 0;
  foreach ($months as $month_name => $month) {
      $monthNumber++;
      $isCurrentMonth = $currentMonth === $monthNumber;
  ?>
    <tr>
      <td<?php echo $isCurrentMonth ?' class="currentday-bg"':'';?>><?php echo $month_name; ?></td>
        <?php
        $pad_day = 1;
        foreach ($month->days as $dayNumber => $day) {
            $isCurrentDay = $isCurrentYear && $isCurrentMonth && $currentDay === $dayNumber;
            $currentDayClass = $isCurrentDay ? ' currentday-border' : '';

            $isError = false;
            $morningClass = '';
            $afternoonClass = '';

            if (strstr($day->display, ';')) {
                $periods = explode(';', $day->display);
                $statuses = explode(';', $day->status);
                $morningDisplay = null;
                $morningStatus = null;
                $afternoonDisplay = null;
                $afternoonStatus = null;

                for ($index = 0; $index < count($periods); $index++) {
                    $period = (int)$periods[$index];
                    $status = isset($statuses[$index]) ? $statuses[$index] : 0;

                    if ($period === 9) {
                        $isError = true;
                    }

                    if (($period === 1) || ($period === 2) || ($period === 4) || ($period === 5)) {
                        $morningDisplay = $period;
                        $morningStatus = $status;
                    }
                    if (($period === 1) || ($period === 3) || ($period === 4) || ($period === 6)) {
                        $afternoonDisplay = $period;
                        $afternoonStatus = $status;
                    }
                }

                if ($morningDisplay !== null) {
                    $morningClass = $getHalfDayClass($morningDisplay, $morningStatus, true);
                }
                if ($afternoonDisplay !== null) {
                    $afternoonClass = $getHalfDayClass($afternoonDisplay, $afternoonStatus, false);
                }
            } else {
                $display = (int)$day->display;
                $status = $day->status;

                if ($display === 9) {
                    $isError = true;
                }
                $morningClass = $getHalfDayClass($display, $status, true);
                $afternoonClass = $getHalfDayClass($display, $status, false);
            }

            if ($isError) {
                echo '<td'.($currentDayClass ? ' class="'.trim($currentDayClass).'"' : '').'><img src="'. base_url() .'assets/images/date_error.png"></td>';
                $pad_day++;
                continue;
            }

            $class = '';
            if ($morningClass === '' && $afternoonClass === '') {
                $class = '';
            } elseif ($morningClass === 'dayoff' && $afternoonClass === 'dayoff') {
                $class = 'dayoff';
            } elseif (($morningClass !== '') && ($morningClass === $afternoonClass) && ($morningClass !== 'dayoff')) {
                $class = 'all' . $morningClass;
            } elseif (($morningClass === '') && ($afternoonClass !== '')) {
                $class = 'pm' . $afternoonClass;
            } elseif (($morningClass !== '') && ($afternoonClass === '')) {
                $class = 'am' . $morningClass;
            } elseif (($morningClass === 'dayoff') && ($afternoonClass !== '')) {
                $class = 'dayoff' . $afternoonClass;
            } elseif (($morningClass !== '') && ($afternoonClass === 'dayoff')) {
                $class = $morningClass . 'dayoff';
            } else {
                $class = $morningClass . $afternoonClass;
            }

            $fullClass = trim($class . $currentDayClass);
            echo '<td title="' . $day->type . '"'.($fullClass !== '' ? ' class="' . $fullClass . '"' : '').'>&nbsp;</td>';
            $pad_day++;
        }

        if ($pad_day <= 31) {
            echo '<td colspan="' . (32 - $pad_day) . '">&nbsp;</td>';
        }
        ?>
    </tr>
  <?php } ?>
        <tr>
            <td>&nbsp;</td>
            <?php for ($ii = 1; $ii <=31; $ii++) {
                    echo '<td>' . $ii . '</td>';
                }?>
        </tr>
  </tbody>
</table>

    </div>
</div>

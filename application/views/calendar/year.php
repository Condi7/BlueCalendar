<?php
/**
 * This view displays a yearly calendar of the leave taken by a user (can be displayed by HR or manager)
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 */

$isCurrentYear = (int)date('Y') === (int)$year;
$currentMonth = (int)date('m');
$currentDay = (int)date('d');
$selectedStatuses = (isset($statusesFilter) && $statusesFilter !== '') ? explode('|', $statusesFilter) : array('1', '2', '3', '4', '5', '6');
$statusQuery = (isset($statusesFilter) && $statusesFilter !== '') ? '?statuses=' . urlencode($statusesFilter) : '';
?>

<style type="text/css">
.allrequested { background-color: #f1c40f !important; color: #fff; }
.allcancellation { background-color: #3a87ad !important; color: #fff; }
.allcanceled { background-color: #f89406 !important; color: #fff; }

.currentday-bg { background-color: #3097d1 !important; color: #fff !important; }
.currentday-border { box-shadow: inset 0 0 0 2px #3097d1 !important; }

.dayoff { background-color: #fcf8e3 !important; color: #fff; }
.amdayoff { color: #fff; background: linear-gradient(135deg, #fcf8e3 50%, #fff 50%) !important; }
.pmdayoff { color: #fff; background: linear-gradient(135deg, #fff 50%, #fcf8e3 50%) !important; }

.planneddayoff { color: #fff; background: linear-gradient(135deg, #999 50%, #fcf8e3 50%) !important; }
.dayoffplanned { color: #fff; background: linear-gradient(135deg, #fcf8e3 50%, #999 50%) !important; }

.accepteddayoff { color: #fff; background: linear-gradient(135deg, #468847 50%, #fcf8e3 50%) !important; }
.dayoffaccepted { color: #fff; background: linear-gradient(135deg, #fcf8e3 50%, #468847 50%) !important; }

.rejecteddayoff { color: #fff; background: linear-gradient(135deg, #ff0000 50%, #fcf8e3 50%) !important; }
.dayoffrejected { color: #fff; background: linear-gradient(135deg, #fcf8e3 50%, #ff0000 50%) !important; }

.amrequested { color: #fff; background: linear-gradient(135deg, #f1c40f 50%, #fff 50%) !important; }
.pmrequested { color: #fff; background: linear-gradient(135deg, #fff 50%, #f1c40f 50%) !important; }
.requesteddayoff { color: #fff; background: linear-gradient(135deg, #f1c40f 50%, #fcf8e3 50%) !important; }
.dayoffrequested { color: #fff; background: linear-gradient(135deg, #fcf8e3 50%, #f1c40f 50%) !important; }

.amcancellation { color: #fff; background: linear-gradient(135deg, #3a87ad 50%, #fff 50%) !important; }
.pmcancellation { color: #fff; background: linear-gradient(135deg, #fff 50%, #3a87ad 50%) !important; }
.cancellationdayoff { color: #fff; background: linear-gradient(135deg, #3a87ad 50%, #fcf8e3 50%) !important; }
.dayoffcancellation { color: #fff; background: linear-gradient(135deg, #fcf8e3 50%, #3a87ad 50%) !important; }

.amcanceled { color: #fff; background: linear-gradient(135deg, #f89406 50%, #fff 50%) !important; }
.pmcanceled { color: #fff; background: linear-gradient(135deg, #fff 50%, #f89406 50%) !important; }
.canceleddayoff { color: #fff; background: linear-gradient(135deg, #f89406 50%, #fcf8e3 50%) !important; }
.dayoffcanceled { color: #fff; background: linear-gradient(135deg, #fcf8e3 50%, #f89406 50%) !important; }

.cancellationcanceled { color: #fff; background: linear-gradient(135deg, #3a87ad 50%, #f89406 50%) !important; }
.canceledcancellation { color: #fff; background: linear-gradient(135deg, #f89406 50%, #3a87ad 50%) !important; }
</style>

<h2><?php echo lang('calendar_year_title');?>&nbsp;<span class="muted">(<?php echo $employee_name;?>)</span>&nbsp;<?php echo $help;?></h2>

<div class="row-fluid">
    <div class="span4">
        <span class="label"><input type="checkbox" checked id="chkPlanned" class="filterStatus"> &nbsp;<?php echo lang('Planned');?></span>&nbsp;
        <span class="label label-success"><input type="checkbox" checked id="chkAccepted" class="filterStatus"> &nbsp;<?php echo lang('Accepted');?></span>&nbsp;
        <span class="label" style="background-color: #f1c40f;"><input type="checkbox" checked id="chkRequested" class="filterStatus"> &nbsp;<?php echo lang('Requested');?></span>&nbsp;
        <span class="label" style="background-color: #f89406;"><input type="checkbox" checked id="chkCanceled" class="filterStatus"> &nbsp;<?php echo lang('Canceled');?></span>&nbsp;
        <span class="label" style="background-color: #3a87ad;"><input type="checkbox" checked id="chkCancellation" class="filterStatus"> &nbsp;<?php echo lang('Cancellation');?></span>&nbsp;
        <span class="label label-important" style="background-color: #ff0000;"><input type="checkbox" checked id="chkRejected" class="filterStatus"> &nbsp;<?php echo lang('Rejected');?></span>
    </div>
    <div class="span4">
        <a href="<?php echo base_url();?>calendar/year/export/<?php echo $employee_id;?>/<?php echo ($year);?><?php echo $statusQuery;?>" class="btn btn-primary"><i class="mdi mdi-download"></i>&nbsp;<?php echo lang('calendar_year_button_export');?></a>
    </div>
    <div class="span4">
        <div class="pull-right">
            <a href="<?php echo base_url();?>calendar/year/<?php echo $employee_id;?>/<?php echo (intval($year) - 1);?><?php echo $statusQuery;?>" class="btn btn-primary"><i class="mdi mdi-chevron-left"></i>&nbsp;<?php echo (intval($year) - 1);?></a>
            <b><?php echo $year;?></b>
            <a href="<?php echo base_url();?>calendar/year/<?php echo $employee_id;?>/<?php echo (intval($year) + 1);?><?php echo $statusQuery;?>" class="btn btn-primary"><?php echo (intval($year) + 1);?>&nbsp;<i class="mdi mdi-chevron-right"></i></a>
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
          case 4: return 'rejected';
          case 5: return 'cancellation';
          case 6: return 'canceled';
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

<script type="text/javascript">
$(function () {
    var selectedStatuses = <?php echo json_encode($selectedStatuses); ?>;
    $('#chkPlanned').prop('checked', selectedStatuses.indexOf('1') !== -1);
    $('#chkRequested').prop('checked', selectedStatuses.indexOf('2') !== -1);
    $('#chkAccepted').prop('checked', selectedStatuses.indexOf('3') !== -1);
    $('#chkRejected').prop('checked', selectedStatuses.indexOf('4') !== -1);
    $('#chkCancellation').prop('checked', selectedStatuses.indexOf('5') !== -1);
    $('#chkCanceled').prop('checked', selectedStatuses.indexOf('6') !== -1);

    function buildStatusesFilter() {
        var statuses = [];
        if ($('#chkPlanned').prop('checked')) statuses.push('1');
        if ($('#chkRequested').prop('checked')) statuses.push('2');
        if ($('#chkAccepted').prop('checked')) statuses.push('3');
        if ($('#chkRejected').prop('checked')) statuses.push('4');
        if ($('#chkCancellation').prop('checked')) statuses.push('5');
        if ($('#chkCanceled').prop('checked')) statuses.push('6');
        return statuses.join('|');
    }

    $('.filterStatus').on('change', function () {
        var statuses = buildStatusesFilter();
        var targetUrl = '<?php echo base_url();?>calendar/year/<?php echo $employee_id;?>/<?php echo $year;?>';
        if (statuses !== '') {
            targetUrl += '?statuses=' + encodeURIComponent(statuses);
        }
        window.location.href = targetUrl;
    });
});
</script>

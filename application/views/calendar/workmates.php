<?php
/**
 * This view displays the leave requests of the workmates of the connected user (employees having the same line manager).
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 */
?>

<div class="row-fluid">
    <div class="span12">

<h2><?php echo lang('calendar_workmates_title');?> &nbsp;<?php echo $help;?></h2>

<p><?php echo lang('calendar_workmates_description');?></p>

<div class="row-fluid">
    <div class="span3"><span class="label"><?php echo lang('Planned');?></span></div>
    <div class="span3"><span class="label label-success"><?php echo lang('Accepted');?></span></div>
    <div class="span3"><span class="label label-warning"><?php echo lang('Requested');?></span></div>
    <div class="span3">&nbsp;</div>
</div>

<div id='calendar'></div>

    </div>
</div>

<div class="modal hide" id="frmModalAjaxWait" data-backdrop="static" data-keyboard="false">
        <div class="modal-header">
            <h1><?php echo lang('global_msg_wait');?></h1>
        </div>
        <div class="modal-body">
            <img src="<?php echo base_url();?>assets/images/loading.gif"  align="middle">
        </div>
 </div>

<link href="<?php echo base_url();?>assets/fullcalendar-2.8.0/fullcalendar.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo base_url();?>assets/fullcalendar-2.8.0/fullcalendar.min.js"></script>
<?php if ($language_code != 'en') {?>
<script type="text/javascript" src="<?php echo base_url();?>assets/fullcalendar-2.8.0/lang/<?php echo strtolower($language_code);?>.js"></script>
<?php }?>
<script src="<?php echo base_url();?>assets/js/bootbox.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    
    //Global Ajax error handling mainly used for session expiration
    $( document ).ajaxError(function(event, jqXHR, settings, errorThrown) {
        $('#frmModalAjaxWait').modal('hide');
        if (jqXHR.status == 401) {
            bootbox.alert("<?php echo lang('global_ajax_timeout');?>", function() {
                //After the login page, we'll be redirected to the current page 
               location.reload();
            });
        } else { //Oups
            bootbox.alert("<?php echo lang('global_ajax_error');?>");
        }
      });
    
    //Create a calendar and fill it with AJAX events
    $('#calendar').fullCalendar({
        timeFormat: ' ', /*Trick to remove the start time of the event*/
        header: {
            left: "prev,next today",
            center: "title",
            right: ""
        },
        events: '<?php echo base_url();?>leaves/workmates',
        loading: function(isLoading) {
            if (isLoading) { //Display/Hide a pop-up showing an animated icon during the Ajax query.
                $('#frmModalAjaxWait').modal('show');
            } else {
                $('#frmModalAjaxWait').modal('hide');
            }    
        },
        eventRender: function(event, element, view) {
            if(event.imageurl){
                $(element).find('span:first').prepend('<img src="' + event.imageurl + '" />');
            }
        },
        eventAfterRender: function(event, element, view) {
            //Add tooltip to the element
            $(element).attr('title', event.title);

            if (typeof event.startdatetype === 'undefined' || typeof event.enddatetype === 'undefined') {
                return;
            }

            if (view.name !== 'month' && view.name.indexOf('basic') !== 0) {
                return;
            }

            var width = parseInt(jQuery(element).css('width'), 10);
            if (isNaN(width) || width <= 0 || !event.start || !event.end) {
                return;
            }

            var toMinutes = function(value, isStart) {
                if (value === 'Morning') return isStart ? 9 * 60 : 13 * 60;
                if (value === 'Afternoon') return isStart ? 14 * 60 : 18 * 60;
                var match = /^([01][0-9]|2[0-3]):([0-5][0-9])$/.exec(String(value));
                if (match) return parseInt(match[1], 10) * 60 + parseInt(match[2], 10);
                return isStart ? 9 * 60 : 18 * 60;
            };

            var workedBefore = function(minutes) {
                var morning = Math.max(0, Math.min(minutes, 13 * 60) - 9 * 60);
                var afternoon = Math.max(0, Math.min(minutes, 18 * 60) - 14 * 60);
                return morning + afternoon;
            };

            var startMinutes = toMinutes(event.startdatetype, true);
            var endMinutes = toMinutes(event.enddatetype, false);
            var startFraction = workedBefore(startMinutes) / 480;
            var endFraction = workedBefore(endMinutes) / 480;

            var startDay = event.start.clone().startOf('day');
            var endDay = event.end.clone().startOf('day');
            var dayCount = endDay.diff(startDay, 'days') + 1;
            if (dayCount < 1) dayCount = 1;

            var dayWidth = width / dayCount;
            var marginLeft = dayWidth * startFraction;
            var marginRight = dayWidth * (1 - endFraction);
            var newWidth = width - marginLeft - marginRight;
            if (newWidth < 1) newWidth = 1;

            $(element).css('width', Math.round(newWidth) + 'px');
            $(element).css('margin-left', Math.round(marginLeft) + 'px');
        },
        windowResize: function(view) {
            $('#calendar').fullCalendar( 'rerenderEvents' );
        }
    });
});
</script>


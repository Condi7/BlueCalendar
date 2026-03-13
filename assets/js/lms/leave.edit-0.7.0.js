/**
 * This Javascript code is used on the create/edit leave request
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 */

function padTimePart(value) {
    var normalized = String(value);
    return normalized.length === 1 ? ('0' + normalized) : normalized;
}

function getTimeFromParts(prefix) {
    var hourSelector = '#' + prefix + '_hour';
    var minuteSelector = '#' + prefix + '_minute';
    if ($(hourSelector).length && $(minuteSelector).length) {
        return padTimePart($(hourSelector).val()) + ':' + padTimePart($(minuteSelector).val());
    }
    return $('#' + prefix + 'datetype').val();
}

function setTimeToParts(prefix, value) {
    var parts = String(value).split(':');
    if (parts.length !== 2) {
        return;
    }
    var hour = padTimePart(parts[0]);
    var minute = padTimePart(parts[1]);
    var hourSelector = '#' + prefix + '_hour';
    var minuteSelector = '#' + prefix + '_minute';
    if ($(hourSelector).length && $(minuteSelector).length) {
        $(hourSelector).val(hour);
        $(minuteSelector).val(minute);
    }
    $('#' + prefix + 'datetype').val(hour + ':' + minute);
}

function syncHiddenTimeFields() {
    if ($('#startdatetype').length) {
        $('#startdatetype').val(getTimeFromParts('start'));
    }
    if ($('#enddatetype').length) {
        $('#enddatetype').val(getTimeFromParts('end'));
    }
}

function updateMinuteOptions(prefix) {
    var hourSelector = '#' + prefix + '_hour';
    var minuteSelector = '#' + prefix + '_minute';
    if (!$(hourSelector).length || !$(minuteSelector).length) {
        return;
    }

    var selectedHour = padTimePart($(hourSelector).val());
    var selectedMinute = padTimePart($(minuteSelector).val());
    var fallbackMinute = '00';

    $(minuteSelector).find('option').prop('disabled', false);
    if (selectedHour === '18') {
        $(minuteSelector).find('option[value="15"]').prop('disabled', true);
        $(minuteSelector).find('option[value="30"]').prop('disabled', true);
        $(minuteSelector).find('option[value="45"]').prop('disabled', true);
        fallbackMinute = '00';
    }

    if ($(minuteSelector).find('option[value="' + selectedMinute + '"]').prop('disabled')) {
        $(minuteSelector).val(fallbackMinute);
    }
}

//Try to calculate the length of the leave
function getLeaveLength(refreshInfos) {
    refreshInfos = typeof refreshInfos !== 'undefined' ? refreshInfos : true;
    var start = moment($('#startdate').val());
    var end = moment($('#enddate').val());
    var startType = getTimeFromParts('start');
    var endType = getTimeFromParts('end');

    syncHiddenTimeFields();

    if (start.isValid() && end.isValid() && startType !== '' && endType !== '') {
        $("#spnDayType").text('');
        if (refreshInfos) getLeaveInfos(false);
    }
}

function isQuarterMinute(value) {
    var parts = value.split(':');
    if (parts.length !== 2) {
        return false;
    }
    return ['00', '15', '30', '45'].indexOf(parts[1]) !== -1;
}

function isInAllowedTimeRange(value) {
    if (!/^([01][0-9]|2[0-3]):[0-5][0-9]$/.test(value)) {
        return false;
    }
    if (!isQuarterMinute(value)) {
        return false;
    }
    return value >= '09:00' && value <= '18:00';
}

function enforceTimeConstraints(selector, fallbackValue) {
    var fieldName = selector === '#startdatetype' ? 'start' : 'end';
    updateMinuteOptions(fieldName);
    var value = getTimeFromParts(fieldName);
    if (!isInAllowedTimeRange(value)) {
        value = fallbackValue;
    }
    if (value < '09:00') {
        value = '09:00';
    }
    if (value > '18:00') {
        value = '18:00';
    }
    if (value.substring(0, 2) === '18' && value.substring(3, 5) !== '00') {
        value = '18:00';
    }
    setTimeToParts(fieldName, value);
    syncHiddenTimeFields();
}

function bindStrictTimeInput(selector, fallbackValue) {
    $(selector).on('input blur', function() {
        enforceTimeConstraints(selector, fallbackValue);
    });
}

function isIncludeBreakChecked() {
    return $('#include_break').length && $('#include_break').is(':checked');
}

function isIncludeBreakEligible() {
    var startType = getTimeFromParts('start');
    var endType = getTimeFromParts('end');
    if (!startType || !endType) {
        return false;
    }
    var startInRange = (startType >= '09:00' && startType <= '13:00');
    var endInRange = (endType >= '13:30' && endType <= '18:00');
    return startInRange && endInRange;
}

function updateIncludeBreakAvailability() {
    if (!$('#include_break').length) {
        return;
    }
    var isFullDay = $('#full_day').length && $('#full_day').is(':checked');
    var eligible = !isFullDay && isIncludeBreakEligible();
    $('#include_break').prop('disabled', !eligible);
    if (!eligible) {
        $('#include_break').prop('checked', false);
    }
}

function updateFullDayUI() {
    if (!$('#full_day').length) {
        return;
    }
    var isFullDay = $('#full_day').is(':checked');
    if (isFullDay) {
        $('#startTimeWrapper').hide();
        $('#endTimeWrapper').hide();
        $('#includeBreakWrapper').hide();
        if ($('#include_break').length) {
            $('#include_break').prop('checked', false);
        }
        setTimeToParts('start', '09:00');
        setTimeToParts('end', '18:00');
    } else {
        $('#startTimeWrapper').show();
        $('#endTimeWrapper').show();
        $('#includeBreakWrapper').show();
        enforceTimeConstraints('#startdatetype', '09:00');
        enforceTimeConstraints('#enddatetype', '18:00');
    }
    syncHiddenTimeFields();
    updateIncludeBreakAvailability();
}

//Get the leave credit, duration and detect overlapping cases (Ajax request)
//Default behavour is to set the duration field. pass false if you want to disable this behaviour
function getLeaveInfos(preventDefault) {
        $('#frmModalAjaxWait').modal('show');
        var start = moment($('#startdate').val());
        var end = moment($('#enddate').val());
        $.ajax({
        type: "POST",
        url: baseURL + "leaves/validate",
        data: {   id: userId,
                    type: $("#type option:selected").text(),
                    startdate: $('#startdate').val(),
                    enddate: $('#enddate').val(),
                    startdatetype: getTimeFromParts('start'),
                    enddatetype: getTimeFromParts('end'),
                    full_day: $('#full_day').length ? ($('#full_day').is(':checked') ? '1' : '0') : '0',
                    leave_id: leaveId
                }
        })
        .done(function(leaveInfo) {
            if (typeof leaveInfo.length !== 'undefined') {
                var duration = parseFloat(leaveInfo.length);
                duration = Math.round(duration * 1000) / 1000;  //Round to 3 decimals only if necessary
                if (!preventDefault) {
                    if (start.isValid() && end.isValid()) {
                        $('#duration').val(duration);
                    }
                }
            }
            if (typeof leaveInfo.credit !== 'undefined') {
                var credit = parseFloat(leaveInfo.credit);
                var duration = parseFloat($("#duration").val());
                if (duration > credit) {
                    $("#lblCreditAlert").show();
                } else {
                    $("#lblCreditAlert").hide();
                }
                if (leaveInfo.credit != null) {
                    $("#lblCredit").text('(' + leaveInfo.credit + ')');
                }
            }
            //Check if the current request overlaps with another one
            showOverlappingMessage(leaveInfo);
            //Or overlaps with a non-working day
            showOverlappingDayOffMessage(leaveInfo);
            //Check if the employee has a contract
            if (leaveInfo.hasContract == false) {
                bootbox.alert(noContractMsg);
            } else {
                //If the employee has a contract, check if the current leave request is not on two yearly leave periods
                var periodStartDate = moment(leaveInfo.PeriodStartDate);
                var periodEndDate = moment(leaveInfo.PeriodEndDate);
                if (start.isValid() && end.isValid() && periodEndDate.isValid()) {
                    if (start.isBefore(periodEndDate) && periodEndDate.isBefore(end)) {
                        bootbox.alert(noTwoPeriodsMsg);
                    }
                    if (start.isBefore(periodStartDate)) {
                        bootbox.alert(noTwoPeriodsMsg);
                    }
                }
            }
            showListDayOff(leaveInfo);
            $('#frmModalAjaxWait').modal('hide');
        });
}

//When editing/viewing a leave request, refresh the information about overlapping and days off in the period
function refreshLeaveInfo() {
        $('#frmModalAjaxWait').modal('show');
        var start = moment($('#startdate').val());
        var end = moment($('#enddate').val());
        $.ajax({
        type: "POST",
        url: baseURL + "leaves/validate",
        data: {   id: userId,
                    type: $("#type option:selected").text(),
                    startdate: $('#startdate').val(),
                    enddate: $('#enddate').val(),
                    startdatetype: getTimeFromParts('start'),
                    enddatetype: getTimeFromParts('end'),
                    full_day: $('#full_day').length ? ($('#full_day').is(':checked') ? '1' : '0') : '0',
                    leave_id: leaveId
                }
        })
        .done(function(leaveInfo) {
            showOverlappingMessage(leaveInfo);
            showOverlappingDayOffMessage(leaveInfo);
            showListDayOff(leaveInfo);
            $('#frmModalAjaxWait').modal('hide');
        });
}

//Display the list of non-working days occuring between the leave request start and end dates
function showListDayOff(leaveInfo) {
    if (typeof leaveInfo.listDaysOff !== 'undefined') {
        var arrayLength = leaveInfo.listDaysOff.length;
        if (arrayLength>0) {
            var htmlTable = "<a href='#divDaysOff' data-toggle='collapse'  class='btn btn-primary input-block-level'>";
            htmlTable += listOfDaysOffTitle.replace("%s", leaveInfo.lengthDaysOff);
            htmlTable += "&nbsp;<i class='icon-chevron-down icon-white'></i></a>\n";
            htmlTable += "<div id='divDaysOff' class='collapse'>";
            htmlTable += "<table class='table table-bordered table-hover table-condensed'>\n";
            htmlTable += "<tbody>";
            for (var i = 0; i < arrayLength; i++) {
                htmlTable += "<tr><td>";
                htmlTable += moment(leaveInfo.listDaysOff[i].date, 'YYYY-MM-DD').format(dateMomentJsFormat);
                htmlTable += " / <b>" + leaveInfo.listDaysOff[i].title + "</b></td>";
                htmlTable += "<td>" + leaveInfo.listDaysOff[i].length + "</td>";
                htmlTable += "</tr>\n";
            }
            htmlTable += "</tbody></table></div>";
            $("#spnDaysOffList").html(htmlTable);
        } else {
            //NOP
        }
    }
}

function showListDayOffHTML(){
  $('#frmModalAjaxWait').modal('show');
  var start = moment($('#startdate').val());
  var end = moment($('#enddate').val());
  $.ajax({
  type: "POST",
  url: baseURL + "leaves/validate",
  data: {   id: userId,
              type: $("#type option:selected").text(),
              startdate: $('#startdate').val(),
              enddate: $('#enddate').val(),
              startdatetype: getTimeFromParts('start'),
              enddatetype: getTimeFromParts('end'),
              full_day: $('#full_day').length ? ($('#full_day').is(':checked') ? '1' : '0') : '0',
              leave_id: leaveId
          }
  })
  .done(function(leaveInfo) {
      $('#frmModalAjaxWait').modal('hide');
      if (typeof leaveInfo.listDaysOff !== 'undefined') {
          var arrayLength = leaveInfo.listDaysOff.length;
          if (arrayLength>0) {
              var htmlTable = "<div id='divDaysOff2'>";
              htmlTable += "<table class='table table-bordered table-hover table-condensed'>\n";
              htmlTable += "<thead class='thead-inverse'>";
              htmlTable += "<tr><th>";
              htmlTable += listOfDaysOffTitle.replace("%s", leaveInfo.lengthDaysOff);
              htmlTable += "</th></tr></thead>";
              htmlTable += "<tbody>";
              for (var i = 0; i < arrayLength; i++) {
                  htmlTable += "<tr><td>";
                  htmlTable += moment(leaveInfo.listDaysOff[i].date, 'YYYY-MM-DD').format(dateMomentJsFormat);
                  htmlTable += " / <b>" + leaveInfo.listDaysOff[i].title + "</b></td>";
                  htmlTable += "<td>" + leaveInfo.listDaysOff[i].length + "</td>";
                  htmlTable += "</tr>\n";
              }
              htmlTable += "</tbody></table></div>";
              bootbox.alert(htmlTable, function() {
                console.log("Alert Callback");
              });
          } else {
              //NOP
          }
      }
  });
}

//Display the list of non-working days occuring between the leave request start and end dates
function showOverlappingMessage(leaveInfo) {
    if (typeof leaveInfo.overlap !== 'undefined') {
        if (Boolean(leaveInfo.overlap)) {
            $("#lblOverlappingAlert").show();
        } else {
            $("#lblOverlappingAlert").hide();
        }
    }
}

//Check if the leave request overlaps with a non-working day
function showOverlappingDayOffMessage(leaveInfo) {
    if (typeof leaveInfo.overlapDayOff !== 'undefined') {
        if (Boolean(leaveInfo.overlapDayOff)) {
            $("#lblOverlappingDayOffAlert").show();
        } else {
            $("#lblOverlappingDayOffAlert").hide();
        }
    }
}

$(function () {
    $('#spnDayType').hide().text('');
    syncHiddenTimeFields();
    updateMinuteOptions('start');
    updateMinuteOptions('end');
    getLeaveLength(false);
    updateFullDayUI();
    enforceTimeConstraints('#startdatetype', '09:00');
    enforceTimeConstraints('#enddatetype', '18:00');
    updateIncludeBreakAvailability();
    bindStrictTimeInput('#startdatetype', '09:00');
    bindStrictTimeInput('#enddatetype', '18:00');

    //Init the start and end date picker and link them (end>=date)
    $("#viz_startdate").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: dateJsFormat,
        altFormat: "yy-mm-dd",
        altField: "#startdate",
        numberOfMonths: 1,
              onClose: function( selectedDate ) {
                $( "#viz_enddate" ).datepicker( "option", "minDate", selectedDate );
              }
    }, $.datepicker.regional[languageCode]);
    $("#viz_enddate").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: dateJsFormat,
        altFormat: "yy-mm-dd",
        altField: "#enddate",
        numberOfMonths: 1,
              onClose: function( selectedDate ) {
                $( "#viz_startdate" ).datepicker( "option", "maxDate", selectedDate );
              }
    }, $.datepicker.regional[languageCode]);

    //Force decimal separator whatever the locale is
    $( "#days" ).keyup(function() {
        var value = $("#days").val();
        value = value.replace(",", ".");
        $("#days").val(value);
    });

    $('#viz_startdate').change(function() {getLeaveLength(true);});
    $('#viz_enddate').change(function() {getLeaveLength();});
    $('#startdatetype, #start_hour, #start_minute').change(function() {
        updateMinuteOptions('start');
        enforceTimeConstraints('#startdatetype', '09:00');
        updateIncludeBreakAvailability();
        getLeaveLength();
    });
    $('#enddatetype, #end_hour, #end_minute').change(function() {
        updateMinuteOptions('end');
        enforceTimeConstraints('#enddatetype', '18:00');
        updateIncludeBreakAvailability();
        getLeaveLength();
    });
    $('#full_day').change(function() {
        updateFullDayUI();
        getLeaveLength();
    });
    $('#include_break').change(function() {
        updateIncludeBreakAvailability();
        getLeaveLength();
    });
    $('#type').change(function() {getLeaveInfos(false);});

    //Check if the user has not exceed the number of entitled days
    $("#duration").keyup(function() {getLeaveInfos(true);});

    $("#frmLeaveForm").submit(function(e) {
        enforceTimeConstraints('#startdatetype', '09:00');
        enforceTimeConstraints('#enddatetype', '18:00');
        updateFullDayUI();
        syncHiddenTimeFields();
        if (validate_form()) {
            return true;
        } else {
            e.preventDefault();
            return false;
        }
    });
});

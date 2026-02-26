<?php
//This is a sample page showing how to create a custom report
//We can get access to all the framework, so you can do anything with the instance of the current controller ($this)

//You can load a language file so as to translate the report if the strings are available
//It can be useful for date formating
$this->lang->load('requests', $this->language);
$this->lang->load('global', $this->language);
?>

<h2>Toutes les demandes de congé</h2>

<?php
//$this is the instance of the current controller, so you can use it for direct access to the database
$this->db->select('users.firstname, users.lastname, leaves.*');
$this->db->select('status.name as status_name, types.name as type_name');
$this->db->from('leaves');
$this->db->join('status', 'leaves.status = status.id');
$this->db->join('types', 'leaves.type = types.id');
$this->db->join('users', 'leaves.employee = users.id');
$this->db->order_by('users.lastname, users.firstname, leaves.startdate', 'desc');
$rows = $this->db->get()->result_array();
?>

<div class="row">
    <div class="span4">
        <label for="txtEmployeeSearch"><?php echo lang('requests_index_thead_fullname');?></label>
        <input type="text" id="txtEmployeeSearch" class="input-xlarge" />
    </div>
    <div class="span3">
        <label for="dtFrom"><?php echo lang('requests_index_thead_startdate');?></label>
        <input type="date" id="dtFrom" class="input-medium" />
    </div>
    <div class="span3">
        <label for="dtTo"><?php echo lang('requests_index_thead_enddate');?></label>
        <input type="date" id="dtTo" class="input-medium" />
    </div>
</div>

<table cellpadding="0" cellspacing="0" border="0" class="display" id="leaves" width="100%">
    <thead>
        <tr>
            <th><?php echo lang('requests_index_thead_id');?></th>
            <th><?php echo lang('requests_index_thead_fullname');?></th>
            <th><?php echo lang('requests_index_thead_startdate');?></th>
            <th><?php echo lang('requests_index_thead_enddate');?></th>
            <th><?php echo lang('requests_index_thead_duration');?></th>
            <th><?php echo lang('requests_index_thead_type');?></th>
            <th><?php echo lang('requests_index_thead_status');?></th>
        </tr>
    </thead>
    <tbody>
<?php foreach ($rows as $row):
    $date = new DateTime($row['startdate']);
    $tmpStartDate = $date->getTimestamp();
    $date = new DateTime($row['enddate']);
    $tmpEndDate = $date->getTimestamp();
    $employeeName = $row['firstname'] . ' ' . $row['lastname'];
?>
    <tr data-employee="<?php echo strtolower($employeeName); ?>" data-start="<?php echo $tmpStartDate; ?>" data-end="<?php echo $tmpEndDate; ?>">
        <td data-order="<?php echo $row['id']; ?>">
            <a href="<?php echo base_url();?>leaves/requests/<?php echo $row['id']; ?>" title="<?php echo lang('requests_index_thead_tip_view');?>"><?php echo $row['id']; ?></a>
        </td>
        <td><?php echo $employeeName; ?></td>
        <td data-order="<?php echo $tmpStartDate; ?>"><?php echo formatLeaveDateTime($row['startdate'], $row['startdatetype'], lang('global_date_format')); ?></td>
        <td data-order="<?php echo $tmpEndDate; ?>"><?php echo formatLeaveDateTime($row['enddate'], $row['enddatetype'], lang('global_date_format')); ?></td>
        <td><?php echo formatLeaveDurationHours($row['duration']); ?></td>
        <td><?php echo $row['type_name']; ?></td>
        <?php
        switch ($row['status']) {
            case 1: echo "<td><span class='label'>" . lang($row['status_name']) . "</span></td>"; break;
            case 2: echo "<td><span class='label label-warning'>" . lang($row['status_name']) . "</span></td>"; break;
            case 3: echo "<td><span class='label label-success'>" . lang($row['status_name']) . "</span></td>"; break;
            default: echo "<td><span class='label label-important' style='background-color: #ff0000;'>" . lang($row['status_name']) . "</span></td>"; break;
        }?>
    </tr>
<?php endforeach ?>
    </tbody>
</table>

<div class="row-fluid"><div class="span12">&nbsp;</div></div>

<a href="<?php echo base_url() . 'excel-export-all-leave-requests'; ?>" class="btn btn-primary"><i class="mdi mdi-download"></i>&nbsp; <?php echo lang('requests_index_button_export');?></a>

<div class="row-fluid"><div class="span12">&nbsp;</div></div>

<link href="<?php echo base_url();?>assets/datatable/DataTables-1.10.11/css/jquery.dataTables.min.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo base_url();?>assets/datatable/DataTables-1.10.11/js/jquery.dataTables.min.js"></script>

<script type="text/javascript">
var leaveTable = null;

function parseDateToTimestamp(dateString, endOfDay) {
    if (!dateString) {
        return null;
    }
    var suffix = endOfDay ? 'T23:59:59' : 'T00:00:00';
    return Math.floor(new Date(dateString + suffix).getTime() / 1000);
}

$.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
    if (settings.nTable.id !== 'leaves') {
        return true;
    }

    var api = new $.fn.dataTable.Api(settings);
    var rowNode = api.row(dataIndex).node();
    var employee = String($(rowNode).data('employee') || '');
    var startTs = parseInt($(rowNode).data('start'), 10);
    var endTs = parseInt($(rowNode).data('end'), 10);

    var employeeSearch = $.trim($('#txtEmployeeSearch').val()).toLowerCase();
    var fromTs = parseDateToTimestamp($('#dtFrom').val(), false);
    var toTs = parseDateToTimestamp($('#dtTo').val(), true);

    if (employeeSearch !== '' && employee.indexOf(employeeSearch) === -1) {
        return false;
    }

    if (fromTs !== null && endTs < fromTs) {
        return false;
    }

    if (toTs !== null && startTs > toTs) {
        return false;
    }

    return true;
});

$(document).ready(function() {
    leaveTable = $('#leaves').DataTable({
        order: [[ 2, "desc" ]],
        dom: 'lrtip',
        language: {
            decimal:            "<?php echo lang('datatable_sInfoThousands');?>",
            processing:       "<?php echo lang('datatable_sProcessing');?>",
            search:              "<?php echo lang('datatable_sSearch');?>",
            lengthMenu:     "<?php echo lang('datatable_sLengthMenu');?>",
            info:                   "<?php echo lang('datatable_sInfo');?>",
            infoEmpty:          "<?php echo lang('datatable_sInfoEmpty');?>",
            infoFiltered:       "<?php echo lang('datatable_sInfoFiltered');?>",
            infoPostFix:        "<?php echo lang('datatable_sInfoPostFix');?>",
            loadingRecords: "<?php echo lang('datatable_sLoadingRecords');?>",
            zeroRecords:    "<?php echo lang('datatable_sZeroRecords');?>",
            emptyTable:     "<?php echo lang('datatable_sEmptyTable');?>",
            paginate: {
                first:          "<?php echo lang('datatable_sFirst');?>",
                previous:   "<?php echo lang('datatable_sPrevious');?>",
                next:           "<?php echo lang('datatable_sNext');?>",
                last:           "<?php echo lang('datatable_sLast');?>"
            },
            aria: {
                sortAscending:  "<?php echo lang('datatable_sSortAscending');?>",
                sortDescending: "<?php echo lang('datatable_sSortDescending');?>"
            }
        }
    });

    $('#txtEmployeeSearch').on('keyup change', function() {
        leaveTable.draw();
    });

    $('#dtFrom, #dtTo').on('change', function() {
        leaveTable.draw();
    });
});
</script>

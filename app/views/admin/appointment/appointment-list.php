<?php
if ($this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) == 'V') {
    include VIEWPATH . 'vendor/header.php';
    $folder_name = 'vendor';
} else {
    include VIEWPATH . 'admin/header.php';
    $folder_name = 'admin';
}
//Get current set category
$get_current_currency = get_current_currency();
?>
<input id="folder_name" name="folder_name" type="hidden" value="<?php echo isset($folder_name) && $folder_name != '' ? $folder_name : ''; ?>"/>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6 col-xl-6">
                            <h4 class="card-title"><?php echo $title; ?></h4>
                            <div class="page-title-box pb-0 d-sm-flex">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>"><?php echo translate('dashboard'); ?></a></li>
                                        <li class="breadcrumb-item active"><?php echo $title; ?></li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 m-auto">
                            <?php $this->load->view('message'); ?>

                            <div class="mb-5">
                                <form class="form" role="form" method="GET" id="appointment_filter" action="<?php echo base_url($folder_name . '/manage-appointment') ?>">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <select name="event" id="event" class="form-control" onchange="change_filter()" style="display: block !important">
                                                    <option value=""><?php echo translate('service') ?></option>
                                                    <?php foreach ($appointment_event as $val): ?>
                                                        <option <?php echo (isset($_REQUEST['event']) && $_REQUEST['event'] == $val['event_id']) ? "selected='selected'" : ""; ?> value="<?php echo $val['event_id'] ?>"><?php echo $val['title'] ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>

                                        <?php if (($this->session->userdata('Type_' . ucfirst($this->uri->segment(1)))) && $this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) != 'V') { ?>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <select name="vendor" id="vendor" class="form-control" onchange="change_filter()" style="display: block !important">
                                                        <option value=""><?php echo translate('vendor') ?></option>

                                                        <?php foreach ($appointment_vendor as $val): ?>
                                                            <option <?php echo (isset($_REQUEST['vendor']) && $_REQUEST['vendor'] == $val['id']) ? "selected='selected'" : ""; ?> value="<?php echo $val['id'] ?>"><?php echo ($val['company_name']); ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <select name="status" id="status" class="form-control" onchange="change_filter()" style="display: block !important">
                                                    <option value=""><?php echo translate('status') ?></option>
                                                    <option value="A" <?php echo (isset($_REQUEST['status']) && $_REQUEST['status'] == 'A') ? "selected='selected'" : ""; ?>><?php echo translate('approved') ?></option>
                                                    <option value="P" <?php echo (isset($_REQUEST['status']) && $_REQUEST['status'] == 'P') ? "selected='selected'" : ""; ?>><?php echo translate('pending') ?></option>
                                                    <option value="R" <?php echo (isset($_REQUEST['status']) && $_REQUEST['status'] == 'R') ? "selected='selected'" : ""; ?>><?php echo translate('Rejected') ?></option>
                                                    <option value="C" <?php echo (isset($_REQUEST['status']) && $_REQUEST['status'] == 'C') ? "selected='selected'" : ""; ?>><?php echo translate('completed') ?></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <select name="appointment_type" id="appointment_type" class="form-control" onchange="change_filter()" style="display: block !important">
                                                    <option value="U" <?php echo (isset($_REQUEST['appointment_type']) && $_REQUEST['appointment_type'] == 'U') ? "selected='selected'" : ""; ?>><?php echo translate('upcoming') . " " . translate('appointment'); ?></option>
                                                    <option value="P" <?php echo (isset($_REQUEST['appointment_type']) && $_REQUEST['appointment_type'] == 'P') ? "selected='selected'" : ""; ?>><?php echo translate('past') . " " . translate('appointment'); ?></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <select name="type" id="type" class="form-control" onchange="change_filter()" style="display: block !important">
                                                    <option value=""><?php echo translate('type') ?></option>
                                                    <option value="F" <?php echo (isset($_REQUEST['type']) && $_REQUEST['type'] == 'F') ? "selected='selected'" : ""; ?>><?php echo translate('free') ?></option>
                                                    <option value="P" <?php echo (isset($_REQUEST['type']) && $_REQUEST['type'] == 'P') ? "selected='selected'" : ""; ?>><?php echo translate('paid') ?></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <a class="btn btn-info btn-sm" href="<?php echo base_url($folder_name . '/manage-appointment') ?>"><i class="fa fa-refresh"></i></a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered dt-responsive nowrap w-100" id="datatable">
                                    <thead>
                                    <tr>
                                        <th class="text-center font-bold dark-grey-text">#</th>
                                        <th class="text-center font-bold dark-grey-text"><?php echo translate('service'); ?></th>
                                        <th class="text-center font-bold dark-grey-text"><?php echo translate('appointment_date'); ?></th>
                                        <th class="text-center font-bold dark-grey-text"><?php echo translate('customer_name'); ?></th>
                                        <th class="text-center font-bold dark-grey-text"><?php echo translate('staff'); ?></th>
                                        <th class="text-center font-bold dark-grey-text"><?php echo translate('status'); ?></th>
                                        <th class="text-center font-bold dark-grey-text"><?php echo translate('amount'); ?></th>
                                        <th class="text-center font-bold dark-grey-text"><?php echo translate('payment'); ?></th>
                                        <th class="text-center font-bold dark-grey-text"><?php echo translate('action'); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if (isset($appointment_data) && count($appointment_data) > 0) {
                                        foreach ($appointment_data as $key => $row) {
                                            $staff_member = "-";
                                            if ($row['staff_id'] != 0) {
                                                $staff_data = get_staff_by_id($row['staff_id'])[0];
                                                $staff_member = $staff_data['first_name'] . " " . $staff_data['last_name'];
                                            }
                                            $newTime = date("Y-m-d H:i:s", strtotime("+" . $row['slot_time'] . " minutes", strtotime($row['start_date'] . " " . $row['start_time'])));
                                            ?>
                                            <tr>
                                                <td class="text-center"><?php echo $key + 1; ?></td>
                                                <td class="text-left"><?php echo ($row['title']); ?><br/>
                                                    <span class="badge badge-success"><?php echo $row['company_name']; ?></span>
                                                </td>
                                                <td class="text-center"><?php echo get_formated_date($row['start_date'] . " " . $row['start_time']); ?></td>
                                                <td class="text-center"><?php echo ($row['first_name']) . " " . ($row['last_name']); ?></td>
                                                <td class="text-center"><?php echo $staff_member; ?></td>
                                                <td class="text-center"><?php echo check_appointment_status($row['status']); ?></td>
                                                <?php if ($row['payment_type'] == 'F'): ?>
                                                    <td class="text-center">
                                                        <span class="badge badge-success"><?php echo translate('free'); ?></span>
                                                    </td>
                                                <?php else: ?>
                                                    <td class="text-center">
                                                        <span class="badge badge-success"><?php echo price_format($row['price']); ?></span>
                                                    </td>
                                                <?php endif; ?>

                                                <td class="text-center"><?php echo check_appointment_pstatus($row['payment_status']); ?></td>
                                                <td class="text-center">
                                                    <a href="<?php echo base_url($folder_name . '/view-booking-details/' . $row['id']); ?>" style="font-size: 12px;" class="btn btn-info"><?php echo translate('details'); ?></i></a>
                                                    <?php
                                                    if ($this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) == 'V') {
                                                        $created_by = $this->session->userdata('Vendor_ID');
                                                    } else {
                                                        $created_by = $this->session->userdata('ADMIN_ID');
                                                    }
                                                    if ($created_by == $row['created_by']) {
                                                        if ($row['status'] == 'A') {
                                                            ?>

                                                            <?php if ($newTime > date("Y-m-d H:i:s")) { ?>
                                                                <a data-toggle="modal" onclick='AppointmentReminderAction(this)' data-target="#remainder-appointment" data-id="<?php echo (int) $row['id']; ?>" class="btn btn-warning font_size_12" title="<?php echo translate('send_mail'); ?>"><?php echo translate('reminder'); ?></a>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                        <?php if ($row['status'] == 'P'): ?>
                                                            <a  data-toggle="modal" data-status='<?php echo $row['status']; ?>' onclick='AppointmentAction(this)' data-target="#appointment-record" data-id="<?php echo (int) $row['id']; ?>" class="btn btn-primary font_size_12" title="<?php echo translate('appointment_action'); ?>"><?php echo translate('approve'); ?></a>
                                                        <?php endif; ?>
                                                        <?php if ($row['status'] == 'A' && $newTime < date("Y-m-d H:i:s")): ?>
                                                            <a id="" data-toggle="modal" data-status='<?php echo $row['status']; ?>' onclick='AppointmentAction(this)' data-target="#appointment-record" data-id="<?php echo (int) $row['id']; ?>" class="btn btn-primary font_size_12" title="<?php echo translate('appointment_action'); ?>"><i class="fa fa-check"></i> <?php echo translate('completed'); ?></a>
                                                        <?php endif; ?>

                                                    <?php }
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end row -->
</div>

<!-- Modal -->
<div class="modal fade" id="appointment-record">
    <div class="modal-dialog">
        <div class="modal-content">
            <?php
            $attributes = array('id' => 'AppointmentRecordForm', 'name' => 'AppointmentRecordForm', 'method' => "post");
            echo form_open('', $attributes);
            ?>
            <input type="hidden" id="record_id"/>
            <div class="modal-header">
                <h4 class="modal-title" style="font-size: 18px;"><?php echo translate('appointment_action'); ?></h4>
                <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="status_val"><?php echo translate('change_status'); ?></label>
                    <select class="form-control d-block" name="status_val" id="status_val" required="">
                        <option value=""><?php echo translate('change_status'); ?></option>
                        <option value="C"><?php echo translate('completed'); ?></option>
                        <option value="A"><?php echo translate('approved'); ?></option>
                        <option value="P"><?php echo translate('pending'); ?></option>
                        <option value="R"><?php echo translate('rejected'); ?></option>
                        <option value="D"><?php echo translate('deleted'); ?></option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <a class="btn btn-primary font_size_12" href="javascript:void(0)" onclick="change_appointment(this);"><?php echo translate('confirm'); ?></a>
                <button data-dismiss="modal" class="btn btn-danger font_size_12"type="button"><?php echo translate('close'); ?></button>
            </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<div class="modal fade" id="appointment-record">
    <div class="modal-dialog">
        <div class="modal-content">
            <?php
            $attributes = array('id' => 'AppointmentRecordForm', 'name' => 'AppointmentRecordForm', 'method' => "post");
            echo form_open('', $attributes);
            ?>
            <input type="hidden" id="record_id"/>
            <div class="modal-header">
                <h4 class="modal-title" style="font-size: 18px;"><?php echo translate('appointment_action'); ?></h4>
                <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="status_val"><?php echo translate('change_status'); ?></label>
                    <select class="form-control d-block" name="status_val" id="status_val" required="">
                        <option value=""><?php echo translate('change_status'); ?></option>
                        <option value="C"><?php echo translate('completed'); ?></option>
                        <option value="A"><?php echo translate('approved'); ?></option>
                        <option value="P"><?php echo translate('pending'); ?></option>
                        <option value="R"><?php echo translate('rejected'); ?></option>
                        <option value="D"><?php echo translate('deleted'); ?></option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">

                <a class="btn btn-primary font_size_12" href="javascript:void(0)" onclick="change_appointment(this);"><?php echo translate('confirm'); ?></a>
                <button data-dismiss="modal" class="btn btn-danger font_size_12" type="button"><?php echo translate('close'); ?></button>
            </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<div class="modal fade" id="remainder-appointment">
    <div class="modal-dialog">
        <div class="modal-content">
            <?php
            $attributes = array('id' => 'AppointmentRecordForm', 'name' => 'AppointmentRemainderForm', 'method' => "post");
            echo form_open('', $attributes);
            ?>
            <input type="hidden" id="event_book_id" name="event_book_id"/>
            <div class="modal-header">
                <h4 class="modal-title" style="font-size: 18px;"><?php echo translate('appointment_action'); ?></h4>
                <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <p id="confirm_msg" style="font-size: 15px;"><?php echo translate('send_event_reminder'); ?></p>
            </div>
            <div class="modal-footer">
                <button  class="btn btn-primary font_size_12" type="button" onclick="send_mail(this);"><?php echo translate('yes'); ?></button>
                <button data-dismiss="modal" class="btn btn-danger font_size_12" type="button"><?php echo translate('no'); ?></button>
            </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<script>
    function change_appointment(e) {
        var id = $("#record_id").val();
        var folder_name = $("#folder_name").val();
        var val = $("#status_val").val();

        if ($('#AppointmentRecordForm').valid()) {
            $.ajax({
                url: site_url + folder_name + "/change-appointment-status/" + id + "/" + val,
                type: "post",
                data: {token_id: csrf_token_name},
                beforeSend: function () {
                    $("body").preloader({
                        percent: 10,
                        duration: 15000
                    });
                },
                success: function (data) {
                    window.location.reload();
                }
            });
        }
    }
    function change_filter() {
        $("#appointment_filter").submit();
    }
    function send_mail(e) {
        var event_book_id = $("#event_book_id").val();
        var folder_name = $("#folder_name").val();
        $.ajax({
            url: site_url + folder_name + "/send-remainder",
            type: "post",
            data: {token_id: csrf_token_name, event_book_id: event_book_id},
            beforeSend: function () {
                $("body").preloader({
                    percent: 10,
                    duration: 15000
                });
            },
            success: function (data) {
                window.location.reload();
            }
        });

    }
    function AppointmentAction(e) {
        $("#status_val option[value='A']").show();
        $("#status_val option[value='P']").show();
        $("#status_val option[value='R']").show();
        $("#status_val option[value='C']").show();
        $("#status_val option[value='D']").show();

        var status = ($(e).data("status"));
        if (status == "A") {
            $("#status_val option[value='A']").hide();
            $("#status_val option[value='P']").hide();
            $("#status_val option[value='R']").hide();
            $("#status_val option[value='C']").show();
            $("#status_val option[value='D']").hide();
        }
        if (status == "P") {
            $("#status_val option[value='A']").show();
            $("#status_val option[value='P']").hide();
            $("#status_val option[value='R']").show();
            $("#status_val option[value='C']").hide();
            $("#status_val option[value='D']").hide();
        }

        if (status == "R") {
            $("#status_val option[value='A']").show();
            $("#status_val option[value='P']").hide();
            $("#status_val option[value='R']").hide();
            $("#status_val option[value='C']").hide();
            $("#status_val option[value='D']").hide();
        }


        $('#record_id').val($(e).data('id'));
    }
    function AppointmentReminderAction(e) {
        $('#event_book_id').val($(e).data('id'));
    }

</script>
<?php
if ($this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) == 'V') {
    include VIEWPATH . 'vendor/footer.php';
} else {
    include VIEWPATH . 'admin/footer.php';
}
?>

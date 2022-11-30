<?php
if ($this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) == 'V') {
    include VIEWPATH . 'vendor/header.php';
    $folder_name = 'vendor';
} else {
    include VIEWPATH . 'admin/header.php';
    $folder_name = 'admin';
}
$staff_member_value = (int) $staff_member_value;
$book_id = $this->uri->segment(3);
$event_id = $this->uri->segment(4);
$current_date_month = date("m", strtotime($current_date));

$disc_date = date("Y-m-d");
$event_price = get_price($event_data['id'], $disc_date);

$event_img_file = '';
$event_img_Arr = json_decode($event_data['image']);
if (isset($event_img_Arr) && !empty($event_img_Arr)) {
    foreach ($event_img_Arr as $key => $value) {
        $all_image[] = check_admin_image(UPLOAD_PATH . "event/" . $value);
    }
    $event_img = isset($event_img_Arr[0]) ? $event_img_Arr[0] : '';
    if ($event_img != '') {

        $original_filename = (pathinfo($event_img, PATHINFO_FILENAME));
        $original_extension = (pathinfo($event_img, PATHINFO_EXTENSION));
        $event_img_file = $original_filename . "_thumb" . "." . $original_extension;
    }
}
if ($event_img_file != '' && file_exists(FCPATH . "assets/uploads/event/" . $event_img_file)) {
    $img_src = base_url() . UPLOAD_PATH . "event/" . $event_img_file;
} else {
    $img_src = base_url() . UPLOAD_PATH . "event/events.png";
}
$get_current_currency = get_current_currency();
//$get_current_currency['currency_code'];
?>
<style>
    select {
        display: block !important;
    }

    #tablemdldatatable tr th:first-child {
        width: 35%;
    }
</style>
<link href="<?php echo $this->config->item('css_url'); ?>step.css" rel="stylesheet"/>
<script src="<?php echo base_url() . js_path . "/jquery.steps.js" ?>"></script>
<div class="dashboard-body">
    <!-- Start Content -->
    <div class="content">
        <!-- Start Container -->
        <div class="container-fluid ">
            <section class="form-light px-2 sm-margin-b-20">
                <input type="hidden" name="currency" id="currency" value="<?php echo $get_current_currency['currency_code']; ?>"/>
                <div class="container container-min-height pb-3">
                    <div class="pt-4">
                        <?php $this->load->view('message'); ?>
                    </div>
                    <h3 class="text-center pt-3"><?php echo translate('update') . " " . translate('appointment_booking'); ?></h3>
                    <hr>
                    <div class="pointer">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="marker" style="background-color: #fff200"></div>
                                <h3 class="mb-0"><?php echo($event_title); ?></h3>
                            </div>
                            <div class="col-md-6 text-sm-right">
                                <h3 class="mb-0"><?php echo date_default_timezone_get() . "(" . date("h:i A") . ")"; ?></h3>
                            </div>
                        </div>
                    </div>
                    <hr>


                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            if ($this->session->flashdata('message')) {
                                echo $this->session->flashdata('message');
                            }
                            ?>
                            <div class="text-center mb-4">
                                <?php if (isset($staff_data) && count($staff_data) > 0): ?>
                                    <div class="row justify-content-center">
                                        <?php
                                        foreach ($staff_data as $val):

                                            if (file_exists(FCPATH . "/" . uploads_path . "/profiles/" . $val['profile_image']) && $val['profile_image'] != '') {
                                                $img_src = base_url() . uploads_path . "/profiles/" . $val['profile_image'];
                                            } else {
                                                $img_src = base_url() . img_path . "/avatar.png";
                                            }
                                            ?>
                                            <div class="col-md-3 <?php echo ($val['id'] == $staff_member_value) ? "staff_active" : ""; ?>" style="padding: 15px;">
                                                <a href="<?php echo base_url($folder_name . '/change-booking-time/' . $this->uri->segment(3) . '/' . $this->uri->segment(4) . '/' . $val['id']); ?>">
                                                    <div class="staff_item">
                                                        <div class="image" style="float: left">
                                                            <img src="<?php echo $img_src; ?>" alt="<?php echo $val['first_name'] . " " . $val['last_name']; ?>" class="img-fluid rounded-circle">
                                                        </div>
                                                        <div class="text">
                                                            <h3 class="h5"><?php echo $val['first_name'] . " " . $val['last_name']; ?></h3>
                                                            <small><?php echo $val['designation']; ?></small>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="row justify-content-center">
                                        <?php
                                        if (file_exists(FCPATH . "/" . uploads_path . "/profiles/" . $current_selected_staff['profile_image']) && $current_selected_staff['profile_image'] != '') {
                                            $img_src = base_url() . uploads_path . "/profiles/" . $current_selected_staff['profile_image'];
                                        } else {
                                            $img_src = base_url() . img_path . "/avatar.png";
                                        }
                                        ?>
                                        <div class="col-md-3 staff_active" style="padding: 15px;">
                                            <a href="javascript:void(0)">
                                                <div class="staff_item">
                                                    <div class="image" style="float: left">
                                                        <img src="<?php echo $img_src; ?>" alt="<?php echo $current_selected_staff['company_name']; ?>" class="img-fluid rounded-circle">
                                                    </div>
                                                    <div class="text">
                                                        <h3 class="h5"><?php echo $current_selected_staff['company_name']; ?></h3>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <div class="row">

                                    <div class="col-md-12 resp_w-250 m-auto">
                                        <!--Carousel Wrapper-->
                                        <div >
                                            <div>
                                                <?php
                                                $ci = 0;
                                                if (isset($day_data) && count($day_data) > 0) {
                                                    foreach ($day_data

                                                    as $key => $value) {
                                                        $today_class = $un_class = $un_style = '';
                                                        if (date('Y-m-d') == date('Y-m-d', strtotime($value['full_date']))) {
                                                            $today_class = "today-day";
                                                        }
                                                        if ($value['check'] == 0) {
                                                            $un_class = "unavailable";
                                                            if (date('Y-m-d') == $value['full_date']) {
                                                                $un_class = 't-unavailable';
                                                            }
                                                            $un_style = "style='pointer-events: none;'";
                                                        }

                                                        if ($ci % 7 == 0) {
                                                            $dt = isset($key) && $key == 0 ? 'active' : '';
                                                            echo '<div>';
                                                        }
                                                        ?>
                                                        <div class="mb-2 resp_w-48 d-inline-block">

                                                            <?php
                                                            if (check_slot_available($event_id, $value['full_date'], $staff_member_value) == "false"):
                                                                if ($value['full_date'] == date('Y-m-d')) {
                                                                    $today_class = "t-unavailable";
                                                                } else {
                                                                    $today_class = "unavailable";
                                                                }
                                                                ?>
                                                                <div class="position-r"
                                                                     data-check_slot_available="<?php echo check_slot_available($event_id, $value['full_date'], $staff_member_value); ?>"
                                                                     data-eventid="<?php echo $event_id; ?>"
                                                                     data-date="<?php echo $value['full_date']; ?>"<?php echo isset($un_style) ? " " . $un_style : ''; ?>>
                                                                     <?php else: ?>
                                                                    <div class="position-r" data-staff="<?php echo (int) $staff_member_value; ?>" onclick="get_time_slots(this);" data-eventid="<?php echo $event_id; ?>" data-date="<?php echo $value['full_date']; ?>"<?php echo isset($un_style) ? " " . $un_style : ''; ?>>
                                                                    <?php endif; ?>

                                                                    <div class="day-circle m-1<?php echo isset($today_class) ? " " . $today_class : ''; ?><?php echo isset($un_class) ? " " . $un_class : ''; ?>">
                                                                        <div class="text-center">
                                                                            <strong class="shorthand" style="font-size: 14px;"><?php echo $value['week']; ?></strong>
                                                                        </div>
                                                                        <div style="font-size: 14px;"><?php echo $value['date']; ?> <?php echo $value['month']; ?></div>
                                                                        <div class="badge badge-blue"><?php echo date('Y', strtotime($value['full_date'])); ?></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <?php
                                                            $ci++;
                                                            if ($ci % 7 == 0) {
                                                                echo '</div>';
                                                            } elseif (count($day_data) == $ci) {
                                                                echo '</div>';
                                                            }
                                                            ?>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="text-center">
                                    <div class="pointer-info">
                                        <div class="marker-info" style="background-color: #289612"></div>
                                        <div class="pointer-info-text"><?php echo translate('today'); ?></div>
                                        <div class="marker-info" style="background-color: #00a2ff"></div>
                                        <div class="pointer-info-text"><?php echo translate('available'); ?></div>
                                        <div class="marker-info" style="background-color: #ccc"></div>
                                        <div class="pointer-info-text"><?php echo translate('unavailable'); ?></div>
                                        <div class="marker-info" style="background-color: #fb0e0e"></div>
                                        <div class="pointer-info-text"><?php echo translate('today_unavailable'); ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="time_slots_model" class="modal fade" role="dialog" data-backdrop="static"
                         data-keyboard="false">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&#8592;</button>
                                    <h4 class="modal-title w-100 text-center m-0"><?php echo translate('select_a_time'); ?></h4>
                                </div>
                                <div class="modal-body" id="time_slots_model_body">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="confirm_model" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="confirm_model" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel"><b><?php echo $event_data['title']; ?></b> <?php echo translate("booking"); ?></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <?php
                                    $attributes = array('id' => 'BookForm', 'name' => 'BookForm', 'method' => "post");
                                    echo form_open(base_url($folder_name . '/service-booking-update'), $attributes);
                                    ?>
                                    <input type="hidden" id="staff_member_id" name="staff_member_id" value="<?php echo $staff_member_value; ?>"/>
                                    <input type="hidden" id="user_slot_time" name="user_slot_time" value="<?php echo $slot_time; ?>"/>
                                    <input type="hidden" id="event_id" name="event_id" value="<?php echo $event_id; ?>"/>
                                    <input type="hidden" id="book_id" name="book_id" value="<?php echo $book_id; ?>"/>
                                    <input type="hidden" id="customer_id" name="customer_id" value="<?php echo $booking_data['customer_id']; ?>"/>
                                    <input type="hidden" id="user_datetime" name="user_datetime"/>
                                    <div id="day_wizard">
                                        <section class="service_information_section">
                                            <table class="table mdl-data-table" id="tablemdldatatable">

                                                <tr>
                                                    <th><?php echo translate('title'); ?></th>
                                                    <th><?php echo ($event_data['company_name']); ?></th>
                                                </tr>
                                                <tr>
                                                    <th><?php echo translate('vendor'); ?></th>
                                                    <th><?php echo ($event_title); ?></th>
                                                </tr>
                                                <tr>
                                                    <th><?php echo translate('slot_time'); ?></th>
                                                    <th><?php echo convertToHoursMins($slot_time); ?></th>
                                                </tr>
                                                <?php if (isset($event_data['payment_type']) && $event_data['payment_type'] == 'P'): ?>
                                                    <tr>
                                                        <th><?php echo translate('price'); ?></th>
                                                        <th id="discount_coupon_price"><?php echo price_format($event_price); ?></th>
                                                    </tr>
                                                <?php else: ?>
                                                    <tr>
                                                        <th><?php echo translate('price'); ?></th>
                                                        <th><?php echo translate('free'); ?></th>
                                                    </tr>
                                                <?php endif; ?>

                                                <tr>
                                                    <th><?php echo translate('category'); ?></th>
                                                    <th><?php echo ($event_data['category_title']); ?></th>
                                                </tr>
                                                <tr>
                                                    <th><?php echo translate('location') . "/" . translate('city'); ?></th>
                                                    <th><?php echo ($event_data['loc_title'] . "/" . $event_data['city_title']); ?></th>
                                                </tr>
                                                <tr>
                                                    <th><?php echo translate('appointment_date'); ?></th>
                                                    <th id="datetime_list"></th>
                                                </tr>
                                            </table>
                                            <hr/>
                                            <div class="form-group text-center">
                                                <button type="submit" class="btn btn-dark button_common submit_btn"><?php echo translate('submit'); ?></button>
                                            </div>
                                        </section>                                          
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </section>
        </div>
    </div>
</div>

<?php
if ($this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) == 'V') {
    include VIEWPATH . 'vendor/footer.php';
} else {
    include VIEWPATH . 'admin/footer.php';
}
?>
<script>
    var CURRENCY = $("#currency").val();
    function confirm_time(e) {
        $(e).parents(".row").find(".time-display").removeClass("w-49 pull-left time-display");
        $(e).parents(".row").find(".time-confirm").addClass("hide-confirm");
        $(e).addClass("w-49 pull-left time-display");
        $(e).next(".time-confirm").removeClass("hide-confirm");
    }

    function confirm_form(e) {
        $("#time_slots_model").modal('hide');
        var date = $(e).data("date");
        var time = $(e).data("time");
        var price = $(e).data("price");
        $("#amount").val(price);
        $("#main_amount").val(price);
        $("#discount_coupon_price").text(CURRENCY + price);

        var event_id = $("#event_id").val();

        var ddate = $(e).data("ddate");
        var dtime = $(e).data("dtime");

        $("#confirm_close").remove();
        $("#confirm_back").prepend('<button type="button" data-eventid="' + event_id + '" id="confirm_close" class="close" data-dismiss="modal" onclick="get_time_slots(this);" data-date="' + date + '">&#8592;</button>');
        $("#user_datetime").val(date + " " + time);
        $("#datetime_list").text(ddate + " | " + dtime);
        $("#confirm_model").modal('show');
        $("body").addClass("model-scroll");
    }
    function get_time_slots(e) {
        var date = $(e).data("date");
        var eventid = $(e).data("eventid");
        var staff_id = $(e).data("staff");
        var url = base_url + "time-slots-admin/<?php echo $slot_time; ?>";

        $.ajax({
            type: "POST",
            url: url,
            data: {date: date, staff: staff_id, eventid: eventid},
            beforeSend: function () {
                $('#loadingmessage').show();
            },
            success: function (html) {
                $("#time_slots_model_body").html(html);
                $("#time_slots_model").modal('show');
                $('#loadingmessage').hide();
                $("body").addClass("model-scroll");
            }
        });
    }

</script>
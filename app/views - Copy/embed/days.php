<?php
$vendor_token = trim($this->uri->segment(2));

$current_date_month = date("m", strtotime($current_date));
$customer_id_sess = (int) $this->session->userdata('CUST_ID');
$staff_member_value = (int) $staff_member_value;

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

//Get current set category
$get_current_currency = get_current_currency();
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo isset($title) ? $title : ""; ?></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/x-icon" href="<?php echo get_fevicon(); ?>"/>
        <link href="<?php echo $this->config->item('css_url'); ?>font-awesome.css" rel="stylesheet">
        <link href="<?php echo $this->config->item('css_url'); ?>line-awesome.min.css" rel="stylesheet">
        <link href="<?php echo $this->config->item('css_url'); ?>bootstrap.css" rel="stylesheet">
        <link href="<?php echo $this->config->item('css_url'); ?>module/bookmyslot.css" rel="stylesheet">
        <link href="<?php echo $this->config->item('css_url'); ?>module/admin_panel.css" rel="stylesheet">
        <link href="<?php echo $this->config->item('css_url'); ?>module/custom.css" rel="stylesheet">

        <script src="<?php echo $this->config->item('js_url'); ?>jquery-3.2.1.min.js"></script>
        <script src="<?php echo $this->config->item('js_url'); ?>popper.min.js"></script>
        <script src="<?php echo $this->config->item('js_url'); ?>bootstrap.min.js"></script>
        <script type="text/javascript" src="<?php echo $this->config->item('js_url'); ?>jquery.validate.min.js" type="text/javascript"></script>
        <script>
            var base_url = '<?php echo base_url() ?>';
            var display_record_per_page = '<?php echo get_site_setting('display_record_per_page'); ?>';
            var csrf_token_name = '<?php echo $this->security->get_csrf_hash(); ?>';
            $.ajaxSetup({
                data: {
                    '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                }
            });
            $(document).ajaxComplete(function () {
                $.ajaxSetup({
                    data: {
                        '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                    }
                });
            });
        </script>

        <style>
            select{
                display: block !important;
            }
            #tablemdldatatable tr th:first-child{
                width: 35%;
            }

        </style>
    </head>
    <body>
        <div id="loadingmessage" class="loadingmessage"></div>
        <div class="main_content">
            <div class="container">

                <link href="<?php echo $this->config->item('css_url'); ?>step.css" rel="stylesheet"/>
                <script src="<?php echo base_url() . js_path . "/jquery.steps.js" ?>"></script>
                <div class="h-100">
                    <input type="hidden" name="currency" id="currency" value="<?php echo $get_current_currency['currency_code']; ?>"/>
                    <div class="container container-min-height pb-3">
                        <div class="pt-4">
                            <?php $this->load->view('message'); ?>        
                        </div>
                        <h3 class="text-center mb-4"><?php echo translate('service') . " " . translate('appointment_booking'); ?></h3>
                        <div class="card"> 
                            <div class="card-body">
                                <div class="pointer">        
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="marker" style="background-color: #fff200"></div>
                                            <h3 class="mb-0"><?php echo ($event_title); ?></h3>
                                        </div>
                                        <div class="col-md-6 text-sm-right">
                                            <h3 class="mb-0"><?php echo date_default_timezone_get() . "(" . date("h:i A") . ")"; ?></h3>
                                        </div>
                                    </div>
                                </div>
                                <hr>
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
                                                <a href="<?php echo base_url('eslots/' . $this->uri->segment(2) . '/' . $this->uri->segment(3) . '/' . $val['id']); ?>">
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
                                    <div class="col-md-12">

                                        <?php
                                        if ($this->session->flashdata('message')) {
                                            echo $this->session->flashdata('message');
                                        }
                                        ?>	
                                        <div class="text-center mb-4">

                                            <div class="row">
                                                <div class="col-md-1 m-auto resp_icon left">
                                                    <span class="week-control left" onclick="check_pos(this);"><i class="fa fa-chevron-left"></i></span>
                                                </div>
                                                <div class="col-md-10 resp_w-250 m-auto">

                                                    <!--Carousel Wrapper-->
                                                    <div id="day-carousel" class="carousel slide carousel-multi-item" data-ride="carousel">
                                                        <div class="carousel-inner text-center" role="listbox">
                                                            <?php
                                                            $ci = 0;
                                                            if (isset($day_data) && count($day_data) > 0) {
                                                                foreach ($day_data as $key => $value) {
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
                                                                        echo '<div class="carousel-item ' . $dt . '">';
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
                                                                            <div class="position-r" data-check_slot_available="<?php echo check_slot_available($event_id, $value['full_date'], $staff_member_value); ?>" data-eventid = "<?php echo $event_id; ?>" data-date="<?php echo $value['full_date']; ?>"<?php echo isset($un_style) ? " " . $un_style : ''; ?>>
                                                                            <?php else: ?>
                                                                                <div class="position-r" data-staff="<?php echo (int) $staff_member_value; ?>" onclick="get_time_slots(this);"  data-eventid = "<?php echo $event_id; ?>" data-date="<?php echo $value['full_date']; ?>"<?php echo isset($un_style) ? " " . $un_style : ''; ?>>
                                                                                <?php endif; ?>

                                                                                <div class="day-circle m-1<?php echo isset($today_class) ? " " . $today_class : ''; ?><?php echo isset($un_class) ? " " . $un_class : ''; ?>">
                                                                                    <div class="text-center">
                                                                                        <strong class="shorthand"><?php echo $value['week']; ?></strong>
                                                                                    </div>
                                                                                    <div><?php echo $value['date']; ?> <?php echo $value['month']; ?></div>
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
                                                    <div class="col-md-1 m-auto resp_icon right">
                                                        <span class="week-control right" onclick="check_pos(this);"><i class="fa fa-chevron-right"></i></span>
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
                            </div>
                        </div>
                    </div>
                </div>


                <div id="time_slots_model" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
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
                                echo form_open(base_url('ebooking-oncash'), $attributes);
                                ?>
                                <input type="hidden" name="amount" id="amount" value="<?php echo isset($event_price) ? $event_price : '0'; ?>">
                                <input type="hidden" name="main_amount" id="main_amount" value="<?php echo isset($event_price) ? $event_price : '0'; ?>">
                                <input type="hidden" id="user_slot_time" name="user_slot_time" value="<?php echo $slot_time; ?>"/>
                                <input type="hidden" id="event_id" name="event_id" value="<?php echo $event_id; ?>"/>
                                <input type="hidden" id="discount_coupon_id" name="discount_coupon_id" value="0"/>
                                <input type="hidden" id="add_ons_amount" name="add_ons_amount" value="0"/>
                                <input type="hidden" id="staff_member_id" name="staff_member_id" value="<?php echo $staff_member_value; ?>"/>
                                <input type="hidden" id="vendor_token" name="vendor_token" value="<?php echo $vendor_token; ?>"/>
                                <?php if ($event_data['payment_type'] == 'P' && count($app_service_addons) > 0): ?>
                                    <input type="hidden" id="add_ons_flag" name="add_ons_flag" value="Y"/>
                                <?php else: ?>
                                    <input type="hidden" id="add_ons_flag" name="add_ons_flag" value="N"/>
                                <?php endif; ?>

                                <input type="hidden" id="user_datetime" name="user_datetime"/>
                                <input type="hidden" id="event_payment_type" name="event_payment_type" value="<?php echo isset($event_payment_type) ? $event_payment_type : ""; ?>"/>
                                <div id="day_wizard">
                                    <h4><?php echo translate("customer") . " " . translate("details"); ?></h4>
                                    <section class="service_information_section">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="first_name"><?php echo translate('first_name'); ?></label>
                                                    <input type="text" maxlength="50" autocomplete="off"  class="form-control" required="" placeholder="<?php echo translate('first_name'); ?>" id="first_name" name="first_name"/>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="last_name"><?php echo translate('last_name'); ?></label>
                                                    <input type="text" maxlength="50"  autocomplete="off" class="form-control"  required="" placeholder="<?php echo translate('last_name'); ?>" id="last_name" name="last_name"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="customer_email"><?php echo translate('email'); ?></label>
                                                    <input type="email" maxlength="50"  autocomplete="off" class="form-control"  required="" placeholder="<?php echo translate('email'); ?>" id="email" name="email"/>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="customer_phone"><?php echo translate('phone'); ?></label>
                                                    <input type="text" maxlength="15" class="form-control"   autocomplete="off" required="" placeholder="<?php echo translate('phone'); ?>" id="phone" name="phone"/>
                                                </div>
                                            </div>
                                        </div>





                                    </section>

                                    <h4><?php echo translate("details"); ?></h4>
                                    <section class="service_information_section">

                                        <div class="table-responsive">
                                            <table class="table mdl-data-table" id="tablemdldatatable">
                                                <tr>
                                                    <th><?php echo translate('service'); ?></th>
                                                    <th><?php echo ($event_title); ?></th>
                                                </tr>
                                                <tr>
                                                    <th><?php echo translate('vendor'); ?></th>
                                                    <th><?php echo ($event_data['company_name']); ?></th>
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
                                        </div>


                                    </section>
                                    <?php if ($event_data['payment_type'] == 'P' && count($app_service_addons) > 0): ?>
                                        <h4><?php echo translate("add_ons"); ?></h4>
                                        <section class="addons_information_section">
                                            <div class="row">

                                                <?php
                                                foreach ($app_service_addons as $val):

                                                    if ($val['image'] != '' && file_exists(FCPATH . "assets/uploads/event/" . $val['image'])) {
                                                        $img_src_add = base_url() . UPLOAD_PATH . "event/" . $val['image'];
                                                    } else {
                                                        $img_src_add = base_url() . UPLOAD_PATH . "event/events.png";
                                                    }
                                                    ?>
                                                    <div class="col-md-4 pull-left">
                                                        <div class="card">
                                                            <img class="card-img-top" src="<?php echo $img_src_add; ?>" alt="Card image cap">
                                                            <div class="card-body">
                                                                <h5 class="card-title"><?php echo $val['title']; ?></h5>
                                                            </div>
                                                            <ul class="list-group list-group-flush">
                                                                <li class="list-group-item"><b><?php echo translate('price'); ?> : </b><?php echo price_format($val['price']); ?>
                                                                    <i class="fa fa-info-circle" title="<?php echo $val['details']; ?>"></i>
                                                                </li>
                                                            </ul>
                                                            <div class="card-body">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" data-p="<?php echo $val['price']; ?>" data-token="<?php echo $val['add_on_id']; ?>" onchange="add_ons_check(this)" name="add_ons_id[]" type="checkbox" id="gridCheck<?php echo $val['add_on_id']; ?>" value="<?php echo $val['add_on_id']; ?>">
                                                                    <label class="form-check-label" for="gridCheck<?php echo $val['add_on_id']; ?>">
                                                                        Add This 
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>

                                            </div>
                                            <h5 class="book_detail text-center">
                                                <?php if ($event_data['payment_type'] == 'P'): ?>
                                                    <p><?php echo translate('price'); ?> : <?php echo $get_current_currency['currency_code']; ?><span class="add_ons_total"><?php echo isset($event_price) ? ($event_price) : '0'; ?></span></p>
                                                <?php else: ?>
                                                    <p><?php echo translate('price'); ?> : <?php echo translate('free'); ?></span></p>
                                                <?php endif; ?>
                                            </h5>
                                        </section>
                                    <?php endif; ?>

                                    <h4><?php echo translate('payment'); ?></h4>
                                    <section class="payment_section">
                                        <div class="form-group">
                                            <label for="description"><?php echo translate('booking_note'); ?></label>
                                            <textarea type="text" class="form-control" rows="1" placeholder="<?php echo translate('booking_note'); ?>" id="description" name="description"></textarea>
                                        </div>
                                        <?php if (isset($event_payment_type) && $event_payment_type == 'P'): ?>
                                            <div class="form-group">
                                                <label class="black-text"><?php echo translate('event_coupon'); ?></label>
                                                <div class="input-group mb-3">
                                                    <input type="text" class="form-control" name="discount_coupon" id="discount_coupon" placeholder="<?php echo translate('event_coupon'); ?>" aria-describedby="basic-addon" >
                                                    <div class="input-group-append">
                                                        <button class="input-group-text border-0 green white-text" id="apply_button" style="display: block" type="button" onclick="apply_coupon_code()"><?php echo translate('apply'); ?></button>
                                                        <button class="input-group-text border-0 grey white-text" id="discard_button" style="display: none"  type="button" onclick="discard_coupon_code()"><?php echo translate('discard'); ?></button>
                                                    </div>
                                                </div>
                                                <p class="error" id="discount_coupon_error"></p>
                                            </div>
                                        <?php endif; ?>
                                        <hr/>
                                        <h5 class="book_detail text-center">
                                            <?php if ($event_data['payment_type'] == 'P'): ?>
                                                <p><?php echo translate('price'); ?> : <?php echo $get_current_currency['currency_code']; ?><span class="add_ons_total"><?php echo isset($event_price) ? ($event_price) : '0'; ?></span></p>
                                            <?php else: ?>
                                                <p><?php echo translate('price'); ?> : <?php echo translate('free'); ?></span></p>
                                            <?php endif; ?>
                                        </h5>
                                        <hr/>
                                        <?php if (isset($event_data['payment_type']) && $event_data['payment_type'] == 'P'): ?>
                                            <div class="form-group text-center">
                                                <p class="black-text" style="font-size: 17px;"><?php echo translate('payment_by'); ?></p>

                                                <!-- Set Cash ON method -->
                                                <?php if (check_payment_method('on_cash')): ?>
                                                    <button type="button" onclick="valid_on_cash()" class="btn btn-primary btn-rounded"><?php echo translate('on_cash'); ?></button>
                                                <?php endif; ?>

                                                <!-- Set Stripe method -->
                                                <?php if (check_payment_method('stripe') && $get_current_currency['stripe_supported'] == 'Y'): ?>
                                                    <button type="button" onclick="valid_stripe()" class="btn btn-warning btn-rounded"><?php echo translate('stripe'); ?></button>
                                                <?php endif; ?>

                                                <!-- Set PayPal ON method -->
                                                <?php if (check_payment_method('paypal') && $get_current_currency['paypal_supported'] == 'Y'): ?>
                                                    <button type="button" onclick="valid_paypal()" class="btn btn-info btn-rounded"><?php echo translate('paypal'); ?></button>
                                                <?php endif; ?>

                                                <?php echo form_error('payment_method'); ?>
                                            </div>
                                        <?php else: ?>
                                            <div class="form-group text-center">
                                                <button type="button" onclick="valid_free()" class="btn btn-dark button_common submit_btn"><?php echo translate('submit'); ?></button>
                                            </div>
                                        <?php endif; ?>
                                    </section>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="<?php echo $this->config->item('js_url'); ?>module/eday_book.js"></script>
        <?php if (check_payment_method('stripe')) { ?>
            <script src="https://checkout.stripe.com/checkout.js"></script>
            <script type="text/javascript">
                                                    var handler = StripeCheckout.configure({
                                                        key: '<?php echo get_Stripepublish(); ?>',
                                                        image: '',
                                                        token: function (token) {
                                                            $('#loadingmessage').show();
                                                            $('#BookForm').append("<input type='hidden' name='stripeToken' value='" + token.id + "' />");
                                                            $("#BookForm").attr("action", base_url + "ebooking-stripe");
                                                            $("#BookForm").submit();
                                                        }
                                                    });

                                                    function get_stripe() {
                                                        var payment_price = $("#amount").val();
                                                        var first_name = $("#first_name").val();
                                                        var last_name = $("#last_name").val();
                                                        var email = $("#email").val();

                                                        var total = parseInt(payment_price) * 100;
                                                        handler.open({
                                                            name: first_name + " " + last_name,
                                                            email: email,
                                                            amount: total
                                                        });
                                                    }
                                                    // Close Checkout on page navigation
                                                    $(window).on('popstate', function () {
                                                        handler.close();
                                                    });</script>
        <?php } ?>
        <script>
            var CURRENCY = $("#currency").val();
            $("#day-carousel").carousel({
                interval: false,
                wrap: false
            });
            $(".left").click(function () {
                $("#day-carousel").carousel("prev");
            });
            $(".right").click(function () {
                $("#day-carousel").carousel("next");
            });
            function get_time_slots(e) {
                $("#confirm_model").modal('hide');
                $('#BookForm')[0].reset();
                date = $(e).data("date");
                var staff_id = $(e).data("staff");
                eventid = $(e).data("eventid");
                var url = base_url + "etime-slots/<?php echo $slot_time; ?>";
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {date: date, staff: staff_id, eventid: eventid},
                    beforeSend: function () {
                        $('#loadingmessage').show();
                    },
                    success: function (html) {
                        if (html == false) {
                            window.location.href = base_url + 'login';
                        }
                        $("#time_slots_model_body").html(html);
                        $("#time_slots_model").modal('show');
                        $('#loadingmessage').hide();
                        $("body").addClass("model-scroll");
                    }
                });
            }
            function discard_coupon_code() {
                var main_amount = parseFloat($("#main_amount").val());
                var add_ons_amount = parseFloat($("#add_ons_amount").val());

                var total_amount = main_amount + add_ons_amount;
                $("#discount_coupon_price").html(CURRENCY + " " + total_amount.toFixed(2));
                $("#apply_button").css("display", "block");
                $(".add_ons_total").text(total_amount.toFixed(2));
                $("#discard_button").css("display", "none");
                $("#discount_coupon").val("");
            }
            check_pos('body');
        </script>
    </body>
</html>
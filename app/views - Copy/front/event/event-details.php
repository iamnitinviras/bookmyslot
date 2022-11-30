<?php
$faq = (isset($event_data['faq']) && !empty($event_data['faq'])) ? json_decode($event_data['faq']) : array();
$total_rate_sum = 0;
include VIEWPATH . 'front/header.php';
$get_discount_price_by_date = get_discount_price_by_date($event_data['id'], date('Y-m-d'));
$total_booked_seat = isset($event_book) ? $event_book : 0;
$total_seat = isset($event_data['total_seat']) ? $event_data['total_seat'] : 0;
$total_available_seat = $total_seat - $total_booked_seat;
$sponser_id = (set_value("sid")) ? set_value("sid") : (!empty($event_data) ? $event_data['sid'] : 0);
$customer_id_sess = (int) $this->session->userdata('CUST_ID');

$profile_image = base_url() . img_path . "/user.png";
if (isset($event_data) && count($event_data) > 0 && !empty($event_data['profile_image']) && $event_data['profile_image'] != 'null') {
    $profile_image = check_profile_image(UPLOAD_PATH . "profiles/" . $event_data['profile_image']);
}

// Get Event Ticket 
$get_ticket_type_by_event = get_ticket_type_by_event($event_data['id']);

$e_start_date = date('Y-m-d', strtotime($event_data['start_date']));
$e_start_time = date('H:i', strtotime($event_data['start_date']));


$e_end_date = date('Y-m-d', strtotime($event_data['end_date']));
$e_end_time = date('H:i', strtotime($event_data['end_date']));


if ($e_start_date == $e_end_date) {
    $event_date_display = date('d M', strtotime($event_data['start_date'])) . " " . $e_start_time . "-" . $e_end_time;
} else {
    $event_date_display = date('d M', strtotime($event_data['start_date'])) . "-" . date('d M', strtotime($event_data['end_date'])) . " " . $e_start_time . "-" . $e_end_time;
}
//Get current set category
$get_current_currency = get_current_currency();
?>
<style>
    .modal-open {
        overflow: hidden;
        position:fixed;
        width: 100%;
        height: 100%;
    }
    .youtube-embed-wrapper{
        height: 0;
        overflow: hidden;
        padding-bottom: 56.25%;
        padding-top: 30px;
        position: relative;
    }
    input[type="image"]{
        max-width: 100%;
        height: auto !important;  
    }
    .event-details_all_content img{
        max-width: 100%;
        height: auto !important;
    }
</style>
<link href="<?php echo $this->config->item('css_url'); ?>step.css" rel="stylesheet"/>
<script src="<?php echo base_url() . js_path . "/jquery.steps.js" ?>"></script>
<script src="<?php echo $this->config->item('js_url'); ?>/module/main.js"></script>
<div class="pt-2">
    <input type="hidden" name="currency" id="currency" value="<?php echo $get_current_currency['currency_code']; ?>"/>
    <div class="container">
        <div class="mt-20">
            <?php $this->load->view('message'); ?>        
        </div>
        <div class="card mb-3">
            <div class="row mx-0">
                <div class="col-md-2 px-0 m-0">
                    <div class="image">
                        <?php
                        $event_img_file = '';
                        $event_img_Arr = json_decode($event_data['image']);
                        if (isset($event_img_Arr) && !empty($event_img_Arr)) {
                            foreach ($event_img_Arr as $key => $value) {
                                $all_image[] = check_event_image(UPLOAD_PATH . "event/" . $value);
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
                        ?>
                        <img src="<?php echo $img_src; ?>" class="img-fluid" >
                    </div>
                </div>
                <div class="col-md-9 m-0 event-book_list">
                    <div class="p-2">
                        <h3 class="text-left"><?php echo $event_data['title']; ?></h3>
                        <div class="row">
                            <div class="col-md-6 m-0">
                                <div class="event_details-list">
                                    <p>
                                        <small><i class="fa fa-calendar pr-1"></i> <?php echo $event_date_display; ?></small>
                                    </p>
                                    <p>
                                        <small><i class="fa fa-map-marker pr-1"></i><?php echo $event_data['address']; ?></small>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-4 my-0">
                                <div class="event_details-list">
                                    <p>
                                        <span class="badge badge-secondary custom_badge"><?php echo translate('category'); ?></span>
                                        <small><?php echo $event_data['category_title']; ?> </small>
                                    </p>

                                    <p>
                                        <span style="width: 100%;font-size: 16px;" class="badge badge-secondary custom_badge event_date_time_class"  data-enddate="<?php echo date("M d, Y H:i:s", strtotime($event_data['end_date'])); ?>"  data-date="<?php echo date("M d, Y H:i:s", strtotime($event_data['start_date'])); ?>" data-id="<?php echo $event_data['id']; ?>" id="event<?php echo $event_data['id']; ?>"></span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-2 my-0">
                                <div class="text-right">
                                    <?php if (count($get_ticket_type_by_event) > 0): ?>
                                        <?php if ($customer_id_sess == 0):
                                            ?>
                                            <button style="width: 150px;" type="button" onclick="login_protected_modal(this)" class="btn btn-dark button_common waves-effect waves-light mt-1" data-message="<?php echo translate('login_required_for_book'); ?>"><?php echo translate('book') . " " . translate('ticket'); ?></button>
                                            <?php
                                        else:
                                            ?>
                                            <button style="width: 150px;" type="button" onclick="eventData(this)" class="btn btn-dark button_common waves-effect waves-light mt-1"><i class="fa fa-ticket"></i> <?php echo translate('book') . " " . translate('ticket'); ?></button>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <button style="width: 150px;" type="button" class="btn btn-danger button_common waves-effect waves-light mt-1"><i class="fa fa-ticket"></i> <?php echo translate('sold_out'); ?></button>
                                    <?php endif; ?>


                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>        
        </div>
    </div>

    <div class="main_event_content">
        <div class="container">
            <div class="row">
                <div class="col-md-8">

                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="event_details">
                                <div class="event-carousel-imgs">
                                    <div class="img_slider">
                                        <div id="carousel-thumb" class="carousel slide carousel-fade carousel-thumbnails" data-ride="carousel">
                                            <!--Slides-->
                                            <div class="carousel-inner" role="listbox">
                                                <?php
                                                if (isset($all_image) && count($all_image) > 0) {
                                                    foreach ($all_image as $key => $value) {
                                                        ?>
                                                        <div class="carousel-item <?php echo isset($key) && $key == 0 ? 'active' : ''; ?>">
                                                            <img class="d-block w-100" src="<?php echo $value; ?>">
                                                        </div>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </div>
                                            <!--/.Slides-->
                                            <!--Controls-->
                                            <a class="carousel-control-prev" href="#carousel-thumb" role="button" data-slide="prev">
                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                <span class="sr-only">Previous</span>
                                            </a>
                                            <a class="carousel-control-next" href="#carousel-thumb" role="button" data-slide="next">
                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                <span class="sr-only">Next</span>
                                            </a>
                                            <!--/.Controls-->
                                            <ol class="carousel-indicators mx-0">
                                                <?php
                                                if (isset($all_image) && count($all_image) > 0) {
                                                    foreach ($all_image as $key => $value) {
                                                        ?>
                                                        <li data-target="#carousel-thumb" data-slide-to="<?php echo $key; ?>" class="<?php echo isset($key) && $key == 0 ? 'active' : ''; ?>"> <img class="d-block w-100" src="<?php echo $value; ?>" class="img-fluid"></li>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                                <div class="event-details_all_content">
                                    <?php echo isset($event_data['description']) ? htmlspecialchars_decode($event_data['description']) : ""; ?>
                                </div>
                            </div>


                            <?php if ((isset($faq) && count($faq) > 0 && !empty($faq))): ?>                        
                                <div class="events-tabs  listing-contact-info">
                                    <div class="card-header transparent">
                                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                                            <?php if (isset($faq) && count($faq) > 0 && !empty($faq)): ?>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="faqs-tab" data-toggle="tab" href="#faqs" role="tab" aria-controls="faqs" aria-selected="false">FAQs</a>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>

                                    <div class="tab-content mt-3" id="myTabContent">
                                        <?php
                                        $faq_active = 'show active';
                                        if (isset($faq) && count($faq) > 0 && !empty($faq)):
                                            ?>
                                            <div class="tab-pane fade <?php echo isset($faq_active) ? $faq_active : '' ?>" id="faqs" role="tabpanel" aria-labelledby="faqs-tab">
                                                <div class="faqs_section">
                                                    <!--Accordion wrapper-->
                                                    <div class="accordion md-accordion" id="accordionfaq" role="tablist" aria-multiselectable="true">
                                                        <?php foreach ($faq as $key => $val): ?>
                                                            <!-- Accordion card -->
                                                            <div class="card">

                                                                <!-- Card header -->
                                                                <div class="card-header" role="tab" id="heading<?php echo $key; ?>">
                                                                    <?php
                                                                    if ($key == 0) {
                                                                        $selected = "true";
                                                                    } else {
                                                                        $selected = "false";
                                                                    }
                                                                    ?>
                                                                    <h5 class="mb-0 card-title">
                                                                        <a data-toggle="collapse" data-parent="#accordionfaq" href="#collapse<?php echo $key; ?>" aria-expanded="<?php echo $selected; ?>" aria-controls="collapse<?php echo $key; ?>">
                                                                            <?php echo trim($val->faq_title); ?>
                                                                        </a>
                                                                    </h5>
                                                                </div>

                                                                <!-- Card body -->
                                                                <div id="collapse<?php echo $key; ?>" class="collapse <?php echo isset($key) && $key == 0 ? 'show' : ''; ?>" role="tabpanel" aria-labelledby="heading<?php echo $key; ?>" data-parent="#accordionfaq">
                                                                    <div class="card-body">
                                                                        <?php echo trim($val->faq_description); ?>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <!-- Accordion card -->
                                                        <?php endforeach; ?>

                                                        <!--End Accordion wrapper-->
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>                            
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 event_right_sidebar">
                    <div class="card mb-0">
                        <div class="px-2 py-3">
                            <div class="user_details">
                                <div class="text-center">
                                    <div class="user-img">
                                        <img src="<?php echo $profile_image; ?>" alt="profile" class="img-fluid rounded-circle" />
                                    </div>
                                    <div class="user_details-info">
                                        <p class="user-name"><?php echo trim($event_data['company_name']); ?></p>
                                        <small><?php echo translate('last_seen') ?> : <?php echo get_last_seen($event_data['app_admin_id']); ?></small>
                                        <?php if (isset($event_data['fb_link']) || isset($event_data['twitter_link']) || isset($event_data['google_link'])) { ?>
                                            <div class="social_icon">
                                                <ul class="list-inline inline-ul">
                                                    <?php if (isset($event_data['fb_link']) && $event_data['fb_link'] != '') { ?>
                                                        <li><a class="fb-ic" href="<?php echo $event_data['fb_link']; ?>" target="_blank"><i class="fa fa-facebook"></i></a></li>
                                                    <?php } if (isset($event_data['twitter_link']) && $event_data['twitter_link'] != '') { ?>
                                                        <li><a class="tw-ic" href="<?php echo $event_data['twitter_link']; ?>" target="_blank"><i class="fa fa-twitter"></i></a></li>
                                                    <?php } if (isset($event_data['google_link']) && $event_data['google_link'] != '') { ?>
                                                        <li><a class="gplus-ic" href="<?php echo $event_data['google_link']; ?>" target="_blank"><i class="fa fa-google-plus"></i></a></li>
                                                    <?php } ?>
                                                </ul>
                                            </div>
                                        <?php } ?>
                                        <div class="gobtn">
                                            <a class="btn text-capitalize" href="<?php echo base_url('v/' . slugify(trim($event_data['company_name'])) . '/' . $event_data['app_admin_id']); ?>"><?php echo translate('view') . " " . translate('organizer') . " " . translate('profile'); ?></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br/>
                    <div class="card">
                        <div class="card-body">
                            <div class="price">
                                <a href="javascript:void(0)" data-toggle="modal" data-target="#contactVendor"><h6 class="mb-0"><?php echo translate('contact') . " " . translate('organizer'); ?></h6></a>
                            </div>
                        </div>
                    </div>
                    <?php if (isset($event_data['address_map_link']) && $event_data['address_map_link'] != ''): ?>
                        <div class="card">
                            <div class="card-body">
                                <div class="price">
                                    <i class="fa fa-location-arrow"></i>
                                    <a target="_blank"  style="color: #000 !important" href="<?php echo $event_data['address_map_link']; ?>"><?php echo translate('get_direction'); ?></a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php
                    if ($sponser_id > 0):
                        ?>
                        <div class="card">
                            <div class="card-body">
                                <div class="spons_details">
                                    <h6><?php echo translate('sponsored_Ad'); ?></h6>
                                    <hr/>
                                    <?php
                                    if ($event_data['sponsor_image'] != '' && file_exists(FCPATH . "assets/uploads/event/" . $event_data['sponsor_image'])) {
                                        $img_srcs = base_url() . UPLOAD_PATH . "event/" . $event_data['sponsor_image'];
                                    } else {
                                        $img_srcs = "";
                                    }
                                    ?>
                                    <?php if (isset($img_srcs) && $img_srcs != ""): ?>
                                        <img src="<?php echo $img_srcs; ?>" alt="<?php echo $event_data['sponsor_name']; ?>" class="img-fluid">
                                    <?php endif; ?>
                                    <h5><?php echo $event_data['sponsor_name']; ?></h5>
                                    <?php if ($event_data['website_link'] != '') { ?><i class="fa fa-globe"></i> <a target="_blank" href="<?php echo $event_data['website_link'] ?>"><?php echo $event_data['website_link'] ?></a><?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if (count($get_ticket_type_by_event) > 0): ?>
    <!--confirm Modal-->
    <div class="modal fade" id="confirm_model" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><?php echo $event_data['title']; ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php
                    $attributes = array('id' => 'BookForm', 'name' => 'BookForm', 'method' => "post", "class" => "oncash");
                    echo form_open(base_url('event-booking-oncash'), $attributes);
                    ?>
                    <input type="hidden" name="amount" id="amount" value="0">
                    <input type="hidden" name="main_amount" id="main_amount" value="0">
                    <input type="hidden" name="main_ticket" id="main_ticket" value="0">
                    <input type="hidden" id="event_id" name="event_id" value="<?php echo $event_data['id']; ?>"/>
                    <input type="hidden" id="event_category_id" name="event_category_id" value="<?php echo $event_data['category_id']; ?>"/>
                    <input type="hidden" id="total_booked_seat" name="total_booked_seat" value=""/>
                    <input type="hidden" id="start_date" name="start_date" value="<?php echo $event_data['start_date']; ?>"/>
                    <input type="hidden" id="first_name" name="first_name" value="<?php echo isset($customer_data) ? $customer_data['first_name'] : ""; ?>"/>
                    <input type="hidden" id="last_name" name="last_name" value="<?php echo isset($customer_data) ? $customer_data['last_name'] : ""; ?>"/>
                    <input type="hidden" id="email" name="email" value="<?php echo isset($customer_data) ? $customer_data['email'] : ""; ?>"/>

                    <div id="wizards">
                        <h4><?php echo translate('ticket') . "s"; ?></h4>
                        <section class="payment_section">
                            <?php foreach ($get_ticket_type_by_event as $val): ?>

                                <div class="card mb-2">
                                    <div class="card-body" style="padding: 0.7rem;">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <h3 style="font-size: 18px;"><?php echo $val['ticket_type_title']; ?></h3>
                                                <p><?php echo ($val['ticket_type_price'] > 0) ? price_format($val['ticket_type_price']) : translate('free'); ?> | <?php echo translate('available') . " " . translate('ticket') . " : " . $val['available_ticket']; ?></p>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <span class="input-group-btn">
                                                        <button type="button" onclick="increment(this, 'd')" data-price="<?php echo $val['ticket_type_price']; ?>" class="btn btn-default m-0" data-id="<?php echo $val['ticket_type_id']; ?>" min="0" max="<?php echo $val['available_ticket']; ?>" data-type="minus">
                                                            <span class="fa fa-minus-circle"></span>
                                                        </button>
                                                    </span>

                                                    <input type="hidden" id="ticket_type_id_<?php echo $val['ticket_type_id']; ?>" disabled="" name="ticket_type_id[]" value="<?php echo $val['ticket_type_id']; ?>"/>
                                                    <input type="text" disabled="" id="ticket_input_id_<?php echo $val['ticket_type_id']; ?>" name="total_seat_book[]" readonly="" style="height: 35px;" class="form-control text-center input-number" value="0" min="1" max="<?php echo $val['available_ticket']; ?>">

                                                    <span class="input-group-btn">
                                                        <button onclick="increment(this, 'i')" type="button" data-price="<?php echo $val['ticket_type_price']; ?>" data-id="<?php echo $val['ticket_type_id']; ?>"  class="btn btn-default m-0" min="0" max="<?php echo $val['available_ticket']; ?>">
                                                            <span class="fa fa-plus-circle"></span>
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            <?php endforeach; ?>
                            <h5 class="book_detail text-center m-1 pt-2">
                                <p><?php echo translate('ticket'); ?> : <span id="total_ticket_display_count">0</span></p> |<p><?php echo translate('price'); ?> : <span class="main_amount_display_total"><?php echo $get_current_currency['currency_code']; ?>0</span></p>
                            </h5>
                        </section>

                        <h4><?php echo translate('payment') . " " . translate("details"); ?></h4>
                        <section class="payment_section">
                            <div class="table-responsive">
                                <table class="table mdl-data-table mt-3" id="tablemdldatatable">
                                    <tr>
                                        <th><?php echo translate('title'); ?></th>
                                        <th><?php echo $event_data['title']; ?></th>
                                    </tr>

                                    <tr>
                                        <th><?php echo translate('price'); ?></th>
                                        <th class="main_amount_display_total"><?php echo translate('free'); ?></th>
                                    </tr>
                                    <tr>
                                        <th><?php echo translate('event') . " " . ('date'); ?></th>
                                        <th><?php echo date("m/d/Y H:i a", strtotime($event_data['start_date'])); ?> to <br/><?php echo date("m/d/Y H:i a", strtotime($event_data['end_date'])); ?></th>
                                    </tr>

                                    <tr>
                                        <th><?php echo translate('total') . " " . ("ticket"); ?></th>
                                        <th id="total_book_ticket"></th>
                                    </tr>
                                </table>
                            </div>

                            <div class="form-group">
                                <label for="description"><?php echo translate('booking_note'); ?></label>
                                <textarea type="text" class="form-control" rows="1" placeholder="<?php echo translate('booking_note'); ?>" id="description" name="description" style="height: auto"></textarea>
                            </div>

                            <div id="hasPayment"  class="text-center" style="display: none;">
                                <div class="form-group">
                                    <p class="black-text" style="font-size: 17px;"><?php echo translate('payment_by'); ?></p>

                                    <!-- Set Cash ON method -->
                                    <?php if (check_payment_method('on_cash')): ?>
                                        <button type="button" onclick="valid_on_cash();" class="btn btn-primary btn-rounded"><?php echo translate('on_cash'); ?></button>
                                    <?php endif; ?>

                                    <!-- Set Stripe method -->
                                    <?php if (check_payment_method('stripe') && $get_current_currency['stripe_supported'] == 'Y'): ?>
                                        <button type="button" onclick="valid_stripe();" class="btn btn-warning btn-rounded"><?php echo translate('stripe'); ?></button>
                                    <?php endif; ?>

                                    <!-- Set PayPal ON method -->
                                    <?php if (check_payment_method('paypal') && $get_current_currency['paypal_supported'] == 'Y'): ?>
                                        <button type="button" onclick="valid_paypal();" class="btn btn-info btn-rounded"><?php echo translate('paypal'); ?></button>
                                    <?php endif; ?>

                                    <!-- Set 2Checkout ON method -->
                                    <?php if (check_payment_method('2checkout') && $get_current_currency['2checkout_supported'] == 'Y'): ?>
                                        <button type="button" onclick="valid_two_checkout()" class="btn btn-secondary btn-rounded">2Checkout</button>
                                    <?php endif; ?>

                                    <?php echo form_error('payment_method'); ?>
                                </div>
                            </div>

                            <div class="form-group" id="hasFree"  style="display: none;">
                                <button type="button" onclick="valid_free();" class="btn btn-dark button_common submit_btn"><?php echo translate('submit'); ?></button>
                            </div>
                        </section>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<div class="modal fade" id="contactVendor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><?php echo translate('contact') . " " . translate('organizer'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php
                $attributes = array('id' => 'FrmContact', 'class' => "small-contact-form", 'name' => 'FrmContact', 'method' => "post");
                echo form_open('contact-action', $attributes);

                echo form_hidden("event_id", $event_data['id']);
                echo form_hidden("admin_id", isset($event_data['created_by']) ? $event_data['created_by'] : 0);
                ?>

                <div class="form-group">
                    <input required="" class="form-control" maxlength="50"  autocomplete="off"  type="text" placeholder="<?php echo translate('name'); ?>" id="fullname" name="fullname" />
                    <?php echo form_error('fullname'); ?>
                </div>
                <div class="form-group">
                    <input class="form-control" required=""  maxlength="50" autocomplete="off"  type="email" placeholder="<?php echo translate('email'); ?>" id="emailid" name="emailid" />
                    <?php echo form_error('emailid'); ?>
                </div>
                <div class="form-group">
                    <input required="" maxlength="12" class="form-control phone"  autocomplete="off" type="text" placeholder="<?php echo translate('phone'); ?>" id="phoneno" name="phoneno" />
                    <?php echo form_error('phoneno'); ?>
                </div>
                <div class="form-group">
                    <textarea  required="" class="form-control" placeholder="<?php echo translate('message'); ?>" id="message" name="message"></textarea>
                    <?php echo form_error('message'); ?>
                </div>
                <div class="form-group mb-0">
                    <button class="btn btn-dark button_common" type="submit"><?php echo translate('send'); ?> <?php echo translate('message'); ?></button>
                </div>
                <?php echo form_close(); ?>

            </div>
        </div>
    </div>
</div>

<?php include VIEWPATH . 'front/footer.php'; ?>
<script src="<?php echo $this->config->item('js_url'); ?>module/event_details.js"></script>
<?php if (check_payment_method('stripe')) { ?>
    <script src="https://checkout.stripe.com/checkout.js"></script>
    <script type="text/javascript">
                                    var handler = StripeCheckout.configure({
                                        key: '<?php echo get_Stripepublish(); ?>',
                                        image: '',
                                        token: function (token) {
                                            $('#loadingmessage').show();
                                            $('#BookForm').append("<input type='hidden' name='stripeToken' value='" + token.id + "' />");
                                            $("#BookForm").attr("action", base_url + "event-booking-stripe");
                                            $("#BookForm").submit();
                                        }
                                    });
                                    $(window).on('popstate', function () {
                                        handler.close();
                                    });
    </script>
<?php } ?>
<script>
    var is_login = '<?php echo $customer_id_sess; ?>';
    var CURRENCY = $("#currency").val();

    if ($(".event_date_time_class").length > 0) {
        $(".event_date_time_class").each(function (e) {
            var event_id = $(this).data('id');
            var date = $(this).data('date');
            var enddate = $(this).data('enddate');
            set_time_count(event_id, date, enddate);
        });
    }

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
    });

    $("#confirm_model").on('hidden.bs.modal', function () {
        location.reload();
    });

</script>
<?php
$url_segment = trim($this->uri->segment(2));
$dashboard_active = $service_coupon_active = "";
$event_active = "";
$holiday_active = "";
$service_appointment_report_active = "";
$event_booking_report_active = "";
$event_category_active = "";
$appointment_active = "";
$payout_request_active = "";
$city_active = "";
$location_active = "";
$master_open = "";
$membership_active = "";
$slider_active = "";
$message_active = "";
$appointment_report_active = "";
$event_open = "";
$payment_history_active = "";
$event_coupon_active = "";
$IntegrateOn_Webpage_active = '';
$event_booking_active = $event_payment_active = '';
$service_list_active = "";
$service_open = '';
$service_category_active = "";

$holiday_array = array("holiday", "add-holiday", 'update-holiday', 'save-holiday', 'delete-holiday');
$eventArr = array("manage-event", "add-event", 'update-event', 'save-event', 'delete-event', 'manage-event-appointment');
$staffArr = array("staff", "add-staff", 'update-staff', 'save-staff', 'delete-staff', 'staff-booking');
$appointmentArr = array("manage-appointment", "view-booking-details", "change-booking-time");
$eventCouponArr = array("manage-coupon", "add-coupon", 'update-coupon', 'save-coupon', 'delete-coupon');
$location_active_Arr = array("location", 'add-location', "save-location");
$sliderArr = array("manage-slider", "add-slider", 'update-slider', 'save-slider', 'delete-slider');
$city_active_Arr = array("city", 'add-city', "save-city");
$event_categoryArr = array("event-category", 'add-category', 'update-category', "save-category");
$membershipArr = array("membership", "membership-purchase", "purchase-details");
$messageArr = array("message", "message-action");
$appointment_reportArr = array("appointment-report");
$payout_reportArr = array("payout-request", "wallet");
$payment_history_array = array("appointment-payments");
$IntegrateOn_WebpageArr = array("IntegrateOn_Webpage");
$service_categoryArr = array("service-category", "add-service-category", "update-service-category", "save-service-category",);
$serviceArr = array("manage-service", "add-service", "update-service", "save-service", "manage-service-addons", "update-service-addons", "add-service-addons");
$event_booking_array = array("event-booking", "view-event-booking-details");
if (isset($url_segment) && in_array($url_segment, $eventArr)) {
    $event_open = "open";
    $event_active = "active";
} elseif (isset($url_segment) && in_array($url_segment, $payment_history_array)) {
    $payment_history_active = "active";
} elseif (isset($url_segment) && in_array($url_segment, $event_categoryArr)) {
    $event_open = "open";
    $event_category_active = "active";
} elseif (isset($url_segment) && in_array($url_segment, $staffArr)) {
    $staff_active = "active";
} elseif (isset($url_segment) && in_array($url_segment, $appointmentArr)) {
    $appointment_active = "active";
} elseif (isset($url_segment) && in_array($url_segment, $sliderArr)) {
    $slider_active = "active";
} elseif (isset($url_segment) && in_array($url_segment, $eventArr)) {
    $event_open = 'open';
    $event_active = "active";
} elseif (isset($url_segment) && $url_segment == 'event-payment') {
    $event_open = 'open';
    $event_payment_active = "active";
} elseif (isset($url_segment) && in_array($url_segment, $event_booking_array)) {
    $event_open = 'open';
    $event_booking_active = "active";
} elseif (isset($url_segment) && in_array($url_segment, $event_categoryArr)) {
    $event_open = 'open';
    $event_category_active = "active";
} elseif (isset($url_segment) && in_array($url_segment, $holiday_array)) {
    $service_open = 'open';
    $holiday_active = "active";
} elseif (isset($url_segment) && in_array($url_segment, $service_categoryArr)) {
    $service_open = 'open';
    $service_category_active = "active";
} elseif (isset($url_segment) && in_array($url_segment, $serviceArr)) {
    $service_open = 'open';
    $service_list_active = "active";
} elseif (isset($url_segment) && in_array($url_segment, $eventCouponArr)) {
    $service_open = 'open';
    $service_coupon_active = "active";
} elseif (isset($url_segment) && in_array($url_segment, $payout_reportArr)) {
    $payout_request_active = "active";
} elseif (isset($url_segment) && in_array($url_segment, $membershipArr)) {
    $membership_active = "active";
} elseif (isset($url_segment) && in_array($url_segment, $messageArr)) {
    $message_active = "active";
} elseif (isset($url_segment) && in_array($url_segment, $appointment_reportArr)) {
    $appointment_report_active = "active";
} elseif (isset($url_segment) && in_array($url_segment, $location_active_Arr)) {
    $master_open = "open";
    $location_active = "active";
} elseif (isset($url_segment) && ($url_segment == "contact-us")) {
    $contact_active = "active";
} elseif (isset($url_segment) && in_array($url_segment, $city_active_Arr)) {
    $master_open = "open";
    $city_active = "active";
} elseif (isset($url_segment) && ($url_segment == "integrateon-webpage")) {
    $IntegrateOn_Webpage_active = "active";
} elseif (isset($url_segment) && ($url_segment == "service-appointment-report")) {
    $report_open = "open";
    $service_appointment_report_active = "active";
} elseif (isset($url_segment) && ($url_segment == "event-booking-report")) {
    $report_open = "open";
    $event_booking_report_active = "active";
} else {
    $dashboard_active = "active";
}

/* Vendor Setting */
$app_vendor_setting_data = app_vendor_setting();
?>

<div id="dashboard-options-menu" class="side-bar dashboard left closed">
    <div class="svg-plus">
        <img src="<?php echo base_url() . img_path; ?>/sidebar/close-icon.png" alt="close"/>
    </div>
    <div class="side-bar-header">
        <div class="user-quickview text-center px-2">
            <div class="outer-ring">
                <a href="<?php echo base_url(); ?>">
                    <figure style="width: 200px;height: 60px">
                        <img src="<?php echo get_CompanyLogo(); ?>" alt='side profile' class="img-fluid w-auto"/>
                    </figure>
                </a>
            </div>
            <p class="user-name"></p>
        </div>
    </div>
    <ul class="sidebar-menu">
        <li class="<?php echo $dashboard_active; ?>">
            <a href="<?php echo base_url('vendor/dashboard'); ?>" class="border-color">
                <i class="fa fa-dashboard"></i>
                <?php echo translate('dashboard'); ?>                   
            </a>
        </li>
        <li class="<?php echo isset($staff_active) ? $staff_active : ""; ?>">
            <a href="<?php echo base_url('vendor/staff'); ?>" class="border-color">
                <i class="fa fa-user-plus"></i>
                <?php echo translate('my_staff'); ?>                           
            </a>
        </li>
        <?php if (get_site_setting('enable_service') == 'Y'): ?>
            <li class="<?php echo $service_list_active . $holiday_active . $service_category_active . $service_coupon_active . $appointment_active . $payment_history_active; ?>">
                <a href="javascript:void(0)" class="border-color">
                    <i class="fa fa-calendar"></i>
                    <?php echo translate('service'); ?>
                    <span><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="sidebar-submenu">
                    <li class="<?php echo $service_list_active; ?>">
                        <a href="<?php echo base_url('vendor/manage-service'); ?>"> 
                            <i class="fa fa-list-alt pr-2"></i>
                            <?php echo translate('service_list'); ?>
                        </a>
                    </li>
                    <?php if (isset($app_vendor_setting_data['allow_service_category']) && $app_vendor_setting_data['allow_service_category'] == "Y"): ?>
                        <li class="<?php echo $service_category_active; ?>">
                            <a href="<?php echo base_url('vendor/service-category'); ?>"> 
                                <i class="fa fa-caret-square-o-up pr-2"></i>
                                <?php echo translate('service_category'); ?>
                            </a>
                        </li>  
                    <?php endif; ?>
                    <li class="<?php echo $service_coupon_active; ?>">
                        <a href="<?php echo base_url('vendor/manage-coupon'); ?>"> 
                            <i class="fa fa-percent pr-2"></i>
                            <?php echo translate('event_coupon'); ?>
                        </a>
                    </li>                
                    <li class="<?php echo $appointment_active; ?>">
                        <a href="<?php echo base_url('vendor/manage-appointment'); ?>"> 
                            <i class="fa fa-bookmark-o pr-2"></i>
                            <?php echo translate('appointment'); ?>
                        </a>
                    </li>                
                    <li class="<?php echo $payment_history_active; ?>">
                        <a href="<?php echo base_url('vendor/appointment-payments'); ?>"> 
                            <i class="fa fa-credit-card pr-2"></i>
                            <?php echo translate('appointment_payment_history'); ?>
                        </a>
                    </li>
                    <li class="<?php echo $holiday_active; ?>">
                        <a href="<?php echo base_url('vendor/holiday'); ?>">
                            <i class="fa fa-gift pr-2"></i>
                            <?php echo translate('holiday'); ?>
                        </a>
                    </li>
                </ul>
            </li>
        <?php endif; ?>
        <?php if (get_site_setting('enable_event') == 'Y'): ?>
            <li class="<?php echo $event_active . $event_category_active . $event_booking_active . $event_payment_active; ?>">
                <a href="javascript:void(0)" class="border-color">
                    <i class="fa fa-server"></i>
                    <?php echo translate('event'); ?>
                    <span><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="sidebar-submenu">
                    <li class="<?php echo $event_active; ?>">
                        <a href="<?php echo base_url('vendor/manage-event'); ?>"> 
                            <i class="fa fa-list-ul pr-2"></i>
                            <?php echo translate('event'); ?>
                        </a>
                    </li>
                    <?php if (isset($app_vendor_setting_data['allow_event_category']) && $app_vendor_setting_data['allow_event_category'] == "Y"): ?>
                        <li class="<?php echo $event_category_active; ?>">
                            <a href="<?php echo base_url('vendor/event-category'); ?>"> 
                                <i class="fa fa-caret-square-o-up pr-2"></i>
                                <?php echo translate('event_category'); ?>
                            </a>
                        </li>
                    <?php endif; ?>
                    <li class="<?php echo $event_booking_active; ?>">
                        <a href="<?php echo base_url('vendor/event-booking'); ?>"> 
                            <i class="fa fa-bookmark-o pr-2"></i>
                            <?php echo translate('event') . " " . translate('booking'); ?>
                        </a>
                    </li>
                    <li class="<?php echo $event_payment_active; ?>">
                        <a href="<?php echo base_url('vendor/event-payment'); ?>"> 
                            <i class="fa fa-credit-card-alt pr-2"></i>
                            <?php echo translate('event') . " " . translate('payment'); ?>
                        </a>
                    </li> 
                </ul>
            </li>
        <?php endif; ?>

        <?php if (get_site_setting('enable_membership') == 'Y'): ?>
            <li class="<?php echo $membership_active; ?>">
                <a href="<?php echo base_url('vendor/membership'); ?>" class="border-color">
                    <i class="fa fa-history"></i>
                    <?php echo translate('membership') . " " . translate('payment'); ?>                        
                </a>
            </li>
        <?php endif; ?>

        <li class="<?php echo $payout_request_active; ?>">
            <a href="<?php echo base_url('vendor/wallet'); ?>" class="border-color">
                <i class="fa fa-dollar"></i>
                <?php echo translate('wallet'); ?>                        
            </a>
        </li>

        <li class="<?php echo $slider_active; ?>">
            <a href="<?php echo base_url('vendor/manage-slider'); ?>" class="border-color">
                <i class="fa fa-sliders"></i>
                <?php echo translate('gallery_image'); ?>                         
            </a>
        </li>

<!--        <li class="<?php echo $message_active; ?>">
            <a href="<?php echo base_url('vendor/message'); ?>" class="border-color">
                <i class="fa fa-envelope"></i>
        <?php echo translate('message'); ?>                        
            </a>
        </li>-->

        <li class="<?php echo isset($contact_active) ? $contact_active : ""; ?>">
            <a href="<?php echo base_url('vendor/contact-us'); ?>" class="border-color">
                <i class="fa fa-mail-forward"></i>
                <?php echo translate('event_inquiry'); ?>                            
            </a>
        </li>
        <li class="<?php echo $event_booking_report_active . $appointment_report_active . $service_appointment_report_active; ?>">
            <a href="javascript:void(0)" class="border-color">
                <i class="fa fa-line-chart"></i>
                <?php echo translate('report'); ?>
                <span><i class="fa fa-angle-left pull-right"></i></span>
            </a>
            <ul class="sidebar-submenu">
                <?php if (get_site_setting('enable_service') == 'Y'): ?>
                    <li class="<?php echo $appointment_report_active; ?>">
                        <a href="<?php echo base_url('vendor/appointment-report'); ?>"> 
                            <i class="fa fa-bookmark pr-2"></i>
                            <?php echo translate('appointment_report'); ?>
                        </a>
                    </li>
                    <li class="<?php echo $service_appointment_report_active; ?>">
                        <a href="<?php echo base_url('vendor/service-appointment-report'); ?>"> 
                            <i class="fa fa-bookmark pr-2"></i>
                            <?php echo translate('service') . " " . translate('appointment'); ?>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (get_site_setting('enable_event') == 'Y'): ?>
                    <li class="<?php echo $event_booking_report_active; ?>">
                        <a href="<?php echo base_url('vendor/event-booking-report'); ?>"> 
                            <i class="fa fa-bookmark pr-2"></i>
                            <?php echo translate('event') . " " . translate('booking'); ?>
                        </a>
                    </li>  
                <?php endif; ?>
            </ul>
        </li>


        <li class="<?php echo $IntegrateOn_Webpage_active; ?>">
            <a href="<?php echo base_url('vendor/integrateon-webpage'); ?>"> 
                <i class="fa fa-laptop pr-2"></i>
                <?php echo translate('integrateon_webpage'); ?>
            </a>
        </li>
        <?php if (isset($app_vendor_setting_data['allow_city_location']) && $app_vendor_setting_data['allow_city_location'] == "Y"): ?>
            <li class="<?php echo $city_active . $location_active; ?>">
                <a href="javascript:void(0)" class="border-color">
                    <i class="fa fa-cog"></i>
                    <span><?php echo translate('master'); ?></span>
                    <span><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="sidebar-submenu">
                    <li class="<?php echo $city_active; ?>">
                        <a href="<?php echo base_url('vendor/city'); ?>"> 
                            <i class="fa fa-gears pr-2"></i>
                            <?php echo translate('city'); ?>
                        </a>
                    </li>                
                    <li class="<?php echo $location_active; ?>">
                        <a href="<?php echo base_url('vendor/location'); ?>"> 
                            <i class="fa fa-map-marker pr-2"></i>
                            <?php echo translate('location'); ?>
                        </a>
                    </li>
                </ul>
            </li>
        <?php endif; ?>
    </ul>
</div>
<!-- End Sidebar -->

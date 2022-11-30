<?php
$url_segment = trim($this->uri->segment(2));
$service_appointment_report_active = "";
$holiday_active = "";
$event_booking_report_active = "";
$dashboard_active = "";
$service_category_active = "";
$customer_active = "";
$vendor_active = "";
$event_active = "";
$event_category_active = "";
$event_coupon_active = "";
$service_coupon_active = "";
$slider_active = "";
$package_active = "";
$appointment_active = "";
$sitesetting_open = "";
$sitesetting_active = "";
$sitesetting_email_active = "";
$sitesetting_display_active = "";
$sitesetting_business_active = "";
$city_active = "";
$location_active = $testimonial_active = "";
$master_open = "";
$vendor_open = "";
$package_open = "";
$package_payment_active = "";
$vendor_payment_active = "";
$vendor_unverified_active = "";
$report_open = "";
$payment_history_active = "";
$vendor_report_active = "";
$customer_report_active = "";
$appointment_report_active = "";
$message_active = "";
$event_open = '';
$language_active = $language_open = '';
$payment_setting_active = '';
$payout_request_active = '';
$faq_active = '';
$content_active = '';
$service_list_active = "";
$service_open = '';
$contact_active = '';
$event_inquiry_active = '';
$IntegrateOn_Webpage_active = '';
$event_booking_active = $event_payment_active = $currency_active = '';

$customerArr = array("customer", "add-customer", 'update-customer', 'save-customer', 'delete-customer', 'customer-booking');
$staffArr = array("staff", "add-staff", 'update-staff', 'save-staff', 'delete-staff', 'staff-booking');
$vendorArr = array("vendor", "add-vendor", 'update-vendor', 'save-vendor', 'delete-vendor');
$eventArr = array("manage-event", "add-event", 'update-event', 'save-event', 'delete-event');
$holiday_array = array("holiday", "add-holiday", 'update-holiday', 'save-holiday', 'delete-holiday');
$eventCouponArr = array("manage-coupon", "add-coupon", 'update-coupon', 'save-coupon', 'delete-coupon');
$sliderArr = array("manage-slider", "add-slider", 'update-slider', 'save-slider', 'delete-slider');
$packageArr = array("manage-package", "package-payment", "add-package", 'update-package', 'save-package', 'delete-package');
$appointmentArr = array("manage-appointment", "view-booking-details", "change-booking-time");
$sitesetting_active_Arr = array("sitesetting", "save-sitesetting");
$displaysetting_active_Arr = array("display-setting", "save-display-setting");
$businesssetting_active_Arr = array("business-setting", "save-business--setting");
$sitesetting_email_Arr = array("email-setting", "save-email-setting");
$location_active_Arr = array("location", 'add-location', 'update-location', "save-location");
$testimonial_active_Arr = array("testimonial", 'add-testimonial', 'update-testimonial', "save-testimonial");
$city_active_Arr = array("manage-city", "city", 'add-city', 'update-city', "save-city");
$currency_active_Arr = array("currency", 'add-currency', 'update-currency', "save-currency");

$event_categoryArr = array("event-category", 'add-category', 'update-category', "save-category");
$service_categoryArr = array("service-category", "add-service-category", "update-service-category", "save-service-category");
$serviceArr = array("manage-service", "add-service", "update-service", "save-service", "save-service-addons", "manage-service-addons", "add-service-addons", "update-service-addons");
$faq_arr = array("add-faq", 'update-faq', 'manage-faq', "faq-delete", "save-faq");

$package_paymentArr = array("package-payment");
$vendor_paymentArr = array("payout-request");
$vendor_reportArr = array("report");
$customer_reportArr = array("customer-report");
$payment_history_array = array("payment-history");
$appointment_reportArr = array("appointment-report");
$messageArr = array("message", "message-action");
$contentArr = array("page_content", "save-content", "manage-content", "add-content", "update-content", "save-content", "delete-content");
$payment_settingArr = array("payment-setting", "save-payment-setting-save");
$language_settingArr = array("language", "manage-language", "language-setting", "update-language", "add-language", "save-language", "language-translate");
$IntegrateOn_WebpageArr = array("integrateon-webpage");
$earning_report_active = "";
if (isset($url_segment) && in_array($url_segment, $customerArr)) {
    $customer_active = "active";
} elseif (isset($url_segment) && in_array($url_segment, $vendorArr)) {
    $vendor_open = "open";
    $vendor_active = "active";
} elseif (isset($url_segment) && in_array($url_segment, $language_settingArr)) {
    $language_open = "open";
    $language_active = "active";
} elseif (isset($url_segment) && in_array($url_segment, $vendor_paymentArr)) {
    $vendor_open = "open";
    $vendor_payment_active = "active";
} elseif (isset($url_segment) && in_array($url_segment, $staffArr)) {
    $staff_active = "active";
} elseif (isset($url_segment) && in_array($url_segment, $payment_history_array)) {
    $payment_history_active = "active";
} elseif (isset($url_segment) && in_array($url_segment, $eventArr)) {
    $event_open = 'open';
    $event_active = "active";
} elseif (isset($url_segment) && $url_segment == 'unverified-vendor') {
    $vendor_open = 'open';
    $vendor_unverified_active = "active";
} elseif (isset($url_segment) && $url_segment == 'event-payment') {
    $event_open = 'open';
    $event_payment_active = "active";
} elseif (isset($url_segment) && ($url_segment == 'event-booking' || $url_segment == 'view-event-booking-details')) {
    $event_open = 'open';
    $event_booking_active = "active";
} elseif (isset($url_segment) && in_array($url_segment, $event_categoryArr)) {
    $event_open = 'open';
    $event_category_active = "active";
} elseif (isset($url_segment) && in_array($url_segment, $service_categoryArr)) {
    $service_open = 'open';
    $service_category_active = "active";
} elseif (isset($url_segment) && in_array($url_segment, $serviceArr)) {
    $service_open = 'open';
    $service_list_active = "active";
} elseif (isset($url_segment) && in_array($url_segment, $eventCouponArr)) {
    $service_open = 'open';
    $service_coupon_active = "active";
} elseif (isset($url_segment) && in_array($url_segment, $holiday_array)) {
    $service_open = 'open';
    $holiday_active = "active";
} elseif (isset($url_segment) && in_array($url_segment, $sliderArr)) {
    $master_open = "open";
    $slider_active = "active";
} elseif (isset($url_segment) && in_array($url_segment, $packageArr)) {
    $package_open = "open";
    $package_active = "active";
} elseif (isset($url_segment) && in_array($url_segment, $package_paymentArr)) {
    $package_open = "open";
    $package_payment_active = "active";
} elseif (isset($url_segment) && in_array($url_segment, $vendor_reportArr)) {
    $report_open = "open";
    $vendor_report_active = "active";
} elseif (isset($url_segment) && in_array($url_segment, $customer_reportArr)) {
    $report_open = "open";
    $customer_report_active = "active";
} elseif (isset($url_segment) && ($url_segment == "earnings-report")) {
    $report_open = "open";
    $earning_report_active = "active";
} elseif (isset($url_segment) && in_array($url_segment, $appointment_reportArr)) {
    $report_open = "open";
    $appointment_report_active = "active";
} elseif (isset($url_segment) && in_array($url_segment, $appointmentArr)) {
    $service_open = 'open';
    $appointment_active = "active";
} elseif (isset($url_segment) && in_array($url_segment, $messageArr)) {
    $message_active = "active";
} elseif (isset($url_segment) && in_array($url_segment, $contentArr)) {
    $content_active = "active";
} elseif (isset($url_segment) && in_array($url_segment, $payment_settingArr)) {
    $sitesetting_open = "open";
    $payment_setting_active = "active";
} elseif (isset($url_segment) && in_array($url_segment, $sitesetting_active_Arr)) {
    $sitesetting_open = "open";
    $sitesetting_active = "active";
} elseif (isset($url_segment) && in_array($url_segment, $sitesetting_email_Arr)) {
    $sitesetting_open = "open";
    $sitesetting_email_active = "active";
} elseif (isset($url_segment) && in_array($url_segment, $displaysetting_active_Arr)) {
    $sitesetting_open = "open";
    $sitesetting_display_active = "active";
} elseif (isset($url_segment) && in_array($url_segment, $businesssetting_active_Arr)) {
    $sitesetting_open = "open";
    $sitesetting_business_active = "active";
} elseif (isset($url_segment) && in_array($url_segment, $location_active_Arr)) {
    $master_open = "open";
    $location_active = "active";
} elseif (isset($url_segment) && in_array($url_segment, $testimonial_active_Arr)) {
    $master_open = "open";
    $testimonial_active = "active";
} elseif (isset($url_segment) && in_array($url_segment, $city_active_Arr)) {
    $master_open = "open";
    $city_active = "active";
} elseif (isset($url_segment) && in_array($url_segment, $currency_active_Arr)) {
    $master_open = "open";
    $currency_active = "active";
} elseif (isset($url_segment) && in_array($url_segment, $faq_arr)) {
    $master_open = "open";
    $faq_active = "active";
} elseif (isset($url_segment) && ($url_segment == "contact-us")) {
    $contact_active = "active";
    $contact_open = "open";
} elseif (isset($url_segment) && ($url_segment == "event-inquiry")) {
    $event_inquiry_active = "active";
    $contact_open = "open";
} elseif (isset($url_segment) && ($url_segment == "integrateon-webpage")) {
    $IntegrateOn_Webpage_active = "active";
} elseif (isset($url_segment) && ($url_segment == "event-booking-report")) {
    $report_open = "open";
    $event_booking_report_active = "active";
} elseif (isset($url_segment) && ($url_segment == "service-appointment-report")) {
    $report_open = "open";
    $service_appointment_report_active = "active";
} else {
    $dashboard_active = "active";
}
?>

<div id="dashboard-options-menu" class="side-bar dashboard left closed">
    <div class="svg-plus">
        <img src="<?php echo base_url() . img_path; ?>/sidebar/close-icon.png" alt="close" />
    </div>
    <div class="side-bar-header">
        <div class="user-quickview text-center px-2">
            <div class="outer-ring">
                <a href="<?php echo base_url(); ?>">
                    <figure style="width: 200px;height: 60px">
                        <img src="<?php echo get_CompanyLogo(); ?>" alt='side profile' class="img-fluid w-auto" />
                    </figure>
                </a>
            </div>
        </div>
    </div>

    <ul class="sidebar-menu">
        <li class="<?php echo $dashboard_active; ?>">
            <a href="<?php echo base_url('admin/dashboard'); ?>" class="border-color">
                <i class="fa fa-dashboard"></i>
                <?php echo translate('dashboard'); ?>
            </a>
        </li>

        <li
            class="<?php echo $customer_active . $vendor_active . $vendor_payment_active . $vendor_unverified_active; ?>">
            <a href="javascript:void(0)" class="border-color">
                <i class="fa fa-share"></i>
                <?php echo translate('users'); ?>
                <span><i class="fa fa-angle-left pull-right"></i></span>
            </a>
            <ul class="sidebar-submenu">
                <li class="<?php echo $customer_active; ?>">
                    <a href="<?php echo base_url('admin/customer'); ?>">
                        <i class="fa fa-user pr-2"></i>
                        <?php echo translate('customer'); ?>
                    </a>
                </li>
                <?php if (get_site_setting('is_display_vendor') == 'Y'): ?>
                    <li class="<?php echo $vendor_active . $vendor_payment_active . $vendor_unverified_active; ?>">
                        <a href="javascript:void(0)">
                            <i class="fa fa-user-plus pr-2"></i>
                            <?php echo translate('vendor'); ?>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="sidebar-submenu">
                            <li class="<?php echo $vendor_active; ?>">
                                <a href="<?php echo base_url('admin/vendor'); ?>">
                                    <?php echo translate('vendor'); ?>
                                </a>
                            </li>
                            <li class="<?php echo $vendor_unverified_active; ?>">
                                <a href="<?php echo base_url('admin/unverified-vendor'); ?>">
                                    <?php echo translate('unverified') . " " . translate('vendor'); ?>
                                </a>
                            </li>
                            <li class="<?php echo $vendor_payment_active; ?>">
                                <a href="<?php echo base_url('admin/payout-request'); ?>">
                                    <?php echo translate('payout_request'); ?>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
        </li>

        <li class="<?php echo isset($staff_active) ? $staff_active : ""; ?>">
            <a href="<?php echo base_url('admin/staff'); ?>" class="border-color">
                <i class="fa fa-user-plus"></i>
                <?php echo translate('my_staff'); ?>
            </a>
        </li>


        <?php if (get_site_setting('enable_service') == 'Y'): ?>
            <li
                class="<?php echo $service_list_active . $holiday_active . $service_category_active . $service_coupon_active . $appointment_active . $payment_history_active; ?>">
                <a href="javascript:void(0)" class="border-color">
                    <i class="fa fa-calendar"></i>
                    <?php echo translate('service'); ?>
                    <span><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="sidebar-submenu">
                    <li class="<?php echo $service_list_active; ?>">
                        <a href="<?php echo base_url('admin/manage-service'); ?>">
                            <i class="fa fa-list-alt pr-2"></i>
                            <?php echo translate('service'); ?>
                        </a>
                    </li>
                    <li class="<?php echo $service_category_active; ?>">
                        <a href="<?php echo base_url('admin/service-category'); ?>">
                            <i class="fa fa-caret-square-o-up pr-2"></i>
                            <?php echo translate('service_category'); ?>
                        </a>
                    </li>
                    <li class="<?php echo $service_coupon_active; ?>">
                        <a href="<?php echo base_url('admin/manage-coupon'); ?>">
                            <i class="fa fa-percent pr-2"></i>
                            <?php echo translate('event_coupon'); ?>
                        </a>
                    </li>
                    <li class="<?php echo $appointment_active; ?>">
                        <a href="<?php echo base_url('admin/manage-appointment'); ?>">
                            <i class="fa fa-bookmark-o pr-2"></i>
                            <?php echo translate('appointment'); ?>
                        </a>
                    </li>
                    <li class="<?php echo $payment_history_active; ?>">
                        <a href="<?php echo base_url('admin/payment-history'); ?>">
                            <i class="fa fa-credit-card pr-2"></i>
                            <?php echo translate('appointment_payment_history'); ?>
                        </a>
                    </li>
                    <li class="<?php echo $holiday_active; ?>">
                        <a href="<?php echo base_url('admin/holiday'); ?>">
                            <i class="fa fa-gift pr-2"></i>
                            <?php echo translate('holiday'); ?>
                        </a>
                    </li>
                </ul>
            </li>
        <?php endif; ?>

        <?php if (get_site_setting('enable_event') == 'Y'): ?>
            <li
                class="<?php echo $event_active . $event_category_active . $event_booking_active . $event_payment_active; ?>">
                <a href="javascript:void(0)" class="border-color">
                    <i class="fa fa-server"></i>
                    <?php echo translate('event'); ?>
                    <span><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="sidebar-submenu">
                    <li class="<?php echo $event_active; ?>">
                        <a href="<?php echo base_url('admin/manage-event'); ?>">
                            <i class="fa fa-list-ul pr-2"></i>
                            <?php echo translate('event'); ?>
                        </a>
                    </li>
                    <li class="<?php echo $event_category_active; ?>">
                        <a href="<?php echo base_url('admin/event-category'); ?>">
                            <i class="fa fa-caret-square-o-up pr-2"></i>
                            <?php echo translate('event_category'); ?>
                        </a>
                    </li>
                    <li class="<?php echo $event_booking_active; ?>">
                        <a href="<?php echo base_url('admin/event-booking'); ?>">
                            <i class="fa fa-bookmark-o pr-2"></i>
                            <?php echo translate('event') . " " . translate('booking'); ?>
                        </a>
                    </li>
                    <li class="<?php echo $event_payment_active; ?>">
                        <a href="<?php echo base_url('admin/event-payment'); ?>">
                            <i class="fa fa-credit-card-alt pr-2"></i>
                            <?php echo translate('event') . " " . translate('payment'); ?>
                        </a>
                    </li>
                </ul>
            </li>
        <?php endif; ?>



        <li
            class="<?php echo $vendor_report_active . $earning_report_active . $customer_report_active . $event_booking_report_active . $appointment_report_active . $service_appointment_report_active; ?>">
            <a href="javascript:void(0)" class="border-color">
                <i class="fa fa-line-chart"></i>
                <?php echo translate('report'); ?>
                <span><i class="fa fa-angle-left pull-right"></i></span>
            </a>
            <ul class="sidebar-submenu">
                <?php if (get_site_setting('is_display_vendor') == 'Y'): ?>
                    <li class="<?php echo $vendor_report_active; ?>">
                        <a href="<?php echo base_url('admin/report'); ?>">
                            <i class="fa fa-user pr-2"></i>
                            <?php echo translate('vendor_report'); ?>
                        </a>
                    </li>
                <?php endif; ?>
                <li class="<?php echo $customer_report_active; ?>">
                    <a href="<?php echo base_url('admin/customer-report'); ?>">
                        <i class="fa fa-users pr-2"></i>
                        <?php echo translate('customer_report'); ?>
                    </a>
                </li>
                <?php if (get_site_setting('enable_service') == 'Y'): ?>
                    <li class="<?php echo $service_appointment_report_active; ?>">
                        <a href="<?php echo base_url('admin/service-appointment-report'); ?>">
                            <i class="fa fa-bookmark pr-2"></i>
                            <?php echo translate('service') . " " . translate('appointment'); ?>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (get_site_setting('enable_event') == 'Y'): ?>
                    <li class="<?php echo $event_booking_report_active; ?>">
                        <a href="<?php echo base_url('admin/event-booking-report'); ?>">
                            <i class="fa fa-bookmark pr-2"></i>
                            <?php echo translate('event') . " " . translate('booking'); ?>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </li>

        <!--        <li class="<?php echo $message_active; ?>">
            <a href="<?php echo base_url('admin/message'); ?>" class="border-color">
                <i class="fa fa-envelope"></i>
        <?php echo translate('message'); ?>                      
            </a>
        </li>-->

        <li class="<?php echo $content_active; ?>">
            <a href="<?php echo base_url('admin/manage-content'); ?>" class="border-color">
                <i class="fa fa-file"></i>
                <span> <?php echo translate('content_management'); ?></span>
            </a>
        </li>

        <li class="<?php echo $contact_active . $event_inquiry_active; ?>">
            <a href="javascript:void(0)" class="border-color">
                <i class="fa fa-mail-forward"></i>
                <?php echo translate('contact-us'); ?>
                <span><i class="fa fa-angle-left pull-right"></i></span>
            </a>
            <ul class="sidebar-submenu">
                <li class="<?php echo isset($contact_active) ? $contact_active : ""; ?>">
                    <a href="<?php echo base_url('admin/contact-us'); ?>">
                        <i class="fa fa-mail-forward pr-2"></i>
                        <?php echo translate('contact-us-request'); ?>
                    </a>
                </li>
                <li class="<?php echo isset($event_inquiry_active) ? $event_inquiry_active : ""; ?>">
                    <a href="<?php echo base_url('admin/event-inquiry'); ?>">
                        <i class="fa fa-mail-forward pr-2"></i>
                        <?php echo translate('event_inquiry'); ?>
                    </a>
                </li>
            </ul>
        </li>

        <li
            class="<?php echo $sitesetting_active . $sitesetting_email_active . $sitesetting_business_active . $sitesetting_display_active . $payment_setting_active; ?>">
            <a href="<?php echo base_url('admin/sitesetting'); ?>" class="border-color">
                <i class="fa fa-cog"></i>
                <span><?php echo translate('site_setting'); ?></span>
            </a>
        </li>

        <li class="<?php echo $IntegrateOn_Webpage_active; ?>">
            <a href="<?php echo base_url('admin/integrateon-webpage'); ?>">
                <i class="fa fa-laptop pr-2"></i>
                <?php echo translate('integrateon_webpage'); ?>
            </a>
        </li>

        <li class="<?php echo $language_active; ?>">
            <a href="<?php echo base_url('admin/manage-language'); ?>" class="border-color">
                <i class="fa fa-language"></i>
                <span><?php echo translate('language_setting'); ?></span>
            </a>
        </li>

        <?php if (get_site_setting('enable_membership') == 'Y'): ?>
            <li class="<?php echo $package_active; ?>">
                <a href="javascript:void(0)" class="border-color">
                    <i class="fa fa-pause"></i>
                    <span><?php echo translate('package'); ?></span>
                    <span><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="sidebar-submenu">
                    <li class="">
                        <a href="<?php echo base_url('admin/manage-package'); ?>">
                            <i class="fa fa-pause pr-2"></i>
                            <?php echo translate('manage') . " " . translate('package'); ?>
                        </a>
                    </li>
                    <li class="">
                        <a href="<?php echo base_url('admin/package-payment'); ?>">
                            <i class="fa fa-dollar pr-2"></i>
                            <?php echo translate('package') . " " . translate('payment'); ?>
                        </a>
                    </li>
                </ul>
            </li>
        <?php endif; ?>
        <li class="<?php echo $city_active . $testimonial_active . $currency_active . $location_active . $slider_active . $faq_active; ?>">
            <a href="javascript:void(0)" class="border-color">
                <i class="fa fa-gears"></i>
                <?php echo translate('master'); ?>
                <span><i class="fa fa-angle-left pull-right"></i></span>
            </a>
            <ul class="sidebar-submenu">
                <li class="<?php echo $currency_active; ?>">
                    <a href="<?php echo base_url('admin/currency'); ?>">
                        <i class="fa fa-dollar pr-2"></i>
                        <?php echo translate('currency'); ?>
                    </a>
                </li>
                <li class="<?php echo $city_active; ?>">
                    <a href="<?php echo base_url('admin/city'); ?>">
                        <i class="fa fa-gears pr-2"></i>
                        <?php echo translate('city'); ?>
                    </a>
                </li>
                <li class="<?php echo $location_active; ?>">
                    <a href="<?php echo base_url('admin/location'); ?>">
                        <i class="fa fa-map-marker pr-2"></i>
                        <?php echo translate('location'); ?>
                    </a>
                </li>
                <?php if (get_site_setting('enable_testimonial') == 'Y'): ?>
                    <li class="<?php echo $testimonial_active; ?>">
                        <a href="<?php echo base_url('admin/testimonial'); ?>">
                            <i class="fa fa-chain pr-2"></i>
                            <?php echo translate('testimonial'); ?>
                        </a>
                    </li>
                <?php endif; ?>
                <li class="<?php echo $slider_active; ?>">
                    <a href="<?php echo base_url('admin/manage-slider'); ?>">
                        <i class="fa fa-photo pr-2"></i>
                        <?php echo translate('gallery_image'); ?>
                    </a>
                </li>
                <li class="<?php echo $faq_active; ?>">
                    <a href="<?php echo base_url('admin/manage-faq'); ?>">
                        <i class="fa fa-question pr-2"></i>
                        <?php echo translate('faqs'); ?>
                    </a>
                </li>
            </ul>
        </li>

    </ul>

</div>
<!-- End Sidebar -->
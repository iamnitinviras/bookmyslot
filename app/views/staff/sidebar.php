<?php
$url_segment = trim($this->uri->segment(2));
$dashboard_active = $service_coupon_active = "";
$appointment_active = "";
$appointment_report_active = "";


$appointmentArr = array("appointment", "view-booking-details");

if (isset($url_segment) && in_array($url_segment, $appointmentArr)) {
    $appointment_active = "active";
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
            <a href="<?php echo base_url('staff/dashboard'); ?>" class="border-color">
                <i class="fa fa-dashboard"></i>
                <?php echo translate('dashboard'); ?>                   
            </a>
        </li>
        <li class="<?php echo isset($appointment_active) ? $appointment_active : ""; ?>">
            <a href="<?php echo base_url('staff/appointment'); ?>" class="border-color">
                <i class="fa fa-calendar"></i>
                <?php echo translate('appointment'); ?>                           
            </a>
        </li>

    </ul>
</div>
<!-- End Sidebar -->

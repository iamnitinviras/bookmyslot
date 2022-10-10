<?php
$vendor_token = trim($this->uri->segment(3));
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
        <div class="main_content">
            <div class="container">
                <br/>
                <br/>
                <div class="dashboard-body">
                    <!-- Start Content -->
                    <div class="content pt-4">
                        <!-- Start Container -->
                        <div class="container container-min-height">
                            <?php $this->load->view('message'); ?>

                            <div class="card mb-3">
                                <div class="text-center">
                                    <div class="success_box">
                                        <div class="row">
                                            <div class="col-lg-9 mx-auto">
                                                <div class="success_icon">
                                                    <img src="<?php echo base_url('assets/images/thanks.png'); ?>"/>
                                                </div>
                                                <div class="success_text">
                                                    <h2><?php echo translate('thanks_booking'); ?></h2>


                                                    <div class="success-btn">
                                                        <?php if ($event_data['type'] == 'S'): ?>
                                                            <a href="<?php echo base_url('eservices/' . $vendor_token); ?>" class="btn btn-info btn-rounded">
                                                                <i class="fa fa-clock-o"></i>
                                                                <span><?php echo translate('new_booking'); ?></span>
                                                            </a>
                                                        <?php else: ?>
                                                            <a href="<?php echo base_url('eevents/' . $vendor_token); ?>" class="btn btn-info btn-rounded">
                                                                <i class="fa fa-clock-o"></i>
                                                                <span><?php echo translate('new_booking'); ?></span>
                                                            </a>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-3">
                                <div class="row mx-0">
                                    <div class="col-md-3 px-0 m-0">
                                        <div class="image">
                                            <img src="<?php echo $img_src; ?>" class="img-fluid">
                                        </div>
                                    </div>
                                    <div class="col-md-9 event-book_list">
                                        <div class="p-1">
                                            <?php if ($event_data['type'] == 'S'): ?>
                                                <h3 class="text-uppercase"><?php echo translate('appointment_details'); ?></h3>
                                            <?php else: ?>
                                                <h3 class="text-uppercase"><?php echo translate('event') . " " . translate('booking') . " " . translate('details'); ?></h3>
                                            <?php endif; ?>
                                            <div class="appointment_content-list">
                                                <div class="row">
                                                    <div class="col-md-6 my-0">
                                                        <p>
                                                            <span class="badge badge-secondary custom_badge"><?php echo translate('title'); ?></span> :
                                                            <span><?php echo $event_data['title']; ?></span>
                                                        </p>
                                                        <p>
                                                            <span class="badge badge-secondary custom_badge"><?php echo translate('category'); ?></span> :
                                                            <span><?php echo $event_data['category_title']; ?></span>
                                                        </p>
                                                        <?php if ($event_data['type'] == 'S'): ?>
                                                            <p>
                                                                <span class="badge badge-secondary custom_badge"><?php echo translate('slot_time'); ?></span> :
                                                                <span><?php echo convertToHoursMins($event_data['slot_time']); ?></span>
                                                            </p>
                                                        <?php else :
                                                            ?>
                                                            <p>
                                                                <span class="badge badge-secondary custom_badge"><?php echo translate("ticket"); ?></span> :
                                                                <span><?php echo $event_data['event_booked_seat']; ?></span>
                                                            </p>
                                                        <?php endif; ?>
                                                        <p>
                                                            <span class="badge badge-secondary custom_badge"><?php echo translate('city'); ?></span> :
                                                            <span><?php echo $event_data['city_title']; ?></span>
                                                        </p>
                                                    </div>
                                                    <div class="col-md-6 my-0">
                                                        <p>
                                                            <span class="badge badge-secondary custom_badge"><?php echo translate('location'); ?></span> :
                                                            <span><?php echo $event_data['loc_title']; ?></span>
                                                        </p>
                                                        <p>
                                                            <?php if ($event_data['type'] == 'S'): ?>
                                                                <span class="badge badge-secondary custom_badge"><?php echo translate('appointment_date'); ?></span> :
                                                            <?php else : ?>
                                                                <span class="badge badge-secondary custom_badge"><?php echo translate('event') . " " . translate('date'); ?></span> :
                                                            <?php endif; ?>

                                                            <span><?php echo get_formated_date(($event_data['start_date'] . " " . $event_data['start_time'])); ?></span>
                                                        </p>
                                                        <p>
                                                            <span class="badge badge-secondary custom_badge"><?php echo translate('status'); ?></span> :
                                                            <span><?php echo check_appointment_status($event_data['status']); ?></span>
                                                        </p>
                <!--                                        <p>
                                                            <span class="badge badge-secondary custom_badge"><?php echo translate('invoice'); ?></span> :
                                                            <span><a class="btn blue-gradient btn-rounded btn-sm" href="<?php echo base_url(UPLOAD_PATH . "invoice/" . $invoice_path); ?>" download target="_blank"><?php echo translate("download"); ?></a></span>
                                                        </p>-->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>        
                            </div>


                        </div>
                    </div>   
                </div>
            </div>
        </div>
    </body>
</html>
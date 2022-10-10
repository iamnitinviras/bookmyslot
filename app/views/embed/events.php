<?php
$token = trim($this->uri->segment(2));
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

    </head>
    <body>
        <div class="main_content">
            <div class="container">
                <br/>
                <br/>
                <div class="row">
                    <?php
                    if (isset($total_events) && count($total_events) > 0) {
                        foreach ($total_events as $row) {
                            $event_img_file = '';
                            $event_img_Arr = json_decode($row['image']);
                            if (isset($event_img_Arr) && !empty($event_img_Arr)) {
                                $event_img = isset($event_img_Arr[0]) ? $event_img_Arr[0] : '';
                                if ($event_img != '') {
                                    $original_filename = (pathinfo($event_img, PATHINFO_FILENAME));
                                    $original_extension = (pathinfo($event_img, PATHINFO_EXTENSION));
                                    $event_img_file = $original_filename . "_thumb" . "." . $original_extension;
                                }
                            }
                            if (isset($event_img_file) && $event_img_file != "" && file_exists(FCPATH . "assets/uploads/event/" . $event_img_file)) {
                                $img_src = base_url() . UPLOAD_PATH . "event/" . $event_img_file;
                            } else {
                                $img_src = base_url() . UPLOAD_PATH . "event/events.png";
                            }

                            $profile_image = base_url() . img_path . "/user.png";
                            if (isset($row['profile_image']) && $row['profile_image'] != "") {
                                $profile_image = check_profile_image(UPLOAD_PATH . "profiles/" . $row['profile_image']);
                            }
                            $e_start_date = date('Y-m-d', strtotime($row['start_date']));
                            $e_start_time = date('H:i', strtotime($row['start_date']));


                            $e_end_date = date('Y-m-d', strtotime($row['end_date']));
                            $e_end_time = date('H:i', strtotime($row['end_date']));


                            if ($e_start_date == $e_end_date) {
                                $event_date_display = date('d M', strtotime($row['start_date'])) . " " . $e_start_time . "-" . $e_end_time;
                            } else {
                                $event_date_display = date('d M', strtotime($row['start_date'])) . "-" . date('d M', strtotime($row['end_date'])) . " " . $e_start_time . "-" . $e_end_time;
                            }
                            ?>
                            <div class="col-md-3">
                                <div class="card hoverable position-r home_card event-card">
                                    <div class="view overlay">
                                        <a class="d-block" href="<?php echo base_url('event-details/' . ($row['event_slug']) . "/" . $token . '/' . $row['event_id']); ?>">
                                            <img class="card-img-top" src="<?php echo $img_src; ?>">
                                        </a>
                                        <div class="prod_btn">
                                            <a class="d-block" href="<?php echo base_url('eevent-details/' . ($row['event_slug']) . "/" . $token . '/' . $row['event_id']); ?>"></a>
                                            <a href="<?php echo base_url('eevent-details/' . ($row['event_slug']) . "/" . $token . '/' . $row['event_id']); ?>" class="transparent border"><?php echo translate('more_info') ?></a>
                                        </div>
                                        <ul class="titlebtn list-inline inline-ul"><li class="product_cat"><a href="javascript:void(0)" style="text-decoration: none;"><?php echo $row['category_title']; ?></a></li></ul>
                                    </div>
                                    <div class="card-body product-docs pb-5px">
                                        <a class="d-block" href="<?php echo base_url('eevent-details/' . ($row['event_slug']) . "/" . $token . '/' . $row['event_id']); ?>">
                                            <h4 class="card-title"><?php echo $row['title']; ?></h4>
                                            <div class="w-100">
                                                <div class="sell mb-3"> 
                                                    <p>
                                                        <i class="fa fa-map-marker pr-10 text-danger"></i>
                                                        <?php echo $row['city_title']; ?> 
                                                        <span class="location-area"><i><?php echo $row['loc_title']; ?></i></span>
                                                    </p>
                                                    <p>
                                                        <i class="fa fa-calendar pr-0 text-danger"></i>
                                                        <?php echo $event_date_display; ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="product-purchase">
                                        <div class="sell">
                                            <a href="javascript:void(0)" style="text-decoration: none;">
                                                <img class="auth-img" src="<?php echo $profile_image; ?>" alt="">
                                            </a>
                                            <p>
                                                <a href="javascript:void(0)" style="text-decoration: none;">
                                                    <span class="category-title" style="color: #151111"><?php echo $row['company_name']; ?></span>
                                                </a>
                                            </p>
                                        </div>
                                        <div class="price_love">
                                            <p><?php echo get_event_price_by_id($row['event_id'], 'D'); ?></p>
                                            <span><a href="<?php echo base_url('eevent-details/' . ($row['event_slug']) . "/" . $token . '/' . $row['event_id']); ?>" style="text-decoration: none"><?php echo translate('get') . ' ' . translate('ticket'); ?></a></span>
                                            <span class="event_timer_span event_date_time_class" data-enddate="<?php echo date("M d, Y H:i:s", strtotime($row['end_date'])); ?>" data-date="<?php echo date("M d, Y H:i:s", strtotime($row['start_date'])); ?>" data-id="<?php echo $row['event_id']; ?>" id="event<?php echo $row['event_id']; ?>"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        ?>
                        <div class="alert alert-danger text-center w-100">No Events Found</div>
                        <?php
                    }
                    ?>

                </div>
            </div>
        </div>
        <script>
            if ($(".event_date_time_class").length > 0) {
                $(".event_date_time_class").each(function (e) {
                    var event_id = $(this).data('id');
                    var date = $(this).data('date');
                    var enddate = $(this).data('enddate');
                    set_time_count(event_id, date, enddate);
                });
            }
            function set_time_count(event_id, date, enddate) {
                var countDownDate = new Date(date).getTime();
                var countDownEndDate = new Date(enddate).getTime();

                // Update the count down every 1 second
                var x = setInterval(function () {

                    // Get todays date and time
                    var now = new Date().getTime();

                    // Find the distance between now and the count down date
                    var distance = countDownDate - now;
                    var distanceEnd = countDownEndDate - now;

                    // Time calculations for days, hours, minutes and seconds
                    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    // Display the result in the element with id="demo"


                    // If the count down is finished, write some text 

                    if (now > countDownEndDate) {
                        //clearInterval(x);
                        document.getElementById("event" + event_id).innerHTML = "<?php echo translate('expired'); ?>";
                    } else if (now >= countDownDate && now <= countDownEndDate) {
                        document.getElementById("event" + event_id).innerHTML = "<?php echo translate('on_going'); ?>";
                    } else {
                        document.getElementById("event" + event_id).innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";
                    }
                }, 1000);
            }
        </script>
    </body>
</html>
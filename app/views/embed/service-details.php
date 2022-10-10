<?php
$token = trim($this->uri->segment(3));
$event_id = trim($this->uri->segment(4));

$faq = (isset($event_data['faq']) && !empty($event_data['faq'])) ? json_decode($event_data['faq']) : array();
$total_rate_sum = 0;
$get_discount_price_by_date = get_discount_price_by_date($event_data['id'], date('Y-m-d'));
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
    </head>
    <body>
        <div class="main_content">
            <div class="container">
                <br/>
                <br/>
                <style>
                    .h-100px{
                        height: 100px;
                    }
                </style>
                <div class="service_details_content">

                    <div class="header_banner h-100px">           
                        <div class="text-left">
                            <h2 class="blue-grey-text ml-20"><?php echo (trim($event_data['title'])); ?></h2>
                        </div>
                    </div>

                    <input type="hidden" id="address" name="address" value="<?php echo isset($event_data['address']) ? $event_data['address'] : '' ?>"/>
                    <div class="container-min-height">

                        <?php
                        if ($this->session->flashdata('message')) {
                            echo $this->session->flashdata('message');
                        }

                        if (isset($event_data) && count($event_data) > 0 && !empty($event_data['image']) && $event_data['image'] != 'null') {
                            foreach (json_decode($event_data['image']) as $key => $value) {
                                $all_image[] = check_service_image(UPLOAD_PATH . "event/" . $value);
                            }
                        }
                        $profile_image = base_url() . img_path . "/user.png";
                        if (isset($admin_data) && count($admin_data) > 0 && !empty($admin_data['profile_image']) && $admin_data['profile_image'] != 'null') {
                            $profile_image = check_profile_image(UPLOAD_PATH . "profiles/" . $admin_data['profile_image']);
                        }
                        ?>   
                        <div class="py-3">
                            <div class="event_details-content">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="card">
                                            <div class="card-body">
                                                <div>
                                                    <?php $this->load->view('message'); ?>
                                                </div>
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
                                                                <span class="sr-only"><?php echo translate('previous'); ?></span>
                                                            </a>
                                                            <a class="carousel-control-next" href="#carousel-thumb" role="button" data-slide="next">
                                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                                <span class="sr-only"><?php echo translate('next'); ?></span>
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
                                                <div class="mt-2">
                                                    <div class="event-all-list">
                                                        <div class="listing-contact-info">
                                                            <div class="card-header transparent">
                                                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                                                    <li>
                                                                        <a class="nav-link active" data-toggle="tab" href="#details" role="tab"><?php echo translate('description'); ?></a>
                                                                    </li>
                                                                    <li>
                                                                        <a class="nav-link" data-toggle="tab" href="#rating" role="tab"><?php echo translate('rating_review'); ?></a>
                                                                    </li>
                                                                    <?php if (isset($faq) && count($faq) > 0 && !empty($faq)): ?>
                                                                        <li>
                                                                            <a class="nav-link" data-toggle="tab" href="#faqs" role="tab"><?php echo translate('service'); ?> <?php echo translate('faqs'); ?></a>
                                                                        </li>
                                                                    <?php endif; ?>
                                                                </ul>
                                                            </div>

                                                            <div class="py-3">
                                                                <div class="tab-content">
                                                                    <!--Panel 1-->
                                                                    <div class="tab-pane fade in show active" id="details" role="tabpanel">
                                                                        <div class="event-details-text">
                                                                            <?php echo $event_data['description']; ?>
                                                                        </div>
                                                                    </div>
                                                                    <!--/.Panel 1-->

                                                                    <!--Panel 2-->
                                                                    <div class="tab-pane fade" id="rating" role="tabpanel">
                                                                        <div class="rating_list">
                                                                            <?php
                                                                            if (isset($event_rating) && count($event_rating) > 0) {
                                                                                foreach ($event_rating as $key_raing => $row) {
                                                                                    $q_rate = $row['quality_rating'];
                                                                                    $l_rate = $row['location_rating'];
                                                                                    $sp_rate = $row['space_rating'];
                                                                                    $se_rate = $row['service_rating'];
                                                                                    $p_rate = $row['price_rating'];
                                                                                    $total_rating = $q_rate + $l_rate + $sp_rate + $se_rate + $p_rate;

                                                                                    $total_avg = ((5 * $total_rating ) / 25);
                                                                                    $total_rate_sum += $total_avg;
                                                                                    $star_avg_fill_width = 0;
                                                                                    if ($total_avg > 0) {
                                                                                        $star_avg_fill_width = ($total_avg * 100) / 5;
                                                                                    }
                                                                                    ?>
                                                                                    <h4 class="user-name"><?php echo ($row['first_name']) . " " . ($row['last_name']); ?></h4>
                                                                                    <p><?php echo $row['review_comment']; ?></p>
                                                                                    <ul class="list-inline inline-ul">



                                                                                        <div class="rating_content"  title="<?php echo isset($total_avg) && $total_avg > 0 ? $total_avg . " / 5" : ""; ?>">
                                                                                            <div class="black_stars_rating">
                                                                                                <img src="<?php echo base_url() . img_path . '/stars_blank.png'; ?>" alt="img">
                                                                                            </div>
                                                                                            <div class="rating_width" style="width:<?php echo $star_avg_fill_width; ?>%;">
                                                                                                <img src="<?php echo base_url() . img_path . '/stars_full.png'; ?>" alt="">
                                                                                            </div>
                                                                                        </div>
                                                                                    </ul>

                                                                                    <?php if (count($event_rating) != $key_raing + 1) { ?>
                                                                                        <hr>
                                                                                        <?php
                                                                                    }
                                                                                }
                                                                            } else {
                                                                                ?>
                                                                                <i class="fa fa-exclamation-triangle" aria-hidden="true" style="color: red; padding-top: 50px; font-size: 40px; text-align: center; width: 100%;" ></i>
                                                                                <h4 class='no_record'> <?php echo translate('no_record_found'); ?></h4>
                                                                            <?php }
                                                                            ?>
                                                                        </div>
                                                                    </div>
                                                                    <!--/.Panel 2-->



                                                                    <!--Panel 4-->
                                                                    <?php if (isset($faq) && count($faq) > 0 && !empty($faq)): ?>
                                                                        <div class="tab-pane fade" id="faqs" role="tabpanel">
                                                                            <div class="accordion md-accordion" id="accordionfaq" role="tablist" aria-multiselectable="true">
                                                                                <?php foreach ($faq as $key => $val): ?>
                                                                                    <!--Accordion wrapper-->


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
                                                                            </div>
                                                                            <!-- Accordion wrapper -->
                                                                        </div>
                                                                    <?php endif; ?>
                                                                    <!--Panel 4-->


                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 right-sidebar-section">
                                        <div class="card mb-0">
                                            <div class="px-2 py-3">
                                                <div class="user_details">
                                                    <div class="text-center">
                                                        <div class="user-img">
                                                            <img src="<?php echo $profile_image; ?>" alt="profile" class="img-fluid rounded-circle" />
                                                        </div>
                                                        <div class="user_details-info">
                                                            <p class="user-name"><?php echo trim($admin_data['company_name']); ?></p>
                                                            <?php if (isset($admin_data['fb_link']) || isset($admin_data['twitter_link']) || isset($admin_data['google_link'])) { ?>
                                                                <div class="social_icon">
                                                                    <ul class="list-inline inline-ul">
                                                                        <?php if (isset($admin_data['fb_link']) && $admin_data['fb_link'] != '') { ?>
                                                                            <li>
                                                                                <a class="fb-ic" href="<?php echo $admin_data['fb_link']; ?>" target="_blank"><i class="fa fa-facebook"></i></a>
                                                                            </li>
                                                                        <?php } if (isset($admin_data['twitter_link']) && $admin_data['twitter_link'] != '') { ?>
                                                                            <li>
                                                                                <a class="tw-ic" href="<?php echo $admin_data['twitter_link']; ?>" target="_blank"><i class="fa fa-twitter"></i></a>
                                                                            </li>
                                                                        <?php } if (isset($admin_data['google_link']) && $admin_data['google_link'] != '') { ?>
                                                                            <li>
                                                                                <a class="gplus-ic" href="<?php echo $admin_data['google_link']; ?>" target="_blank"><i class="fa fa-google-plus"></i></a>
                                                                            </li>
                                                                        <?php } ?>
                                                                    </ul>
                                                                </div>
                                                            <?php } ?>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="purchase-button btn_list my-1">
                                            <a href="<?php echo base_url('eslots/' . $token . '/' . $event_data['id']); ?>" class="btn btn-dark button_common btn-lg w-100 mx-0 text-center mt-1 mb-1">
                                                <i class="fa fa-ticket"></i>
                                                <?php echo translate('book_your_appointment'); ?>
                                            </a>
                                        </div>

                                        <div class="card listing-contact-info">
                                            <div class="card-header transparent">
                                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                                    <li>
                                                        <a class="nav-link active" id="listing-tab" data-toggle="tab" href="#listing" role="tab" aria-controls="listing" aria-selected="true">
                                                            <?php echo translate('service') . " " . translate('details'); ?>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="nav-link" id="c_small_form-tab" data-toggle="tab" href="#c_small_form" role="tab" aria-controls="c_small_form" aria-selected="false">
                                                            <?php echo translate('contact_vendor'); ?> 
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="card-body">
                                                <div class="tab-content" id="profiletabControl">
                                                    <div class="tab-pane fade show active" id="listing" role="tabpanel" aria-labelledby="listing-tab">
                                                        <div class="listing-info">
                                                            <ul class="list-inline">
                                                                <li>
                                                                    <i class="la la-map-marker"></i>
                                                                    <?php echo $event_data['address']; ?>
                                                                    <?php if (isset($event_data['address_map_link']) && $event_data['address_map_link'] != ''): ?>
                                                                        <a target="_blank" class="btn text-capitalize waves-effect waves-light" style="color: #000 !important;border: 1px solid #d9d9d9;margin-left: 10px" href="<?php echo $event_data['address_map_link']; ?>"><?php echo translate('get_direction'); ?></a>
                                                                    <?php endif;
                                                                    ?>
                                                                </li>
                                                                <li>
                                                                    <i class="la la-list"></i>
                                                                    <?php echo $event_data['category_title']; ?>
                                                                </li>
                                                                <li>
                                                                    <i class="la la-clock-o"></i>
                                                                    <?php echo convertToHoursMins($event_data['slot_time']); ?>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="c_small_form" role="tabpanel" aria-labelledby="c_small_form-tab">
                                                        <div class="small-form">
                                                            <?php
                                                            $attributes = array('id' => 'FrmContact', 'class' => "small-contact-form", 'name' => 'FrmContact', 'method' => "post");
                                                            echo form_open('econtact-action', $attributes);
                                                            echo form_hidden("event_id", $this->uri->segment(4));
                                                            echo form_hidden("v_token", $token);
                                                            echo form_hidden("event_title_hd", ($event_data['event_slug']));
                                                            echo form_hidden("admin_id", isset($admin_data['id']) ? $admin_data['id'] : 0);
                                                            ?>

                                                            <div class="form-group">
                                                                <input required="" class="form-control"  autocomplete="off"  maxlength="100" type="text" placeholder="<?php echo translate('name'); ?>" id="fullname" name="fullname" />
                                                                <?php echo form_error('fullname'); ?>
                                                            </div>
                                                            <div class="form-group">
                                                                <input class="form-control" required="" autocomplete="off"  maxlength="50" type="email" placeholder="<?php echo translate('email'); ?>" id="emailid" name="emailid" />
                                                                <?php echo form_error('emailid'); ?>
                                                            </div>
                                                            <div class="form-group">
                                                                <input required="" class="form-control phone" autocomplete="off"  maxlength="15" type="text" placeholder="<?php echo translate('phone'); ?>" id="phoneno" name="phoneno" />
                                                                <?php echo form_error('phoneno'); ?>
                                                            </div>
                                                            <div class="form-group">
                                                                <textarea  required=""class="form-control"  autocomplete="off" placeholder="<?php echo translate('message'); ?>" id="message" name="message"></textarea>
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
                                        </div>

                                        <div class="card">
                                            <div class="card-body">
                                                <div class="price">
                                                    <h6 class="mb-0">
                                                        <?php echo translate('total_booking'); ?>
                                                    </h6>
                                                    <p class="text-right"><?php echo isset($event_book) ? $event_book : 0; ?></p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card">
                                            <div class="card-body">
                                                <div class="price">
                                                    <h6 class="mb-0">
                                                        <?php echo translate('rating'); ?>
                                                    </h6>
                                                    <p class="text-right">

                                                    <ul class="list-inline inline-ul float-right">
                                                        <?php
                                                        $total_nos = count($event_rating);

                                                        $total_ratings = $total_nos * 5;
                                                        if ($total_nos > 0) {
                                                            $all_avg = (5 * $total_rate_sum) / $total_ratings;
                                                        }
                                                        $total_avg = isset($all_avg) ? $all_avg : 0;


                                                        $star_avg_fill_width = 0;
                                                        if ($total_avg > 0) {
                                                            $star_avg = number_format($total_avg, 2);
                                                            $star_avg_fill_width = ((100 * $star_avg) / 5);
                                                        }
                                                        ?>
                                                        <div class="rating_content"  title="<?php echo isset($total_avg) && $total_avg > 0 ? $total_avg . " / 5" : ""; ?>">
                                                            <div class="black_stars_rating">
                                                                <img src="<?php echo base_url() . img_path . '/stars_blank.png'; ?>" alt="img">
                                                            </div>
                                                            <div class="rating_width" style="width:<?php echo $star_avg_fill_width; ?>%;">
                                                                <img src="<?php echo base_url() . img_path . '/stars_full.png'; ?>" alt="">
                                                            </div>
                                                        </div>
                                                    </ul>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card">
                                            <div class="card-body">
                                                <div class="price">
                                                    <h6 class="mb-0">
                                                        <?php echo translate('price'); ?>
                                                    </h6>
                                                    <?php if (isset($event_data['payment_type']) && $event_data['payment_type'] == 'F'): ?>
                                                        <p class="text-right"><?php echo translate('free'); ?></p>
                                                    <?php else: ?>
                                                        <?php if ($get_discount_price_by_date == $event_data['price']): ?>
                                                            <p class="text-right"><?php echo price_format($event_data['price']); ?></p>
                                                        <?php else: ?>
                                                            <p class="text-right"><?php echo price_format($get_discount_price_by_date); ?> <span class="total_price"><?php echo price_format($event_data['price']); ?></span></p>                                        
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="purchase-button btn_list"></div>                    
                                    <!-- end /.purchase-button -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="<?php echo $this->config->item('js_url'); ?>module/event_details.js"></script>
        <script>
            $('.note-video-clip').each(function () {
                var tmp = $(this).wrap('<p/>').parent().html();
                $(this).parent().html('<div class="embed-responsive embed-responsive-16by9">' + tmp + '</div>');
            });
        </script>
    </body>
</html>


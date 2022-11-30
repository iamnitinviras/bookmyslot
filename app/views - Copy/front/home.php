<?php
include VIEWPATH . 'front/header.php';

$location = $this->uri->segment(2);
$search_txt = $this->input->get('search_as');
$totalRowCount = isset($total_Event) && !empty($total_Event) ? count($total_Event) : 0;
$showLimit = get_site_setting('display_record_per_page');
$select_City = $this->session->userdata('location');
?>
<input type="hidden" id="row" value="<?php echo get_site_setting('display_record_per_page'); ?>">
<input type="hidden" id="all" value="<?php echo isset($total_Event) && !empty($total_Event) ? count($total_Event) : 0; ?>">
<input type="hidden" value="N" id="sort_by" name="sort_by"/>
<input type="hidden" id="slug" name="slug" value="home"/>

<!--Owl Carousel-->
<link href="<?php echo $this->config->item('js_url'); ?>owl-carousel/owl.theme.default.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo $this->config->item('js_url'); ?>owl-carousel/owl.carousel.css" rel="stylesheet" type="text/css"/>
<script src="<?php echo $this->config->item('js_url'); ?>owl-carousel/owl.carousel.min.js" type="text/javascript"></script>

<!--Date-Picker-->
<link href="<?php echo $this->config->item('css_url'); ?>datepicker.css" rel="stylesheet">
<script src="<?php echo $this->config->item('js_url'); ?>datepicker.js"></script>
<?php if (get_site_setting('enable_service') == 'Y'): ?>
    <?php if (get_site_setting('is_display_category') == "Y") { ?>
        <div class="event_slider">
            <div class="container">
                <div class="owl-carousel owl-theme" id="category-slider">                
                    <?php
                    if (isset($Service_Category) && !empty($Service_Category)) {
                        foreach ($Service_Category as $row) {
                            ?>
                            <div class="item">
                                <a href="<?php echo base_url('services?category=' . $row['id']); ?>">
                                    <div class="event_img_title">
                                        <div class="event_img">
                                            <?php
                                            if (file_exists(FCPATH . "assets/uploads/category/" . $row['event_category_image'])) {
                                                $img_src = base_url() . UPLOAD_PATH . "category/" . $row['event_category_image'];
                                            } else {
                                                $img_src = base_url(img_path) . "/categories/default.png";
                                            }
                                            ?>
                                            <img src="<?php echo $img_src; ?>" class="img-fluid"/>
                                        </div>
                                        <h6><?php echo $row['title']; ?></h6>                        
                                    </div>
                                </a>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>            
            </div>
        </div>
    <?php } ?>
    <div class="py-4">
        <div class="container container-min-height">
            <?php $this->load->view('message'); ?>   
            <h3 class="text-center"><?php echo translate('latest_services') . " " . translate('in') . " " . $select_City; ?></h3>
            <?php
            if (isset($total_service) && count($total_service) > 0):
                ?>
                <div class="mb-0 resp_mb-60">
                    <div class="row">
                        <?php
                        foreach ($total_service as $row) {

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

                            if (isset($event_img_file) && $event_img_file != "") {
                                if (file_exists(FCPATH . "assets/uploads/event/" . $event_img_file)) {
                                    $img_src = base_url() . UPLOAD_PATH . "event/" . $event_img_file;
                                } else {
                                    $img_src = base_url(img_path . '/service.jpg');
                                }
                            } else {
                                $img_src = base_url(img_path . '/service.jpg');
                            }

                            /* Vendor Image */
                            $profile_image = base_url() . img_path . "/user.png";
                            if (isset($row['profile_image']) && $row['profile_image'] != "") {
                                $profile_image = check_profile_image(UPLOAD_PATH . "profiles/" . $row['profile_image']);
                            }

                            $get_discount_price_by_date = get_discount_price_by_date($row['id'], date('Y-m-d'));
                            ?>

                            <div class="col-md-3">
                                <div class="card hoverable position-r home_card event-card">
                                    <div class="view overlay">
                                        <a class="d-block" href="<?php echo base_url('service-details/' . ($row['event_slug']) . "/" . $row['event_id']); ?>">
                                            <img class="card-img-top" src="<?php echo $img_src; ?>">
                                        </a>
                                        <div class="prod_btn">
                                            <a class="d-block" href="<?php echo base_url('service-details/' . ($row['event_slug']) . "/" . $row['event_id']); ?>"></a>
                                            <a href="<?php echo base_url('service-details/' . ($row['event_slug']) . "/" . $row['event_id']); ?>" class="transparent border"><?php echo translate('more_info') ?></a>
                                        </div>
                                        <ul class="titlebtn list-inline inline-ul">
                                            <li class="product_cat"><a href="<?php echo base_url('services?category=' . $row['category_id']); ?>" style="text-decoration: none;"><?php echo $row['category_title']; ?></a></li>
                                        </ul>
                                    </div>
                                    <div class="card-body product-docs pb-5px">
                                        <a class="d-block" href="<?php echo base_url('service-details/' . ($row['event_slug']) . "/" . $row['event_id']); ?>">
                                            <h4 class="card-title"><?php echo $row['title']; ?></h4>
                                            <div class="w-100">
                                                <div class="sell mb-3"> 
                                                    <p>
                                                        <i class="fa fa-map-marker pr-10 text-danger"></i>
                                                        <?php echo $row['city_title']; ?> 
                                                        <span class="location-area"><i><?php echo $row['loc_title']; ?></i></span>
                                                    </p>
                                                </div>
                                                <div class="sell mb-3">
                                                    <p>
                                                        <i class="fa fa-clock-o mr-10 text-success"></i><?php echo convertToHoursMins($row['slot_time']); ?>
                                                        <?php if ($get_discount_price_by_date != $row['price']): ?>
                                                            <span class="total_discount"><?php echo number_format($row['discount'], 0) ?>% <?php echo translate('off'); ?></span>
                                                        <?php endif; ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="product-purchase">
                                        <div class="sell">
                                            <a href="<?php echo base_url('v/' . slugify(trim($row['company_name'])) . '/' . $row['created_by']); ?>" style="text-decoration: none;">
                                                <img class="auth-img" src="<?php echo $profile_image; ?>" alt="">
                                            </a>
                                            <p>
                                                <a href="<?php echo base_url('v/' . slugify(trim($row['company_name'])) . '/' . $row['created_by']); ?>" style="text-decoration: none;">
                                                    <span class="category-title" style="color: #151111"><?php echo $row['company_name']; ?></span>
                                                </a>
                                            </p>
                                        </div>
                                        <div class="price_love">
                                            <?php if ($row['payment_type'] == "F"): ?>
                                                <span><?php echo translate('free'); ?></span>
                                                <?php
                                            else:
                                                ?>
                                                <?php if ($get_discount_price_by_date == $row['price']): ?>
                                                    <p class="text-right"><?php echo price_format($row['price']); ?></p>
                                                <?php else: ?>
                                                    <p class="text-right"><?php echo price_format($get_discount_price_by_date); ?> <span class="total_price"><?php echo price_format($row['price']); ?></span></p>                                        
                                                <?php endif; ?>
                                            <?php endif; ?>

                                            <span><a href="<?php echo base_url('day-slots/' . $row['id']); ?>" style="text-decoration: none"><?php echo translate('book') . " " . translate('now'); ?></a></span>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <a href="<?php echo base_url('services'); ?>" class="btn white-text event_page" style="background-color: <?php echo $header_color_code; ?>;" title=""><?php echo translate('discover_more_events') . " " . translate('in'); ?> <?php echo $select_City; ?> <i class="fa fa-angle-right pl-3"></i></a>
                    </div>
                </div>
            <?php else: ?>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <img src="<?php echo base_url() . img_path . "/no-result.png"; ?>" alt="no-result">
                    </div>
                </div>

            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<?php if (get_site_setting('enable_event') == 'Y'): ?>
    <?php if (isset($total_Event) && count($total_Event) > 0): ?>
        <div class="py-4 white">
            <div class="container">

                <?php if (count($events_category)): ?>
                    <div class="event_category">
                        <h3 class="text-center"><?php echo translate('things_to_do_around') ?> <?php echo $select_City; ?></h3>
                        <div class="row">
                            <?php
                            foreach ($events_category as $val):

                                $mainc_image = $val['event_category_image'];

                                if (isset($mainc_image) && $mainc_image != "" && file_exists(FCPATH . uploads_path . '/category/' . $mainc_image)) {
                                    $images_src = base_url(uploads_path . '/category/' . $mainc_image);
                                } else {
                                    $images_src = base_url(img_path . '/event_category.jpg');
                                }
                                ?>
                                <div>

                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="event_c_img">
                                        <img src="<?php echo $images_src; ?>" alt="category img" class="img-fluid" />
                                        <a href="<?php echo base_url('events?category=' . $val['id']); ?>" title="<?php echo $val['title']; ?>">
                                            <div class="overlay">
                                                <p><?php echo $val['title']; ?></p>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                    </div>
                <?php endif; ?>

                <div class="heading_section">
                    <div class="row">
                        <div class="col-md-10 mx-auto text-center">
                            <h3><?php echo translate('latest_events') . " " . translate('in'); ?> <?php echo $this->session->userdata('location'); ?></h3>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <?php
                    foreach ($total_Event as $row) {
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
                        if (isset($event_img_file) && $event_img_file != "") {
                            if (file_exists(FCPATH . "assets/uploads/event/" . $event_img_file)) {
                                $img_src = base_url() . UPLOAD_PATH . "event/" . $event_img_file;
                            } else {
                                $img_src = base_url() . UPLOAD_PATH . "event/events.png";
                            }
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
                                    <a class="d-block" href="<?php echo base_url('event-details/' . ($row['event_slug']) . "/" . $row['event_id']); ?>">
                                        <img class="card-img-top" src="<?php echo $img_src; ?>">
                                    </a>
                                    <div class="prod_btn">
                                        <a class="d-block" href="<?php echo base_url('event-details/' . ($row['event_slug']) . "/" . $row['event_id']); ?>"></a>
                                        <a href="<?php echo base_url('event-details/' . ($row['event_slug']) . "/" . $row['event_id']); ?>" class="transparent border"><?php echo translate('more_info') ?></a>
                                    </div>
                                    <ul class="titlebtn list-inline inline-ul"><li class="product_cat"><a href="<?php echo base_url('events?category=' . $row['category_id']); ?>" style="text-decoration: none;"><?php echo $row['category_title']; ?></a></li></ul>
                                </div>
                                <div class="card-body product-docs pb-5px">
                                    <a class="d-block" href="<?php echo base_url('event-details/' . ($row['event_slug']) . "/" . $row['event_id']); ?>">
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
                                        <a href="<?php echo base_url('v/' . slugify(trim($row['company_name'])) . '/' . $row['created_by']); ?>" style="text-decoration: none;">
                                            <img class="auth-img" src="<?php echo $profile_image; ?>" alt="">
                                        </a>
                                        <p>
                                            <a href="<?php echo base_url('v/' . slugify(trim($row['company_name'])) . '/' . $row['created_by']); ?>" style="text-decoration: none;">
                                                <span class="category-title" style="color: #151111"><?php echo $row['company_name']; ?></span>
                                            </a>
                                        </p>
                                    </div>
                                    <div class="price_love">
                                        <p><?php echo get_event_price_by_id($row['event_id'], 'D'); ?></p>
                                        <span><a href="<?php echo base_url('event-details/' . ($row['event_slug']) . "/" . $row['event_id']); ?>" style="text-decoration: none"><?php echo translate('get') . ' ' . translate('ticket'); ?></a></span>
                                        <span class="event_timer_span event_date_time_class" data-enddate="<?php echo date("M d, Y H:i:s", strtotime($row['end_date'])); ?>" data-date="<?php echo date("M d, Y H:i:s", strtotime($row['start_date'])); ?>" data-id="<?php echo $row['event_id']; ?>" id="event<?php echo $row['event_id']; ?>"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <?php if (isset($total_Event) && count($total_Event) > 0): ?>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <a href="<?php echo base_url('events'); ?>" class="btn white-text event_page" style="background-color: <?php echo $header_color_code; ?>;"  title="<?php echo translate('events'); ?> <?php echo translate('in'); ?> <?php echo $select_City; ?>"><?php echo translate('events'); ?> <?php echo translate('in'); ?> <?php echo $select_City; ?><i class="fa fa-angle-right pl-3"></i></a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>
<?php if (get_site_setting('enable_testimonial') == 'Y'): ?>
    <?php
    $get_app_testimonial = get_app_testimonial();
    if (count($get_app_testimonial) > 0):
        ?>
        <div class="py-4">
            <div class="container">
                <div class="event_category mt-20">
                    <h3 class="text-center"><?php echo translate('testimonial') ?></h3>
                </div>
                <div class="row">
                    <div class="owl-carousel owl-theme" id="testimonial-sliders">                
                        <?php
                        if (isset($get_app_testimonial) && !empty($get_app_testimonial)) {
                            foreach ($get_app_testimonial as $row) {

                                $testimonial_image = $row['image'];
                                if (isset($testimonial_image) && $testimonial_image != "") {
                                    if (file_exists(FCPATH . 'assets/uploads/category/' . $testimonial_image)) {
                                        $testimonial_image_path = base_url("assets/uploads/category/" . $testimonial_image);
                                    } else {
                                        $testimonial_image_path = base_url() . img_path . "/avatar.png";
                                    }
                                } else {
                                    $testimonial_image_path = base_url() . img_path . "/avatar.png";
                                }
                                ?>
                                <div class="testimonial">
                                    <div class="pic">
                                        <img src="<?php echo $testimonial_image_path; ?>" alt="">
                                    </div>
                                    <p class="description"><?php echo $row['details']; ?></p>
                                    <h3 class="title"><?php echo $row['name']; ?></h3>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>  
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>
<?php include VIEWPATH . 'front/footer.php'; ?>
<script>
    $(document).ready(function () {
        if ($('#testimonial-sliders').length > 0) {
            $('#testimonial-sliders').owlCarousel({
                autoplay: false,
                loop: false,
                dots: true,
                responsiveClass: true,
                responsive: {
                    0: {
                        items: 2,
                        slideBy: 2,
                    },

                }

            });

        }

    });
    if ($(".event_date_time_class").length > 0) {
        $(".event_date_time_class").each(function (e) {
            var event_id = $(this).data('id');
            var date = $(this).data('date');
            var enddate = $(this).data('enddate');
            set_time_count(event_id, date, enddate);
        });
    }
</script>
<?php
include VIEWPATH . 'front/header.php';
$profile_image = base_url() . img_path . "/user.png";
if (isset($admin_data) && count($admin_data) > 0 && !empty($admin_data['profile_image']) && $admin_data['profile_image'] != 'null') {
    $profile_image = check_profile_image(UPLOAD_PATH . "profiles/" . $admin_data['profile_image']);
}

if (isset($admin_data) && count($admin_data) > 0 && !empty($admin_data['profile_cover_image']) && $admin_data['profile_cover_image'] != 'null') {
    if(file_exists(FCPATH.UPLOAD_PATH . "profiles/" . $admin_data['profile_cover_image'])){
        $profile_cover_image = base_url(UPLOAD_PATH . "profiles/" . $admin_data['profile_cover_image']);
    }else{
        $profile_cover_image=base_url("assets/images/banner.jpg");
    }
}
$first_name = isset($admin_data['first_name']) ? $admin_data['first_name'] : '';
$admin_id_profile = isset($admin_data['id']) ? $admin_data['id'] : '';
$company_name_profile = isset($admin_data['company_name']) ? $admin_data['company_name'] : '';
$last_name = isset($admin_data['last_name']) ? $admin_data['last_name'] : '';
$full_name = ($first_name) . " " . ($last_name);
$address = isset($admin_data['address']) ? $admin_data['address'] : '';
$email = isset($admin_data['email']) ? $admin_data['email'] : '';
$phone = isset($admin_data['phone']) ? $admin_data['phone'] : '';
$profile_text = isset($admin_data['profile_text']) ? $admin_data['profile_text'] : '';
$website = isset($admin_data['website']) ? $admin_data['website'] : '';
$fb_link = isset($admin_data['fb_link']) ? $admin_data['fb_link'] : '';
$twitter_link = isset($admin_data['twitter_link']) ? $admin_data['twitter_link'] : '';
$google_link = isset($admin_data['google_link']) ? $admin_data['google_link'] : '';
$instagram_link = isset($admin_data['instagram_link']) ? $admin_data['instagram_link'] : '';
$customer_id_sess = (int) $this->session->userdata('CUST_ID');
$vendor_id = (int) $this->uri->segment('2');

$avg_quality_rating = cal_avarage_rating('quality_rating', $vendor_id);
$avg_location_rating = cal_avarage_rating('location_rating', $vendor_id);
$avg_space_rating = cal_avarage_rating('space_rating', $vendor_id);
$avg_service_rating = cal_avarage_rating('service_rating', $vendor_id);
$avg_price_rating = cal_avarage_rating('price_rating', $vendor_id);

$total_rating = $avg_quality_rating + $avg_location_rating + $avg_space_rating + $avg_service_rating + $avg_price_rating;
$total_avg = number_format(((5 * $total_rating ) / 25), 2);
$is_add_review = 1;
if (isset($rating_data) && !empty($rating_data)) {
    $is_add_review = 0;
    foreach ($rating_data as $row) {
        $quality_rating = $row['quality_rating'];
        $location_rating = $row['location_rating'];
        $space_rating = $row['space_rating'];
        $service_rating = $row['service_rating'];
        $price_rating = $row['price_rating'];

        $total_cust_rating = $quality_rating + $location_rating + $space_rating + $service_rating + $price_rating;
        $total_avg_cust_rating = ((5 * $total_cust_rating) / 50);

        $comment = $row['review_comment'];
        $review_id = $row['id'];
    }
}
?>
<link rel="stylesheet" href="<?php echo $this->config->item('js_url'); ?>fancybox/jquery.fancybox.min.css">
<script type="text/javascript" src="<?php echo $this->config->item('js_url'); ?>fancybox/jquery.fancybox.min.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item('js_url'); ?>their-sticky-sidebar/ResizeSensor.min.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item('js_url'); ?>their-sticky-sidebar/theia-sticky-sidebar.min.js"></script>

<div class="container">
    <?php if (isset($profile_cover_image) && $profile_cover_image != ''):
        ?>   
        <div class="header_banner" style="background-image:url('<?php echo $profile_cover_image; ?>')">
            <?php
        else:
            ?>
            <div class="header_banner" style="background-color:#304352;">
            <?php
            endif;
            ?>
        </div>
    </div>

    <div class="container">
        <div class="profile_banner">
            <div class="row mx-0">           
                <div class="col-md-6 pr-0">
                    <div class="logo">
                        <div class="logo-img">
                            <a href="">
                                <img src="<?php echo $profile_image; ?>" alt="<?php echo $full_name; ?>" class="hidden">
                            </a>
                        </div>
                    </div>
                    <div class="profile-title">
                        <h1>
                            <span><?php echo $company_name_profile; ?></span>
                        </h1> 
                        <small><?php echo translate('last_seen') ?> : <?php echo get_last_seen($admin_data['id']); ?></small>
                    </div>
                </div>
            </div>
        </div>
        <div class="profile_details_tabbing">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li>
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">
                        <i class="la la-home"></i>
                        <?php echo translate('home'); ?>
                    </a>
                </li>
                <li>
                    <a class="nav-link" id="event-tab" data-toggle="tab" href="#event" role="tab" aria-controls="event" aria-selected="false">
                        <i class="la la-calendar-check-o"></i>
                        <?php echo translate('event'); ?> 
                    </a>
                </li>
                <li>
                    <a class="nav-link" id="service-tab" data-toggle="tab" href="#service" role="tab" aria-controls="service" aria-selected="false">
                        <i class="la la-calendar-check-o"></i>
                        <?php echo translate('service'); ?> 
                    </a>
                </li>
                <li>
                    <a class="nav-link" id="photos-tab" data-toggle="tab" href="#photos" role="tab" aria-controls="photos" aria-selected="false">
                        <i class="la la-image"></i>
                        <?php echo translate('gallery_image'); ?>
                    </a>
                </li>
                <li>
                    <a class="nav-link" id="reviews-tab" data-toggle="tab" href="#reviews" role="tab" aria-controls="photos" aria-selected="false">
                        <i class="la la-star-o"></i>
                        <?php echo translate('review'); ?>
                    </a>
                </li>
            </ul>
        </div>
    </div>


    <div class="container container-min-height">
        <div class="">
            <?php
            $this->load->view('message');
            $profile_image = base_url() . img_path . "/default_user.png";
            if (isset($admin_data) && count($admin_data) > 0 && !empty($admin_data['profile_image']) && $admin_data['profile_image'] != 'null') {
                $profile_image = check_admin_image(UPLOAD_PATH . "profiles/" . $admin_data['profile_image']);
            }
            ?>        
        </div>

        <div class="tab-content" id="profiletabControl">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div class="row">       
                    <div class="col-md-4 order-sm-2">
                        <div class="b-info">
                            <div class="card">
                                <div class="card-header">
                                    <h6>
                                        <i class="fa fa-file-text-o"></i>
                                        <?php echo translate('business') . " " . translate("info"); ?>
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="card-content">
                                        <ul class="list-inline">
                                            <?php if ($address != ''): ?>
                                                <li>
                                                    <span class="ico-text">
                                                        <i class="la la-map-marker grey-icon rounded-circle"></i>
                                                        <p><?php echo ($address); ?></p>
                                                    </span>
                                                </li>
                                                <?php
                                            endif;
                                            if ($email != ''):
                                                ?>
                                                <li>
                                                    <span class="ico-text">
                                                        <i class="la la-envelope grey-icon rounded-circle"></i>
                                                        <p><a href="mailto:<?php echo $email; ?>" target="_top"><?php echo $email; ?></a></p>
                                                    </span>
                                                </li>
                                                <?php
                                            endif;
                                            if ($phone != ''):
                                                ?>
                                                <li>
                                                    <span class="ico-text">
                                                        <i class="la la-phone grey-icon rounded-circle"></i>
                                                        <p><a href="tel:<?php echo $phone; ?>"><?php echo $phone; ?></a></p>
                                                    </span>
                                                </li>
                                                <?php
                                            endif;
                                            if ($website != ''):
                                                ?>
                                                <li>
                                                    <span class="ico-text">
                                                        <i class="la la-globe grey-icon rounded-circle"></i>
                                                        <p><a href="<?php echo $website; ?>" target="blank"><?php echo $website; ?></a></p>
                                                    </span>
                                                </li>
                                            <?php endif; ?>
                                        </ul>

                                        <ul class="list-inline inline-ul b-social-link">
                                            <?php if ($fb_link != '') { ?>
                                                <li>
                                                    <a class="btn-fb" href="<?php echo $fb_link; ?>" target="_blank">
                                                        <i class="fa fa-facebook grey-icon"></i>
                                                    </a>
                                                </li>
                                            <?php } if ($twitter_link != '') { ?>
                                                <li>
                                                    <a class="btn-tw" href="<?php echo $twitter_link; ?>" target="_blank">
                                                        <i class="fa fa-twitter grey-icon"></i>
                                                    </a>
                                                </li>
                                            <?php } if ($google_link != '') { ?>

                                                <li>
                                                    <a class="btn-gplus" href="<?php echo $google_link; ?>" target="_blank">
                                                        <i class="fa fa-google-plus grey-icon"></i>
                                                    </a>
                                                </li>
                                            <?php } if ($instagram_link != '') { ?>
                                                <li>
                                                    <a class="btn-ins" href="<?php echo $instagram_link; ?>" target="_blank">
                                                        <i class="fa fa-instagram grey-icon"></i>
                                                    </a>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card desc_content">
                            <div class="card-header">                              
                                <h6><i class="fa fa-sitemap"></i>
                                    <?php echo translate('categories'); ?>
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="card-content">
                                    <?php
                                    if (isset($category_data) && !empty($category_data)) {
                                        foreach ($category_data as $eRow) {
                                            if (isset($eRow['event_category_image']) && $eRow['event_category_image'] != "") {
                                                if (file_exists(FCPATH . "assets/uploads/category/" . $eRow['event_category_image'])) {
                                                    $event_category_image = base_url() . UPLOAD_PATH . "category/" . $eRow['event_category_image'];
                                                } else {
                                                    $event_category_image = base_url(img_path) . "/categories/default.png";
                                                }
                                            } else {
                                                $event_category_image = base_url(img_path) . "/categories/default.png";
                                            }

                                            if ($eRow['type'] == 'S') {
                                                $app_event_category_type = base_url("services?category=" . $eRow['id'] . "&vendor=" . $admin_id_profile);
                                            } else {
                                                $app_event_category_type = base_url("events?category=" . $eRow['id'] . "&vendor=" . $admin_id_profile);
                                            }
                                            ?>
                                            <div class="cate-img-name">
                                                <a href="<?php echo $app_event_category_type; ?>">
                                                    <img src="<?php echo $event_category_image; ?>" class="img-fluid" height="30" width="30"/>
                                                    <h6><?php echo $eRow['title'] ?></h6>
                                                </a>
                                            </div>
                                            <?php
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-8 order-sm-1">
                        <?php if (isset($vendor_rating_data) && !empty($vendor_rating_data)): ?>
                            <div class="card average-review">
                                <?php if (isset($vendor_rating_data) && !empty($vendor_rating_data)) { ?>
                                    <div class="card-header">                              
                                        <h6><i class="fa fa-star-o"></i>
                                            <?php echo translate('average') . " " . translate('review'); ?> 
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="card-content">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="overallrating">
                                                        <?php echo $total_avg; ?>
                                                    </div>
                                                    <div class="totalrating">
                                                        <p>5</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <ul class="list-inline inline-ul">
                                                        <li>
                                                            <h6><?php echo (translate('quality')); ?></h6>
                                                            <h5> <?php echo $avg_quality_rating; ?></h5>
                                                        </li>
                                                        <li>
                                                            <h6><?php echo (translate('location')); ?></h6>
                                                            <h5><?php echo $avg_location_rating; ?></h5>
                                                        </li>
                                                        <li>
                                                            <h6><?php echo (translate('space')); ?></h6>
                                                            <h5><?php echo $avg_space_rating; ?></h5>
                                                        </li>
                                                        <li>
                                                            <h6><?php echo (translate('service')); ?></h6>
                                                            <h5><?php echo $avg_service_rating; ?></h5>
                                                        </li>
                                                        <li>
                                                            <h6><?php echo (translate('price')); ?></h6>
                                                            <h5><?php echo $avg_price_rating; ?></h5>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                <?php }
                                ?>

                            </div>
                        <?php endif; ?>
                        <?php if ($profile_text != ''): ?>
                            <div class="card desc_content">
                                <div class="card-header">                              
                                    <h6><i class="fa fa-file-text-o"></i>
                                        <?php echo translate('description'); ?>
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="card-content">
                                        <p>

                                            <?php echo $profile_text; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="card photos">
                            <div class="card-header">                              
                                <h6>
                                    <i class="fa fa-image"></i>
                                    <?php echo translate('gallery_image'); ?>
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="card-content">
                                    <ul class="images list-inline inline-ul">
                                        <?php
                                        if (isset($vendor_gallery_data) && !empty($vendor_gallery_data)) {
                                            $total_images = count($vendor_gallery_data);
                                            $gi = 0;
                                            foreach ($vendor_gallery_data as $Row) {

                                                $g_img = $Row['image'];
                                                if (file_exists(FCPATH . "assets/uploads/slider/" . $g_img)) {
                                                    $g_img_src = base_url() . UPLOAD_PATH . "slider/" . $g_img;
                                                    $open_img = $g_img;
                                                } else {
                                                    $g_img_src = base_url() . UPLOAD_PATH . "slider/no_image.jpg";
                                                    $open_img = "no_image.jpg";
                                                }
                                                $gi++;
                                                ?>
                                                <li class="image">
                                                    <a data-fancybox="mygallery" href="<?php echo base_url() . UPLOAD_PATH . "/slider/" . $open_img; ?>">
                                                        <img src="<?php echo $g_img_src; ?>" class="img-fluid img-thumbnail">
                                                    </a>
                                                    <?php if ($total_images > 5 && $gi == 5) : ?>
                                                        <div class="overly-box">
                                                            <div class="overlay-text">
                                                                <h1>5 + <?php echo translate('more_images'); ?></h1>
                                                            </div>
                                                        </div>
                                                        <?php
                                                        break;
                                                    endif;
                                                    ?>
                                                </li>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <li>
                                                <?php echo translate('no_gallery_found'); ?>
                                            </li>
                                        <?php } ?>

                                    </ul>
                                </div>
                            </div>
                            <?php if (isset($vendor_gallery_data) && !empty($vendor_gallery_data) && count($vendor_gallery_data) > 5): ?>
                                <div class="card-footer text-center">
                                    <a href="javascript:void(0)" onclick="open_photo_gallery(this)
                                                    ;">
                                           <?php echo translate('see_all'); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php if (isset($vendor_rating_data) && !empty($vendor_rating_data)): ?>
                            <div class="card">
                                <div class="card-body py-1">
                                    <div class="row text-center">
                                        <div class="col-md-8">
                                            <div class="review-msg text-sm-left">
                                                <span class="pink-text text-bold"><?php echo isset($vendor_rating_data) && !empty($vendor_rating_data) ? count($vendor_rating_data) : translate('add'); ?></span> <?php echo translate('reviews_for') . " " . $full_name; ?> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>


                </div>
            </div>
            <div class="tab-pane fade" id="event" role="tabpanel" aria-labelledby="event-tab">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h6>
                                    <i class="la la-image"></i>
                                    <?php echo translate('event'); ?> 
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="card-content">

                                    <div class="row">
                                        <?php
                                        if (isset($event_data) && !empty($event_data)) {
                                            foreach ($event_data as $row) {
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

                                                /* Vendor Image */
                                                $profile_image = base_url() . img_path . "/user.png";
                                                if (isset($row['profile_image']) && $row['profile_image'] != "") {
                                                    $profile_image = check_profile_image(UPLOAD_PATH . "profiles/" . $row['profile_image']);
                                                }

                                                $e_start_date=date('Y-m-d',strtotime($row['start_date']));
                                                $e_start_time=date('H:i',strtotime($row['start_date']));
                                                
                        
                                                $e_end_date=date('Y-m-d',strtotime($row['end_date']));
                                                $e_end_time=date('H:i',strtotime($row['end_date']));
                        
                        
                                                if($e_start_date==$e_end_date){
                                                    $event_date_display=date('d M',strtotime($row['start_date']))." ".$e_start_time."-".$e_end_time;
                                                }else{
                                                    $event_date_display=date('d M',strtotime($row['start_date']))."-".date('d M',strtotime($row['end_date']))." ".$e_start_time."-".$e_end_time;
                                                }
                                                ?>
                                                <div class="col-md-3">
                                                    <div class="card hoverable position-r home_card event-card">
                                                        <div class="view overlay">
                                                            <a class="d-block" href="<?php echo base_url('event-details/' . slugify($row['title']) . "/" . $row['id']); ?>">
                                                                <img class="card-img-top" src="<?php echo $img_src; ?>">
                                                            </a>
                                                            <div class="prod_btn">
                                                                <a class="d-block" href="<?php echo base_url('event-details/' . slugify($row['title']) . "/" . $row['id']); ?>"></a>
                                                                <a href="<?php echo base_url('event-details/' . slugify($row['title']) . "/" . $row['id']); ?>" class="transparent border"><?php echo translate('more_info') ?></a>
                                                            </div>
                                                            <ul class="titlebtn list-inline inline-ul"><li class="product_cat"><a href="<?php echo base_url('events?category=' . $row['category_id']); ?>" style="text-decoration: none;"><?php echo $row['category_title']; ?></a></li></ul>
                                                        </div>
                                                        <div class="card-body product-docs pb-5px">
                                                            <a class="d-block" href="<?php echo base_url('event-details/' . slugify($row['title']) . "/" . $row['id']); ?>">
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
                                                                        <span class="category-title" style="color: #151111"><?php echo trim($row['company_name']); ?></span>
                                                                    </a>
                                                                </p>
                                                            </div>
                                                            <div class="price_love">
                                                            <p><?php echo get_event_price_by_id($row['id'], 'D'); ?></p>
                                                                <span><a href="<?php echo base_url('event-details/' . slugify($row['title']) . "/" . $row['id']); ?>" style="text-decoration: none"><?php echo translate('get') . ' ' . translate('ticket'); ?></a></span>
                                                                <span class="event_timer_span event_date_time_class"  data-enddate="<?php echo date("M d, Y H:i:s", strtotime($row['end_date'])); ?>"  data-date="<?php echo date("M d, Y H:i:s", strtotime($row['start_date'])); ?>" data-id="<?php echo $row['id']; ?>" id="event<?php echo $row['id']; ?>"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                        }
                                        ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="service" role="tabpanel" aria-labelledby="service-tab">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h6>
                                    <i class="la la-image"></i>
                                    <?php echo translate('service'); ?> 
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="card-content">

                                    <div class="row">
                                        <?php
                                        if (isset($service_data) && !empty($service_data)) {
                                            foreach ($service_data as $row) {
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


                                                $profile_image = base_url() . img_path . "/user.png";
                                                if (isset($row['profile_image']) && $row['profile_image'] != "") {
                                                    $profile_image = check_profile_image(UPLOAD_PATH . "profiles/" . $row['profile_image']);
                                                }
                                                ?>
                                                <div class="col-md-3">
                                                    <div class="card hoverable position-r home_card event-card">
                                                        <div class="view overlay">
                                                            <a class="d-block" href="<?php echo base_url('service-details/' . slugify($row['title']) . "/" . $row['id']); ?>">
                                                                <img class="card-img-top" src="<?php echo $img_src; ?>">
                                                            </a>
                                                            <div class="prod_btn">
                                                                <a class="d-block" href="<?php echo base_url('service-details/' . slugify($row['title']) . "/" . $row['id']); ?>"></a>
                                                                <a href="<?php echo base_url('service-details/' . slugify($row['title']) . "/" . $row['id']); ?>" class="transparent border"><?php echo translate('more_info') ?></a>
                                                            </div>
                                                            <ul class="titlebtn list-inline inline-ul"><li class="product_cat"><a href="<?php echo base_url('services?category=' . $row['category_id']); ?>" style="text-decoration: none;"><?php echo $row['category_title']; ?></a></li></ul>
                                                        </div>
                                                        <div class="card-body product-docs pb-5px">
                                                            <a class="d-block" href="<?php echo base_url('service-details/' . slugify($row['title']) . "/" . $row['id']); ?>">
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
                                                                        <p><i class="fa fa-clock-o mr-10 text-success"></i><?php echo convertToHoursMins($row['slot_time']); ?></p>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </div>
                                                        <div class="product-purchase">
                                                            <div class="sell">
                                                                <a href="<?php echo base_url('v/' . slugify(trim($row['company_name'])) . '/' . $row['created_by']); ?>" style="text-decoration: none;">
                                                                    <img class="auth-img" src="<?php echo $profile_image; ?>" alt="<?php echo trim($row['company_name']); ?>">
                                                                </a>
                                                                <p>
                                                                    <a href="<?php echo base_url('v/' . slugify(trim($row['company_name'])) . '/' . $row['created_by']); ?>" style="text-decoration: none;">
                                                                        <span class="category-title" style="color: #151111"><?php echo trim($row['company_name']); ?></span>
                                                                    </a>
                                                                </p>
                                                            </div>
                                                            <div class="price_love">
                                                                <?php if ($row['payment_type'] == "F"): ?>
                                                                    <span><?php echo translate('free'); ?></span>
                                                                    <?php
                                                                else:
                                                                    $get_discount_price_by_date = get_discount_price_by_date($row['id'], date('Y-m-d'));
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
                                        }
                                        ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="photos" role="tabpanel" aria-labelledby="photos-tab">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h6>
                                    <i class="la la-image"></i>
                                    <?php echo translate('gallery_image'); ?>
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="card-content">
                                    <ul class="row mx-0 list-inline inline-ul">
                                        <?php
                                        if (isset($vendor_gallery_data) && !empty($vendor_gallery_data)) {
                                            foreach ($vendor_gallery_data as $Row) {

                                                $g_img = $Row['image'];
                                                if (file_exists(FCPATH . "assets/uploads/slider/" . $g_img)) {
                                                    $g_img_src = base_url() . UPLOAD_PATH . "slider/" . $g_img;
                                                    $open_img = $g_img;
                                                } else {
                                                    $g_img_src = base_url() . UPLOAD_PATH . "slider/no_image.jpg";
                                                    $open_img = "no_image.jpg";
                                                }
                                                ?>
                                                <li class="col-md-3 px-1 gallery_img">
                                                    <a data-fancybox="mygallery1" href="<?php echo base_url() . UPLOAD_PATH . "slider/" . $open_img; ?>">
                                                        <img src="<?php echo $g_img_src; ?>" class="img-fluid h-250">
                                                    </a>
                                                </li>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <li class="text-center"><center>  <?php echo translate('no_gallery_found'); ?></center></li>
                                            <?php
                                        }
                                        ?>

                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                <div class="row">
                    <?php if (isset($vendor_rating_data) && !empty($vendor_rating_data)): ?>
                        <div class="col-md-4 sticky_box order-sm-2">
                            <div class="card">
                                <div class="card-header">
                                    <h6>
                                        <i class="la la-star-o"></i>
                                        <span class="pink-text text-bold"><?php echo isset($vendor_rating_data) && !empty($vendor_rating_data) ? count($vendor_rating_data) : translate('add'); ?></span> <?php echo translate('reviews_for') . " " . $full_name; ?> 
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="card-content">
                                        <div class="rated-info_module">
                                            <div class="rated-info_title"><?php echo (translate('quality')); ?></div>
                                            <div class="rated-info_wrap">
                                                <div class="progress">
                                                    <div class="progress-bar" role="progressbar" style="width: <?php echo $avg_quality_rating * 20; ?>%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="50"></div>
                                                </div>                                         
                                            </div> 
                                            <div class="rated_overallRating"><?php echo $avg_quality_rating; ?></div>
                                        </div>
                                        <div class="rated-info_module">
                                            <div class="rated-info_title"><?php echo (translate('location')); ?></div>
                                            <div class="rated-info_wrap">
                                                <div class="progress">
                                                    <div class="progress-bar" role="progressbar" style="width: <?php echo $avg_location_rating * 20; ?>%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>                                         
                                            </div> 
                                            <div class="rated_overallRating"><?php echo $avg_location_rating; ?></div>
                                        </div>
                                        <div class="rated-info_module">
                                            <div class="rated-info_title"><?php echo (translate('space')); ?></div>
                                            <div class="rated-info_wrap">
                                                <div class="progress">
                                                    <div class="progress-bar" role="progressbar" style="width: <?php echo $avg_space_rating * 20; ?>%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>                                         
                                            </div> 
                                            <div class="rated_overallRating"><?php echo $avg_space_rating; ?></div>
                                        </div>
                                        <div class="rated-info_module">
                                            <div class="rated-info_title"><?php echo (translate('service')); ?></div>
                                            <div class="rated-info_wrap">
                                                <div class="progress">
                                                    <div class="progress-bar" role="progressbar" style="width: <?php echo $avg_service_rating * 20; ?>%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>                                         
                                            </div> 
                                            <div class="rated_overallRating"><?php echo $avg_service_rating; ?></div>
                                        </div>
                                        <div class="rated-info_module">
                                            <div class="rated-info_title"><?php echo (translate('price')); ?></div>
                                            <div class="rated-info_wrap">
                                                <div class="progress">
                                                    <div class="progress-bar" role="progressbar" style="width: <?php echo $avg_price_rating * 20; ?>%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>                                         
                                            </div> 
                                            <div class="rated_overallRating"><?php echo $avg_price_rating; ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-md-6 my-0">
                                            <div class="review_name-date">
                                                <h6><?php echo $full_name; ?></h6>  
                                            </div>
                                        </div>
                                        <div class="col-md-6 my-0 text-sm-right">
                                            <div class="average-review">
                                                <div class="overallrating">
                                                    <?php echo $total_avg; ?>
                                                </div>
                                                <div class="totalrating">
                                                    <p>5</p>
                                                </div>
                                            </div>                                     
                                        </div>
                                    </div>                                
                                </div>
                            </div>

                        </div>

                        <div class="col-md-8 order-sm-1">
                            <div class="comment-full-card">
                                <?php
                                foreach ($vendor_rating_data as $Row) {
                                    $cust_profile_img = $Row['profile_image'];
                                    $cust_name = ($Row['first_name']) . " " . $Row['last_name'];
                                    $disp_date = isset($Row['created_on']) && $Row['created_on'] != '' ? date('M d, Y', strtotime($Row['created_on'])) : "";
                                    $quality_rating = $Row['quality_rating'];
                                    $location_rating = $Row['location_rating'];
                                    $space_rating = $Row['space_rating'];
                                    $service_rating = $Row['service_rating'];
                                    $price_rating = $Row['price_rating'];
                                    $review_comment = $Row['review_comment'];
                                    $cust_id = $Row['customer_id'];
                                    $event_title = $Row['title'];

                                    $total = $quality_rating + $location_rating + $space_rating + $service_rating + $price_rating;

                                    $cust_avg_rating = number_format(((5 * $total) / 25), 2);
                                    ?>

                                    <div class="card mb-1 cust_review_wrapper">
                                        <div class="card-header">
                                            <div class="comment-review-header">
                                                <div class="row">
                                                    <div class="col-5 my-0">
                                                        <img src="<?php echo check_profile_image(UPLOAD_PATH . "profiles/" . $cust_profile_img); ?>" onerror="<?php echo base_url(); ?>" class="img-fluid hw_40" alt="user profile"/>
                                                        <div class="review_name-date">
                                                            <h6><?php echo isset($cust_name) ? $cust_name : ''; ?></h6>  
                                                            <p><?php echo $disp_date; ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="col-4  text-right">
                                                        <a href="javascript:void(0)"><span class="badge indigo"><?php echo $event_title; ?></span></a>
                                                    </div>
                                                    <div class="col-3 my-0 text-right">
                                                        <div class="average-review">
                                                            <div class="overallrating">
                                                                <?php echo $cust_avg_rating; ?>
                                                            </div>
                                                            <div class="totalrating">
                                                                <p>5</p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="card-content">
                                                <div class="comment-review-content">
                                                    <p><?php echo $review_comment; ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="m-0">
                                        <div class="average-review">
                                            <?php if ($is_add_review == 0): ?>
                                                <div class="card-body">
                                                    <div class="card-content">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="overallrating">
                                                                    <?php echo isset($cust_avg_rating) ? number_format($cust_avg_rating, 2) : 0; ?>
                                                                </div>
                                                                <div class="totalrating">
                                                                    <p>5</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-8">
                                                                <ul class="list-inline inline-ul">
                                                                    <li>
                                                                        <h6><?php echo (translate('quality')); ?></h6>
                                                                        <h5> <?php echo isset($quality_rating) ? number_format($quality_rating, 2) : 0; ?></h5>
                                                                    </li>
                                                                    <li>
                                                                        <h6><?php echo (translate('location')); ?></h6>
                                                                        <h5><?php echo isset($location_rating) ? number_format($location_rating, 2) : 0; ?></h5>
                                                                    </li>
                                                                    <li>
                                                                        <h6><?php echo (translate('space')); ?></h6>
                                                                        <h5><?php echo isset($space_rating) ? number_format($space_rating, 2) : 0; ?></h5>
                                                                    </li>
                                                                    <li>
                                                                        <h6><?php echo (translate('service')); ?></h6>
                                                                        <h5><?php echo isset($service_rating) ? number_format($service_rating, 2) : 0; ?></h5>
                                                                    </li>
                                                                    <li>
                                                                        <h6><?php echo (translate('price')); ?></h6>
                                                                        <h5><?php echo isset($price_rating) ? number_format($price_rating, 2) : 0; ?></h5>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            endif;
                                            ?>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>

                            </div>

                        </div>   
                    <?php else: ?>
                        <div class="col-md-12">
                            <div class="alert alert-info col-md-12"><?php echo translate('no_review_available') . " " . translate('for') . " " . $full_name; ?></div>
                        </div>

                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $("[data-fancybox]").fancybox();
    });

    $(document).ready(function () {
        $('.sticky_box').theiaStickySidebar();
    });

    function open_photo_gallery() {
        $("#photos-tab").trigger("click");
    }

</script>
<?php include VIEWPATH . 'front/footer.php'; ?>
<script>
    if ($(".event_date_time_class").length > 0) {
        $(".event_date_time_class").each(function (e) {
            var event_id = $(this).data('id');
            var date = $(this).data('date');
            var enddate = $(this).data('enddate');
            set_time_count(event_id, date, enddate);
        });
    }
</script>
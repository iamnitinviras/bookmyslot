<?php
$url_segment = trim($this->uri->segment(1));
$location_segment = trim($this->uri->segment(2));

$header_color_code = get_site_setting('header_color_code');
$login_active = $vendor_login_active = $vendor_register_active = '';
$search_allowed = array('category-details', 'event-listing', 'event-category', 'search', 'services', 'events');
$location_allowed = array('category-details', 'service-details', 'v', 'event-details', 'event-listing', 'event-category', 'services', 'events', 'search');
if ($url_segment == 'appointment') {
    $appointment_active = 'active';
}
$appointment_active = "";

if ($url_segment == 'login') {
    $login_active = 'active';
} else if ($url_segment == 'register') {
    $register_active = 'active';
} else if ($url_segment == 'register') {
    $register_active = 'active';
} else if ($url_segment == 'event-booking') {
    $event_booking = 'active';
} else if ($location_segment == 'login') {
    $vendor_login_active = 'active';
} else if ($url_segment == 'vendor-register') {
    $vendor_register_active = 'active';
} else if ($url_segment == 'appointment') {
    $appointment_active = 'active';
} else if ($url_segment == 'message') {
    $message_active = 'active';
} else if ($url_segment == 'profile' || $url_segment == 'change-password' || $url_segment == 'payment-history') {
    $profile_drop_active = 'active';
    if ($url_segment == 'profile') {
        $profile_active = 'active';
    } else if ($url_segment == 'payment-history') {
        $payment_active = 'active';
    } else {
        $password_active = 'active';
    }
} else {
    $home_active = 'active';
}
$select_City = $body_cls = "";
$select_City = $this->session->userdata('location');
$language_data = get_languages();
$Total_Event_Count = isset($total_Event) && is_array($total_Event) ? count($total_Event) : 0;
$customer_id_sess = (int) $this->session->userdata('CUST_ID');
$to_id = 0;
if ($customer_id_sess > 0) {
    $to_id = get_latest_chat($customer_id_sess);
}
$footer_fixed_pages_arr = array("login", "vendor", "staff", "register", "forgot-password", "reset-password-admin", "reset-password", "day-slots", "change-password", "message", "services", "terms-conditon", "privecy-policy", "faqs", "login-action", "forgot-password-action", "register-save", "reset-password-admin-action", "vendor-register-save", "reset-password-action", "register-success");
if (in_array($url_segment, $footer_fixed_pages_arr) || in_array($location_segment, $footer_fixed_pages_arr)) {
    $body_cls = 'footer_fixed';
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
        <meta name="viewport" content="width=device-width">
        <?php
        if ($url_segment = "event-details") {
            if (isset($meta_description) && $meta_description != '') {
                ?>
                <meta name="description" content="<?php echo $meta_description; ?>"/>
                <?php
            }
            if (isset($meta_keyword) && $meta_keyword != '') {
                ?>
                <meta name="keywords" content="<?php echo $meta_keyword; ?>"/>
                <?php
            }
            if (isset($meta_og_img) && $meta_og_img != '') {
                ?>
                <meta name="og:image" content="<?php echo check_admin_image(UPLOAD_PATH . "event/seo_image/" . $meta_og_img); ?>"/>
                <?php
            }
        }
        ?>
        <link rel="icon" type="image/x-icon" href="<?php echo get_fevicon(); ?>"/>
        <title><?php
            echo get_CompanyName();
            if (!empty($title))
                echo " | " . $title;
            ?></title>
        <link href="<?php echo $this->config->item('css_url'); ?>font-awesome.css" rel="stylesheet">
        <link href="<?php echo $this->config->item('css_url'); ?>line-awesome.min.css" rel="stylesheet">
        <link href="<?php echo $this->config->item('css_url'); ?>bootstrap.css" rel="stylesheet">
        <link href="<?php echo $this->config->item('css_url'); ?>module/bookmyslot.css" rel="stylesheet">
        <link href="<?php echo $this->config->item('css_url'); ?>module/admin_panel.css" rel="stylesheet">
        <link href="<?php echo $this->config->item('css_url'); ?>module/custom.css" rel="stylesheet">
        <link href="<?php echo $this->config->item('css_url'); ?>datatables.min.css" rel="stylesheet"/>
        <link href="<?php echo $this->config->item('css_url'); ?>slidePanel.css" rel="stylesheet">

        <script type="text/javascript" src="<?php echo $this->config->item('js_url'); ?>jquery-3.2.1.min.js"></script>
        <script type="text/javascript" src="<?php echo $this->config->item('js_url'); ?>popper.min.js"></script>
        <script type="text/javascript" src="<?php echo $this->config->item('js_url'); ?>bootstrap.min.js"></script>
        <script type="text/javascript" src="<?php echo $this->config->item('js_url'); ?>jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="<?php echo $this->config->item('js_url'); ?>datatables.min.js"></script>
        <script type="text/javascript" src="<?php echo $this->config->item('js_url'); ?>jquery.validate.min.js" type="text/javascript"></script>
        <script type="text/javascript" src="<?php echo $this->config->item('js_url'); ?>jquery-slidePanel.js" type="text/javascript"></script>
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
        <!--loader-->
        <link href="<?php echo $this->config->item('assets_url'); ?>loader/css/preloader.css" rel="stylesheet">
        <script type="text/javascript" src="<?php echo $this->config->item('assets_url'); ?>loader/js/jquery.preloader.min.js"></script>

        <style>
            .button_common, .button_common:hover, .button_common:focus, .button_common:active{
                background: <?php echo $header_color_code != '' && $header_color_code != NULL ? $header_color_code : '#4b6499' ?>;
                border-color: <?php echo $header_color_code != '' && $header_color_code != NULL ? $header_color_code : '#4b6499' ?>;
            }
        </style>
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="<?php echo $body_cls; ?>">
        <noscript>
        <meta http-equiv="refresh" content="0; URL=<?php echo base_url('no-script'); ?>">
        </noscript>
        <div id="loadingmessage" class="loadingmessage"></div>        
        <!--Header-->
        <?php
        if ($url_segment != 'vendor-register' && $url_segment != 'register') {
            ?>
            <nav class="navbar navbar-expand-lg navbar-light bg-light" style="background-color : <?php echo $header_color_code != '' && $header_color_code != NULL ? $header_color_code : '#4b6499' ?>!important">
                <div class="container">

                    <?php if (get_site_setting('is_display_location') == 'Y') { ?>
                        <a class="navbar-brand" href="<?php echo base_url(isset($select_City) && $select_City != '' ? 'city/' . $select_City : ''); ?>">
                            <img src="<?php echo get_CompanyLogo(); ?>" class="img-fluid resp_h-35 h-39" alt="logo">
                        </a> 
                    <?php } else { ?>
                        <a class="navbar-brand" href="<?php echo base_url(); ?>">
                            <img src="<?php echo get_CompanyLogo(); ?>" class="img-fluid resp_h-35 h-39" alt="logo">
                        </a> 
                    <?php } ?>

                    <?php if (get_site_setting('is_display_location') == 'Y'): ?>                
                        <?php if (in_array($this->uri->segment(1), $location_allowed) || $this->uri->segment(1) == ''): ?>
                            <ul class="navbar-nav mr-auto">
                                <li>
                                    <div class="texticon-group">
                                        <div class="location_input" style="cursor: pointer" data-toggle="modal" data-target="#locationPopup">
                                            <p  style="cursor: pointer">
                                                <span class="font-sm white-text city-name"><?php echo isset($select_City) && $select_City != '' ? ($select_City) : translate('city'); ?>
                                                    <i class="fa fa-caret-down"></i>
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        <?php endif; ?>
                    <?php endif ?>

                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse ml-auto" id="navbarSupportedContent">
                        <div class="ml-auto">
                            <div class="menu_items">
                                <ul class="navbar-nav sidbar_ulnav top_navbar">
                                    <li class="nav-item">
                                        <a href="<?php echo base_url(isset($select_City) && $select_City != '' ? 'city/' . $select_City : '') ?>" class="nav-link <?php echo isset($home_active) ? $home_active : ''; ?>">
                                            <?php echo translate('home'); ?>
                                        </a>
                                    </li>
                                    <?php
                                    if ($this->session->userdata('CUST_ID')) {

                                        if ($this->session->userdata('CUST_ID') && $this->session->userdata('CUST_ID') > 0):
                                            if (get_site_setting('enable_service') == 'Y'):
                                                ?>
                                                <li class="nav-item">
                                                    <a href="<?php echo base_url('appointment') ?>" class="nav-link <?php echo isset($appointment_active) ? $appointment_active : ''; ?>">
                                                        <?php echo translate('my_appointment'); ?>
                                                    </a>
                                                </li>
                                            <?php endif; ?>


                                            <li class="nav-item px-0">
                                                <a class="nav-link border-0 <?php echo isset($profile_drop_active) ? $profile_drop_active : ""; ?>" href="<?php echo base_url('profile'); ?>">
                                                    <?php $login_customer = get_CustomerDetails(); ?>
                                                    <?php if (isset($login_customer['profile_image']) && $login_customer['profile_image'] != ""): ?>
                                                        <img src="<?php echo check_profile_image(UPLOAD_PATH . "profiles/" . $login_customer['profile_image']); ?>" onerror="<?php echo base_url(); ?>" class="img-fluid hw_40" alt="user profile"/>
                                                    <?php else: ?>
                                                        <img src="<?php echo base_url() . img_path . "/user.png"; ?>" onerror="<?php echo base_url(); ?>" class="img-fluid hw_40" alt="user profile"/>
                                                    <?php endif; ?>

                                                    <?php if (isset($login_customer['first_name']) && $login_customer['first_name'] != ""): ?>
                                                        <?php echo ($login_customer['first_name']) . " " . $login_customer['last_name']; ?>
                                                    <?php endif; ?>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="<?php echo base_url('logout') ?>" class="nav-link">
                                                    <i class="fa fa-sign-out"></i>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    <?php } else { ?>
                                        <li class="nav-item">
                                            <a href="<?php echo base_url('login') ?>" class="nav-link <?php echo isset($login_active) ? $login_active : ''; ?>">
                                                <?php echo translate('login'); ?>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="<?php echo base_url('register') ?>" class="nav-link <?php echo isset($register_active) ? $register_active : ''; ?>">
                                                <?php echo translate('register'); ?>
                                            </a>
                                        </li>
                                        <?php if (get_site_setting('is_display_vendor') == "Y") { ?>
                                            <li class="nav-item dropdown px-0">
                                                <a class="nav-link dropdown-toggle <?php echo $vendor_login_active . $vendor_register_active; ?>" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                    <?php echo translate('vendor'); ?>                                  </a>
                                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                                    <li> <a class="dropdown-item <?php echo isset($vendor_login_active) ? $vendor_login_active : ''; ?>" href="<?php echo base_url('vendor/login') ?>"> <?php echo translate('login'); ?></a></li>
                                                    <li><a class="dropdown-item <?php echo isset($vendor_register_active) ? $vendor_register_active : ''; ?>" href="<?php echo base_url('vendor-register') ?>"><?php echo translate('register'); ?></a></li>
                                                </ul>
                                            </li>
                                            <?php
                                        }
                                    }
                                    ?>
                                </ul>
                            </div>
                            <div class="search-items">
                                <?php if (get_site_setting('is_display_searchbar') == 'Y'): ?>
                                    <?php if (in_array($this->uri->segment(1), $search_allowed) || $this->uri->segment(1) == ''): ?>
                                        <form class="search-box" method="get" action="<?php echo site_url('search'); ?>">
                                            <div class="input-group">
                                                <input type="text" class="form-control"  autocomplete="off" value="<?php echo trim($this->input->get('q')); ?>" style="width: 140px;" id="search_input" name="q" placeholder="Search" aria-label="<?php echo translate('search') ?>" aria-describedby="basic-addon2">
                                                <div class="input-group-append">
                                                    <button class="btn btn-dark search-btn m-0" type="submit"><i class="fa fa-search"></i></button>
                                                </div>
                                            </div>
                                        </form>
                                    <?php endif; ?>
                                <?php endif; ?>



                                <div class="lang_box">
                                    <?php
                                    if (get_site_setting('is_display_language') == 'Y') {
                                        $language_sesstion = $this->session->userdata('language');
                                        $language_text = translate('select') . " " . translate('language');
                                        if (isset($language_sesstion) && $language_sesstion != "" && $language_sesstion != NULL) {
                                            $language_title_data = get_single_row("app_language", "title", "db_field='" . $language_sesstion . "'");
                                            $language_text = ($language_title_data['title']);
                                        } else {
                                            $language = get_site_setting('language');
                                            $language_title_data = get_single_row("app_language", "title", "db_field='" . $language . "'");
                                            $language_text = ($language_title_data['title']);
                                        }
                                        ?>
                                        <div class="language_dropdown dropdown">
                                            <button class="btn white m-0"><?php echo $language_text; ?></button>
                                            <div class="dropdown-content">
                                                <?php
                                                if (isset($language_data) && !empty($language_data)) {

                                                    foreach ($language_data as $row) {
                                                        ?>
                                                        <a href="<?php echo base_url('set_language/' . $row['db_field']); ?>"><?php echo ($row['title']); ?></a>
                                                        <?php
                                                    }
                                                }
                                                ?>

                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>    
        <?php } ?>
        <!--End Header-->


        <!--Location Popup-->
        <div class="location_popup">
            <!-- Modal -->
            <div class="modal fade" id="locationPopup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog top_modal modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <button type="button" class="close location_close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <div class="search loaders">
                                <!--Loader-->

                                <!--End Loader-->
                                <div class="container">
                                    <br>
                                    <h5 class="h4 mb-2 popup_header"><?php echo translate('pick_city'); ?></h5>
                                    <p class="grey-text mb-4"><?php echo translate('finds_awesome_events'); ?></p>

                                    <div class="searchbox mb-4">
                                        <?php
                                        $attributes = array('id' => 'homelocationsearch', 'name' => 'homelocationsearch', 'method' => "post");
                                        echo form_open('', $attributes);
                                        ?>
                                        <div class="row">
                                            <div class="col-md-12 m-auto">
                                                <div class="search_box">
                                                    <div class="form-group mb-0">
                                                        <i class="fa fa-map-marker"></i>
                                                        <input autocomplete="off" id="search" class="form-control" name="search" placeholder="<?php echo translate('enter_your_city'); ?>" type="search" value="">
                                                    </div>
                                                </div>
                                                <div class="searchbox_suggestion_wrapper d-none">
                                                    <ul class="searchbox_suggestion">
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        </form>
                                    </div>
                                    <!--template bindings={}--> 


                                    <p class="top_cities"><?php echo translate('top_cities'); ?></p>
                                    <div class="city_names">
                                        <?php
                                        if (isset($topCity_List) && !empty($topCity_List)) {
                                            foreach ($topCity_List as $crow) {
                                                ?>
                                                <a onclick="change_current_city(this)" href="javascript:void(0)" data-name="<?php echo $crow['city_title']; ?>" class="badge badge-pill white"><?php echo $crow['city_title']; ?></a>
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
        </div>
        <!--End Location Popup-->

        <script>
            $('.search-btn, #search_input').on("click", function () {
                $('.sidbar_ulnav').addClass("close_menu");
                $("#search_input").animate({width: "300px"});
            });

            $(document).bind('click', function (e) {
                if (!$(e.target).is('.search-btn') && !$(e.target).is('#search_input')) {
                    $('.sidbar_ulnav').removeClass("close_menu");
                    $("#search_input").animate({width: "140px"});
                }
            });
        </script>
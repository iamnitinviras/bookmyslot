<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title><?php echo (get_CompanyName());
        if (!empty($title))
            echo " | " . $title;
        ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- App favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo get_fevicon(); ?>"/>

    <!-- plugin css -->
    <link href="<?php echo base_url('assets/admin/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css'); ?>" rel="stylesheet" type="text/css" />

    <!-- preloader css -->
    <link rel="stylesheet" href="<?php echo base_url('assets/admin/css/preloader.min.css');?>" type="text/css" />

    <!-- DataTables -->
    <link href="<?php echo base_url('assets/admin/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css');?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url('assets/admin/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css');?>" rel="stylesheet" type="text/css" />

    <!-- Responsive datatable examples -->
    <link href="<?php echo base_url('assets/admin/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css');?>" rel="stylesheet" type="text/css" />

    <!-- Bootstrap Css -->
    <link href="<?php echo base_url('assets/admin/css/bootstrap.min.css');?>" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="<?php echo base_url('assets/admin/css/icons.min.css');?>" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="<?php echo base_url('assets/admin/css/app.min.css');?>" id="app-style" rel="stylesheet" type="text/css" />

    <script>
        var site_url = "<?php echo $this->config->item('site_url'); ?>";
        var userid = '<?php echo $this->session->userdata('ADMIN_ID'); ?>';
    </script>
    <script src="<?php echo base_url('assets/admin/libs/jquery/jquery.min.js');?>"></script>
    <script>
        var base_url = '<?php echo base_url() ?>';
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

<body data-topbar="dark">

<!-- <body data-layout="horizontal"> -->

<!-- Begin page -->
<div id="layout-wrapper">


    <header id="page-topbar">
        <div class="navbar-header">
            <div class="d-flex">
                <!-- LOGO -->
                <div class="navbar-brand-box">
                    <a href="<?php echo base_url('admin/dashboard') ?>" class="logo logo-dark">
                        <span class="logo-sm">
                            <img src="<?php echo get_CompanyLogo(); ?>" alt="" height="30">
                        </span>
                        <span class="logo-lg">
                            <img src="<?php echo get_CompanyLogo(); ?>" alt="" height="24"> <span class="logo-txt"></span>
                        </span>
                    </a>

                    <a href="<?php echo base_url('admin/dashboard') ?>" class="logo logo-light">
                        <span class="logo-sm">
                            <img src="<?php echo get_CompanyLogo(); ?>" alt="" height="30">
                        </span>
                        <span class="logo-lg">
                            <img src="<?php echo get_CompanyLogo(); ?>" alt="" height="24"> <span class="logo-txt"></span>
                        </span>
                    </a>
                </div>

                <button type="button" class="btn btn-sm px-3 font-size-16 header-item" id="vertical-menu-btn">
                    <i class="fa fa-fw fa-bars"></i>
                </button>
            </div>

            <div class="d-flex">

                <!-- master Module-->
                <div class="dropdown d-inline-block">
                    <button type="button" class="btn header-item"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i data-feather="grid" class="icon-lg"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                        <div class="p-2">
                            <div class="row g-0">
                                <div class="col-md-4">
                                    <a class="dropdown-icon-item" href="<?php echo base_url('admin/city'); ?>">
                                        <img src="<?php echo base_url('assets/admin/images/icon/city.png') ?>">
                                        <span><?php echo translate('city') ?></span>
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a class="dropdown-icon-item" href="<?php echo base_url('admin/location'); ?>">
                                        <img src="<?php echo base_url('assets/admin/images/icon/location.png') ?>">
                                        <span><?php echo translate('location') ?></span>
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a class="dropdown-icon-item" href="<?php echo base_url('admin/currency'); ?>">
                                        <img src="<?php echo base_url('assets/admin/images/icon/currency.png') ?>">
                                        <span><?php echo translate('currency'); ?></span>
                                    </a>
                                </div>

                                <?php if (get_site_setting('enable_testimonial') == 'Y'): ?>
                                    <div class="col-md-4">
                                        <a class="dropdown-icon-item" href="<?php echo base_url('admin/testimonial'); ?>">
                                            <img src="<?php echo base_url('assets/admin/images/icon/testimonial.png') ?>">
                                            <span><?php echo translate('testimonial') ?></span>
                                        </a>
                                    </div>
                                <?php endif; ?>
                                <div class="col-md-4">
                                    <a class="dropdown-icon-item" href="<?php echo base_url('admin/slider'); ?>">
                                        <img src="<?php echo base_url('assets/admin/images/icon/gallery_image.png');?>">
                                        <span><?php echo translate('gallery_image') ?></span>
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a class="dropdown-icon-item" href="<?php echo base_url('admin/faq'); ?>">
                                        <img src="<?php echo base_url('assets/admin/images/icon/faqs.png');?>">
                                        <span><?php echo translate('faqs'); ?></span>
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>


                <!-- Site Settings-->
                <div class="dropdown d-inline-block">
                    <button type="button" class="btn header-item"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i data-feather="settings" class="icon-lg"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                        <div class="p-2">
                            <div class="row g-0">
                                <div class="col-md-4">
                                    <a class="dropdown-icon-item" href="<?php echo base_url('admin/sitesetting'); ?>">
                                        <img src="<?php echo base_url('assets/admin/images/icon/setting.png') ?>">
                                        <span><?php echo translate('site_setting') ?></span>
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a class="dropdown-icon-item" href="<?php echo base_url('admin/setting/email'); ?>">
                                        <img src="<?php echo base_url('assets/admin/images/icon/email.png') ?>">
                                        <span><?php echo translate('email_setting') ?></span>
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a class="dropdown-icon-item" href="<?php echo base_url('admin/setting/currency'); ?>">
                                        <img src="<?php echo base_url('assets/admin/images/icon/currency.png') ?>">
                                        <span><?php echo translate('currency'); ?></span>
                                    </a>
                                </div>

                                <div class="col-md-4">
                                    <a class="dropdown-icon-item" href="<?php echo base_url('admin/setting/display'); ?>">
                                        <img src="<?php echo base_url('assets/admin/images/icon/display.png') ?>">
                                        <span><?php echo translate('display_setting') ?></span>
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a class="dropdown-icon-item" href="<?php echo base_url('admin/setting/payment'); ?>">
                                        <img src="<?php echo base_url('assets/admin/images/icon/payment.png') ?>">
                                        <span><?php echo translate('payment') ?></span>
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a class="dropdown-icon-item" href="<?php echo base_url('admin/setting/vendor'); ?>">
                                        <img src="<?php echo base_url('assets/admin/images/icon/vendor.png') ?>">
                                        <span><?php echo translate('vendor'); ?></span>
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="dropdown d-inline-block">
                    <button type="button" class="btn header-item bg-soft-light border-start border-end" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="d-none d-xl-inline-block ms-1 fw-medium">Admin</span>
                        <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <!-- item-->
                        <a class="dropdown-item" href="<?php echo base_url('admin/profile') ?>"><i class="mdi mdi-face-profile font-size-16 align-middle me-1"></i> <?php echo translate('profile_setting'); ?></a>
                        <a class="dropdown-item" href="<?php echo base_url('admin/update-password-action') ?>"><i class="mdi mdi-key-change font-size-16 align-middle me-1"></i> <?php echo translate('change_password'); ?></a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="<?php echo base_url('admin/logout') ?>"><i class="mdi mdi-logout font-size-16 align-middle me-1"></i> <?php echo translate('logout'); ?></a>
                    </div>
                </div>

            </div>
        </div>
    </header>

    <!-- ========== Left Sidebar Start ========== -->
    <div class="vertical-menu">

        <div data-simplebar class="h-100">

            <!--- Sidemenu -->
            <div id="sidebar-menu">
                <!-- Left Menu Start -->
                <ul class="metismenu list-unstyled" id="side-menu">
                    <li>
                        <a href="<?php echo base_url('admin/dashboard') ?>">
                            <i data-feather="home"></i>
                            <span class="badge rounded-pill bg-soft-success text-success float-end">9+</span>
                            <span data-key="t-dashboard"> <?php echo translate('dashboard'); ?></span>
                        </a>
                    </li>

                    <li>
                        <a href="<?php echo base_url('admin/manage-appointment'); ?>">
                            <i data-feather="calendar"></i>
                            <span data-key="t-calendar"><?php echo translate('appointment'); ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('admin/customer'); ?>">
                            <i data-feather="calendar"></i>
                            <span data-key="t-calendar"><?php echo translate('customer'); ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('admin/staff'); ?>">
                            <i data-feather="calendar"></i>
                            <span data-key="t-calendar"><?php echo translate('my_staff'); ?></span>
                        </a>
                    </li>
                    <?php if (get_site_setting('is_display_vendor') == 'Y'): ?>
                        <li>
                            <a href="javascript: void(0);" class="has-arrow">
                                <i data-feather="users"></i>
                                <span data-key="t-contacts"><?php echo translate('vendor'); ?></span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="<?php echo base_url('admin/vendor'); ?>" data-key="t-user-grid"><?php echo translate('vendor'); ?></a></li>
                                <li><a href="<?php echo base_url('admin/vendor/unverified'); ?>" data-key="t-user-list"><?php echo translate('unverified') . " " . translate('vendor'); ?></a></li>
                                <li><a href="<?php echo base_url('admin/payout-request'); ?>" data-key="t-profile"><?php echo translate('payout_request'); ?></a></li>
                            </ul>
                        </li>
                    <?php endif; ?>

                    <?php if (get_site_setting('enable_service') == 'Y'): ?>
                        <li>
                            <a href="javascript: void(0);" class="has-arrow">
                                <i data-feather="layout"></i>
                                <span data-key="t-pages"><?php echo translate('service'); ?></span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="<?php echo base_url('admin/service'); ?>" data-key="t-user-grid"><?php echo translate('service'); ?></a></li>
                                <li><a href="<?php echo base_url('admin/service/category'); ?>" data-key="t-user-list"><?php echo translate('service_category'); ?></a></li>
                                <li><a href="<?php echo base_url('admin/coupon'); ?>" data-key="t-profile"><?php echo translate('event_coupon'); ?></a></li>
                                <li><a href="<?php echo base_url('admin/payment-history'); ?>" data-key="t-profile"><?php echo translate('appointment_payment_history'); ?></a></li>
                                <li><a href="<?php echo base_url('admin/holiday'); ?>" data-key="t-profile"><?php echo translate('holiday'); ?></a></li>
                            </ul>
                        </li>
                    <?php endif; ?>

                    <?php if (get_site_setting('enable_event') == 'Y'): ?>
                        <li>
                            <a href="javascript: void(0);" class="has-arrow">
                                <i data-feather="briefcase"></i>
                                <span data-key="t-components"><?php echo translate('event'); ?></span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="<?php echo base_url('admin/event'); ?>" data-key="t-user-grid"><?php echo translate('event'); ?></a></li>
                                <li><a href="<?php echo base_url('admin/event/category'); ?>" data-key="t-user-list"><?php echo translate('event_category'); ?></a></li>
                                <li><a href="<?php echo base_url('admin/event/booking'); ?>" data-key="t-profile"><?php echo translate('event') . " " . translate('booking'); ?></a></li>
                                <li><a href="<?php echo base_url('admin/event/payment'); ?>" data-key="t-profile"><?php echo translate('event') . " " . translate('payment'); ?></a></li>
                            </ul>
                        </li>
                    <?php endif; ?>

                    <li>
                        <a href="javascript: void(0);" class="has-arrow">
                            <i data-feather="gift"></i>
                            <span data-key="t-ui-elements"><?php echo translate('contact-us'); ?></span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="<?php echo base_url('admin/contact-us'); ?>" data-key="t-user-grid"><?php echo translate('contact-us-request'); ?></a></li>
                            <li><a href="<?php echo base_url('admin/event-inquiry'); ?>" data-key="t-user-list"><?php echo translate('event_inquiry'); ?></a></li>
                        </ul>
                    </li>


                    <li>
                        <a href="<?php echo base_url('admin/manage-content'); ?>">
                            <i data-feather="file"></i>
                            <span data-key="t-file"><?php echo translate('content_management'); ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('admin/manage-language'); ?>">
                            <i data-feather="globe"></i>
                            <span data-key="t-language"><?php echo translate('language_setting'); ?></span>
                        </a>
                    </li>

                    <?php if (get_site_setting('enable_membership') == 'Y'): ?>
                        <li>
                            <a href="javascript: void(0);" class="has-arrow">
                                <i data-feather="dollar-sign"></i>
                                <span data-key="t-ui-elements"><?php echo translate('package'); ?></span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="<?php echo base_url('admin/manage-package'); ?>" data-key="t-user-grid"><?php echo translate('manage') . " " . translate('package'); ?></a></li>
                                <li><a href="<?php echo base_url('admin/package-payment'); ?>" data-key="t-user-list"><?php echo translate('package') . " " . translate('payment'); ?></a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
            <!-- Sidebar -->
        </div>
    </div>
    <!-- Left Sidebar End -->



    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content">
        <div class="page-content">
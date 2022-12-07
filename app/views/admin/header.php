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

                <div class="dropdown d-none d-lg-inline-block ms-1">
                    <button type="button" class="btn header-item"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i data-feather="grid" class="icon-lg"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                        <div class="p-2">
                            <div class="row g-0">
                                <div class="col">
                                    <a class="dropdown-icon-item" href="#">
                                        <img src="<?php echo base_url('assets/admin/images/brands/github.png');?>" alt="Github">
                                        <span>GitHub</span>
                                    </a>
                                </div>
                                <div class="col">
                                    <a class="dropdown-icon-item" href="#">
                                        <img src="<?php echo base_url('assets/admin/images/brands/bitbucket.png');?>" alt="bitbucket">
                                        <span>Bitbucket</span>
                                    </a>
                                </div>
                                <div class="col">
                                    <a class="dropdown-icon-item" href="#">
                                        <img src="<?php echo base_url('assets/admin/images/brands/dribbble.png');?>" alt="dribbble">
                                        <span>Dribbble</span>
                                    </a>
                                </div>
                            </div>

                            <div class="row g-0">
                                <div class="col">
                                    <a class="dropdown-icon-item" href="#">
                                        <img src="<?php echo base_url('assets/admin/images/brands/dropbox.png');?>" alt="dropbox">
                                        <span>Dropbox</span>
                                    </a>
                                </div>
                                <div class="col">
                                    <a class="dropdown-icon-item" href="#">
                                        <img src="<?php echo base_url('assets/admin/images/brands/mail_chimp.png');?>" alt="mail_chimp">
                                        <span>Mail Chimp</span>
                                    </a>
                                </div>
                                <div class="col">
                                    <a class="dropdown-icon-item" href="#">
                                        <img src="<?php echo base_url('assets/admin/images/brands/slack.png');?>" alt="slack">
                                        <span>Slack</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="dropdown d-inline-block">
                    <button type="button" class="btn header-item noti-icon position-relative" id="page-header-notifications-dropdown"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i data-feather="bell" class="icon-lg"></i>
                        <span class="badge bg-danger rounded-pill">5</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                         aria-labelledby="page-header-notifications-dropdown">
                        <div class="p-3">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="m-0"> Notifications </h6>
                                </div>
                                <div class="col-auto">
                                    <a href="#!" class="small text-reset text-decoration-underline"> Unread (3)</a>
                                </div>
                            </div>
                        </div>
                        <div data-simplebar style="max-height: 230px;">
                            <a href="#!" class="text-reset notification-item">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <img src="<?php echo base_url('assets/admin/images/users/avatar-3.jpg');?>" class="rounded-circle avatar-sm" alt="user-pic">
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">James Lemire</h6>
                                        <div class="font-size-13 text-muted">
                                            <p class="mb-1">It will seem like simplified English.</p>
                                            <p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span>1 hours ago</span></p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <a href="#!" class="text-reset notification-item">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 avatar-sm me-3">
                                        <span class="avatar-title bg-primary rounded-circle font-size-16">
                                            <i class="bx bx-cart"></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">Your order is placed</h6>
                                        <div class="font-size-13 text-muted">
                                            <p class="mb-1">If several languages coalesce the grammar</p>
                                            <p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span>3 min ago</span></p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <a href="#!" class="text-reset notification-item">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 avatar-sm me-3">
                                        <span class="avatar-title bg-success rounded-circle font-size-16">
                                            <i class="bx bx-badge-check"></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">Your item is shipped</h6>
                                        <div class="font-size-13 text-muted">
                                            <p class="mb-1">If several languages coalesce the grammar</p>
                                            <p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span>3 min ago</span></p>
                                        </div>
                                    </div>
                                </div>
                            </a>

                            <a href="#!" class="text-reset notification-item">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <img src="<?php echo base_url('assets/admin/images/users/avatar-6.jpg');?>" class="rounded-circle avatar-sm" alt="user-pic">
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">Salena Layfield</h6>
                                        <div class="font-size-13 text-muted">
                                            <p class="mb-1">As a skeptical Cambridge friend of mine occidental.</p>
                                            <p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span>1 hours ago</span></p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="p-2 border-top d-grid">
                            <a class="btn btn-sm btn-link font-size-14 text-center" href="javascript:void(0)">
                                <i class="mdi mdi-arrow-right-circle me-1"></i> <span>View More..</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="dropdown d-inline-block">
                    <button type="button" class="btn header-item right-bar-toggle me-2">
                        <i data-feather="settings" class="icon-lg"></i>
                    </button>
                </div>

                <div class="dropdown d-inline-block">
                    <button type="button" class="btn header-item bg-soft-light border-start border-end" id="page-header-user-dropdown"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
                    <li class="menu-title" data-key="t-menu">Menu</li>

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
                                <li><a href="<?php echo base_url('admin/unverified-vendor'); ?>" data-key="t-user-list"><?php echo translate('unverified') . " " . translate('vendor'); ?></a></li>
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
                                <li><a href="<?php echo base_url('admin/manage-service'); ?>" data-key="t-user-grid"><?php echo translate('service'); ?></a></li>
                                <li><a href="<?php echo base_url('admin/service-category'); ?>" data-key="t-user-list"><?php echo translate('service_category'); ?></a></li>
                                <li><a href="<?php echo base_url('admin/manage-coupon'); ?>" data-key="t-profile"><?php echo translate('event_coupon'); ?></a></li>
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
                                <li><a href="<?php echo base_url('admin/manage-event'); ?>" data-key="t-user-grid"><?php echo translate('event'); ?></a></li>
                                <li><a href="<?php echo base_url('admin/event-category'); ?>" data-key="t-user-list"><?php echo translate('event_category'); ?></a></li>
                                <li><a href="<?php echo base_url('admin/event-booking'); ?>" data-key="t-profile"><?php echo translate('event') . " " . translate('booking'); ?></a></li>
                                <li><a href="<?php echo base_url('admin/event-payment'); ?>" data-key="t-profile"><?php echo translate('event') . " " . translate('payment'); ?></a></li>
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
                        <a href="<?php echo base_url('admin/sitesetting'); ?>">
                            <i data-feather="settings"></i>
                            <span data-key="t-settings"><?php echo translate('site_setting'); ?></span>
                        </a>
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

                    <li>
                        <a href="javascript: void(0);" class="has-arrow">
                            <i data-feather="layers"></i>
                            <span data-key="t-tasks"><?php echo translate('master'); ?></span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="<?php echo base_url('admin/currency'); ?>" data-key="t-user-grid"><?php echo translate('currency'); ?></a></li>
                            <li><a href="<?php echo base_url('admin/city'); ?>" data-key="t-user-list"><?php echo translate('city'); ?></a></li>
                            <li><a href="<?php echo base_url('admin/location'); ?>" data-key="t-user-list"><?php echo translate('location'); ?></a></li>
                            <?php if (get_site_setting('enable_testimonial') == 'Y'): ?>
                                <li><a href="<?php echo base_url('admin/testimonial'); ?>" data-key="t-user-list"><?php echo translate('testimonial'); ?></a></li>
                            <?php endif; ?>
                            <li><a href="<?php echo base_url('admin/manage-slider'); ?>" data-key="t-user-list"><?php echo translate('gallery_image'); ?></a></li>
                            <li><a href="<?php echo base_url('admin/manage-faq'); ?>" data-key="t-user-list"><?php echo translate('faqs'); ?></a></li>
                        </ul>
                    </li>

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


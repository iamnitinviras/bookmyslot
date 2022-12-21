<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
        <meta name="viewport" content="width=device-width">
        <link rel="icon" type="image/x-icon" href="<?php echo get_fevicon(); ?>"/>
        <title><?php
            echo (get_CompanyName());
            if (!empty($title))
                echo " | " . $title;
            ?></title>
        <link href="<?php echo $this->config->item('css_url'); ?>font-awesome.css" rel="stylesheet">
        <link href="<?php echo $this->config->item('css_url'); ?>line-awesome.min.css" rel="stylesheet">
        <link href="<?php echo $this->config->item('css_url'); ?>bootstrap.css" rel="stylesheet">
        <link href="<?php echo $this->config->item('css_url'); ?>datatables.min.css" rel="stylesheet"/>
        <link href="<?php echo $this->config->item('css_url'); ?>module/bookmyslot.css" rel="stylesheet">
        <link href="<?php echo $this->config->item('css_url'); ?>module/sidebar.css" rel="stylesheet">
        <link href="<?php echo $this->config->item('css_url'); ?>module/admin_panel.css" rel="stylesheet">
        <link href="<?php echo $this->config->item('css_url'); ?>datepicker.css" rel="stylesheet">
        <link href="<?php echo $this->config->item('css_url'); ?>timepicker.css" rel="stylesheet">
        <link href="<?php echo $this->config->item('css_url'); ?>slidePanel.css" rel="stylesheet">
        <link href="<?php echo $this->config->item('css_url'); ?>module/custom.css" rel="stylesheet">
        <link href="https://cdn.datatables.net/plug-ins/1.10.19/integration/font-awesome/dataTables.fontAwesome.css" rel="stylesheet">
         
        <script type="text/javascript" src="<?php echo $this->config->item('js_url'); ?>jquery-3.2.1.min.js"></script>        
        <script type="text/javascript" src="<?php echo $this->config->item('js_url'); ?>jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="<?php echo $this->config->item('js_url'); ?>datatables.min.js"></script>
        <script type="text/javascript" src="<?php echo $this->config->item('js_url'); ?>jquery.validate.min.js" type="text/javascript"></script>

        <script>
            var site_url = "<?php echo $this->config->item('site_url'); ?>";
            var userid = '<?php echo $this->session->userdata('Vendor_ID'); ?>';
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
        <!--loader-->
        <link href="<?php echo $this->config->item('assets_url'); ?>loader/css/preloader.css" rel="stylesheet">
        <script type="text/javascript" src="<?php echo $this->config->item('assets_url'); ?>loader/js/jquery.preloader.min.js"></script>

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="footer_fixed">
        <div id="loadingmessage" class="loadingmessage"></div>
        <div id="paymentloadingmessage" class="paymentloadingmessage"><h3> Please do not refresh the page and wait while we are processing your payment.<br>This can take a few minutes.</h3></div>
        <?php
        include VIEWPATH . 'vendor/sidebar.php';
        $url_segment = trim($this->uri->segment(1));
        $profile_active = $wallet_active = "";
        $change_password_active = "";
        $wallet_Arr = array("payout-request");
        $profile_Arr = array("vendor-profile");

        $change_passwordArr = array("vendor-update-password");
        if (isset($url_segment) && in_array($url_segment, $profile_Arr)) {
            $profile_active = "active";
        } elseif (isset($url_segment) && in_array($url_segment, $change_passwordArr)) {
            $change_password_active = "active";
        } elseif (isset($url_segment) && in_array($url_segment, $wallet_Arr)) {
            $wallet_active = "active";
        }

        $admin_id = (int) $this->session->userdata('Vendor_ID');
        $this->db->select('my_wallet as total');
        $this->db->where('id', $admin_id);
        $my_wallet = $this->db->get('app_users')->row_array();
        ?>
        <!-- Start Topbar -->
        <nav class="nav navbar py-3 white">
            <div class="container-fluid pr-0">
                <a href="" class="db-close-button"></a>
                <a href="<?php echo base_url('vendor/logout') ?>" class="db-options-button">
                    <img src="<?php echo base_url() . img_path; ?>/svg/back-icon.png" alt="db-list-right">
                </a>
                <div class="db-item">
                    <div class="db-side-bar-handler">
                        <img src="<?php echo base_url() . img_path; ?>/sidebar/db-list-left.png" alt="db-list-left">
                    </div>
                </div>
                <ul class="nav navbar-nav nav-flex-icons ml-auto sidbar_ulnav top_navbar">
                    <!-- Dropdown -->
                    <li class="nav-item" style="border: 1px solid #1f3f68;padding-right: 10px;border-radius: 50px;">
                        <a  href="<?php echo base_url() ?>" target="_blank" class="<?php echo $profile_active; ?>  nav-link waves-effect"><i class="fa fa-globe"></i> <span class="clearfix d-none d-sm-inline-block"><?php echo translate('view_website'); ?></span></a>
                    </li>
                    <li class="nav-item">
                        <a  href="<?php echo base_url('vendor/profile') ?>" class="<?php echo $profile_active; ?>  nav-link waves-effect text-muted"><i class="fa fa-user"></i> <span class="clearfix d-none d-sm-inline-block"><?php echo translate('profile_setting'); ?></span></a>
                    </li>
                    <li class="nav-item">
                        <a  href="<?php echo base_url('vendor/wallet') ?>" class="<?php echo $wallet_active; ?>  nav-link waves-effect text-muted"><i class="fa fa-dollar"></i> <span class="clearfix d-none d-sm-inline-block"><?php echo translate('earnings'); ?> $<?php echo isset($my_wallet['total']) ? $my_wallet['total'] : 0 ?></span></a>
                    </li>
                    <li class="nav-item">
                        <a  href="<?php echo base_url('vendor/update-password') ?>" class="<?php echo $change_password_active; ?> nav-link waves-effect text-muted"><i class="fa fa-key"></i> <span class="clearfix d-none d-sm-inline-block"><?php echo translate('Change_password'); ?></span></a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo base_url('vendor/logout') ?>" class="nav-link waves-effect text-muted">
                            <i class="fa fa-sign-out"></i>
                            <span class="clearfix d-none d-sm-inline-block"><?php echo translate('logout'); ?></span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
        <div style="min-height: 500px">
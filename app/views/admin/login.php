<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title><?php echo (get_CompanyName());
        if (!empty($title))
            echo " | " . $title;
        ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo get_fevicon(); ?>"/>

    <!-- preloader css -->
    <link rel="stylesheet" href="<?php echo base_url('assets/admin/css/preloader.min.css');?>" type="text/css" />

    <!-- Bootstrap Css -->
    <link href="<?php echo base_url('assets/admin/css/bootstrap.min.css');?>" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="<?php echo base_url('assets/admin/css/icons.min.css');?>" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="<?php echo base_url('assets/admin/css/app.min.css');?>" id="app-style" rel="stylesheet" type="text/css" />

</head>

<body>

<!-- <body data-layout="horizontal"> -->
<div class="auth-page">
    <div class="container-fluid p-0">
        <div class="row g-0 justify-content-center">
            <div class="col-xxl-3 col-lg-4 col-md-5">

                <div class="auth-full-page-content d-flex p-sm-5 p-4">
                    <div class="w-100">
                        <div class="d-flex flex-column h-100">
                            <div class="mb-4 mb-md-5 text-center">
                                <a href="<?php echo base_url('admin/login'); ?>" class="d-block auth-logo">
                                    <img id="imageurl" class="img-responsive img-fluid" style="" src="<?php echo get_CompanyLogo(); ?>" alt="<?php echo translate('image'); ?>">
                                </a>
                            </div>
                            <div class="auth-content my-auto">
                                <?php $this->load->view('message'); ?>

                                <div class="text-center">
                                    <h5 class="mb-0"><?php echo translate('admin_login'); ?></h5>
                                </div>

                                <?php
                                $attributes = array('id' => 'Login',"class"=>"mt-4 pt-2", 'name' => 'Login', 'method' => "post");
                                echo form_open('admin/login-action', $attributes);
                                ?>
                                    <div class="form-floating form-floating-custom mb-4">
                                        <input type="email" autocomplete="off" placeholder="<?php echo translate('email'); ?>" id="username" name="username" value='<?php if (!empty($enter_email)) echo $enter_email; ?>' class="form-control">
                                        <label for="input-username"><?php echo translate('email'); ?></label>
                                        <div class="form-floating-icon">
                                            <i data-feather="users"></i>
                                        </div>
                                        <?php echo form_error('username'); ?>
                                    </div>

                                    <div class="form-floating form-floating-custom mb-4 auth-pass-inputgroup">
                                        <input type="password" autocomplete="off"  placeholder="<?php echo translate('password'); ?>" id="password-input" name="password" class="form-control pe-5">

                                        <button type="button" class="btn btn-link position-absolute h-100 end-0 top-0" id="password-addon">
                                            <i class="mdi mdi-eye-outline font-size-18 text-muted"></i>
                                        </button>

                                        <label for="input-password"><?php echo translate('password'); ?></label>
                                        <div class="form-floating-icon">
                                            <i data-feather="lock"></i>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <button class="btn btn-primary w-100 waves-effect waves-light" type="submit"><?php echo translate('login'); ?></button>
                                    </div>

                                </form>

                                <div class="mt-5 text-center">
                                    <p class="text-muted mb-0"><a href="<?php echo base_url("admin/forgot-password"); ?>" class="text-primary fw-semibold"><?php echo translate('forgot_password'); ?>?</a></p>
                                </div>

                            </div>
                            <div class="mt-4 mt-md-5 text-center">
                                <p class="mb-0">© <script>document.write(new Date().getFullYear())</script>  <?php echo get_CompanyName();?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end auth full page content -->
            </div>
            <!-- end col -->
            <?php
            include VIEWPATH . 'admin/auth_sidebar.php';
            ?>
            <!-- end col -->
        </div>
        <!-- end row -->
    </div>
    <!-- end container fluid -->
</div>


<!-- JAVASCRIPT -->
<script src="<?php echo base_url('assets/admin/libs/jquery/jquery.min.js');?>"></script>
<script src="<?php echo base_url('assets/admin/libs/bootstrap/js/bootstrap.bundle.min.js');?>"></script>
<script src="<?php echo $this->config->item('js_url'); ?>jquery.validate.min.js"></script>
<script src="<?php echo base_url('assets/admin/libs/metismenu/metisMenu.min.js');?>"></script>
<script src="<?php echo base_url('assets/admin/libs/simplebar/simplebar.min.js');?>"></script>
<script src="<?php echo base_url('assets/admin/libs/node-waves/waves.min.js');?>"></script>
<script src="<?php echo base_url('assets/admin/libs/feather-icons/feather.min.js');?>"></script>
<script src="<?php echo base_url('assets/admin/libs/pace-js/pace.min.js');?>"></script>
<script src="<?php echo base_url('assets/admin/js/pages/pass-addon.init.js');?>"></script>
<script src="<?php echo base_url('assets/admin/js/pages/feather-icon.init.js');?>"></script>
<script src="<?php echo $this->config->item('js_url'); ?>module/content.js" type="text/javascript"></script>
</body>
</html>
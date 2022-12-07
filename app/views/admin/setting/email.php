<?php
include VIEWPATH . 'admin/header.php';
$smtp_host = isset($email_data->smtp_host) ? $email_data->smtp_host : set_value('smtp_host');
$smtp_username = isset($email_data->smtp_username) ? $email_data->smtp_username : set_value('smtp_username');
$smtp_password = isset($email_data->smtp_password) ? $email_data->smtp_password : set_value('smtp_password');
$smtp_port = isset($email_data->smtp_port) ? $email_data->smtp_port : set_value('smtp_port');
$smtp_secure = isset($email_data->smtp_secure) ? $email_data->smtp_secure : set_value('smtp_secure');
$email_from = isset($email_data->email_from) ? $email_data->email_from : set_value('email_from');
$mail_type = isset($email_data->mail_type) ? $email_data->mail_type : set_value('mail_type');
?>
<style>
    .select-wrapper input.select-dropdown {
        color: black;
    }
</style>
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6 col-xl-6">
                                <h4 class="card-title"><?php echo translate('email_setting'); ?></h4>
                                <div class="page-title-box pb-0 d-sm-flex">
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>"><?php echo translate('dashboard'); ?></a></li>
                                            <li class="breadcrumb-item active"><?php echo translate('email_setting'); ?></li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-6 text-end add-new-btn-parent"></div>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link "  href="<?php echo base_url('admin/setting/site'); ?>" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                    <span class="d-none d-sm-block"><?php echo translate('site_setting'); ?></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active"  href="<?php echo base_url('admin/setting/email'); ?>" role="tab">
                                    <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                    <span class="d-none d-sm-block"><?php echo translate('email_setting'); ?></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link"  href="<?php echo base_url('admin/setting/currency'); ?>" role="tab">
                                    <span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
                                    <span class="d-none d-sm-block"><?php echo translate('currency'); ?></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link"  href="<?php echo base_url('admin/setting/business'); ?>" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                                    <span class="d-none d-sm-block"><?php echo translate('business'); ?></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link"  href="<?php echo base_url('admin/setting/display'); ?>" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                                    <span class="d-none d-sm-block"><?php echo translate('display_setting'); ?></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link"  href="<?php echo base_url('admin/setting/payment'); ?>" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                                    <span class="d-none d-sm-block"><?php echo translate('payment_setting'); ?></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link"  href="<?php echo base_url('admin/setting/vendor'); ?>" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                                    <span class="d-none d-sm-block"><?php echo translate('vendor') . ' ' . translate('setting'); ?></span>
                                </a>
                            </li>
                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content p-3 text-muted">
                            <div class="tab-pane active" role="tabpanel">
                                <?php $this->load->view('message'); ?>
                                <?php echo form_open('admin/save-email-setting', array('name' => 'site_email_form', 'id' => 'site_email_form')); ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label style="color: #757575;" > <?php echo translate('type'); ?> <small class="required">*</small></label>
                                        <div class="form-group form-inline">
                                            <?php
                                            $smtp = $php_mailer = '';
                                            $display_block_php = $display_smtp = "display:none";
                                            if (isset($mail_type) && $mail_type == "P") {
                                                $php_mailer = "checked";
                                                $display_block_php = 'display:block';
                                            } else {
                                                $display_smtp = 'display:block';
                                                $smtp = "checked";
                                            }
                                            ?>
                                            <div class="form-group">
                                                <input name='mail_type' value="P" type='radio' onclick="func_php()" id='active' <?php echo $php_mailer; ?>>
                                                <label for="active">PHP Mailer</label>
                                            </div>
                                            <div class="form-group">
                                                <input name='mail_type' type='radio' onclick="func_smtp()" value='S' id='inactive'  <?php echo $smtp; ?>>
                                                <label for='inactive'>SMTP</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="php_block" style="<?php echo $display_block_php; ?>">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <?php echo form_label('From email : <small class ="required">*</small>', 'email_from', array('class' => 'control-label')); ?>
                                                <?php echo form_input(array('autocomplete' => 'off', 'type' => 'text', 'id' => 'email_from', 'class' => 'form-control', "required" => true, 'name' => 'email_from', 'value' => $email_from, 'placeholder' => 'From email')); ?>
                                                <?php echo form_error('email_from'); ?>
                                            </div>
                                            <div class="error" id="smtp_host_validate"></div>
                                        </div>
                                    </div>
                                </div>


                                <!---- -->
                                <div id="smtp_block" style="<?php echo $display_smtp; ?>">
                                    <div class="row mb-2">
                                        <div class="col-md-6 ">
                                            <div class="form-group">
                                                <?php echo form_label(translate('smtp_host') . ' : <small class ="required">*</small>', 'smtp_host', array('class' => 'control-label')); ?>
                                                <?php echo form_input(array('autocomplete' => 'off', 'id' => 'smtp_host', 'class' => 'form-control', 'name' => 'smtp_host', 'value' => $smtp_host, 'placeholder' => translate('smtp_host'))); ?>
                                                <?php echo form_error('smtp_host'); ?>
                                            </div>
                                            <div class="error" id="smtp_host_validate"></div>
                                        </div>
                                        <div class="col-md-6 ">
                                            <div class="form-group">
                                                <?php echo form_label(translate('password') . ' : <small class ="required">*</small>', 'smtp_password', array('class' => 'control-label')); ?>
                                                <?php echo form_password(array('autocomplete' => 'off', 'id' => 'smtp_password', 'class' => 'form-control', 'name' => 'smtp_password', 'value' => $smtp_password, 'placeholder' => translate('password'))); ?>
                                                <?php echo form_error('smtp_password'); ?>
                                            </div>
                                            <div class="error" id="smtp_password_validate"></div>
                                        </div>
                                        <div class="col-md-6 ">
                                            <div class="form-group">
                                                <?php echo form_label(translate('smtp_secure') . ' : <small class ="required">*</small>', 'smtp_secure', array('class' => 'control-label')); ?>
                                                <select name="smtp_secure" id="smtp_secure" class="form-control">
                                                    <option value="tls" <?php echo isset($smtp_secure) && $smtp_secure == 'tls' ? "selected" : "" ?>>TLS</option>
                                                    <option value="ssl" <?php echo isset($smtp_secure) && $smtp_secure == 'ssl' ? "selected" : "" ?>>SSL</option>
                                                </select>
                                                <?php echo form_error('smtp_secure'); ?>
                                            </div>
                                            <div class="error" id="smtp_secure_validate"></div>
                                        </div>
                                        <div class="col-md-6 ">
                                            <div class="form-group">
                                                <?php echo form_label(translate('username') . ' : <small class ="required">*</small>', 'smtp_username', array('class' => 'control-label')); ?>
                                                <?php echo form_input(array('autocomplete' => 'off', 'id' => 'smtp_username', 'class' => 'form-control', 'name' => 'smtp_username', 'value' => $smtp_username, 'placeholder' => translate('username'))); ?>
                                                <?php echo form_error('smtp_username'); ?>
                                            </div>
                                            <div class="error" id="smtp_username_validate"></div>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-6 ">
                                            <div class="form-group">
                                                <?php echo form_label(translate('port') . ' : <small class ="required">*</small>', 'smtp_port', array('class' => 'control-label')); ?>
                                                <?php echo form_input(array('autocomplete' => 'off', 'id' => 'smtp_port', 'class' => 'form-control', 'type' => 'number', 'name' => 'smtp_port', 'value' => $smtp_port, 'placeholder' => translate('port'))); ?>
                                                <?php echo form_error('smtp_port'); ?>
                                            </div>
                                            <div class="error" id="smtp_port_validate"></div>
                                        </div>
                                        <div class="col-md-6 ">
                                            <div class="form-group">
                                                <?php echo form_label('From email : <small class ="required">*</small>', 'email_from_smtp', array('class' => 'control-label')); ?>
                                                <?php echo form_input(array('autocomplete' => 'off', 'type' => 'email', 'id' => 'email_from_smtp', 'class' => 'form-control', "required" => true, 'name' => 'email_from_smtp', 'value' => $email_from, 'placeholder' => 'From email')); ?>
                                                <?php echo form_error('email_from_smtp'); ?>
                                            </div>
                                            <div class="error" id="smtp_host_validate"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6 b-r">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-success waves-effect"><?php echo translate('update'); ?></button>
                                        </div>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->
    </div>
<script src="<?php echo $this->config->item('js_url'); ?>module/sitesetting.js" type="text/javascript"></script>
<?php include VIEWPATH . 'admin/footer.php'; ?>
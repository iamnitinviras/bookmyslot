<?php include VIEWPATH . 'staff/header.php'; ?>
<div class="dashboard-body">
    <!-- Start Content -->
    <div class="content">
        <!-- Start Container -->
        <div class="container-fluid">
            <!--Section-->
            <section class="form-light px-2 sm-margin-b-20 ">
                <?php $this->load->view('message'); ?>

                <!--Header-->                            
                <div class="header bg-color-base p-3">
                    <h3 class="black-text font-bold mb-0">
                        <?php echo translate('change'); ?> <?php echo translate('password'); ?>
                    </h3>
                </div>                            
                <!--Header-->

                <div class="card">
                    <div class="card-body resp_mx-0">
                        <?php
                        $hidden = array("id" => $id);
                        $attributes = array('id' => 'Update_password', 'name' => 'Update_password', 'method' => "post");
                        echo form_open('staff/update-password-action', $attributes);
                        ?>
                        <div class="form-group">
                            <label for="old_password"> <?php echo translate('current'); ?> <?php echo translate('password'); ?><small class="required">*</small></label>
                            <input type="password" id="old_password" name="old_password" class="form-control" placeholder="<?php echo translate('current'); ?> <?php echo translate('password'); ?>">                                    
                            <?php echo form_error('old_password'); ?>
                        </div>
                        <div class="form-group">
                            <label for="password"> <?php echo translate('password'); ?> <small class="required">*</small></label>
                            <input type="password" id="password" name="password" class="form-control" placeholder="<?php echo translate('password'); ?>">                                    
                            <?php echo form_error('password'); ?>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password"> <?php echo translate('confirm'); ?> <?php echo translate('password'); ?> <small class="required">*</small></label>
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="<?php echo translate('confirm'); ?> <?php echo translate('password'); ?>">                                    
                            <?php echo form_error('confirm_password'); ?>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success waves-effect"><?php echo translate('update'); ?></button>
                            <a  class="btn btn-info waves-effect" href="<?php echo base_url('staff/dashboard'); ?>"><?php echo translate('cancel'); ?></a>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
                <!--/Form with header-->
            </section>
            <!-- End Section-->
        </div>
    </div>
</div>
<!-- Custom Script --><script src="<?php echo $this->config->item('js_url'); ?>module/staff_content.js" type="text/javascript"></script>
<?php include VIEWPATH . 'staff/footer.php'; ?>   

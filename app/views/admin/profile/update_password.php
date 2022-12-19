<?php include VIEWPATH . 'admin/header.php'; ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6 col-xl-6">
                            <h4 class="card-title"><?php echo $title; ?></h4>
                            <div class="page-title-box pb-0 d-sm-flex">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>"><?php echo translate('dashboard'); ?></a></li>
                                        <li class="breadcrumb-item active"><?php echo $title; ?></li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <?php
                $hidden = array("id" => $id);
                $attributes = array('id' => 'Update_password', 'name' => 'Update_password', 'method' => "post");
                echo form_open('admin/update-password-action', $attributes);
                ?>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 m-auto">
                            <?php $this->load->view('message'); ?>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="old_password"> <?php echo translate('current'); ?> <?php echo translate('password'); ?><small class="required">*</small></label>
                                        <input type="password" id="old_password" autocomplete="off"  name="old_password" class="form-control" placeholder="<?php echo translate('current'); ?> <?php echo translate('password'); ?>">
                                        <?php echo form_error('old_password'); ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="password"> <?php echo translate('password'); ?> <small class="required">*</small></label>
                                        <input type="password" id="password" autocomplete="off"  name="password" class="form-control" placeholder="<?php echo translate('password'); ?>">
                                        <?php echo form_error('password'); ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="confirm_password"> <?php echo translate('confirm'); ?> <?php echo translate('password'); ?> <small class="required">*</small></label>
                                        <input type="password" id="confirm_password" autocomplete="off"  name="confirm_password" class="form-control" placeholder="<?php echo translate('confirm'); ?> <?php echo translate('password'); ?>">
                                        <?php echo form_error('confirm_password'); ?>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary"><?php echo translate('save'); ?></button>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
    <!-- end row -->
</div>
<!-- Custom Script --><script src="<?php echo $this->config->item('js_url'); ?>module/content.js" type="text/javascript"></script>
<?php include VIEWPATH . 'admin/footer.php'; ?>   

<?php
if ($this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) == 'V') {
    include VIEWPATH . 'vendor/header.php';
    $folder_name = 'vendor';
} else {
    include VIEWPATH . 'admin/header.php';
    $folder_name = 'admin';
}
$title = (set_value("title")) ? set_value("title") : (!empty($app_faq) ? $app_faq['title'] : '');
$description = (set_value("description")) ? set_value("description") : (!empty($app_faq) ? $app_faq['description'] : '');
$status = (set_value("status")) ? set_value("status") : (!empty($app_faq) ? $app_faq['status'] : '');
$id = (set_value("id")) ? set_value("id") : (!empty($app_faq) ? $app_faq['id'] : 0);
?>
<input id="folder_name" name="folder_name" type="hidden" value="<?php echo isset($folder_name) && $folder_name != '' ? $folder_name : ''; ?>"/>
<div class="dashboard-body">
    <!-- Start Content -->
    <div class="content">
        <!-- Start Container -->
        <div class="container-fluid">
            <section class="form-light px-2 sm-margin-b-20 ">
                <?php $this->load->view('message'); ?>

                <div class="header bg-color-base p-3">
                    <h3 class="black-text font-bold mb-0">
                        <?php echo isset($id) && $id > 0 ? translate('update') : translate('add'); ?> <?php echo translate('faqs'); ?>
                    </h3>
                </div>

                <div class="card">
                    <div class="card-body resp_mx-0">
                        <?php
                        $form_url = 'admin/save-faq';
                        ?>
                        <?php
                        echo form_open($form_url, array('name' => 'FaqForm', 'id' => 'FaqForm'));
                        echo form_input(array('type' => 'hidden', 'name' => 'id', 'id' => 'id', 'value' => $id));
                        ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label for="title"> <?php echo translate('title'); ?><small class="required">*</small></label>
                                    <input type="text" autocomplete="off" id="title" name="title" value="<?php echo $title; ?>" class="form-control" placeholder="<?php echo translate('title'); ?>">                                    
                                    <?php echo form_error('title'); ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label for="description"> <?php echo translate('description'); ?><small class="required">*</small></label>
                                    <textarea id="description" class="form-control" placeholder="<?php echo translate('description'); ?>" name="description"><?php echo $description; ?></textarea>
                                    <?php echo form_error('description'); ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <label> <?php echo translate('status'); ?> <small class="required">*</small></label>
                                <div class="form-inline">
                                    <?php
                                    $active = $inactive = '';
                                    if ($status == "I") {
                                        $inactive = "checked";
                                    } else {
                                        $active = "checked";
                                    }
                                    ?>
                                    <div class="form-group mb-0">
                                        <input name='status' value="A" type='radio' id='active'   <?php echo $active; ?>>
                                        <label for="active"><?php echo translate('active'); ?></label>
                                    </div>
                                    <div class="form-group mb-0">
                                        <input name='status' type='radio'  value='I' id='inactive'  <?php echo $inactive; ?>>
                                        <label for='inactive'><?php echo translate('inactive'); ?></label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-success waves-effect"><?php echo translate('save'); ?></button>
                            <a href="<?php echo base_url('admin/manage-faq'); ?>" class="btn btn-info waves-effect"><?php echo translate('cancel'); ?></a>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                    <!--/Form with header-->
                </div>
                <!--Card-->
            </section>
            <!-- End Login-->
        </div>
    </div>
</div>
<script src="<?php echo $this->config->item('js_url'); ?>module/faq.js" type="text/javascript"></script>
<?php
include VIEWPATH . 'admin/footer.php';
?>
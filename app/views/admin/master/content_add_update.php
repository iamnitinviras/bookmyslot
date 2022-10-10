<?php
if ($this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) == 'V') {
    include VIEWPATH . 'vendor/header.php';
    $folder_name = 'vendor';
} else {
    include VIEWPATH . 'admin/header.php';
    $folder_name = 'admin';
}
$title = (set_value("title")) ? set_value("title") : (!empty($content) ? $content['title'] : '');
$description = (set_value("description")) ? set_value("description") : (!empty($content) ? $content['description'] : '');
$status = (set_value("status")) ? set_value("status") : (!empty($content) ? $content['status'] : '');
$id = (set_value("id")) ? set_value("id") : (!empty($content) ? $content['id'] : 0);
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
                        <?php echo isset($id) && $id > 0 ? translate('update') : translate('add'); ?> <?php echo translate('content_management'); ?>
                    </h3>
                </div>

                <div class="card">
                    <div class="card-body resp_mx-0">
                        <?php
                        $form_url = $folder_name . '/save-content';
                        ?>
                        <?php
                        echo form_open($form_url, array('name' => 'ContentForm', 'id' => 'ContentForm'));
                        echo form_input(array('type' => 'hidden', 'name' => 'id', 'id' => 'id', 'value' => $id));
                        ?>
                        <div class="form-group">
                            <label for="title"> <?php echo translate('title'); ?><small class="required">*</small></label>
                            <input type="text" autocomplete="off" id="title" name="title" value="<?php echo $title; ?>" class="form-control" placeholder="<?php echo translate('title'); ?>">                                    
                            <?php echo form_error('title'); ?>
                        </div>
                        <div class="form-group">
                            <label for="description"> <?php echo translate('description'); ?><small class="required">*</small></label>
                            <textarea  placeholder="<?php echo translate('description'); ?>" id="summornote_div_id" name="description" class="form-control"><?php echo $description; ?></textarea>
                            <?php echo form_error('description'); ?>
                        </div>

                        <label style="color: #757575;" > <?php echo translate('status'); ?> <small class="required">*</small></label>
                        <div class="form-group form-inline">
                            <?php
                            $active = $inactive = '';
                            if ($status == "I") {
                                $inactive = "checked";
                            } else {
                                $active = "checked";
                            }
                            ?>
                            <div class="form-group">
                                <input name='status' value="A" type='radio' id='active'   <?php echo $active; ?>>
                                <label for="active"><?php echo translate('active'); ?></label>
                            </div>
                            <div class="form-group">
                                <input name='status' type='radio'  value='I' id='inactive'  <?php echo $inactive; ?>>
                                <label for='inactive'><?php echo translate('inactive'); ?></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success waves-effect" ><?php echo translate('save'); ?></button>
                            <a href="<?php echo base_url('admin/manage-content'); ?>" class="btn btn-info waves-effect"><?php echo translate('cancel'); ?></a>
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
<script src="<?php echo $this->config->item('js_url'); ?>additional-method.js" type="text/javascript"></script>
<script src="<?php echo $this->config->item('js_url'); ?>module/page_content.js" type="text/javascript"></script>
<?php
include VIEWPATH . 'admin/footer.php';
?>
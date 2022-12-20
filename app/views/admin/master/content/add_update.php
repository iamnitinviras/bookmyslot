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
                                        <li class="breadcrumb-item"><a href="<?php echo base_url('admin/content'); ?>"><?php echo translate('content'); ?></a></li>
                                        <li class="breadcrumb-item active"><?php echo $title; ?></li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                $form_url = $folder_name . '/content/save';
                ?>
                <?php
                echo form_open($form_url, array('name' => 'ContentForm', 'id' => 'ContentForm'));
                echo form_input(array('type' => 'hidden', 'name' => 'id', 'id' => 'id', 'value' => $id));
                ?>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 m-auto">
                            <?php $this->load->view('message'); ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="title"> <?php echo translate('title'); ?><small class="required">*</small></label>
                                        <input type="text" autocomplete="off" id="title" name="title" value="<?php echo $title; ?>" class="form-control" placeholder="<?php echo translate('title'); ?>">
                                        <?php echo form_error('title'); ?>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="description"> <?php echo translate('description'); ?><small class="required">*</small></label>
                                        <textarea  placeholder="<?php echo translate('description'); ?>" id="summornote_div_id" name="description" class="form-control"><?php echo $description; ?></textarea>
                                        <?php echo form_error('description'); ?>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <label style="color: #757575;" > <?php echo translate('status'); ?> <small class="required">*</small></label>
                                    <div class="form-group">
                                        <?php
                                        $active = $inactive = '';
                                        if ($status == "I") {
                                            $inactive = "checked";
                                        } else {
                                            $active = "checked";
                                        }
                                        ?>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="status" id="active" value="A" <?php echo $active; ?>>
                                            <label class="form-check-label" for="active"><?php echo translate('active'); ?></label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="status" id="inactive"  value='I' <?php echo $inactive; ?>>
                                            <label class="form-check-label" for="inactive"><?php echo translate('inactive'); ?></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary"><?php echo translate('save'); ?></button>
                        <a href="<?php echo base_url($folder_name.'/content'); ?>" class="btn btn-outline-secondary"><?php echo translate('cancel'); ?></a>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
    <!-- end row -->
</div>
<script src="<?php echo $this->config->item('js_url'); ?>additional-method.js" type="text/javascript"></script>
<script src="<?php echo $this->config->item('js_url'); ?>module/page_content.js" type="text/javascript"></script>
<?php
include VIEWPATH . 'admin/footer.php';
?>
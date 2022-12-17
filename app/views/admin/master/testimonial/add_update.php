<?php
if ($this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) == 'V') {
    include VIEWPATH . 'vendor/header.php';
    $folder_name = 'vendor';
} else {
    include VIEWPATH . 'admin/header.php';
    $folder_name = 'admin';
}
$name = (set_value("name")) ? set_value("name") : (!empty($app_testimonial) ? $app_testimonial['name'] : '');
$details = (set_value("details")) ? set_value("details") : (!empty($app_testimonial) ? $app_testimonial['details'] : '');
$status = (set_value("status")) ? set_value("status") : (!empty($app_testimonial) ? $app_testimonial['status'] : '');
$id = (set_value("id")) ? set_value("id") : (!empty($app_testimonial) ? $app_testimonial['id'] : 0);

$image = (set_value("image")) ? set_value("image") : (!empty($app_testimonial) ? $app_testimonial['image'] : '');
if (isset($image) && $image != "") {
    if (file_exists(FCPATH . 'assets/uploads/category/' . $image)) {
        $image_path = base_url("assets/uploads/category/" . $image);
    } else {
        $image_path = base_url() . img_path . "/avatar.png";
    }
} else {
    $image_path =base_url() . img_path . "/avatar.png";
}
$form_url = 'admin/save-testimonial';
?>
<input id="folder_name" name="folder_name" type="hidden" value="<?php echo isset($folder_name) && $folder_name != '' ? $folder_name : ''; ?>"/>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6 col-xl-6">
                            <h4 class="card-title"><?php echo isset($id) && $id > 0 ? translate('update') : translate('add'); ?> <?php echo translate('testimonial'); ?></h4>
                            <div class="page-title-box pb-0 d-sm-flex">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>"><?php echo translate('dashboard'); ?></a></li>
                                        <li class="breadcrumb-item"><a href="<?php echo base_url('admin/testimonial'); ?>"><?php echo translate('testimonial'); ?></a></li>
                                        <li class="breadcrumb-item active"><?php echo isset($id) && $id > 0 ? translate('update') : translate('add'); ?> <?php echo translate('testimonial'); ?></li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                echo form_open_multipart($form_url, array('name' => 'testimonial_form', 'id' => 'testimonial_form'));
                echo form_input(array('type' => 'hidden', 'name' => 'id', 'id' => 'id', 'value' => $id));
                ?>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 m-auto">
                            <?php $this->load->view('message'); ?>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group mb-0">
                                        <label for="title"> <?php echo translate('name'); ?><small class="required">*</small></label>
                                        <input type="text" required="" autocomplete="off" id="title" name="name" value="<?php echo $name; ?>" class="form-control" placeholder="<?php echo translate('name'); ?>">
                                        <?php echo form_error('name'); ?>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group mb-0">
                                        <label for="details"> <?php echo translate('details'); ?><small class="required">*</small></label>
                                        <textarea id="details" required="" autocomplete="off"  class="form-control" placeholder="<?php echo translate('details'); ?>" name="details"><?php echo $details; ?></textarea>
                                        <?php echo form_error('details'); ?>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label><?php echo translate('status'); ?><small class="required">*</small></label>
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

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="title"> <?php echo translate('image'); ?></label><br/>
                                        <div class="d-inline-block">
                                            <?php
                                            echo form_input(array('type' => 'hidden', 'name' => 'hidden_testimonial_image', 'id' => 'hidden_testimonial_image', 'value' => $image));
                                            echo form_input(array('type' => 'file', 'id' => 'image', 'class' => 'form-control', 'name' => 'image', 'accept' => 'image/x-png,image/gif,image/jpeg,image/png'));
                                            ?><br/>
                                            <?php echo form_error('image'); ?>
                                            <img id="preview"  src="<?php echo $image_path; ?>"  style="height: 50px;width: 50px"/>
                                        </div>
                                        <div class="d-inline-block">

                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="form-group mb-0">
                        <button type="submit" class="btn btn-primary"><?php echo translate('save'); ?></button>
                        <a href="<?php echo base_url($folder_name.'/testimonial'); ?>" class="btn btn-outline-secondary"><?php echo translate('cancel'); ?></a>
                    </div>
                </div>
                <?php echo form_close(); ?>

            </div>
        </div>
    </div>
    <!-- end row -->
</div>

<script src="<?php echo $this->config->item('js_url'); ?>module/testimonial.js" type="text/javascript"></script>
<script>

</script>
<?php
include VIEWPATH . 'admin/footer.php';
?>
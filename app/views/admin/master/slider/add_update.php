<?php
if ($this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) == 'V') {
    include VIEWPATH . 'vendor/header.php';
    $folder_name = 'vendor';
    $form_url = 'vendor/slider/save';
} else {
    $folder_name = 'admin';
    $form_url = 'admin/slider/save';
    include VIEWPATH . 'admin/header.php';
}

$status = (set_value("status")) ? set_value("status") : (!empty($slider_data) ? $slider_data['status'] : '');
$image_data = !empty($slider_data) ? $slider_data['image'] : '';
$id = (set_value("id")) ? set_value("id") : (!empty($slider_data) ? $slider_data['id'] : 0);
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-12 col-xl-12">
                            <h4 class="card-title"><?php echo translate('gallery_image'); ?></h4>
                            <div class="page-title-box pb-0 d-sm-flex">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="<?php echo base_url($folder_name.'/dashboard'); ?>"><?php echo translate('dashboard'); ?></a></li>
                                        <li class="breadcrumb-item"><a href="<?php echo base_url($folder_name.'/slider'); ?>"><?php echo translate('gallery_image'); ?></a></li>
                                        <li class="breadcrumb-item active"><?php echo isset($id) && $id > 0 ? translate('update') : translate('add'); ?> <?php echo translate('gallery_image'); ?></li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 m-auto">
                            <?php $this->load->view('message'); ?>
                            <?php
                            echo form_open_multipart($form_url, array('name' => 'SliderForm', 'id' => 'SliderForm'));
                            echo form_input(array('type' => 'hidden', 'name' => 'id', 'id' => 'id', 'value' => $id));
                            echo form_input(array('type' => 'hidden', 'name' => 'old_slider_image', 'id' => 'old_slider_image', 'value' => $image_data));
                            echo form_input(array('type' => 'hidden', 'name' => 'folder_name', 'id' => 'folder_name', 'value' => isset($folder_name) && $folder_name != '' ? $folder_name : ''));
                            ?>

                            <div class="row mb-5">
                                <div class="col-md-6">
                                    <div class="form-group" id="image-data">
                                        <label for="image"><?php echo translate('image'); ?> <small class="required">*</small></label>
                                        <input type="file" id="imageurl" name="image" class="form-control" value="<?php echo $image_data; ?>" onchange="readURL(this)" <?php echo isset($image_data) && $image_data != '' ? '' : 'required'; ?> >
                                        <?php echo form_error('image'); ?>
                                    </div>
                                    <div class="form-group mt-2">
                                        <?php if (isset($image_data) && $image_data != '') { ?>
                                            <img id="imageurl"  class="img"  style="border-radius:2%;" src="<?php echo check_admin_image(UPLOAD_PATH . "slider/" . $image_data); ?>" alt="No Image" width="100" height="100">
                                        <?php } else { ?>
                                            <img id="imageurl" class="img"  style="border-radius:2%;" src="<?php echo check_admin_image(img_path . "/no-image.png"); ?>" alt="No Image" width="100" height="100">
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label"><?php echo translate('status'); ?> <small class="required">*</small></label>
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

                            <div class="d-flex flex-wrap gap-2">
                                <button type="submit" class="btn btn-primary"><?php echo translate('save'); ?></button>
                                <a href="<?php echo base_url($folder_name.'/slider'); ?>" class="btn btn-outline-secondary"><?php echo translate('cancel'); ?></a>
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
<script src="<?php echo $this->config->item('js_url'); ?>module/slider.js" type="text/javascript"></script>
<?php
if ($this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) == 'V') {
    include VIEWPATH . 'vendor/footer.php';
} else {
    include VIEWPATH . 'admin/footer.php';
}
?>
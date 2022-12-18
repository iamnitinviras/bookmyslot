<?php
if ($this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) == 'V') {
    include VIEWPATH . 'vendor/header.php';
    $folder_name = 'vendor';
    $form_url = 'vendor/service/category/save';
} else {
    include VIEWPATH . 'admin/header.php';
    $folder_name = 'admin';
    $form_url = 'admin/service/category/save';
}
$title_e = (set_value("title")) ? set_value("title") : (!empty($category_data) ? $category_data['title'] : '');
$status = (set_value("status")) ? set_value("status") : (!empty($category_data) ? $category_data['status'] : '');
$type = (set_value("type")) ? set_value("type") : (!empty($category_data) ? $category_data['type'] : '');
$event_category_image = (set_value("event_category_image")) ? set_value("event_category_image") : (!empty($category_data) ? $category_data['event_category_image'] : '');
$id = !empty($category_data) ? $category_data['id'] : 0;


if (isset($event_category_image) && $event_category_image != "") {
    if (file_exists(FCPATH . 'assets/uploads/category/' . $event_category_image)) {
        $image = base_url("assets/uploads/category/" . $event_category_image);
    } else {
        $image = base_url("assets/images/no-image.png");
    }
} else {
    $image = base_url("assets/images/no-image.png");
}
?>
<input id="folder_name" name="folder_name" type="hidden" value="<?php echo isset($folder_name) && $folder_name != '' ? $folder_name : ''; ?>"/>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6 col-xl-6">
                            <h4 class="card-title"><?php echo isset($id) && $id > 0 ? translate('update') : translate('add'); ?> <?php echo translate("service") . " " . translate('category'); ?></h4>
                            <div class="page-title-box pb-0 d-sm-flex">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>"><?php echo translate('dashboard'); ?></a></li>
                                        <li class="breadcrumb-item"><a href="<?php echo base_url($folder_name.'/service/category'); ?>"><?php echo translate("service") . " " . translate('category'); ?></a></li>
                                        <li class="breadcrumb-item active"><?php echo isset($id) && $id > 0 ? translate('update') : translate('add'); ?> <?php echo translate("service") . " " . translate('category'); ?></li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <?php
                echo form_open_multipart($form_url, array('name' => 'ServiceCategoryForm', 'id' => 'ServiceCategoryForm'));
                echo form_input(array('type' => 'hidden', 'name' => 'id', 'id' => 'id', 'value' => $id));
                echo form_input(array('type' => 'hidden', 'name' => 'image_validate', 'id' => 'image_validate', 'value' => 0));
                ?>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 m-auto">
                            <?php $this->load->view('message'); ?>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="title"> <?php echo translate('title'); ?><small class="required">*</small></label>
                                        <input type="text"  autocomplete="off" id="title" maxlength="40" name="title" value="<?php echo $title_e; ?>" class="form-control" placeholder="<?php echo translate('title'); ?>">
                                        <?php echo form_error('title'); ?>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="title"><?php echo translate('event_category_image'); ?><small class="required">*</small></label><br/>
                                        <?php
                                        echo form_input(array('type' => 'hidden', 'name' => 'hidden_category_image', 'id' => 'hidden_category_image', 'value' => $event_category_image));
                                        if ($id == 0) {
                                            echo form_input(array('type' => 'file', 'required' => "true", 'id' => 'event_category_image', 'class' => 'form-control', 'name' => 'event_category_image', 'accept' => 'image/x-png,image/gif,image/jpeg,image/png'));
                                        } else {
                                            echo form_input(array('type' => 'file', 'id' => 'event_category_image', 'class' => 'form-control', 'name' => 'event_category_image', 'accept' => 'image/x-png,image/gif,image/jpeg,image/png'));
                                        }
                                        ?><br/>
                                        <img id="preview" class="mt-2" src="<?php echo $image; ?>"  style="height: 50px;width: 50px"/>
                                        <?php echo form_error('event_category_image'); ?>
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
                                <div class="col-md-12">
                                    <small> (Image size must be 40X40.)</small>
                                </div>
                            </div>



                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary"><?php echo translate('save'); ?></button>
                        <a href="<?php echo base_url($folder_name.'/service/category'); ?>" class="btn btn-outline-secondary"><?php echo translate('cancel'); ?></a>
                    </div>
                </div>
                <?php echo form_close(); ?>

            </div>
        </div>
    </div>
    <!-- end row -->
</div>

<script src="<?php echo $this->config->item('js_url'); ?>module/service-category.js" type="text/javascript"></script>
<?php
if ($this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) == 'V') {
    include VIEWPATH . 'vendor/footer.php';
} else {
    include VIEWPATH . 'admin/footer.php';
}
?>
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#preview').attr('src', e.target.result);
                $('#image_validate').attr('value', 1);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#event_category_image").change(function () {
        readURL(this);
    });
</script>
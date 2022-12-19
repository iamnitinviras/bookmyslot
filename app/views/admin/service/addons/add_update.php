<?php
if ($this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) == 'V') {
    include VIEWPATH . 'vendor/header.php';
    $folder_name = 'vendor';
    $form_url = 'vendor/service/addons/save';
} else {
    include VIEWPATH . 'admin/header.php';
    $folder_name = 'admin';
    $form_url = 'admin/service/addons/save';
}
$title = (set_value("title")) ? set_value("title") : (!empty($app_service_addons) ? $app_service_addons['title'] : '');
$details = (set_value("details")) ? set_value("details") : (!empty($app_service_addons) ? $app_service_addons['details'] : '');
$price = (set_value("price")) ? set_value("price") : (!empty($app_service_addons) ? $app_service_addons['price'] : '');
$event_add_on_image = isset($app_service_addons['image']) ? $app_service_addons['image'] : "";
$add_on_id = !empty($app_service_addons) ? $app_service_addons['add_on_id'] : 0;


if (isset($app_service_addons['image']) && $app_service_addons['image'] != "") {
    if (file_exists(FCPATH . 'assets/uploads/event/' . $app_service_addons['image'])) {
        $image = base_url("assets/uploads/event/" . $app_service_addons['image']);
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
                            <h4 class="card-title"><?php echo $title; ?></h4>
                            <div class="page-title-box pb-0 d-sm-flex">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>"><?php echo translate('dashboard'); ?></a></li>
                                        <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>"><?php echo translate('dashboard'); ?></a></li>
                                        <li class="breadcrumb-item active"><?php echo $title; ?></li>
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
                            echo form_open_multipart($form_url, array('name' => 'ServiceAddonsForm', 'id' => 'ServiceAddonsForm'));
                            echo form_input(array('type' => 'hidden', 'name' => 'id', 'id' => 'id', 'value' => $add_on_id));
                            echo form_input(array('type' => 'hidden', 'name' => 'image_validate', 'id' => 'image_validate', 'value' => 0));
                            echo form_input(array('type' => 'hidden', 'name' => 'service_id', 'id' => 'service_id', 'value' => $service_id));
                            ?>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="title"> <?php echo translate('title'); ?><small class="required">*</small></label>
                                        <input type="text" autocomplete="off"  id="title" required="" name="title" value="<?php echo $title; ?>" class="form-control" placeholder="<?php echo translate('title'); ?>">
                                        <?php echo form_error('title'); ?>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="details"> <?php echo translate('description'); ?><small class="required">*</small></label>
                                        <textarea id="details"name="details" required=""  placeholder="<?php echo translate('description'); ?>" class="form-control"><?php echo $title; ?></textarea>
                                        <?php echo form_error('details'); ?>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="price"> <?php echo translate('price'); ?><small class="required">*</small></label>
                                        <input type="number"  autocomplete="off" id="price" min="1" required="" name="price" value="<?php echo $price; ?>" class="form-control" placeholder="<?php echo translate('price'); ?>">
                                        <?php echo form_error('price'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="title" class="mt-2"> <?php echo translate('image'); ?><small class="required">*</small></label><br/>
                                        <div class="d-inline-block">
                                            <img id="preview"  src="<?php echo $image; ?>"  style="height: 50px;width: 50px"/>
                                        </div>

                                        <div class="d-inline-block">
                                            <?php
                                            echo form_input(array('type' => 'hidden', 'name' => 'hidden_add_on_image', 'id' => 'hidden_add_on_image', 'value' => $event_add_on_image));
                                            if ($add_on_id == 0) {
                                                echo form_input(array('type' => 'file', 'required' => "true", 'id' => 'event_add_on_image', 'class' => 'form-control', 'name' => 'event_add_on_image', 'accept' => 'image/x-png,image/gif,image/jpeg,image/png'));
                                            } else {
                                                echo form_input(array('type' => 'file', 'id' => 'event_add_on_image', 'class' => 'form-control', 'name' => 'event_add_on_image', 'accept' => 'image/x-png,image/gif,image/jpeg,image/png'));
                                            }
                                            ?><br/>
                                            <?php echo form_error('event_add_on_image'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success waves-effect" style="margin-top: 25px;"><?php echo translate('save'); ?></button>
                                <button type="button" onclick="goBack()" class="btn btn-info waves-effect" style="margin-top: 25px;"><?php echo translate('cancel'); ?></button>
                            </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary"><?php echo translate('save'); ?></button>
                    </div>
                </div>
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

    $("#event_add_on_image").change(function () {
        readURL(this);
    });
</script>
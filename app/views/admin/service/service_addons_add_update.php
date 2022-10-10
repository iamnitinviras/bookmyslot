<?php
if ($this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) == 'V') {
    include VIEWPATH . 'vendor/header.php';
    $folder_name = 'vendor';
} else {
    include VIEWPATH . 'admin/header.php';
    $folder_name = 'admin';
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
<div class="dashboard-body">
    <!-- Start Content -->
    <div class="content">
        <!-- Start Container -->
        <div class="container-fluid">
            <section class="form-light px-2 sm-margin-b-20 ">
                <?php $this->load->view('message'); ?>

                <div class="header bg-color-base p-3">
                    <h3 class="black-text font-bold mb-0"><?php echo isset($add_on_id) && $add_on_id > 0 ? translate('update') : translate('add'); ?> <?php echo translate("service") . " " . translate('add_ons'); ?></h3>
                </div>

                <div class="card">
                    <div class="card-body resp_mx-0">
                        <?php
                        if ($this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) == 'V') {
                            $form_url = 'vendor/save-service-addons';
                        } else {
                            $form_url = 'admin/save-service-addons';
                        }
                        ?>
                        <?php
                        echo form_open_multipart($form_url, array('name' => 'ServiceAddonsForm', 'id' => 'ServiceAddonsForm'));
                        echo form_input(array('type' => 'hidden', 'name' => 'id', 'id' => 'id', 'value' => $add_on_id));
                        echo form_input(array('type' => 'hidden', 'name' => 'image_validate', 'id' => 'image_validate', 'value' => 0));
                        echo form_input(array('type' => 'hidden', 'name' => 'service_id', 'id' => 'service_id', 'value' => $service_id));
                        ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title"> <?php echo translate('title'); ?><small class="required">*</small></label>
                                    <input type="text" autocomplete="off"  id="title" required="" name="title" value="<?php echo $title; ?>" class="form-control" placeholder="<?php echo translate('title'); ?>">                                    
                                    <?php echo form_error('title'); ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="details"> <?php echo translate('description'); ?><small class="required">*</small></label>
                                    <textarea id="details"name="details" required=""  placeholder="<?php echo translate('description'); ?>" class="form-control"><?php echo $title; ?></textarea>
                                    <?php echo form_error('details'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="price"> <?php echo translate('price'); ?><small class="required">*</small></label>
                                    <input type="number"  autocomplete="off" id="price" min="1" required="" name="price" value="<?php echo $price; ?>" class="form-control" placeholder="<?php echo translate('price'); ?>">
                                    <?php echo form_error('price'); ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title" class="mt-2"> <?php echo translate('image'); ?><small class="required">*</small></label><br/>
                                    <div class="d-inline-block">
                                        <img id="preview"  src="<?php echo $image; ?>"  style="height: 50px;width: 50px"/>
                                    </div>

                                    <div class="d-inline-block">
                                        <?php
                                        echo form_input(array('type' => 'hidden', 'name' => 'hidden_add_on_image', 'id' => 'hidden_add_on_image', 'value' => $event_add_on_image));
                                        if ($add_on_id == 0) {
                                            echo form_input(array('type' => 'file', 'required' => "true", 'id' => 'event_add_on_image', 'class' => '', 'name' => 'event_add_on_image', 'accept' => 'image/x-png,image/gif,image/jpeg,image/png'));
                                        } else {
                                            echo form_input(array('type' => 'file', 'id' => 'event_add_on_image', 'class' => '', 'name' => 'event_add_on_image', 'accept' => 'image/x-png,image/gif,image/jpeg,image/png'));
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
                    <!--/Form with header-->
                </div>
                <!--Card-->
            </section>
            <!-- End Login-->
        </div>
    </div>
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
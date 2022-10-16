<?php
if ($this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) == 'V') {
    include VIEWPATH . 'vendor/header.php';
    $folder_name = 'vendor';
} else {
    include VIEWPATH . 'admin/header.php';
    $folder_name = 'admin';
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
<div class="dashboard-body">
    <!-- Start Content -->
    <div class="content">
        <!-- Start Container -->
        <div class="container-fluid">
            <section class="form-light px-2 sm-margin-b-20 ">
                <?php $this->load->view('message'); ?>
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="black-text font-bold mb-0"><?php echo isset($id) && $id > 0 ? translate('update') : translate('add'); ?> <?php echo translate("service") . " " . translate('category'); ?></h5>
                    </div>
                    <div class="card-body resp_mx-0">
                        <?php
                        if ($this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) == 'V') {
                            $form_url = 'vendor/save-service-category';
                        } else {
                            $form_url = 'admin/save-service-category';
                        }
                        ?>
                        <?php
                        echo form_open_multipart($form_url, array('name' => 'ServiceCategoryForm', 'id' => 'ServiceCategoryForm'));
                        echo form_input(array('type' => 'hidden', 'name' => 'id', 'id' => 'id', 'value' => $id));

                        echo form_input(array('type' => 'hidden', 'name' => 'image_validate', 'id' => 'image_validate', 'value' => 0));
                        ?>
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
                                    <label for="title" class="mt-2"><?php echo translate('event_category_image'); ?><small class="required">*</small></label><br/>
                                    <?php
                                    echo form_input(array('type' => 'hidden', 'name' => 'hidden_category_image', 'id' => 'hidden_category_image', 'value' => $event_category_image));
                                    if ($id == 0) {
                                        echo form_input(array('type' => 'file', 'required' => "true", 'id' => 'event_category_image', 'class' => '', 'name' => 'event_category_image', 'accept' => 'image/x-png,image/gif,image/jpeg,image/png'));
                                    } else {
                                        echo form_input(array('type' => 'file', 'id' => 'event_category_image', 'class' => '', 'name' => 'event_category_image', 'accept' => 'image/x-png,image/gif,image/jpeg,image/png'));
                                    }
                                    ?><br/>
                                    <img id="preview" class="mt-2" src="<?php echo $image; ?>"  style="height: 50px;width: 50px"/>
                                    <?php echo form_error('event_category_image'); ?>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label><?php echo translate('status'); ?> <small class="required">*</small></label>
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
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <small> (Image size must be 40X40.)</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-success waves-effect"><?php echo translate('save'); ?></button>
                            <a href="<?php echo base_url($folder_name . '/service-category'); ?>" class="btn btn-info waves-effect"><?php echo translate('cancel'); ?></a>
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

    $("#event_category_image").change(function () {
        readURL(this);
    });
</script>
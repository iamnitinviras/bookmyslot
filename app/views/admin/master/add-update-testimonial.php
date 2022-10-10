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
                        <?php echo isset($id) && $id > 0 ? translate('update') : translate('add'); ?> <?php echo translate('testimonial'); ?>
                    </h3>
                </div>

                <div class="card">
                    <div class="card-body resp_mx-0">
                        <?php
                        $form_url = 'admin/save-testimonial';
                        ?>
                        <?php
                        echo form_open_multipart($form_url, array('name' => 'testimonial_form', 'id' => 'testimonial_form'));
                        echo form_input(array('type' => 'hidden', 'name' => 'id', 'id' => 'id', 'value' => $id));
                        ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label for="title"> <?php echo translate('name'); ?><small class="required">*</small></label>
                                    <input type="text" required="" autocomplete="off" id="title" name="name" value="<?php echo $name; ?>" class="form-control" placeholder="<?php echo translate('name'); ?>">                                    
                                    <?php echo form_error('name'); ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label for="details"> <?php echo translate('details'); ?><small class="required">*</small></label>
                                    <textarea id="details" required="" autocomplete="off"  class="form-control" placeholder="<?php echo translate('details'); ?>" name="details"><?php echo $details; ?></textarea>
                                    <?php echo form_error('details'); ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title" class="mt-2"> <?php echo translate('image'); ?></label><br/>
                                    <div class="d-inline-block">
                                        <img id="preview"  src="<?php echo $image_path; ?>"  style="height: 50px;width: 50px"/>
                                    </div>

                                    <div class="d-inline-block">
                                        <?php
                                        echo form_input(array('type' => 'hidden', 'name' => 'hidden_testimonial_image', 'id' => 'hidden_testimonial_image', 'value' => $image));
                                        echo form_input(array('type' => 'file', 'id' => 'image', 'class' => '', 'name' => 'image', 'accept' => 'image/x-png,image/gif,image/jpeg,image/png'));
                                        ?><br/>
                                        <?php echo form_error('image'); ?>
                                    </div>


                                </div>

                            </div>
                            <div class="col-md-6">
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
                            <a href="<?php echo base_url('admin/testimonial'); ?>" class="btn btn-info waves-effect"><?php echo translate('cancel'); ?></a>
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
<script src="<?php echo $this->config->item('js_url'); ?>module/testimonial.js" type="text/javascript"></script>
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#preview').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#image").change(function () {
        readURL(this);
    });
</script>
<?php
include VIEWPATH . 'admin/footer.php';
?>
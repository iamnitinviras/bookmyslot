<?php
$id = isset($staff_data['id']) ? $staff_data['id'] : set_value('id');
$first_name = isset($staff_data['first_name']) ? $staff_data['first_name'] : set_value('first_name');
$last_name = isset($staff_data['last_name']) ? $staff_data['last_name'] : set_value('last_name');
$email = isset($staff_data['email']) ? $staff_data['email'] : set_value('email');
$designation = isset($staff_data['designation']) ? $staff_data['designation'] : set_value('designation');
$phone = isset($staff_data['phone']) ? $staff_data['phone'] : set_value('phone');
$status = (set_value("status")) ? set_value("status") : (!empty($category_data) ? $staff_data['status'] : '');
$profile_image = (set_value("profile_image")) ? set_value("profile_image") : (!empty($staff_data) ? $staff_data['profile_image'] : '');
if (isset($profile_image) && $profile_image != "") {
    if (file_exists(FCPATH . 'assets/uploads/profiles/' . $profile_image)) {
        $image = base_url("assets/uploads/profiles/" . $profile_image);
    } else {
        $image = base_url("assets/images/no-image.png");
    }
} else {
    $image = base_url("assets/images/no-image.png");
}
if ($this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) == 'V') {
    include VIEWPATH . 'vendor/header.php';
    $folder_name = 'vendor';
} else {
    include VIEWPATH . 'admin/header.php';
    $folder_name = 'admin';
}
?>
<style>
    .select-wrapper input.select-dropdown {
        color: black;
    }
</style>
<div class="dashboard-body">
    <!-- Start Content -->
    <div class="content">
        <!-- Start Container -->
        <div class="container-fluid">
            <section class="form-light px-2 sm-margin-b-20 ">
                <!-- Row -->
                <div class="row">
                    <div class="col-md-12 m-auto">
                        <?php $this->load->view('message'); ?>

                        <div class="header bg-color-base p-3">
                            <?php if (isset($staff_data['id']) && $staff_data['id'] > 0): ?>
                                <h3 class="black-text mb-0 font-bold"><?php echo translate('update'); ?> <?php echo translate('staff'); ?></h3>
                            <?php else: ?>
                                <h3 class="black-text mb-0 font-bold"><?php echo translate('add'); ?> <?php echo translate('staff'); ?></h3>
                            <?php endif; ?>
                        </div>

                        <div class="card">
                            <div class="card-body resp_mx-0">
                                <?php
                                $attributes = array('id' => 'frmStaff', 'name' => 'frmCustomer', 'method' => "post");
                                echo form_open_multipart($folder_name . '/save-staff', $attributes);
                                ?>
                                <input type="hidden" id="folder_name" value="<?php echo $folder_name; ?>"/>
                                <input type="hidden" name="staff_id" id="staff_id" value="<?php echo isset($staff_data['id']) ? $staff_data['id'] : 0; ?>"/>
                                <div class="row">
                                    <div class="col-md-6 ">
                                        <div class="form-group">
                                            <?php echo form_label(translate('first_name') . ' : <small class ="required">*</small>', 'first_name', array('class' => 'control-label')); ?>
                                            <?php echo form_input(array('autocomplete'=>'off','id' => 'first_name', 'class' => 'form-control', 'name' => 'first_name', 'value' => $first_name, 'placeholder' => translate('first_name'))); ?>
                                            <?php echo form_error('first_name'); ?>
                                        </div>
                                        <div class="error" id="first_name_validate"></div>
                                    </div>
                                    <div class="col-md-6 ">
                                        <div class="form-group">
                                            <?php echo form_label(translate('last_name') . ' : <small class ="required">*</small>', 'last_name', array('class' => 'control-label')); ?>
                                            <?php echo form_input(array('autocomplete'=>'off','id' => 'last_name', 'class' => 'form-control', 'name' => 'last_name', 'value' => $last_name, 'placeholder' => translate('last_name'))); ?>
                                            <?php echo form_error('last_name'); ?>
                                        </div>
                                        <div class="error" id="last_name_validate"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 ">
                                        <div class="form-group">
                                            <?php echo form_label(translate('email') . ' : <small class ="required">*</small>', 'email', array('class' => 'control-label')); ?>
                                            <?php echo form_input(array('autocomplete'=>'off','type' => 'email', 'id' => 'email', 'class' => 'form-control', 'name' => 'email', 'value' => $email, 'placeholder' => translate('email'))); ?>
                                            <?php echo form_error('email'); ?>
                                        </div>
                                        <div class="error" id="email_validate"></div>
                                    </div>
                                    <div class="col-md-6 ">
                                        <div class="form-group">
                                            <?php echo form_label(translate('phone') . ' :', 'phone', array('class' => 'control-label')); ?>
                                            <?php echo form_input(array('autocomplete'=>'off','minlength' => "10", 'maxlength' => "10", 'id' => 'phone', 'class' => 'form-control', 'name' => 'phone', 'value' => $phone, 'placeholder' => translate('phone'))); ?>
                                            <?php echo form_error('phone'); ?>
                                        </div>
                                        <div class="error" id="phone_validate"></div>
                                    </div> 
                                </div>
                                <div class="row">
                                    <div class="col-md-6 ">
                                        <div class="form-group">
                                            <?php echo form_label(translate('designation') . ' : <small class ="required">*</small>', 'email', array('class' => 'control-label')); ?>
                                            <?php echo form_input(array('autocomplete'=>'off','type' => 'text','required' => "true", 'id' => 'designation', 'class' => 'form-control', 'name' => 'designation', 'value' => $designation, 'placeholder' => translate('designation'))); ?>
                                            <?php echo form_error('designation'); ?>
                                        </div>
                                        <div class="error" id="email_validate"></div>
                                    </div>
                                    
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="title" class="mt-2"> <?php echo translate('profile_image'); ?></label><br/>
                                            <div class="d-inline-block">
                                                <img id="preview"  src="<?php echo $image; ?>"  style="height: 50px;width: 50px"/>
                                            </div>

                                            <div class="d-inline-block">
                                                <?php
                                                echo form_input(array('type' => 'hidden', 'name' => 'hidden_profile_image', 'id' => 'hidden_profile_image', 'value' => $profile_image));
                                                if ($id == 0) {
                                                    echo form_input(array('type' => 'file', 'required' => "true", 'id' => 'profile_image', 'class' => '', 'name' => 'profile_image', 'accept' => 'image/x-png,image/gif,image/jpeg,image/png'));
                                                } else {
                                                    echo form_input(array('type' => 'file', 'id' => 'profile_image', 'class' => '', 'name' => 'profile_image', 'accept' => 'image/x-png,image/gif,image/jpeg,image/png'));
                                                }
                                                ?><br/>
                                                <?php echo form_error('profile_image'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
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
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6 b-r">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-success waves-effect" style="margin-top: 25px;"><?php echo translate('submit'); ?></button>
                                            <a href="<?php echo base_url($folder_name . '/staff'); ?>" class="btn btn-info waves-effect" style="margin-top: 25px;"><?php echo translate('cancel'); ?></a>
                                        </div>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>
                        <!--/Form with header-->
                    </div>
                    <!--Card-->
                </div>
                <!-- End Col -->
            </section>
        </div>
        <!--Row-->
        <!-- End Login-->
    </div>
</div>
<script src="<?php echo $this->config->item('js_url'); ?>module/staff.js" type="text/javascript"></script>
<?php include VIEWPATH . $folder_name . '/footer.php'; ?>

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

    $("#profile_image").change(function () {
        readURL(this);
    });
</script>
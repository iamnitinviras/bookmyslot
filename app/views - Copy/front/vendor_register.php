<?php include VIEWPATH . 'front/header.php'; ?>
<!-- Start Content -->
<script src="<?php echo $this->config->item('js_url'); ?>module/additional-methods.js" type="text/javascript"></script>
<section class="form-light">
    <div class="container-fluid">
        <!-- Row -->
        <div class="row">
            <div class="col-lg-4 col-md-7 mx-md-auto my-4">

                <div class="card my-3">
                    <div class="header">
                        <h3 class="my-3 text-center"><?php echo translate('vendor_registration'); ?></h3>
                    </div>

                    <div class="n_page-redirect mt-3">
                        <p><?php echo translate('already_created_account?'); ?> <a href="<?php echo base_url("vendor/login"); ?>" class="ml-1 font-bold"> <?php echo translate('login'); ?></a></p>
                    </div>

                    <div class="card-body resp_mx-0">
                        <?php $this->load->view('message'); ?>

                        <div class="steps-form-2 d-none">
                            <div class="steps-row-2 setup-panel-2 d-flex justify-content-between">
                                <div class="steps-step-2">
                                    <a href="#step-4" type="button" class="btn btn-amber btn-rounded waves-effect ml-0" data-toggle="tooltip" data-placement="top" title="Basic Information">
                                        <?php echo translate('account'); ?> <?php echo translate('information'); ?>
                                    </a>
                                </div>
                                <div class="steps-step-2">
                                    <a href="#step-6" type="button" class="btn btn-blue-grey btn-rounded waves-effect" data-toggle="tooltip" data-placement="top" title=" Media">
                                        <?php echo translate('business'); ?> <?php echo translate('information'); ?>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <?php
                        $attributes = array('id' => 'Register_user', 'name' => 'Register_user', 'method' => "post");
                        echo form_open_multipart('vendor-register-save', $attributes);
                        ?>

                        <div class="setup-content-2" id="step-4">
                            <h3 class="font-bold pl-0 mt-0 mb-4"><strong><?php echo translate('account'); ?> <?php echo translate('information'); ?></strong></h3>

                            <div class="form-group">
                                <label for="first_name"> <?php echo translate('first_name'); ?> <small class="required">*</small></label>
                                <input type="text" id="first_name" autocomplete="off" name="first_name" class="form-control" placeholder="<?php echo translate('first_name'); ?>" value="<?php echo set_value('first_name', $this->input->post('first_name')); ?>">                                        
                                <?php echo form_error('first_name'); ?>
                            </div>
                            <div class="error" id="first_name_validate"></div>

                            <div class="form-group">
                                <label for="last_name"> <?php echo translate('last_name'); ?><small class="required">*</small></label>
                                <input type="text" id="last_name" autocomplete="off"  name="last_name" class="form-control" placeholder="<?php echo translate('last_name'); ?>" value="<?php echo set_value('last_name', $this->input->post('last_name')); ?>">                                        
                                <?php echo form_error('last_name'); ?>
                            </div>
                            <div class="error" id="last_name_validate"></div>

                            <div class="form-group">
                                <label for="email"> <?php echo translate('email'); ?> <small class="required">*</small></label>
                                <input type="email" id="email" name="email" autocomplete="off"  class="form-control" placeholder="<?php echo translate('email'); ?>"  value="<?php echo set_value('email', $this->input->post('email')); ?>">                                        
                                <?php echo form_error('email'); ?>
                            </div>
                            <div class="error" id="email_validate"></div>

                            <div class="form-group">
                                <label for="password"> <?php echo translate('password'); ?> <small class="required mr-5px">*</small>
                                    <i class="fa fa-info-circle" tabindex="0" data-html="true" data-toggle="popover" title="<b>Password</b> - Rules" data-content='<span class="d-block"><b> <?php echo translate('info'); ?> - </b></span><span class="d-block">- <?php echo translate('password_length'); ?>'></i></label>
                                <input type="password" id="password" name="password"  autocomplete="off" class="form-control" placeholder="<?php echo translate('password'); ?>">                                        
                                <?php echo form_error('password'); ?>
                            </div>
                            <div class="error" id="password_validate"></div>

                            <button class="btn btn-dark button_common nextBtn-2 float-right mb-4" type="button"><?php echo translate('next'); ?></button>
                        </div>

                        <div class="setup-content-2" id="step-6">
                            <h3 class="font-bold pl-0 mt-0 mb-4"><strong><?php echo translate('business'); ?> <?php echo translate('information'); ?></strong></h3>

                            <div class="form-group">
                                <label for="address"> <?php echo translate('address'); ?> <small class="required">*</small></label>
                                <textarea class="form-control" name="address" id="address"  autocomplete="off" placeholder="<?php echo translate('address'); ?>"><?php echo set_value('address', $this->input->post('address')); ?></textarea>
                                <?php echo form_error('address'); ?>
                            </div>
                            <div class="error" id="address_validate"></div>

                            <div class="form-group">
                                <label for="phone"> <?php echo translate('phone'); ?> <small class="required">*</small></label>
                                <input type="text" id="phone" name="phone" class="form-control phone_integers"  autocomplete="off"  placeholder="<?php echo translate('phone'); ?>" minlength="10" maxlength="16"  value="<?php echo set_value('phone', $this->input->post('phone')); ?>">                                        
                                <?php echo form_error('phone'); ?>
                            </div>
                            <div class="error" id="phone_validate"></div>

                            <div class="form-group">
                                <label for="company"> <?php echo translate('company'); ?> <small class="required">*</small></label>
                                <input type="text" id="company" name="company" class="form-control"  autocomplete="off"  placeholder="<?php echo translate('company'); ?>"  value="<?php echo set_value('company', $this->input->post('company')); ?>">                                        
                                <?php echo form_error('company'); ?>
                            </div>
                            <div class="error" id="company_validate"></div>

                            <div class="form-group">
                                <label for="website"> <?php echo translate('website'); ?></label>
                                <input type="text" id="website" name="website" class="form-control"  autocomplete="off"  placeholder="<?php echo translate('website'); ?>"  value="<?php echo set_value('website', $this->input->post('website')); ?>">                                        
                                <?php echo form_error('website'); ?>
                            </div>
                            <div class="error" id="website_validate"></div>

                            <button class="btn btn-dark button_common prevBtn-2 float-left mb-4" type="button"><?php echo translate('previous'); ?></button>
                            <button class="btn btn-dark button_common float-right mb-4" type="submit"><?php echo translate('register'); ?></button>
                        </div>                   
                        <?php echo form_close(); ?>

                    </div>
                </div>
            </div>
        </div>
        <!-- End Col -->
    </div>
</section>

<script>
    // Steppers                
    $(document).ready(function () {
        var navListItems = $('div.setup-panel-2 div a'),
                allWells = $('.setup-content-2'),
                allNextBtn = $('.nextBtn-2'),
                allPrevBtn = $('.prevBtn-2');

        allWells.hide();

        navListItems.click(function (e) {
            e.preventDefault();
            var $target = $($(this).attr('href')),
                    $item = $(this);

            if (!$item.hasClass('disabled')) {
                navListItems.removeClass('btn-amber').addClass('btn-blue-grey');
                $item.addClass('btn-amber');
                allWells.hide();
                $target.show();
                $target.find('input:eq(0)').focus();
            }
        });

        allPrevBtn.click(function () {
            var curStep = $(this).closest(".setup-content-2"),
                    curStepBtn = curStep.attr("id"),
                    prevStepSteps = $('div.setup-panel-2 div a[href="#' + curStepBtn + '"]').parent().prev().children("a");

            prevStepSteps.removeAttr('disabled').trigger('click');
        });

        allNextBtn.click(function () {
            var curStep = $(this).closest(".setup-content-2"),
                    curStepBtn = curStep.attr("id"),
                    nextStepSteps = $('div.setup-panel-2 div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
                    curInputs = curStep.find("input[type='number'], input[type='text'],input[type='password'],input[type='url'],input[type='email'],input[type='file'], textarea ,select"),
                    isValid = true;

            var form = $("#site_form");
            form.validate({
                ignore: [],
                rules: {
                    company_name: {
                        required: true
                    },
                    company_email1: {
                        required: true,
                        email: true
                    },
                    home_page: {
                        required: true
                    },
                    Pro_img: {
                        extension: "png|jpeg|jpg",
                    },
                    banner_img: {
                        extension: "png|jpeg|jpg",
                    },
                    fevicon_icon: {
                        extension: "ico",
                    }
                },
                messages: {
                    company_name: {
                        required: "Please Enter Company name"
                    },
                    company_email1: {
                        required: "Please enter email ",
                        email: "Please enter valid email "
                    },
                    home_page: {
                        required: "Please select home page ",
                    },
                    Pro_img: {
                        extension: "File must be JPEG or PNG "
                    },
                    banner_img: {
                        extension: "File must be JPEG or PNG "
                    }

                },
                errorElement: 'div',
                errorPlacement: function (error, element) {
                    element.parents(".form-group").append(error);
                }
            });
            if (!curInputs.valid()) {
                return false;
            }
            if (isValid)
                nextStepSteps.removeAttr('disabled').trigger('click');
        });

        $('div.setup-panel-2 div a.btn-amber').trigger('click');


    });
</script>
<script src="<?php echo $this->config->item('js_url'); ?>module/vendor_register.js" type="text/javascript"></script>
<?php include VIEWPATH . 'front/footer.php'; ?>

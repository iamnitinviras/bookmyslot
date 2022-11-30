<?php
$company_email1 = get_site_setting('company_email1');
$company_address1 = get_site_setting('company_address1');
$company_phone1 = get_site_setting('company_phone1');
include VIEWPATH . 'front/header.php';
?>
<div class="my-3">
    <div class="container container-min-height">
        <div class="contact-wapper">
            <div class="header text-center">
                <h3><?php echo translate('contact-us') ?></h3>
                <p><?php echo translate('contact-us-information'); ?></p>
                <?php $this->load->view('message'); ?>
            </div>
            <div class="row contact-info">
                <?php if (isset($company_address1) && $company_address1 != ""): ?>
                    <div class="col-md-4">
                        <div class="contact-address">
                            <i class="fa fa-map-marker"></i>
                            <h3><?php echo translate('address'); ?></h3>
                            <address><?php echo trim($company_address1); ?></address>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if (isset($company_phone1) && $company_phone1 != ""): ?>
                    <div class="col-md-4">
                        <div class="contact-phone">
                            <i class="fa fa-phone"></i>
                            <h3><?php echo translate('phone'); ?></h3>
                            <p><a href="tel:<?php echo $company_phone1; ?>"><?php echo $company_phone1; ?></a></p>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if (isset($company_email1) && $company_email1 != ""): ?>
                    <div class="col-md-4">
                        <div class="contact-email">
                            <i class="fa fa-envelope-o"></i>
                            <h3><?php echo translate('email'); ?></h3>
                            <p><a href="mailto:<?php echo $company_email1; ?>"><?php echo $company_email1; ?></a></p>
                        </div>
                    </div>
                <?php endif; ?>

            </div>

            <div class="form">           
                <?php
                $attributes = array('id' => 'contactForm', 'name' => 'contactForm', 'method' => "post");
                echo form_open('contact-us-front', $attributes);
                ?>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <input type="text" name="name" class="form-control" id="name" placeholder="<?php echo translate('name'); ?>" required="">
                        <div class="validation"></div>
                    </div>
                    <div class="form-group col-md-6">
                        <input type="email" class="form-control" name="email" id="email" placeholder="<?php echo translate('email'); ?>" required="">
                        <div class="validation"></div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <input type="text" name="phone" class="form-control" id="phone" placeholder="<?php echo translate('phone'); ?>" required="">
                        <div class="validation"></div>
                    </div>
                    <div class="form-group col-md-6">
                        <input type="text" class="form-control" name="subject" id="subject" placeholder="<?php echo translate('subject'); ?>" required="">
                        <div class="validation"></div>
                    </div>
                </div>
                <div class="form-group">
                    <textarea class="form-control" name="message" rows="5" placeholder="<?php echo translate('message'); ?>" required=""></textarea>
                    <div class="validation"></div>
                </div>
                <div class="footer-btn-text text-center"><button class="btn bg-dark button_common color-white"  type="submit"><?php echo translate('submit'); ?></button></div>
                    <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
<?php include VIEWPATH . 'front/footer.php'; ?>

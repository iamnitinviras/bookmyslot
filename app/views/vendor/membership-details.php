<?php
include VIEWPATH . 'vendor/header.php';
?>

<div class="dashboard-body">
    <!-- Start Content -->
    <div class="content">
        <!-- Start Container -->
        <div class="container-fluid ">
            <section class="form-light px-2 sm-margin-b-20">
                <!-- Row -->
                <div class="row">
                    <div class="col-md-6 m-auto">
                        <?php $this->load->view('message'); ?>

                        <div class="header bg-color-base p-3">
                            <h3 class="black-text font-bold mb-0">
                                <?php echo translate('membership_details') ?>
                            </h3>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <?php
                                echo form_open_multipart('vendor/package-purchase', array('name' => 'PackageForm', 'id' => 'PackageForm'));
                                ?>
                                <input type="hidden" id="package_id" name="package_id" value="<?php echo $package_data['id']; ?>">

                                <input type="hidden" id="first_name" name="first_name" value="<?php echo isset($vendor_data['first_name']) ? $vendor_data['first_name'] : ""; ?>"/>
                                <input type="hidden" id="last_name" name="last_name" value="<?php echo isset($vendor_data['last_name']) ? $vendor_data['last_name'] : ""; ?>"/>
                                <input type="hidden" id="email" name="email" value="<?php echo isset($vendor_data['email']) ? $vendor_data['email'] : ""; ?>"/>

                                <div class="table-responsive">
                                    <table class="table mdl-data-table">
                                        <thead>
                                            <tr>
                                                <th class="font-bold dark-grey-text" style="width: 30%"><?php echo translate('package'); ?></th>
                                                <th><?php echo $package_data['title']; ?></th>
                                            </tr>
                                            <tr>
                                                <th class="font-bold dark-grey-text" style="width: 30%"><?php echo translate('price'); ?></th>
                                                <th><?php echo price_format($package_data['price']); ?></th>
                                            </tr>
                                            <tr>
                                                <th class="font-bold dark-grey-text" style="width: 30%"><?php echo translate('validity'); ?></th>
                                                <th><?php echo $package_data['package_month']; ?> <?php echo translate('month'); ?></th>
                                            </tr>
                                            <tr>
                                                <th class="font-bold dark-grey-text" style="width: 30%"><?php echo translate('description'); ?></th>
                                                <th><?php echo $package_data['description']; ?></th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div> 
                                <div class="form-group">
                                    <label class="black-text"><?php echo translate('select'); ?> <?php echo translate('payment_method'); ?><small class="required">*</small></label>
                                    <select class="kb-select initialized" id="payment_method" name="payment_method" onchange="get_stripe(this.value);"> 
                                        <option value=""><?php echo translate('select') . " " . translate('payment_method'); ?></option>
                                        <?php if (check_payment_method('stripe')) { ?>
                                            <option value="stripe"><?php echo translate('stripe'); ?></option>
                                        <?php } if (check_payment_method('paypal')) { ?>
                                            <option value="paypal"><?php echo translate('paypal'); ?></option>
                                        <?php } if (check_payment_method('2checkout')) { ?>
                                            <option value="2checkout">2Checkout</option>
                                        <?php } ?>
                                    </select>
                                    <?php echo form_error('payment_method'); ?>
                                </div>

                                <div id="verify"></div>
                                <div class="form-group">
                                    <button type="button" onclick="history.go(-1);" class="btn btn-danger waves-effect" style="margin-top: 25px;"><?php echo translate('cancel'); ?></button>
                                    <button type="submit" class="btn btn-success waves-effect pull-right" style="margin-top: 25px;"><?php echo translate('purchase'); ?></button>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>
                    </div>
                    <!--col-md-12-->
                </div>
                <!--Row-->
            </section>
        </div>
    </div>   
</div>
<script src="https://checkout.stripe.com/checkout.js"></script>
<script type="text/javascript">
                                        var handler = StripeCheckout.configure({
                                            key: '<?php echo get_Stripepublish(); ?>',
                                            image: '',
                                            token: function (token) {
                                                $('#PackageForm').append("<input type='hidden' name='stripeToken' value='" + token.id + "' />");
                                                $("#PackageForm").submit();
                                            }
                                        });

                                        function get_stripe(type) {
                                            if (type == "paypal") {
                                                $("#PackageForm").submit();
                                            }
                                            package_id = $('#package_id').val();
                                            var first_name = $("#first_name").val();
                                            var last_name = $("#last_name").val();
                                            var email = $("#email").val();

                                            if (type == 'stripe') {
                                                $.ajax({
                                                    url: site_url + "vendor/check-package-price/" + package_id,
                                                    success: function (total) {
                                                        total = total * 100;
                                                        handler.open({
                                                            name: first_name + " " + last_name,
                                                            email: email,
                                                            amount: total
                                                        });
                                                    }
                                                });

                                            }

                                            if (type == "2checkout") {
                                                $("#PackageForm").submit();
                                            }
                                        }
                                        // Close Checkout on page navigation
                                        $(window).on('popstate', function () {
                                            handler.close();
                                        });
                                        $("#PackageForm").submit(function () {
                                            if ($("#PackageForm").valid()) {
                                                $('.paymentloadingmessage').show();
                                            }
                                        });
</script>
<?php
include VIEWPATH . 'vendor/footer.php';
?>
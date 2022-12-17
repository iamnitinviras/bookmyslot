<?php
include VIEWPATH . 'admin/header.php';

$stripe = (set_value("stripe")) ? set_value("stripe") : (!empty($payment_data) ? $payment_data['stripe'] : 'N');
$paypal = (set_value("paypal")) ? set_value("paypal") : (!empty($payment_data) ? $payment_data['paypal'] : 'N');
$on_cash = (set_value("on_cash")) ? set_value("on_cash") : (!empty($payment_data) ? $payment_data['on_cash'] : 'N');
$stripe_secret = (set_value("stripe_secret")) ? set_value("stripe_secret") : (!empty($payment_data) ? $payment_data['stripe_secret'] : '');
$stripe_publish = (set_value("stripe_publish")) ? set_value("stripe_publish") : (!empty($payment_data) ? $payment_data['stripe_publish'] : '');
$paypal_merchant_email = (set_value("paypal_merchant_email")) ? set_value("paypal_merchant_email") : (!empty($payment_data) ? $payment_data['paypal_merchant_email'] : '');
$paypal_sendbox_live = (set_value("paypal_sendbox_live")) ? set_value("paypal_sendbox_live") : (!empty($payment_data) ? $payment_data['paypal_sendbox_live'] : '');

$two_checkout = (set_value("2checkout")) ? set_value("2checkout") : (!empty($payment_data) ? $payment_data['2checkout'] : 'N');
$two_checkout_account_no = (set_value("2checkout_account_no")) ? set_value("2checkout_account_no") : (!empty($payment_data) ? $payment_data['2checkout_account_no'] : 'N');
$two_checkout_live_sandbox = (set_value("2checkout_live_sandbox")) ? set_value("2checkout_live_sandbox") : (!empty($payment_data) ? $payment_data['2checkout_live_sandbox'] : 'S');
$two_checkout_publishable_key = (set_value("2checkout_publishable_key")) ? set_value("2checkout_publishable_key") : (!empty($payment_data) ? $payment_data['2checkout_publishable_key'] : 'S');
$two_checkout_private_key = (set_value("2checkout_private_key")) ? set_value("2checkout_private_key") : (!empty($payment_data) ? $payment_data['2checkout_private_key'] : 'S');

$id = !empty($payment_data) ? $payment_data['id'] : 0;

$stripe_yes = $stripe_no = "";

if ($stripe == 'Y') {
    $stripe_yes = 'checked';
} else {
    $stripe_no = 'checked';
}

$paypal_yes = $paypal_no = "";
if ($paypal == 'Y') {
    $paypal_yes = 'checked';
} else {
    $paypal_no = 'checked';
}

$two_checkout_yes = $two_checkout_no = "";

if ($two_checkout == 'Y') {
    $two_checkout_yes = 'checked';
} else {
    $two_checkout_no = 'checked';
}

$on_cash_yes = $on_cash_no = "";
if ($on_cash == 'Y') {
    $on_cash_yes = 'checked';
} else {
    $on_cash_no = 'checked';
}
?>
<input id="folder_name" name="folder_name" type="hidden" value="<?php echo isset($folder_name) && $folder_name != '' ? $folder_name : ''; ?>"/>


<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-12 col-xl-12">
                            <h4 class="card-title"><?php echo translate('update_payment_setting'); ?></h4>
                            <div class="page-title-box pb-0 d-sm-flex">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>"><?php echo translate('dashboard'); ?></a></li>
                                        <li class="breadcrumb-item active"><?php echo translate('update_payment_setting'); ?></li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">

                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link "  href="<?php echo base_url('admin/setting/site'); ?>" role="tab">
                                <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                <span class="d-none d-sm-block"><?php echo translate('site_setting'); ?></span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"  href="<?php echo base_url('admin/setting/email'); ?>" role="tab">
                                <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                <span class="d-none d-sm-block"><?php echo translate('email_setting'); ?></span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"  href="<?php echo base_url('admin/setting/currency'); ?>" role="tab">
                                <span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
                                <span class="d-none d-sm-block"><?php echo translate('currency'); ?></span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"  href="<?php echo base_url('admin/setting/business'); ?>" role="tab">
                                <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                                <span class="d-none d-sm-block"><?php echo translate('business'); ?></span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"  href="<?php echo base_url('admin/setting/display'); ?>" role="tab">
                                <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                                <span class="d-none d-sm-block"><?php echo translate('display_setting'); ?></span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link active"  href="<?php echo base_url('admin/setting/payment'); ?>" role="tab">
                                <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                                <span class="d-none d-sm-block"><?php echo translate('payment_setting'); ?></span>
                            </a>
                        </li>
                    </ul>

                    <?php $this->load->view('message'); ?>
                    <?php
                    echo form_open('admin/save-payment-setting', array('name' => 'PaymentForm', 'id' => 'PaymentForm'));
                    echo form_input(array('type' => 'hidden', 'name' => 'id', 'id' => 'id', 'value' => $id));
                    ?>

                    <div class="card">
                        <div class="card-body">

                            <label style="color: #757575;" > <?php echo translate('stripe'); ?></label>
                            <div class="form-group form-inline">
                                <div class="form-group">
                                    <input name='stripe' value="Y" type='radio' id='stripe_yes'   <?php echo isset($stripe_yes) ? $stripe_yes : ''; ?> onchange="check_stripe_val(this.value);">
                                    <label for="stripe_yes"><?php echo translate('yes'); ?></label>
                                </div>
                                <div class="form-group">
                                    <input name='stripe' type='radio'  value='N' id='stripe_no'  <?php echo isset($stripe_no) ? $stripe_no : ''; ?> onchange="check_stripe_val(this.value);">
                                    <label for='stripe_no'><?php echo translate('no'); ?></label>
                                </div>
                            </div>
                            <div class="form-group stripe-html d-none">
                                <label for="stripe_secret"> <?php echo translate('stripe_secret_key'); ?><small class="required">*</small></label>
                                <input type="text" autocomplete="off" id="stripe_secret" name="stripe_secret" value="<?php echo $stripe_secret; ?>" class="form-control" placeholder="<?php echo translate('stripe_secret_key'); ?>">
                                <?php echo form_error('stripe_secret'); ?>
                            </div>
                            <div class="form-group stripe-html d-none">
                                <label for="stripe_publish"> <?php echo translate('stripe_publish_key'); ?><small class="required">*</small></label>
                                <input type="text" autocomplete="off" id="stripe_publish" name="stripe_publish" value="<?php echo $stripe_publish; ?>" class="form-control" placeholder="<?php echo translate('stripe_publish_key'); ?>">
                                <?php echo form_error('stripe_publish'); ?>
                            </div>
                            <hr/>

                            <label style="color: #757575;" > <?php echo translate('paypal'); ?></label>
                            <div class="form-group form-inline">
                                <div class="form-group">
                                    <input name='paypal' value="Y" type='radio' id='paypal_yes'   <?php echo isset($paypal_yes) ? $paypal_yes : ''; ?> onchange="check_paypal_val(this.value);">
                                    <label for="paypal_yes"><?php echo translate('yes'); ?></label>
                                </div>
                                <div class="form-group">
                                    <input name='paypal' type='radio'  value='N' id='paypal_no'  <?php echo isset($paypal_no) ? $paypal_no : ''; ?> onchange="check_paypal_val(this.value);">
                                    <label for='paypal_no'><?php echo translate('no'); ?></label>
                                </div>
                            </div>
                            <div class=" palpal-html">
                                <div class="form-group">
                                    <label for="paypal_sendbox_live"> <?php echo translate('paypal_mode'); ?></label>
                                    <select class="form-control" id="paypal_sendbox_live" name="paypal_sendbox_live" style="display: block !important;">
                                        <option <?php echo ($paypal_sendbox_live == 'S') ? "selected='selected'" : ""; ?> value="S"><?php echo translate('paypal_sendbox'); ?></option>
                                        <option <?php echo ($paypal_sendbox_live == 'L') ? "selected='selected'" : ""; ?> value="L"><?php echo translate('paypal_live'); ?></option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="paypal_merchant_email"> <?php echo translate('paypal_merchant_email'); ?></label>
                                    <input type="email" id="paypal_merchant_email" name="paypal_merchant_email" value="<?php echo $paypal_merchant_email; ?>" class="form-control" placeholder="<?php echo translate('paypal_merchant_email'); ?>">
                                    <?php echo form_error('paypal_merchant_email'); ?>
                                </div>
                            </div>

                            <hr/>

                            <label style="color: #757575;" >2Checkout</label>
                            <div class="form-group form-inline">
                                <div class="form-group">
                                    <input name='2checkout' value="Y" type='radio' id='checkout_yes'   <?php echo isset($two_checkout_yes) ? $two_checkout_yes : ''; ?> onchange="check_twoCheckout_val(this.value);">
                                    <label for="checkout_yes"><?php echo translate('yes'); ?></label>
                                </div>
                                <div class="form-group">
                                    <input name='2checkout' type='radio'  value='N' id='checkout_no'  <?php echo isset($two_checkout_no) ? $two_checkout_no : ''; ?> onchange="check_twoCheckout_val(this.value);">
                                    <label for='checkout_no'><?php echo translate('no'); ?></label>
                                </div>
                            </div>

                            <div class="twoCheckout-html d-none">
                                <div class="form-group">
                                    <label for="2checkout_account_no">2Checkout Account Number<small class="required">*</small></label>
                                    <input type="text" id="2checkout_account_no" autocomplete="off" name="2checkout_account_no" value="<?php echo $two_checkout_account_no; ?>" class="form-control" placeholder="2Checkout Account Number">
                                    <?php echo form_error('2checkout_account_no'); ?>
                                </div>
                                <div class="form-group">
                                    <label for="2checkout_publishable_key">2Checkout Publishable Key<small class="required">*</small></label>
                                    <input type="text" id="2checkout_publishable_key" autocomplete="off" name="2checkout_publishable_key" value="<?php echo $two_checkout_publishable_key; ?>" class="form-control" placeholder="2Checkout Publishable Key">
                                    <?php echo form_error('2checkout_publishable_key'); ?>
                                </div>
                                <div class="form-group">
                                    <label for="2checkout_private_key">2Checkout Private Key<small class="required">*</small></label>
                                    <input type="text" id="2checkout_private_key" autocomplete="off" name="2checkout_private_key" value="<?php echo $two_checkout_private_key; ?>" class="form-control" placeholder="2Checkout Private Key">
                                    <?php echo form_error('2checkout_private_key'); ?>
                                </div>
                                <div class="form-group">
                                    <label for="2checkout_live_sandbox">2Checkout Type</label>
                                    <select class="form-control" id="2checkout_live_sandbox" name="2checkout_live_sandbox" style="display: block !important;">
                                        <option <?php echo ($two_checkout_live_sandbox == 'S') ? "selected='selected'" : ""; ?> value="S">Sandbox</option>
                                        <option <?php echo ($two_checkout_live_sandbox == 'L') ? "selected='selected'" : ""; ?> value="L">Live</option>
                                    </select>
                                </div>
                            </div>

                            <hr/>

                            <label style="color: #757575;" > <?php echo translate('on_cash'); ?></label>
                            <div class="form-group form-inline">
                                <div class="form-group">
                                    <input name='on_cash' value="Y" type='radio' id='on_cash_yes'   <?php echo isset($on_cash_yes) ? $on_cash_yes : ''; ?>>
                                    <label for="on_cash_yes"><?php echo translate('yes'); ?></label>
                                </div>
                                <div class="form-group">
                                    <input name='on_cash' type='radio'  value='N' id='on_cash_no'  <?php echo isset($on_cash_no) ? $on_cash_no : ''; ?>>
                                    <label for='on_cash_no'><?php echo translate('no'); ?></label>
                                </div>
                            </div>

                        </div>
                        <div class="card-footer">
                            <div class="d-flex flex-wrap gap-2">
                                <button type="submit" class="btn btn-primary"><?php echo translate('save'); ?></button>
                            </div>
                        </div>

                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
    <!-- end row -->
</div>
<script>
    check_stripe_val('<?php echo $stripe; ?>');
    check_paypal_val('<?php echo $paypal; ?>');
    check_twoCheckout_val('<?php echo $two_checkout; ?>');
</script>
<?php include VIEWPATH . 'admin/footer.php'; ?>
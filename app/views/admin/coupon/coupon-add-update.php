<?php
if ($this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) == 'V') {
    include VIEWPATH . 'vendor/header.php';
    $folder_name = 'vendor';
} else {
    include VIEWPATH . 'admin/header.php';
    $folder_name = 'admin';
}

$id = (set_value("id")) ? set_value("id") : (!empty($coupon_data) ? $coupon_data['id'] : '');
$title = (set_value("title")) ? set_value("title") : (!empty($coupon_data) ? $coupon_data['title'] : '');
$valid_till = (set_value("valid_till")) ? set_value("valid_till") : (!empty($coupon_data) ? $coupon_data['valid_till'] : '');
$event_id = (set_value("event_id")) ? set_value("event_id") : (!empty($coupon_data) ? json_decode($coupon_data['event_id']) : array());
$code = (set_value("code")) ? set_value("code") : (!empty($coupon_data) ? $coupon_data['code'] : '');
$discount_type = (set_value("discount_type")) ? set_value("discount_type") : (!empty($coupon_data) ? $coupon_data['discount_type'] : '');
$discount_value = (set_value("discount_value")) ? set_value("discount_value") : (!empty($coupon_data) ? $coupon_data['discount_value'] : '');
$status = (set_value("status")) ? set_value("status") : (!empty($coupon_data) ? $coupon_data['status'] : '');
?>
<div class="dashboard-body">
    <!-- Start Content -->
    <div class="content">
        <!-- Start Container -->
        <div class="container-fluid">
            <section class="form-light px-2 sm-margin-b-20 ">
                <?php $this->load->view('message'); ?>

                <div class="header bg-color-base p-3">
                    <h3 class="black-text font-bold mb-0"><?php echo isset($id) && $id > 0 ? translate('update') : translate('add'); ?> <?php echo translate('coupon'); ?></h3>
                </div>

                <div class="card">
                    <div class="card-body resp_mx-0">
                        <?php
                        if ($this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) == 'V') {
                            $form_url = 'vendor/save-coupon';
                        } else {
                            $form_url = 'admin/save-coupon';
                        }
                        ?>
                        <?php
                        echo form_open_multipart($form_url, array('name' => 'CouponAddForm', 'id' => 'CouponAddForm'));
                        echo form_input(array('type' => 'hidden', 'name' => 'id', 'id' => 'id', 'value' => $id));
                        ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label for="title"> <?php echo translate('coupon_title'); ?><small class="required">*</small></label>
                                    <input type="text" autocomplete="off" required="" id="title" name="title" value="<?php echo $title; ?>" class="form-control" placeholder="<?php echo translate('coupon_title'); ?>">                                    
                                    <?php echo form_error('title'); ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label for="title"> <?php echo translate('expiry_date'); ?><small class="required">*</small></label>
                                    <input type="date" autocomplete="off" min="<?php echo date("m-d-Y") ?>" required="" id="valid_till" name="valid_till" value="<?php echo $valid_till; ?>" class="form-control" placeholder="<?php echo translate('expiry_date'); ?>">                                    
                                    <?php echo form_error('valid_till'); ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label for="title"> <?php echo translate('code'); ?><small class="required">*</small></label>
                                    <input type="text" autocomplete="off" required="" id="code" name="code" value="<?php echo $code; ?>" class="form-control" placeholder="<?php echo translate('code'); ?>">                                    
                                    <?php echo form_error('code'); ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label for="title"> <?php echo translate('coupon_discount_on'); ?><small class="required">*</small></label><br/>
                                    <select id="event_id" name="event_id[]" required="" class="form-control" multiple="multiple" style="width: 100%">
                                        <?php foreach ($event_data as $value): ?>
                                            <option <?php echo (in_array($value['id'], $event_id)) ? "selected='selected'" : ""; ?> value="<?php echo $value['id'] ?>"><?php echo $value['title'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php echo form_error('event_id'); ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label for="title"> <?php echo translate('discount_type'); ?><small class="required">*</small></label>
                                    <select name="discount_type" id="discount_type" class="form-control" required="" style="display: block !important;">
                                        <option <?php echo ($discount_type == 'A') ? "selected='selected'" : ""; ?> value="A"><?php echo translate('amount'); ?></option>
                                        <option <?php echo ($discount_type == 'P') ? "selected='selected'" : ""; ?> value="P"><?php echo translate('percentage'); ?></option>
                                    </select>
                                    <?php echo form_error('discount_type'); ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label for="title"> <?php echo translate('discount_value'); ?><small class="required">*</small></label>
                                    <input type="number" required="" autocomplete="off" id="discount_value" name="discount_value" value="<?php echo $discount_value; ?>" class="form-control" placeholder="<?php echo translate('discount_value'); ?>">                                    
                                    <?php echo form_error('discount_value'); ?>
                                </div>
                            </div>
                        </div>

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
                            <div class="form-group">
                                <input name='status' value="A" type='radio' id='active'   <?php echo $active; ?>>
                                <label for="active"><?php echo translate('active'); ?></label>
                            </div>
                            <div class="form-group">
                                <input name='status' type='radio'  value='I' id='inactive'  <?php echo $inactive; ?>>
                                <label for='inactive'><?php echo translate('inactive'); ?></label>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-success waves-effect" style="margin-top: 25px;"><?php echo translate('save'); ?></button>
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
<script src="<?php echo $this->config->item('js_url'); ?>module/coupon.js" type="text/javascript"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<?php
if ($this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) == 'V') {
    include VIEWPATH . 'vendor/footer.php';
} else {
    include VIEWPATH . 'admin/footer.php';
}
 ?>
<script>
    $(document).ready(function () {
        $('#event_id').select2();
    });
</script>
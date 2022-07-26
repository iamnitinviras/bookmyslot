<?php
if ($this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) == 'V') {
    include VIEWPATH . 'vendor/header.php';
    $folder_name = 'vendor';
    $form_url = 'vendor/coupon/save';
} else {
    include VIEWPATH . 'admin/header.php';
    $folder_name = 'admin';
    $form_url = 'admin/coupon/save';
}

$id = (set_value("id")) ? set_value("id") : (!empty($coupon_data) ? $coupon_data['id'] : '');
$coupon_title = (set_value("title")) ? set_value("title") : (!empty($coupon_data) ? $coupon_data['title'] : '');
$valid_till = (set_value("valid_till")) ? set_value("valid_till") : (!empty($coupon_data) ? $coupon_data['valid_till'] : '');
$event_id = (set_value("event_id")) ? set_value("event_id") : (!empty($coupon_data) ? json_decode($coupon_data['event_id']) : array());
$code = (set_value("code")) ? set_value("code") : (!empty($coupon_data) ? $coupon_data['code'] : '');
$discount_type = (set_value("discount_type")) ? set_value("discount_type") : (!empty($coupon_data) ? $coupon_data['discount_type'] : '');
$discount_value = (set_value("discount_value")) ? set_value("discount_value") : (!empty($coupon_data) ? $coupon_data['discount_value'] : '');
$status = (set_value("status")) ? set_value("status") : (!empty($coupon_data) ? $coupon_data['status'] : '');
?>

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
                                        <li class="breadcrumb-item"><a href="<?php echo base_url($folder_name.'/coupon'); ?>"><?php echo translate('coupon'); ?></a></li>
                                        <li class="breadcrumb-item active"><?php echo $title; ?></li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <?php
                echo form_open_multipart($form_url, array('name' => 'CouponAddForm', 'id' => 'CouponAddForm'));
                echo form_input(array('type' => 'hidden', 'name' => 'id', 'id' => 'id', 'value' => $id));
                ?>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 m-auto">
                            <?php $this->load->view('message'); ?>

                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <div class="form-group mb-0">
                                        <label for="title"> <?php echo translate('coupon_title'); ?><small class="required">*</small></label>
                                        <input type="text" autocomplete="off" required="" id="title" name="title" value="<?php echo $coupon_title; ?>" class="form-control" placeholder="<?php echo translate('coupon_title'); ?>">
                                        <?php echo form_error('title'); ?>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group mb-0">
                                        <label for="title"> <?php echo translate('expiry_date'); ?><small class="required">*</small></label>
                                        <input type="date" autocomplete="off" min="<?php echo date("m-d-Y") ?>" required="" id="valid_till" name="valid_till" value="<?php echo $valid_till; ?>" class="form-control" placeholder="<?php echo translate('expiry_date'); ?>">
                                        <?php echo form_error('valid_till'); ?>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-0">
                                        <label for="title"> <?php echo translate('code'); ?><small class="required">*</small></label>
                                        <input type="text" autocomplete="off" required="" id="code" name="code" value="<?php echo $code; ?>" class="form-control" placeholder="<?php echo translate('code'); ?>">
                                        <?php echo form_error('code'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4">
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
                                <div class="col-md-4">
                                    <div class="form-group mb-0">
                                        <label for="title"> <?php echo translate('discount_type'); ?><small class="required">*</small></label>
                                        <select name="discount_type" id="discount_type" class="form-control" required="" style="display: block !important;">
                                            <option <?php echo ($discount_type == 'A') ? "selected='selected'" : ""; ?> value="A"><?php echo translate('amount'); ?></option>
                                            <option <?php echo ($discount_type == 'P') ? "selected='selected'" : ""; ?> value="P"><?php echo translate('percentage'); ?></option>
                                        </select>
                                        <?php echo form_error('discount_type'); ?>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group mb-0">
                                        <label for="title"> <?php echo translate('discount_value'); ?><small class="required">*</small></label>
                                        <input type="number" required="" autocomplete="off" id="discount_value" name="discount_value" value="<?php echo $discount_value; ?>" class="form-control" placeholder="<?php echo translate('discount_value'); ?>">
                                        <?php echo form_error('discount_value'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <label><?php echo translate('status'); ?> <small class="required">*</small></label>
                                    <div class="form-group">
                                        <?php
                                        $active = $inactive = '';
                                        if ($status == "I") {
                                            $inactive = "checked";
                                        } else {
                                            $active = "checked";
                                        }
                                        ?>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="status" id="active" value="A" <?php echo $active; ?>>
                                            <label class="form-check-label" for="active"><?php echo translate('active'); ?></label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="status" id="inactive"  value='I' <?php echo $inactive; ?>>
                                            <label class="form-check-label" for="inactive"><?php echo translate('inactive'); ?></label>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary"><?php echo translate('save'); ?></button>
                        <a href="<?php echo base_url($folder_name.'/coupon'); ?>" class="btn btn-outline-secondary"><?php echo translate('cancel'); ?></a>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
    <!-- end row -->
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
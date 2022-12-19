<?php
if ($this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) == 'V') {
    include VIEWPATH . 'vendor/header.php';
    $folder_name = 'vendor';
    $form_url = 'vendor/holiday/save';
} else {
    include VIEWPATH . 'admin/header.php';
    $folder_name = 'admin';
    $form_url = 'admin/holiday/save';
}
$title_e = (set_value("title")) ? set_value("title") : (!empty($holiday) ? $holiday['title'] : '');
$holiday_date = (set_value("holiday_date")) ? date("m/d/Y", strtotime(set_value("holiday_date"))) : (!empty($holiday) ? date("m/d/Y", strtotime($holiday['holiday_date'])) : '');
$status = (set_value("status")) ? set_value("status") : (!empty($holiday) ? $holiday['status'] : 'A');
$id = !empty($holiday) ? $holiday['id'] : 0;
?>
<input id="folder_name" name="folder_name" type="hidden" value="<?php echo isset($folder_name) && $folder_name != '' ? $folder_name : ''; ?>"/>
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
                                        <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>"><?php echo translate('dashboard'); ?></a></li>
                                        <li class="breadcrumb-item active"><?php echo $title; ?></li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <?php
                echo form_open($form_url, array('name' => 'ServiceHolidayForm', 'id' => 'ServiceHolidayForm'));
                echo form_input(array('type' => 'hidden', 'name' => 'id', 'id' => 'id', 'value' => $id));
                ?>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 m-auto">
                            <?php $this->load->view('message'); ?>


                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="title"> <?php echo translate('title'); ?><small class="required">*</small></label>
                                        <input type="text" required="" autocomplete="off" id="title" maxlength="40" name="title" value="<?php echo $title_e; ?>" class="form-control" placeholder="<?php echo translate('title'); ?>">
                                        <?php echo form_error('title'); ?>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="holiday_date"> <?php echo translate('date'); ?><small class="required">*</small></label>
                                        <input type="text"  required=""  autocomplete="off" id="holiday_date" name="holiday_date" value="<?php echo $holiday_date; ?>" class="form-control" placeholder="<?php echo translate('date'); ?>">
                                        <?php echo form_error('holiday_date'); ?>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label style="color: #757575;" > <?php echo translate('status'); ?> <small class="required">*</small></label>
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
                        <a href="<?php echo base_url($folder_name.'/holiday'); ?>" class="btn btn-outline-secondary"><?php echo translate('cancel'); ?></a>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
    <!-- end row -->
</div>

<?php
if ($this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) == 'V') {
    include VIEWPATH . 'vendor/footer.php';
} else {
    include VIEWPATH . 'admin/footer.php';
}
?>
<script src="<?php echo $this->config->item('js_url'); ?>module/holiday.js" type='text/javascript'></script>
<script>
    if ($('#holiday_date').length > 0) {
        $('#holiday_date').datepicker({
            format: 'mm/dd/yyyy',
            minDate: new Date()
        });
    }
</script>
<?php
if ($this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) == 'V') {
    include VIEWPATH . 'vendor/header.php';
    $folder_name = 'vendor';
    $form_url = 'vendor/save-location';
} else {
    include VIEWPATH . 'admin/header.php';
    $folder_name = 'admin';
    $form_url = 'admin/save-location';
}
$loc_title = (set_value("loc_title")) ? set_value("loc_title") : (!empty($loc_data) ? $loc_data['loc_title'] : '');
$loc_city_id = (set_value("loc_city_id")) ? set_value("loc_city_id") : (!empty($loc_data) ? $loc_data['loc_city_id'] : '');
$loc_status = (set_value("loc_status")) ? set_value("loc_status") : (!empty($loc_data) ? $loc_data['loc_status'] : '');
$id = (set_value("loc_id")) ? set_value("loc_id") : (!empty($loc_data) ? $loc_data['loc_id'] : 0);
?>
<input id="folder_name" name="folder_name" type="hidden" value="<?php echo isset($folder_name) && $folder_name != '' ? $folder_name : ''; ?>"/>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6 col-xl-6">
                            <h4 class="card-title"><?php echo isset($id) && $id > 0 ? translate('update') : translate('add'); ?> <?php echo translate('location'); ?></h4>
                            <div class="page-title-box pb-0 d-sm-flex">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>"><?php echo translate('dashboard'); ?></a></li>
                                        <li class="breadcrumb-item"><a href="<?php echo base_url('admin/location'); ?>"><?php echo translate('location'); ?></a></li>
                                        <li class="breadcrumb-item active"><?php echo isset($id) && $id > 0 ? translate('update') : translate('add'); ?> <?php echo translate('location'); ?></li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 m-auto">
                            <?php $this->load->view('message'); ?>
                        </div>
                    </div>
                    <?php
                    echo form_open($form_url, array('name' => 'LocationForm', 'id' => 'LocationForm'));
                    echo form_input(array('type' => 'hidden', 'name' => 'id', 'id' => 'id', 'value' => $id));
                    ?>

                    <div class="row mb-5">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"><?php echo translate('select'); ?> <?php echo translate('city'); ?> <small class="required">*</small></label>
                                <?php
                                $options[''] = translate('select') . ' ' . translate('city');
                                if (isset($city_list) && !empty($city_list)) {
                                    foreach ($city_list as $row) {
                                        $options[$row['city_id']] = $row['city_title'];
                                    }
                                }
                                $attributes = array('class' => 'form-control', 'id' => 'loc_city_id', '');
                                echo form_dropdown('loc_city_id', $options, $loc_city_id, $attributes);
                                echo form_error('loc_city_id');
                                ?>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="loc_title"><?php echo translate('title'); ?><small class="required">*</small></label>
                                <input type="text" autocomplete="off" id="loc_title" name="loc_title" value="<?php echo $loc_title; ?>" class="form-control" placeholder="<?php echo translate('title'); ?>">
                                <?php echo form_error('loc_title'); ?>
                            </div>
                        </div>

                        <div class="col-md-4">

                            <label><?php echo translate('status'); ?> <small class="required">*</small></label>
                            <div class="form-group">
                                <?php
                                $active = $inactive = '';
                                if ($loc_status == "I") {
                                    $inactive = "checked";
                                } else {
                                    $active = "checked";
                                }
                                ?>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="loc_status" id="active" value="A" <?php echo $active; ?>>
                                    <label class="form-check-label" for="active"><?php echo translate('active'); ?></label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="loc_status" id="inactive"  value='I' <?php echo $inactive; ?>>
                                    <label class="form-check-label" for="inactive"><?php echo translate('inactive'); ?></label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary"><?php echo translate('save'); ?></button>
                        <a href="<?php echo base_url($folder_name.'/location'); ?>" class="btn btn-outline-secondary"><?php echo translate('cancel'); ?></a>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo $this->config->item('js_url'); ?>module/location.js" type="text/javascript"></script>
<?php
if ($this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) == 'V') {
    include VIEWPATH . 'vendor/footer.php';
} else {
    include VIEWPATH . 'admin/footer.php';
}
?>
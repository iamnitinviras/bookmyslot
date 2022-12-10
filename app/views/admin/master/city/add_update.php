<?php
if ($this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) == 'V') {
    include VIEWPATH . 'vendor/header.php';
    $folder_name = 'vendor';
    $form_url = 'vendor/save-city';
} else {
    include VIEWPATH . 'admin/header.php';
    $folder_name = 'admin';
    $form_url = 'admin/save-city';
}

$city_title = (set_value("city_title")) ? set_value("city_title") : (!empty($city_data) ? $city_data['city_title'] : '');
$city_status = (set_value("city_status")) ? set_value("city_status") : (!empty($city_data) ? $city_data['city_status'] : '');
$id = (set_value("city_id")) ? set_value("city_id") : (!empty($city_data) ? $city_data['city_id'] : 0);
?>
<input id="folder_name" name="folder_name" type="hidden" value="<?php echo isset($folder_name) && $folder_name != '' ? $folder_name : ''; ?>"/>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6 col-xl-6">
                                <h4 class="card-title"><?php echo isset($id) && $id > 0 ? translate('update') : translate('add'); ?> <?php echo translate('city'); ?></h4>
                                <div class="page-title-box pb-0 d-sm-flex">
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>"><?php echo translate('dashboard'); ?></a></li>
                                            <li class="breadcrumb-item"><a href="<?php echo base_url('admin/city'); ?>"><?php echo translate('city'); ?></a></li>
                                            <li class="breadcrumb-item active"><?php echo isset($id) && $id > 0 ? translate('update') : translate('add'); ?> <?php echo translate('city'); ?></li>
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
                            echo form_open($form_url, array('name' => 'CityForm', 'id' => 'CityForm'));
                            echo form_input(array('type' => 'hidden', 'name' => 'id', 'id' => 'id', 'value' => $id));
                        ?>
                        <div class="row mb-5">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="city_title"> <?php echo translate('title'); ?><small class="required">*</small></label>
                                    <input type="text" autocomplete="off" id="city_title" name="city_title" value="<?php echo $city_title; ?>" class="form-control" placeholder="<?php echo translate('title'); ?>">
                                    <?php echo form_error('city_title'); ?>
                                </div>
                            </div>
                            <?php
                            $active = $inactive = '';
                            if ($city_status == "I") {
                                $inactive = "checked";
                            } else {
                                $active = "checked";
                            }
                            ?>
                            <div class="col-md-4">
                                <label><?php echo translate('status'); ?> <small class="required">*</small></label>
                                <div class="form-group">

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="city_status" id="active" value="A" <?php echo $active; ?>>
                                        <label class="form-check-label" for="active"><?php echo translate('active'); ?></label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="city_status" id="inactive"  value='I' <?php echo $inactive; ?>>
                                        <label class="form-check-label" for="inactive"><?php echo translate('inactive'); ?></label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap gap-2">
                            <button type="submit" class="btn btn-primary"><?php echo translate('save'); ?></button>
                            <a href="<?php echo base_url($folder_name.'/city'); ?>" class="btn btn-outline-secondary"><?php echo translate('cancel'); ?></a>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script src="<?php echo $this->config->item('js_url'); ?>module/city.js" type="text/javascript"></script>
<?php
if ($this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) == 'V') {
    include VIEWPATH . 'vendor/footer.php';
} else {
    include VIEWPATH . 'admin/footer.php';
}
?>
<?php
include VIEWPATH . 'admin/header.php';
$title = (set_value("title")) ? set_value("title") : (!empty($package_data) ? $package_data['title'] : '');
$description = (set_value("description")) ? set_value("description") : (!empty($package_data) ? $package_data['description'] : '');
$price = (set_value("price")) ? set_value("price") : (!empty($package_data) ? $package_data['price'] : '0');
$package_month = (set_value("package_month")) ? set_value("package_month") : (!empty($package_data) ? $package_data['package_month'] : '0');
$status = (set_value("status")) ? set_value("status") : (!empty($package_data) ? $package_data['status'] : '');
$id = (set_value("id")) ? set_value("id") : (!empty($package_data) ? $package_data['id'] : 0);
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
                                        <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>"><?php echo translate('dashboard'); ?></a></li>
                                        <li class="breadcrumb-item active"><?php echo $title; ?></li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <?php
                $form_url = 'admin/save-package';
                ?>
                <?php
                echo form_open_multipart($form_url, array('name' => 'PackageForm', 'id' => 'PackageForm'));
                echo form_input(array('type' => 'hidden', 'name' => 'id', 'id' => 'id', 'value' => $id));
                ?>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 m-auto">
                            <?php $this->load->view('message'); ?>

                            <div class="form-group">
                                <label for="title"> <?php echo translate('title'); ?><small class="required">*</small></label>
                                <input type="text" autocomplete="off" id="title" require name="title" value="<?php echo $title; ?>" class="form-control" placeholder="<?php echo translate('title'); ?>">
                                <?php echo form_error('title'); ?>
                            </div>
                            <div class="form-group">
                                <label for="title"> <?php echo translate('description'); ?><small class="required">*</small></label>
                                <textarea id="description" require="" name="description" class="form-control" placeholder="<?php echo translate('description'); ?>"><?php echo $description; ?></textarea>
                                <?php echo form_error('description'); ?>
                            </div>

                            <div class="form-group">
                                <label for="price"> <?php echo translate('price'); ?> <small class="required">*</small></label>
                                <input type="number" autocomplete="off" require placeholder="<?php echo translate('price'); ?>" id="price" name="price" min="1" value="<?php echo $price; ?>" class="form-control">
                                <?php echo form_error('price'); ?>
                            </div>

                            <div class="form-group">
                                <label for="package_month"> <?php echo translate('validity'); ?> <small class="required">*(Number of month)</small></label>
                                <input type="number" autocomplete="off" require placeholder="<?php echo translate('validity'); ?>" id="package_month" name="package_month" min="1" max="12" value="<?php echo $package_month; ?>" class="form-control">
                                <?php echo form_error('package_month'); ?>
                            </div>

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
                <div class="card-footer">
                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary"><?php echo translate('save'); ?></button>
                        <a href="<?php echo base_url('admin/package'); ?>" class="btn btn-outline-secondary"><?php echo translate('cancel'); ?></a>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
    <!-- end row -->
</div>
<script src="<?php echo $this->config->item('js_url'); ?>module/package.js" type="text/javascript"></script>
<?php include VIEWPATH . 'admin/footer.php'; ?>
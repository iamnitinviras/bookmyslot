<?php
include VIEWPATH . 'admin/header.php';
$title = (set_value("title")) ? set_value("title") : (!empty($language_data) ? $language_data['title'] : '');
$status = (set_value("status")) ? set_value("status") : (!empty($language_data) ? $language_data['status'] : '');
$id = (set_value("id")) ? set_value("id") : (!empty($language_data) ? $language_data['id'] : 0);
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6 col-xl-6">
                            <h4 class="card-title"><?php echo translate('language'); ?></h4>
                            <div class="page-title-box pb-0 d-sm-flex">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>"><?php echo translate('dashboard'); ?></a></li>
                                        <li class="breadcrumb-item active"><?php echo translate('language'); ?></li>
                                        <li class="breadcrumb-item active"><?php echo isset($id) && $id > 0 ? translate('update') : translate('add'); ?> <?php echo translate('language'); ?></li>
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
                            <?php
                            echo form_open_multipart('admin/save-language', array('name' => 'LanguageForm', 'id' => 'LanguageForm'));
                            echo form_input(array('type' => 'hidden', 'name' => 'id', 'id' => 'id', 'value' => $id));
                            ?>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="name"> <?php echo translate('title'); ?><small class="required">*</small></label>
                                                <input type="text" autocomplete="off" id="title" name="title" required="" value="<?php echo $title; ?>" class="form-control" placeholder="<?php echo translate('title'); ?>">
                                                <?php echo form_error('title'); ?>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
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
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="form-group mb-0">
                                        <button type="submit" class="btn btn-success waves-effect"><?php echo translate('save'); ?></button>
                                        <a href="<?php echo base_url('admin/manage-language'); ?>" class="btn btn-info waves-effect"><?php echo translate('cancel'); ?></a>
                                    </div>
                                </div>
                            </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end row -->
</div>
<script src="<?php echo $this->config->item('js_url'); ?>module/language.js" type="text/javascript"></script>
<?php
include VIEWPATH . 'admin/footer.php';
?>
<?php
if ($this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) == 'V') {
    include VIEWPATH . 'vendor/header.php';
    $folder_name = 'vendor';
} else {
    include VIEWPATH . 'admin/header.php';
    $folder_name = 'admin';
}
?>
<input id="folder_name" name="folder_name" type="hidden" value="<?php echo isset($folder_name) && $folder_name != '' ? $folder_name : ''; ?>"/>
<div class="dashboard-body">
    <!-- Start Content -->
    <div class="content">
        <!-- Start Container -->
        <div class="container-fluid ">
            <section class="form-light px-2 sm-margin-b-20">
                <!-- Row -->
                <div class="row">
                    <div class="col-md-12 m-auto">
                        <?php $this->load->view('message'); ?>

                        <div class="header bg-color-base p-3">
                            <div class="row">
                                <span class="col-md-9 col-9 m-0">
                                    <h3 class="black-text font-bold mb-0"><?php echo translate('manage') . " " . translate('service') . " " . translate('add_ons'); ?></h3>
                                </span>
                                <span class="col-md-3 col-3 text-right m-0">
                                    <?php if ($this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) == 'V') { ?>
                                        <a  href='<?php echo base_url('vendor/add-service-addons/' . $service_id); ?>' title="<?php echo translate('add') . " " . translate('service') . " " . translate('add_ons'); ?>" data-toggle="tooltip" data-placement="top" class="btn-floating btn-sm btn-success m-0"><i class="fa fa-plus-circle"></i></a>
                                    <?php } else { ?>
                                        <a  href='<?php echo base_url('admin/add-service-addons/' . $service_id); ?>' title="<?php echo translate('add') . " " . translate('service') . " " . translate('add_ons'); ?>" data-toggle="tooltip" data-placement="top" class="btn-floating btn-sm btn-success m-0"><i class="fa fa-plus-circle"></i></a>
                                    <?php } ?>
                                </span>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <?php echo translate('service') . " " . translate('details'); ?>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title"><b><?php echo translate('title'); ?> : </b> <?php echo isset($service_data['title']) ? $service_data['title'] : ""; ?></h5>
                                <p class="card-text"><b><?php echo translate('price'); ?> : </b> <?php echo price_format($service_data['price']); ?></p>
                            </div>
                        </div>
                        <br/>
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive ox-unset">
                                    <table class="table mdl-data-table" id="example">
                                        <thead>
                                            <tr>
                                                <th class="text-center font-bold dark-grey-text">#</th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('title'); ?></th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('description'); ?></th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('price'); ?></th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('image'); ?></th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('action'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (isset($app_service_addons) && count($app_service_addons) > 0) {
                                                foreach ($app_service_addons as $key => $row) {
                                                    if ($this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) == 'V') {
                                                        $update_url = 'vendor/update-service-addons/' . $service_id . '/' . $row['add_on_id'];
                                                    } else {
                                                        $update_url = 'admin/update-service-addons/' . $service_id . '/' . $row['add_on_id'];
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td class="text-center"><?php echo $key + 1; ?></td>
                                                        <td class="text-center"><?php echo $row['title']; ?></td>
                                                        <td class="text-center"><?php echo $row['details']; ?></td>
                                                        <td class="text-center"><?php echo price_format($row['price']); ?></td>
                                                        <td class="text-center">
                                                            <img src = "<?php echo check_admin_image(UPLOAD_PATH . "event/" . $row['image']); ?>" class = "img-thumbnail mr-10 mb-10 height-100" width = "100px"/>
                                                        </td>
                                                        <td class="td-actions text-center">
                                                            <a href="<?php echo base_url($update_url); ?>" class="btn-danger btn-floating btn-sm blue-gradient" title="<?php echo translate('edit'); ?>" data-toggle="tooltip" data-placement="top"><i class="fa fa-pencil"></i></a>
                                                            <span class="d-inline-block" title="<?php echo translate('delete'); ?>" data-toggle="tooltip" data-placement="top"><a id="" data-toggle="modal" onclick='DeleteRecord(this)' data-target="#delete-record" data-id="<?php echo (int) $row['add_on_id']; ?>" class="btn-danger btn-floating btn-sm red-gradient" title="<?php echo translate('delete'); ?>"><i class="fa fa-trash"></i></a></span>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
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
<!-- Modal -->
<div class="modal fade" id="delete-record">
    <div class="modal-dialog">
        <div class="modal-content">
            <?php
            $attributes = array('id' => 'DeleteRecordForm', 'name' => 'DeleteRecordForm', 'method' => "post");
            echo form_open('', $attributes);
            ?>
                <input type="hidden" id="record_id"/>
                <div class="modal-header">
                    <h4 id='some_name' class="modal-title" style="font-size: 18px;"></h4>
                    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <p id='confirm_msg' style="font-size: 15px;"></p>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-primary font_size_12" href="javascript:void(0)" id="RecordAddonsDelete" ><?php echo translate('confirm'); ?></a>
                    <button data-dismiss="modal" class="btn btn-danger font_size_12" type="button"><?php echo translate('close'); ?></button>
                    
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>


<script src="<?php echo $this->config->item('js_url'); ?>module/service.js" type='text/javascript'></script>
<?php
if ($this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) == 'V') {
    include VIEWPATH . 'vendor/footer.php';
} else {
    include VIEWPATH . 'admin/footer.php';
}
?>
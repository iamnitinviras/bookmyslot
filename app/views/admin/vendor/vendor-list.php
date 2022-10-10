<?php include VIEWPATH . 'admin/header.php'; ?>
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
                                    <h3 class="black-text font-bold mb-0"><?php echo translate('manage'); ?> <?php echo translate('vendor'); ?></h3>
                                </span>  
                                <span class="col-md-3 col-3 text-right m-0">
                                    <a href='<?php echo base_url('admin/add-vendor'); ?>' class="btn-floating btn-sm btn-success m-0"><i class="fa fa-plus-circle"></i></a>
                                </span>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table mdl-data-table" id="example">
                                        <thead>
                                            <tr>
                                                <th class="text-center font-bold dark-grey-text">#</th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('name'); ?></th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('company_name'); ?></th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('email'); ?></th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('status'); ?></th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('verification'); ?></th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('action'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 1;
                                            if (isset($vendor_data) && count($vendor_data) > 0) {
                                                foreach ($vendor_data as $row) {

                                                    $profile_status = "";

                                                    if ($row['profile_status'] == 'V') {
                                                        $profile_status = translate('approved');
                                                    } elseif ($row['profile_status'] == 'N') {
                                                        $profile_status = translate('unverified');
                                                    } elseif ($row['profile_status'] == 'R') {
                                                        $profile_status = translate('rejected');
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td class="text-center"><?php echo $i; ?></td>
                                                        <td class="text-center"><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
                                                        <td class="text-center"><?php echo $row['company_name']; ?></td>
                                                        <td class="text-center"><?php echo $row['email']; ?></td>

                                                        <td class="text-center"><?php echo print_vendor_status($row['status']); ?></td>
                                                        <td class="text-center"><?php echo $profile_status; ?></td>
                                                        <td class="td-actions text-center">
                                                            <a href="<?php echo base_url('admin/update-vendor/' . $row['id']); ?>" class="btn btn-primary font_size_12" title="<?php echo translate('edit'); ?>" data-toggle="tooltip" data-placement="top"><i class="fa fa-pencil"></i></a>
                                                            <?php if ($row['status'] != 'P' && $row['profile_status'] == 'V') : ?>
                                                                <span class="d-inline-block" title="<?php echo translate('change_status'); ?>" data-toggle="tooltip" data-placement="top">
                                                                    <a id="" data-toggle="modal" onclick='ChangeStatus(this)' data-target="#change-status" data-id="<?php echo (int) $row['id']; ?>" class="btn btn-info font_size_12"><i class="fa fa-eye"></i></a>
                                                                </span>
                                                            <?php endif; ?>
                                                            <span class="d-inline-block" title="<?php echo translate('customer'); ?>" data-toggle="tooltip" data-placement="top">
                                                                <a id="" data-toggle="modal" onclick='DeleteRecord(this)' data-target="#delete-record" data-id="<?php echo (int) $row['id']; ?>" class="btn btn-danger font_size_12"><i class="fa fa-close"></i></a>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                    $i++;
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

                <a class="btn btn-primary font_size_12" href="javascript:void(0)" id="RecordDelete" ><?php echo translate('confirm'); ?></a>
                <button data-dismiss="modal" class="btn btn-danger font_size_12" type="button"><?php echo translate('close'); ?></button>
            </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- Status Modal -->
<div class="modal fade" id="change-status">
    <div class="modal-dialog">
        <div class="modal-content">
            <?php
            $attributes = array('id' => 'StausForm', 'name' => 'StausForm', 'method' => "post");
            echo form_open('', $attributes);
            ?>
            <input type="hidden" id="CustomerIDVal"/>
            <div class="modal-header">
                <h4 id='CustomerTitle' class="modal-title" style="font-size: 18px;"></h4>
                <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <select name="get_status" id="get_status" class="form-control" required style="display: block !important;">
                        <option value=""><?php echo translate('change_status'); ?></option>
                        <option value="A"><?php echo translate('active'); ?></option>
                        <option value="I"><?php echo translate('inactive'); ?></option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">

                <a class="btn btn-primary font_size_12" href="javascript:void(0)" id="CustomerChange" ><?php echo translate('confirm'); ?></a>
                <button data-dismiss="modal" class="btn btn-danger font_size_12" type="button"><?php echo translate('close'); ?></button>
            </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<script src="<?php echo $this->config->item('js_url'); ?>module/vendor.js" type='text/javascript'></script>
<?php include VIEWPATH . 'admin/footer.php'; ?>
<?php include VIEWPATH . 'admin/header.php'; ?>

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
                                            <li class="breadcrumb-item"><a href="<?php echo base_url('admin/vendor'); ?>"><?php echo translate('vendor'); ?></a></li>
                                            <li class="breadcrumb-item active"><?php echo $title; ?></li>
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

                                <div class="table-responsive">
                                    <table class="table table-bordered dt-responsive nowrap w-100" id="datatable">
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
                                                    $profile_status = "<span class='badge badge-success'>" . translate('approved') . "</span>";
                                                } elseif ($row['profile_status'] == 'N') {
                                                    $profile_status = "<span class='badge badge-info'>" . translate('unverified') . "</span>";
                                                } elseif ($row['profile_status'] == 'R') {
                                                    $profile_status = "<span class='badge badge-danger'>" . translate('unverified') . "</span>";
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
                                                            <span class="d-inline-block" title="<?php echo translate('update') . " " . translate('status'); ?>" data-toggle="tooltip" data-placement="top">
                                                                <a id="" data-toggle="modal" onclick='ChangeStatus(this)' data-target="#change-status" data-id="<?php echo (int) $row['id']; ?>" class="btn btn-primary font_size_12"><i class="fa fa-eye"></i></a>
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
                </div>
            </div>
        </div>
        <!-- end row -->
    </div>

<!-- Status Modal -->
<div class="modal fade" id="change-status">
    <div class="modal-dialog">
        <div class="modal-content">

            <?php
            $form_url = 'admin/vendor/unverified_vendor_action';
            echo form_open_multipart($form_url, array('name' => 'StausForm', 'id' => 'StausForm'));
            ?>
            <input type="hidden" id="CustomerIDVal" name="CustomerIDVal"/>
            <div class = "modal-header">
                <h4 class = "modal-title" style = "font-size: 18px;"><?php echo translate('update') . " " . translate('status'); ?></h4>
                <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <select name="get_status" id="get_status" class="form-control" required style="display: block !important;">
                        <option value=""><?php echo translate('update') . " " . translate('status'); ?></option>
                        <option value="V"><?php echo translate('approve'); ?></option>
                        <option value="R"><?php echo translate('reject'); ?></option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">

                <button class="btn btn-primary font_size_12" type="button" id="UpdateCustomerStatus" ><?php echo translate('confirm'); ?></button>
                <button data-dismiss="modal" class="btn btn-danger font_size_12" type="button"><?php echo translate('close'); ?></button>
            </div>
            <?php echo form_close(); ?>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<script src="<?php echo $this->config->item('js_url'); ?>module/vendor.js" type='text/javascript'></script>
<?php include VIEWPATH . 'admin/footer.php'; ?>
<?php include VIEWPATH . 'admin/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6 col-xl-6">
                            <h4 class="card-title"><?php echo translate('manage'); ?> <?php echo translate('customer'); ?></h4>
                            <div class="page-title-box pb-0 d-sm-flex">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>"><?php echo translate('dashboard'); ?></a></li>
                                        <li class="breadcrumb-item active"><?php echo translate('customer'); ?></li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-6 text-end add-new-btn-parent">
                            <div class="text-sm-end">
                                <a href='<?php echo base_url('admin/customer/add'); ?>'class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2">
                                    <i class="mdi mdi-plus me-1"></i>
                                    <?php echo translate('add'); ?> <?php echo translate('customer'); ?>
                                </a>
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
                                        <th>#</th>
                                        <th><?php echo translate('customer_name'); ?></th>
                                        <th><?php echo translate('customer_email'); ?></th>
                                        <th class="text-center"><?php echo translate('phone'); ?></th>
                                        <th class="text-center"><?php echo translate('status'); ?></th>
                                        <th class="text-center"><?php echo translate('created_date'); ?></th>
                                        <th class="text-center"><?php echo translate('action'); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $i = 1;
                                    if (isset($customer_data) && count($customer_data) > 0) {
                                        foreach ($customer_data as $row) {
                                            ?>
                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <td><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
                                                <td><?php echo $row['email']; ?></td>
                                                <td class="text-center"><?php echo $row['phone']; ?></td>
                                                <td class="text-center"><?php echo print_vendor_status($row['status']); ?></td>
                                                <td class="text-center"><?php echo get_formated_date($row['created_on'], "N"); ?></td>
                                                <td class="td-actions text-center">
                                                    <a href="<?php echo base_url('admin/customer/booking/' . $row['id']); ?>" class="text-primary" title="<?php echo translate('view_details'); ?>" data-toggle="tooltip" data-placement="top"><i class="mdi mdi-information font-size-18"></i></a>
                                                    <a href="<?php echo base_url('admin/customer/edit/' . $row['id']); ?>" class="text-success" title="<?php echo translate('edit'); ?>" data-toggle="tooltip" data-placement="top"><i class="mdi mdi-pencil font-size-18"></i></a>
                                                    <a href="javascript:void(0)" data-bs-toggle="modal" onclick='DeleteRecord(this)' data-bs-target="#delete-record" data-id="<?php echo (int) $row['id']; ?>" class="text-danger"><i class="mdi mdi-delete font-size-18"></i></a>
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
            <input type="hidden" id="CustomerStatusVal"/>
            <div class="modal-header">
                <h4 id='CustomerTitle' class="modal-title" style="font-size: 18px;"></h4>
                <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <p id='CustomerMsg' style="font-size: 15px;"></p>
            </div>
            <div class="modal-footer">
                <a class="btn btn-primary font_size_12" href="javascript:void(0)" id="CustomerChange" ><?php echo translate('confirm'); ?></a>
                <button data-dismiss="modal" class="btn btn-danger font_size_12" type="button"><?php echo translate('close'); ?></button>
            </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<script src="<?php echo $this->config->item('js_url'); ?>module/customer.js" type='text/javascript'></script>
<?php include VIEWPATH . 'admin/footer.php'; ?>
<?php
if ($this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) == 'V') {
    include VIEWPATH . 'vendor/header.php';
    $folder_name = 'vendor';
} else {
    include VIEWPATH . 'admin/header.php';
    $folder_name = 'admin';
}
?>
    <input type="hidden" id="folder_name" value="<?php echo $folder_name; ?>"/>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6 col-xl-6">
                                <h4 class="card-title"><?php echo translate('manage'); ?> <?php echo translate('staff'); ?></h4>
                                <div class="page-title-box pb-0 d-sm-flex">
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>"><?php echo translate('dashboard'); ?></a></li>
                                            <li class="breadcrumb-item active"><?php echo translate('staff'); ?></li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-6 text-end add-new-btn-parent">
                                <div class="text-sm-end">
                                    <a href='<?php echo base_url($folder_name.'/staff/add'); ?>'class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2"><i class="mdi mdi-plus me-1"></i> <?php echo translate('add'); ?> <?php echo translate('staff'); ?></a>
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
                                            <th class="text-center font-bold dark-grey-text"><?php echo translate('email'); ?></th>
                                            <th class="text-center font-bold dark-grey-text"><?php echo translate('phone'); ?></th>
                                            <th class="text-center font-bold dark-grey-text"><?php echo translate('status'); ?></th>
                                            <th class="text-center font-bold dark-grey-text"><?php echo translate('created_date'); ?></th>
                                            <th class="text-center font-bold dark-grey-text"><?php echo translate('action'); ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $i = 1;
                                        if (isset($staff_data) && count($staff_data) > 0) {
                                            foreach ($staff_data as $row) {
                                                ?>
                                                <tr>
                                                    <td class="text-center"><?php echo $i; ?></td>
                                                    <td class="text-center"><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
                                                    <td class="text-center"><?php echo $row['email']; ?></td>
                                                    <td class="text-center"><?php echo $row['phone']; ?></td>
                                                    <td class="text-center"><?php echo print_vendor_status($row['status']); ?></td>
                                                    <td class="text-center"><?php echo get_formated_date($row['created_on'], "N"); ?></td>
                                                    <td class="td-actions text-center">
                                                        <a href="<?php echo base_url($folder_name . '/staff/edit/' . $row['id']); ?>" class="text-success" title="<?php echo translate('edit'); ?>" data-toggle="tooltip" data-placement="top"><i class="mdi mdi-pencil font-size-18"></i></a>
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
        <!-- end row -->
    </div>
<script src="<?php echo $this->config->item('js_url'); ?>module/staff.js" type='text/javascript'></script>
<?php include VIEWPATH . $folder_name . '/footer.php'; ?>
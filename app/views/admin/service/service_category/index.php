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


    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6 col-xl-6">
                                <h4 class="card-title"><?php echo translate('service') . " " . translate('category'); ?></h4>
                                <div class="page-title-box pb-0 d-sm-flex">
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>"><?php echo translate('dashboard'); ?></a></li>
                                            <li class="breadcrumb-item active"><?php echo translate('service') . " " . translate('category'); ?></li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-6 text-end add-new-btn-parent">
                                <div class="text-sm-end">
                                    <a href='<?php echo base_url($folder_name.'/service/category/add'); ?>'class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2"><i class="mdi mdi-plus me-1"></i> <?php echo translate('add'); ?> <?php echo translate('city'); ?></a>
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
                                            <th class="text-center font-bold dark-grey-text"><?php echo translate('title'); ?></th>
                                            <th class="text-center font-bold dark-grey-text"><?php echo translate('status'); ?></th>
                                            <th class="text-center font-bold dark-grey-text"><?php echo translate('created_date'); ?></th>
                                            <th class="text-center font-bold dark-grey-text"><?php echo translate('action'); ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if (isset($category_data) && count($category_data) > 0) {
                                            foreach ($category_data as $key => $row) {
                                                if ($row['status'] == "A") {
                                                    $status_string = '<span class="badge bg-success">' . translate('active') . '</span>';
                                                } else {
                                                    $status_string = '<span class="badge bg-danger">' . translate('inactive') . '</span>';
                                                }
                                                ?>
                                                <tr>
                                                    <td class="text-center"><?php echo $key + 1; ?></td>
                                                    <td class="text-center"><?php echo $row['title']; ?></td>
                                                    <td class="text-center"><?php echo $status_string; ?></td>
                                                    <td class="text-center"><?php echo get_formated_date($row['created_on'], "N"); ?></td>
                                                    <td class="td-actions text-center">
                                                        <?php
                                                        if ($this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) == 'V') {
                                                            $created_by = $this->session->userdata('Vendor_ID');
                                                            if ($created_by == $row['created_by']) {
                                                                ?>
                                                                <a href="<?php echo base_url('vendor/update-service-category/' . $row['id']); ?>" class="text-success" title="<?php echo translate('edit'); ?>"><i class="mdi mdi-pencil font-size-18"></i></a>
                                                                <a href="javascript:void(0)" data-bs-toggle="modal" onclick='DeleteRecord(this)' data-bs-target="#delete-record" data-id="<?php echo (int) $row['id']; ?>" class="text-danger" title="<?php echo translate('delete'); ?>"><i class="mdi mdi-delete font-size-18"></i></a>
                                                                <?php
                                                            } else {
                                                                echo '-';
                                                            }
                                                        } else {
                                                            ?>
                                                            <a href="<?php echo base_url('vendor/update-service-category/' . $row['id']); ?>" class="text-success" title="<?php echo translate('edit'); ?>"><i class="mdi mdi-pencil font-size-18"></i></a>
                                                            <a href="javascript:void(0)" data-bs-toggle="modal" onclick='DeleteRecord(this)' data-bs-target="#delete-record" data-id="<?php echo (int) $row['id']; ?>" class="text-danger" title="<?php echo translate('delete'); ?>"><i class="mdi mdi-delete font-size-18"></i></a>
                                                        <?php } ?>
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
                </div>
            </div>
        </div>
        <!-- end row -->
    </div>
<script src="<?php echo $this->config->item('js_url'); ?>module/service-category.js" type='text/javascript'></script>
<?php
if ($this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) == 'V') {
    include VIEWPATH . 'vendor/footer.php';
} else {
    include VIEWPATH . 'admin/footer.php';
}
?>
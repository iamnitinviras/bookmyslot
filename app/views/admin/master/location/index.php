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
                            <h4 class="card-title"><?php echo translate('location'); ?></h4>
                            <div class="page-title-box pb-0 d-sm-flex">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>"><?php echo translate('dashboard'); ?></a></li>
                                        <li class="breadcrumb-item active"><?php echo translate('location'); ?></li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-6 text-end add-new-btn-parent">
                            <div class="text-sm-end">
                                <a href='<?php echo base_url($folder_name.'/add-location'); ?>'class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2"><i class="mdi mdi-plus me-1"></i> <?php echo translate('add'); ?> <?php echo translate('location'); ?></a>
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
                                        <th class="text-left font-bold dark-grey-text">#</th>
                                        <th class="text-left font-bold dark-grey-text"><?php echo translate('title'); ?></th>
                                        <th class="text-left font-bold dark-grey-text"><?php echo translate('city'); ?></th>
                                        <th class="text-center font-bold dark-grey-text"><?php echo translate('status'); ?></th>
                                        <th class="text-center font-bold dark-grey-text"><?php echo translate('created_date'); ?></th>
                                        <th class="text-center font-bold dark-grey-text"><?php echo translate('action'); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if (isset($loc_data) && count($loc_data) > 0) {
                                        foreach ($loc_data as $key => $row) {


                                            if ($row['loc_status'] == "A") {
                                                $status_string = '<span class="badge bg-success">' . translate('active') . '</span>';
                                            } else {
                                                $status_string = '<span class="badge bg-danger">' . translate('inactive') . '</span>';
                                            }
                                            ?>
                                            <tr>
                                                <td class="text-left"><?php echo $key + 1; ?></td>
                                                <td class="text-left"><?php echo $row['loc_title']; ?></td>
                                                <td class="text-left"><?php echo $row['city_title']; ?></td>
                                                <td class="text-center"><?php echo $status_string; ?></td>
                                                <td class="text-center"><?php echo get_formated_date($row['loc_created_on'], "N"); ?></td>
                                                <td class="td-actions text-center">
                                                    <?php
                                                    if ($this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) == 'V') {
                                                        $created_by = $this->session->userdata('Vendor_ID');
                                                        if ($created_by == $row['loc_created_by']) {
                                                            ?>
                                                            <a href="<?php echo base_url('vendor/update-location/' . $row['loc_id']); ?>" class="text-success" title="<?php echo translate('edit'); ?>"><i class="mdi mdi-pencil font-size-18"></i></a>
                                                            <a id="" data-toggle="modal" onclick='DeleteRecord(this)' data-target="#delete-record" data-id="<?php echo (int) $row['loc_id']; ?>" class="text-danger" title="<?php echo translate('delete'); ?>"><i class="mdi mdi-delete font-size-18"></i></a>
                                                            <?php
                                                        } else {
                                                            echo '-';
                                                        }
                                                    } else {
                                                        ?>
                                                        <a href="<?php echo base_url('admin/update-location/' . $row['loc_id']); ?>" class="text-success" title="<?php echo translate('edit'); ?>" data-toggle="tooltip" data-placement="top"><i class="mdi mdi-pencil font-size-18"></i></a>
                                                        <span class="d-inline-block" title="<?php echo translate('delete'); ?>" data-toggle="tooltip" data-placement="top"><a id="" data-toggle="modal" onclick='DeleteRecord(this)' data-target="#delete-record" data-id="<?php echo (int) $row['loc_id']; ?>" class="text-danger"><i class="mdi mdi-delete font-size-18"></i></a></span>
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
                        <!--col-md-12-->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end row -->
</div>
<script src="<?php echo $this->config->item('js_url'); ?>module/location.js" type='text/javascript'></script>
<?php
if ($this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) == 'V') {
    include VIEWPATH . 'vendor/footer.php';
} else {
    include VIEWPATH . 'admin/footer.php';
}
?>
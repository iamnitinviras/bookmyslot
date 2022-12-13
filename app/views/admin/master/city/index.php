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
                            <h4 class="card-title"><?php echo translate('city'); ?></h4>
                            <div class="page-title-box pb-0 d-sm-flex">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>"><?php echo translate('dashboard'); ?></a></li>
                                        <li class="breadcrumb-item active"><?php echo translate('city'); ?></li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-6 text-end add-new-btn-parent">
                            <div class="text-sm-end">
                                <a href='<?php echo base_url($folder_name.'/add-city'); ?>'class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2"><i class="mdi mdi-plus me-1"></i> <?php echo translate('add'); ?> <?php echo translate('city'); ?></a>
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
                                        <?php if ($folder_name == 'admin'): ?>
                                            <th class="text-center font-bold dark-grey-text"><?php echo translate('is_default'); ?></th>
                                        <?php endif; ?>
                                        <th class="text-center font-bold dark-grey-text"><?php echo translate('created_date'); ?></th>
                                        <th class="text-center font-bold dark-grey-text"><?php echo translate('action'); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if (isset($city_data) && count($city_data) > 0) {
                                        foreach ($city_data as $key => $row) {
                                            if ($row['city_status'] == "A") {
                                                $status_string = '<span class="badge bg-primary">' . translate('active') . '</span>';
                                            } else {
                                                $status_string = '<span class="badge bg-danger">' . translate('inactive') . '</span>';
                                            }
                                            ?>
                                            <tr>
                                                <td class="text-center"><?php echo $key + 1; ?></td>
                                                <td class="text-center"><?php echo $row['city_title']; ?></td>
                                                <td class="text-center"><?php echo $status_string; ?></td>
                                                <?php if ($folder_name == 'admin'): ?>
                                                    <td class="text-center">
                                                        <input type="checkbox" onchange="updateDefaultCity(this)" <?php echo ($row['is_default'] == 1) ? "checked='checked'" : ""; ?> style="visibility: visible !important;left: 0px !important;position: relative;" name="is_default" data-id="<?php echo $row['city_id']; ?>"/>
                                                    </td>
                                                <?php endif; ?>

                                                <td class="text-center"><?php echo get_formated_date($row['city_created_on'], "N"); ?></td>
                                                <td class="td-actions text-center">
                                                    <?php
                                                    if ($this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) == 'V') {
                                                        $created_by = $this->session->userdata('Vendor_ID');
                                                        if ($created_by == $row['city_created_by']) {
                                                            ?>
                                                            <a href="<?php echo base_url('admin/update-city/' . $row['city_id']); ?>" class="text-success"><i class="mdi mdi-pencil font-size-18"></i></a>
                                                            <a href="javascript:void(0);" data-toggle="modal" onclick='DeleteRecord(this)' data-target="#delete-record" data-id="<?php echo (int) $row['city_id']; ?>"  class="text-danger"><i class="mdi mdi-delete font-size-18"></i></a>
                                                            <?php
                                                        } else {
                                                            echo '-';
                                                        }
                                                    } else {
                                                        ?>
                                                        <a href="<?php echo base_url('admin/update-city/' . $row['city_id']); ?>" class="text-success"><i class="mdi mdi-pencil font-size-18"></i></a>
                                                        <a href="javascript:void(0);" data-bs-toggle="modal" onclick='DeleteRecord(this)' data-bs-target="#delete-record" data-id="<?php echo (int) $row['city_id']; ?>"  class="text-danger"><i class="mdi mdi-delete font-size-18"></i></a>
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
<script src="<?php echo $this->config->item('js_url'); ?>module/city.js" type='text/javascript'></script>
<?php
if ($this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) == 'V') {
    include VIEWPATH . 'vendor/footer.php';
} else {
    include VIEWPATH . 'admin/footer.php';
}
?>
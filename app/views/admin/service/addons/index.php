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
                            <h4 class="card-title"><?php echo $title; ?></h4>
                            <div class="page-title-box pb-0 d-sm-flex">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>"><?php echo translate('dashboard'); ?></a></li>
                                        <li class="breadcrumb-item active"><?php echo $title; ?></li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-6 text-end add-new-btn-parent">
                            <div class="text-sm-end">
                                <a href='<?php echo base_url($folder_name.'/add-service-addons/'.$service_id); ?>'class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2"><i class="mdi mdi-plus me-1"></i> <?php echo translate('add') . " " . translate('service') . " " . translate('add_ons'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 m-auto">
                            <?php $this->load->view('message'); ?>

                            <h5 class="card-title"><b><?php echo translate('title'); ?> : </b> <?php echo isset($service_data['title']) ? $service_data['title'] : ""; ?></h5>
                            <p class="card-text"><b><?php echo translate('price'); ?> : </b> <?php echo price_format($service_data['price']); ?></p>

                            <div class="table-responsive">
                                <table class="table table-bordered dt-responsive nowrap w-100" id="datatable">
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
                                                $update_url = 'vendor/service/addons/edit/' . $service_id . '/' . $row['add_on_id'];
                                            } else {
                                                $update_url = 'admin/service/addons/edit/' . $service_id . '/' . $row['add_on_id'];
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
                                                    <a href="<?php echo base_url($update_url); ?>" class="text-success" title="<?php echo translate('edit'); ?>" data-bs-toggle="tooltip" data-bs-placement="top">
                                                        <i class="mdi mdi-pencil font-size-18"></i>
                                                    </a>
                                                    <a href="javascript:void(0)" data-bs-toggle="modal" onclick='DeleteRecord(this)' data-bs-target="#delete-record" data-id="<?php echo (int) $row['add_on_id']; ?>" class="text-danger" title="<?php echo translate('delete'); ?>">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
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
<script src="<?php echo $this->config->item('js_url'); ?>module/service.js" type='text/javascript'></script>
<?php
if ($this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) == 'V') {
    include VIEWPATH . 'vendor/footer.php';
} else {
    include VIEWPATH . 'admin/footer.php';
}
?>
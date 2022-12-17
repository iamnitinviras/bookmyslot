<?php
include VIEWPATH . 'admin/header.php';
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6 col-xl-6">
                            <h4 class="card-title"><?php echo translate('testimonial'); ?></h4>
                            <div class="page-title-box pb-0 d-sm-flex">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>"><?php echo translate('dashboard'); ?></a></li>
                                        <li class="breadcrumb-item active"><?php echo translate('testimonial'); ?></li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-6 text-end add-new-btn-parent">
                            <div class="text-sm-end">
                                <a href='<?php echo base_url('admin/add-testimonial'); ?>'class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2"><i class="mdi mdi-plus me-1"></i> <?php echo translate('add'); ?> <?php echo translate('testimonial'); ?></a>
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
                                        <th class="text-left font-bold dark-grey-text"><?php echo translate('name'); ?></th>
                                        <th class="text-left font-bold dark-grey-text"><?php echo translate('image'); ?></th>
                                        <th class="text-left font-bold dark-grey-text"><?php echo translate('details'); ?></th>
                                        <th class="text-left font-bold dark-grey-text"><?php echo translate('status'); ?></th>
                                        <th class="text-left font-bold dark-grey-text"><?php echo translate('created_date'); ?></th>
                                        <th class="text-left font-bold dark-grey-text"><?php echo translate('action'); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if (isset($testimonial_data) && count($testimonial_data) > 0) {
                                        foreach ($testimonial_data as $key => $row) {
                                            if ($row['status'] == "A") {
                                                $status_string = '<span class="badge bg-success">' . translate('active') . '</span>';
                                            } else {
                                                $status_string = '<span class="badge bg-danger">' . translate('inactive') . '</span>';
                                            }


                                            $image = $row['image'];
                                            if (isset($image) && $image != "") {
                                                if (file_exists(FCPATH . 'assets/uploads/category/' . $image)) {
                                                    $image_path = base_url("assets/uploads/category/" . $image);
                                                } else {
                                                    $image_path = base_url() . img_path . "/avatar.png";
                                                }
                                            } else {
                                                $image_path = base_url() . img_path . "/avatar.png";
                                            }
                                            ?>
                                            <tr>
                                                <td class="text-left"><?php echo $key + 1; ?></td>
                                                <td class="text-left"><?php echo $row['name']; ?></td>
                                                <td class="text-left"><img src="<?php echo $image_path; ?>" height="70" width="70" class="img-responsive img-thumbnail"/></td>
                                                <td class="text-left"><?php echo $row['details']; ?></td>
                                                <td class="text-left"><?php echo $status_string; ?></td>
                                                <td class="text-left"><?php echo get_formated_date($row['created_date'], "N"); ?></td>
                                                <td class="td-actions text-left">
                                                    <a href="<?php echo base_url('admin/update-testimonial/' . $row['id']); ?>" class="text-success" title="<?php echo translate('edit'); ?>" data-toggle="tooltip" data-placement="top"><i class="mdi mdi-pencil font-size-18"></i></a>
                                                    <a href="javascript:void(0)" data-bs-toggle="modal" onclick='DeleteRecord(this)' data-bs-target="#delete-record" data-id="<?php echo (int) $row['id']; ?>" class="text-danger"><i class="mdi mdi-delete font-size-18"></i></a>
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
<script src="<?php echo $this->config->item('js_url'); ?>module/testimonial.js" type='text/javascript'></script>
<?php
include VIEWPATH . 'admin/footer.php';
?>
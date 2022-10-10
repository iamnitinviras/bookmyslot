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
                                    <h3 class="black-text font-bold mb-0"><?php echo translate('manage'); ?> <?php echo translate('city'); ?></h3>
                                </span>  
                                <span class="col-md-3 col-3 text-right m-0">
                                    <?php if ($this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) == 'V') { ?>
                                        <a  href='<?php echo base_url('vendor/add-city'); ?>' class="btn-floating btn-sm btn-success m-0"><i class="fa fa-plus-circle"></i></a>
                                    <?php } else { ?>
                                        <a  href='<?php echo base_url('admin/add-city'); ?>' class="btn-floating btn-sm btn-success m-0"><i class="fa fa-plus-circle"></i></a>
                                    <?php } ?>
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
                                                        $status_string = '<span class="badge badge-success">' . translate('active') . '</span>';
                                                    } else {
                                                        $status_string = '<span class="badge badge-danger">' . translate('inactive') . '</span>';
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td class="text-center"><?php echo $key + 1; ?></td>
                                                        <td class="text-center"><?php echo $row['city_title']; ?></td>
                                                        <td class="text-center"><?php echo $status_string; ?></td>
                                                        <?php if ($folder_name == 'admin'): ?>
                                                            <td class="text-center">
                                                                <input type="checkbox" <?php echo ($row['is_default'] == 1) ? "checked='checked'" : ""; ?> style="visibility: visible !important;left: 0px !important;position: relative;" name="is_default" data-id="<?php echo $row['city_id']; ?>"/>
                                                            </td>
                                                        <?php endif; ?>

                                                        <td class="text-center"><?php echo get_formated_date($row['city_created_on'], "N"); ?></td>
                                                        <td class="td-actions text-center">
                                                            <?php
                                                            if ($this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) == 'V') {
                                                                $created_by = $this->session->userdata('Vendor_ID');
                                                                if ($created_by == $row['city_created_by']) {
                                                                    ?>
                                                                    <a href="<?php echo base_url('vendor/update-city/' . $row['city_id']); ?>" class="btn btn-primary font_size_12" title="<?php echo translate('edit'); ?>"><i class="fa fa-pencil"></i></a>
                                                                    <a id="" data-toggle="modal" onclick='DeleteRecord(this)' data-target="#delete-record" data-id="<?php echo (int) $row['city_id']; ?>" class="btn btn-danger font_size_12" title="<?php echo translate('delete'); ?>"><i class="fa fa-trash"></i></a>
                                                                    <?php
                                                                } else {
                                                                    echo '-';
                                                                }
                                                            } else {
                                                                ?>
                                                                <a href="<?php echo base_url('admin/update-city/' . $row['city_id']); ?>" class="btn btn-primary font_size_12" title="<?php echo translate('edit'); ?>" data-toggle="tooltip" data-placement="top"><i class="fa fa-pencil"></i></a>
                                                                <span class="d-inline-block" title="<?php echo translate('delete'); ?>" data-toggle="tooltip" data-placement="top"><a id="" data-toggle="modal" onclick='DeleteRecord(this)' data-target="#delete-record" data-id="<?php echo (int) $row['city_id']; ?>" class="btn btn-danger font_size_12"><i class="fa fa-trash"></i></a></span>
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
<?php if ($folder_name == 'admin'): ?>
    <script type="text/javascript">
        $(document).ready(function () {
            $('input[type="checkbox"]').click(function () {
                if ($(this).prop("checked") == true) {

                    var id = $(this).data('id');
                    $.ajax({
                        url: site_url + "admin/default-city/" + id,
                        type: "post",
                        data: {token_id: csrf_token_name},
                        beforeSend: function () {
                            $("body").preloader({
                                percent: 10,
                                duration: 15000
                            });
                        },
                        success: function (data) {
                            if (data == true) {
                                window.location.reload();
                            } else {
                                window.location.reload();
                            }
                        }
                    });
                }
            });
        });
    </script>
<?php endif; ?>

<script src="<?php echo $this->config->item('js_url'); ?>module/city.js" type='text/javascript'></script>
<?php
if ($this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) == 'V') {
    include VIEWPATH . 'vendor/footer.php';
} else {
    include VIEWPATH . 'admin/footer.php';
}
?>
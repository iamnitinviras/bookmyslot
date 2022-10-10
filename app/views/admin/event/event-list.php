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
                                    <h3 class="black-text font-bold mb-0"><?php echo translate('manage'); ?> <?php echo translate('event'); ?></h3>
                                </span>  
                                <span class="col-md-3 col-3 text-right m-0">
                                    <?php if ($this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) == 'V') { ?>
                                        <a  href='<?php echo base_url('vendor/add-event'); ?>' class="btn-floating btn-sm btn-success m-0"><i class="fa fa-plus-circle"></i></a>
                                    <?php } else { ?>
                                        <a  href='<?php echo base_url('admin/add-event'); ?>' class="btn-floating btn-sm btn-success m-0"><i class="fa fa-plus-circle"></i></a>
                                    <?php } ?>
                                </span>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <form class="form" role="form" method="GET" id="appointment_filter" action="<?php echo base_url($folder_name . '/manage-event') ?>">
                                    <div class="row">
                                        <?php if (($this->session->userdata('Type_' . ucfirst($this->uri->segment(1)))) && $this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) != 'V') { ?>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <select  name="vendor" id="vendor" class="form-control" onchange="this.form.submit()" style="display: block !important">
                                                        <option value=""><?php echo translate('vendor') ?></option>

                                                        <?php foreach ($vendor_list as $val): ?>
                                                            <option <?php echo (isset($_REQUEST['vendor']) && $_REQUEST['vendor'] == $val['id']) ? "selected='selected'" : ""; ?> value="<?php echo $val['id'] ?>"><?php echo ($val['company_name']); ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>


                                            <div class="col-md-1">
                                                <a class="btn btn-info btn-sm" href="<?php echo base_url($folder_name . '/manage-event') ?>"><i class="fa fa-refresh"></i></a>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </form>
                                <div class="table-responsive">
                                    <table class="table mdl-data-table" id="example">
                                        <thead>
                                            <tr>
                                                <th class="text-center font-bold dark-grey-text">#</th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('title'); ?></th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('category'); ?></th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('vendor'); ?></th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('date'); ?></th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('status'); ?></th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('action'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (isset($event_data) && count($event_data) > 0) {
                                                foreach ($event_data as $key => $row) {

                                                    if (isset($row['id']) && $row['id'] != NULL) {
                                                        if ($row['status'] == "A") {
                                                            $status_string = '<span class="badge badge-success">' . translate('active') . '</span>';
                                                        } else if ($row['status'] == "E") {
                                                            $status_string = '<span class="badge badge-danger">' . translate('expired') . '</span>';
                                                        } else if ($row['status'] == "SS") {
                                                            $status_string = '<span class="badge badge-info">' . translate('service_suspended') . '</span>';
                                                        } else {
                                                            $status_string = '<span class="badge badge-danger">' . translate('inactive') . '</span>';
                                                        }
                                                        if ($this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) == 'V') {
                                                            $update_url = 'vendor/update-event/' . $row['id'];
                                                            $manage_url = 'vendor/event-booking?event=' . $row['id'];
                                                        } else {
                                                            $update_url = 'admin/update-event/' . $row['id'];
                                                            $manage_url = 'admin/event-booking?event=' . $row['id'];
                                                        }

                                                        $event_date = get_formated_date($row['start_date'], 'Y');
                                                        ?>
                                                        <tr>
                                                            <td class="text-center"><?php echo $key + 1; ?></td>
                                                            <td class="text-left">
                                                                <?php echo character_limiter($row['title'], 30); ?><br/>
                                                                <span class="badge badge-success"><?php echo $row['company_name']; ?></span>
                                                            </td>
                                                            <td class="text-center"><?php echo $row['category_title']; ?></td>
                                                            <td class="text-center"><?php echo ($row['company_name']); ?></td>
                                                            <td class="text-center"><?php echo $event_date; ?></td>
                                                            <td class="text-center"><?php echo $status_string; ?></td>
                                                            <td class="td-actions text-center">

                                                                <?php if ($row['status'] != "E"): ?>
                                                                    <a href="<?php echo base_url($update_url); ?>" class="btn btn-primary font_size_12" title="<?php echo translate('edit'); ?>" data-toggle="tooltip" data-placement="top"><i class="fa fa-pencil"></i></a>
                                                                <?php endif; ?>

                                                                <span class="d-inline-block" title="<?php echo translate('delete'); ?>" data-toggle="tooltip" data-placement="top"><a id="" data-toggle="modal" onclick='DeleteRecord(this)' data-target="#delete-record" data-id="<?php echo (int) $row['id']; ?>" class="btn btn-danger font_size_12"><i class="fa fa-trash"></i></a></span>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
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

<script src="<?php echo $this->config->item('js_url'); ?>module/event.js" type='text/javascript'></script>
<?php
if ($this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) == 'V') {
    include VIEWPATH . 'vendor/footer.php';
} else {
    include VIEWPATH . 'admin/footer.php';
}
?>
<?php
include VIEWPATH . 'admin/header.php';
?>
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
                                    <h3 class="black-text font-bold mb-0"><?php echo translate('customer'); ?> <?php echo translate('booking'); ?></h3>
                                </span>  
                                <span class="col-md-3 col-3 text-right m-0">
                                    <a href='<?php echo base_url('admin/add-customer'); ?>' class="btn-floating btn-sm btn-success m-0"><i class="fa fa-plus-circle"></i></a>
                                </span>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true"><?php echo translate('service'); ?></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false"><?php echo translate('event'); ?></a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="pills-tabContent">
                                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                                        <div class="table-responsive">
                                            <table class="table mdl-data-table" id="example">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center font-bold dark-grey-text">#</th>
                                                        <th class="text-center font-bold dark-grey-text"><?php echo translate('title'); ?></th>
                                                        <th class="text-center font-bold dark-grey-text"><?php echo translate('date'); ?></th>
                                                        <th class="text-center font-bold dark-grey-text"><?php echo translate('time'); ?></th>
                                                        <th class="text-center font-bold dark-grey-text"><?php echo translate('created_by'); ?></th>
                                                        <th class="text-center font-bold dark-grey-text"><?php echo translate('type'); ?></th>
                                                        <th class="text-center font-bold dark-grey-text"><?php echo translate('payment') . ' ' . translate('status'); ?></th>
                                                        <th class="text-center font-bold dark-grey-text"><?php echo translate('action'); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $i = 1;
                                                    if (isset($service_appointment_data) && count($service_appointment_data) > 0) {
                                                        foreach ($service_appointment_data as $row) {
                                                            ?>
                                                            <tr>
                                                                <td class="text-center"><?php echo $i; ?></td>
                                                                <td class="text-center"><?php echo $row['title']; ?></td>
                                                                <td class="text-center"><?php echo get_formated_date($row['start_date'], ''); ?></td>
                                                                <td class="text-center"><?php echo get_formated_time(strtotime($row['start_time'])); ?></td>
                                                                <td class="text-center"><?php echo ($row['first_name']) . ' ' . $row['last_name']; ?></td>
                                                                <td class="text-center"><?php echo $row['payment_type'] == 'F' ? translate('free') : price_format($row['price']); ?></td>
                                                                <td class="text-center"><?php echo check_appointment_pstatus($row['payment_status']); ?></td>
                                                                <td class="text-center">
                                                                    <a href="<?php echo base_url('admin/view-booking-details/' . $row['id']); ?>" class="btn btn-info"><span class="fa fa-info"></span></a>
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
                                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                                        <div class="table-responsive">
                                            <table class="table mdl-data-table" id="example2">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center font-bold dark-grey-text">#</th>
                                                        <th class="text-center font-bold dark-grey-text"><?php echo translate('title'); ?></th>
                                                        <th class="text-center font-bold dark-grey-text"><?php echo translate('date'); ?></th>
                                                        <th class="text-center font-bold dark-grey-text"><?php echo translate('time'); ?></th>
                                                        <th class="text-center font-bold dark-grey-text"><?php echo translate('created_by'); ?></th>
                                                        <th class="text-center font-bold dark-grey-text"><?php echo translate('type'); ?></th>
                                                        <th class="text-center font-bold dark-grey-text"><?php echo translate('payment') . ' ' . translate('status'); ?></th>
                                                        <th class="text-center font-bold dark-grey-text"><?php echo translate('action'); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $i = 1;
                                                    if (isset($event_appointment_data) && count($event_appointment_data) > 0) {
                                                        foreach ($event_appointment_data as $row) {
                                                            ?>
                                                            <tr>
                                                                <td class="text-center"><?php echo $i; ?></td>
                                                                <td class="text-center"><?php echo $row['title']; ?></td>
                                                                <td class="text-center"><?php echo get_formated_date($row['start_date'], ''); ?></td>
                                                                <td class="text-center"><?php echo get_formated_time(strtotime($row['start_time'])); ?></td>
                                                                <td class="text-center"><?php echo ($row['first_name']) . ' ' . $row['last_name']; ?></td>
                                                                <td class="text-center"><?php echo $row['payment_type'] == 'F' ? translate('free') : price_format($row['price']); ?></td>
                                                                <td class="text-center"><?php echo check_appointment_pstatus($row['payment_status']); ?></td>
                                                                <td class="text-center">
                                                                    <a href="<?php echo base_url('admin/view-booking-details/' . $row['id']); ?>" class="btn btn-info"><span class="fa fa-info"></span></a>
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
                <button data-dismiss="modal" class="btn blue-gradient btn-rounded pull-left" type="button"><?php echo translate('close'); ?></button>
                <a class="btn purple-gradient btn-rounded" href="javascript:void(0)" id="RecordDelete" ><?php echo translate('confirm'); ?></a>
            </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
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
                <button data-dismiss="modal" class="btn blue-gradient btn-rounded pull-left" type="button"><?php echo translate('close'); ?></button>
                <a class="btn purple-gradient btn-rounded" href="javascript:void(0)" id="CustomerChange" ><?php echo translate('confirm'); ?></a>
            </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<script src="<?php echo $this->config->item('js_url'); ?>module/customer.js" type='text/javascript'></script>
<?php include VIEWPATH . 'admin/footer.php'; ?>
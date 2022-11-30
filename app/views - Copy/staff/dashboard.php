<?php
include VIEWPATH . 'staff/header.php';
?>
<!-- start dashboard -->
<div class="dashboard-body">
    <!-- Start Content -->
    <div class="content pt-0">
        <!-- Start Container -->
        <div class="container-fluid">
            <!-- Start Section -->
            <div class="row">
                <div class="col-md-12 pt-2">
                    <?php $this->load->view('message'); ?>
                </div>
            </div>
            <section class="form-light content px-2 sm-margin-b-20 pt-0">
                <div class="row">
                    <div class="col-xl-4 col-md-3">
                        <!--Card-->
                        <div class="card">
                            <!--Card Data-->
                            <div class="row mt-3">
                                <div class="col-md-3 col-3 text-left pl-3">
                                    <a type="button" href='javascript:void(0)' class="btn-floating mt-0 danger-color btn-lg dark-blue lighten-1 ml-3 waves-effect waves-light"><i class="fa fa-calendar" aria-hidden="true"></i></a>
                                </div>
                                <div class="col-md-9 col-9 text-right pr-30">
                                    <h5 class="ml-4 mb-2 font-bold"><?php echo isset($appointment_data) ? count($appointment_data) : 0; ?></h5>
                                    <p class="font-small grey-text"><?php echo translate('pending') . " " . translate('appointment'); ?></p>
                                </div>
                            </div>     
                        </div>
                        <!--/.Card-->
                    </div>
                    <div class="col-xl-4 col-md-3">
                        <!--Card-->
                        <div class="card">
                            <!--Card Data-->
                            <div class="row mt-3">
                                <div class="col-md-3 col-3 text-left pl-3">
                                    <a type="button" href='javascript:void(0)' class="btn-floating mt-0 success-color btn-lg dark-blue lighten-1 ml-3 waves-effect waves-light"><i class="fa fa-calendar" aria-hidden="true"></i></a>
                                </div>
                                <div class="col-md-9 col-9 text-right pr-30">
                                    <h5 class="ml-4 mb-2 font-bold"><?php echo isset($completed_appointment) ? $completed_appointment : 0; ?></h5>
                                    <p class="font-small grey-text"><?php echo translate('completed') . " " . translate('appointment'); ?></p>
                                </div>
                            </div>     
                        </div>
                        <!--/.Card-->
                    </div>
                    
                </div>
            </section>
            <div class="card">
                <div class="card-body">
                    <h3 class="text-left" style="font-size: 18px;"><?php echo translate('upcoming_appointment'); ?></h3>
                    <hr>
                    <div class="table-responsive">
                        <table class="table mdl-data-table">
                            <thead>
                                <tr>
                                    <th class="text-center font-bold dark-grey-text">#</th>
                                    <th class="text-center font-bold dark-grey-text"><?php echo translate('service'); ?></th>
                                    <th class="text-center font-bold dark-grey-text"><?php echo translate('customer_name'); ?></th>
                                    <th class="text-center font-bold dark-grey-text"><?php echo translate('slot_time'); ?></th>
                                    <th class="text-center font-bold dark-grey-text"><?php echo translate('appointment_date'); ?></th>
                                    <th class="text-center font-bold dark-grey-text"><?php echo translate('type'); ?></th>
                                    <th class="text-center font-bold dark-grey-text"><?php echo translate('payment'); ?></th>
                                    <th class="text-center font-bold dark-grey-text"><?php echo translate('status'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($appointment_data) && count($appointment_data) > 0) {
                                    foreach ($appointment_data as $key => $row) {
                                        ?>
                                        <tr>
                                            <td class="text-center"><?php echo $key + 1; ?></td>
                                            <td class="text-center"><?php echo ($row['title']); ?></td>
                                            <td class="text-center"><?php echo ($row['first_name']) . " " . ($row['last_name']); ?></td>
                                            <td class="text-center"><?php echo $row['slot_time'] . " " . translate('minute'); ?></td>
                                            <td class="text-center"><?php echo get_formated_date($row['start_date'] . " " . $row['start_time']); ?></td>
                                            <?php if ($row['payment_type'] == 'F'): ?>
                                                <td class="text-center">
                                                    <span class="badge badge-success"><?php echo translate('free'); ?></span>
                                                </td>
                                            <?php else: ?>
                                                <td class="text-center">
                                                    <span class="badge badge-primary"><?php echo translate('paid'); ?></span>
                                                </td>
                                            <?php endif; ?>
                                            <td class="text-center"><?php echo check_appointment_pstatus($row['payment_status']); ?></td>
                                            <td class="text-center"><?php echo check_appointment_status($row['status']); ?></td>
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

            <section class="mb-2">
                <div class="row">
                    <?php if (isset($pending_appointment) && count($pending_appointment) > 0): ?>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body pt-2">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p style="font-size: 18px;" class="text-left"><?php echo translate('pending') . " " . translate('appointment'); ?></p>
                                        </div>
                                        <div class="col-md-6">
                                            <a class="btn btn-primary m-0 pull-right" href="<?php echo base_url('staff/appointment?status=P'); ?>"><?php echo translate('approve'); ?></a>
                                        </div>
                                    </div>
                                    <hr class="mt-0">
                                    <div class="table-responsive">
                                        <table class="table mdl-data-table">
                                            <thead>
                                                <tr>
                                                    <th class="text-center font-bold dark-grey-text">#</th>
                                                    <th class="text-center font-bold dark-grey-text"><?php echo translate('service'); ?></th>
                                                    <th class="text-center font-bold dark-grey-text"><?php echo translate('slot_time'); ?></th>
                                                    <th class="text-center font-bold dark-grey-text"><?php echo translate('appointment_date'); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $cnt = 1;
                                                if (isset($pending_appointment) && count($pending_appointment) > 0) {
                                                    foreach ($pending_appointment as $row) {
                                                        ?>
                                                        <tr>
                                                            <td class="text-center"><?php echo $cnt; ?></td>
                                                            <td class="text-center"><?php echo ($row['title']); ?></td>
                                                            <td class="text-center"><?php echo convertToHoursMins($row['slot_time']); ?></td>
                                                            <td class="text-center"><?php echo get_formated_date($row['start_date'] . " " . $row['start_time']); ?></td>
                                                        </tr>
                                                        <?php
                                                        $cnt++;
                                                    }
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
               </div>
            </section>

        </div>
        <!-- End Container -->
    </div> 
    <!-- End Content -->
</div> 
<!-- End dashboard -->
<?php include VIEWPATH . 'staff/footer.php'; ?>

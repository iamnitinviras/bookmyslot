<?php
include VIEWPATH . 'admin/header.php';
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
            <!-- Card Color Section -->
            <section class="mb-2">
                <div class="row">
                    <!--Grid column-->
                    <div class="col-xl-3 col-md-3">
                        <!--Card-->
                        <div class="card">
                            <!--Card Data-->
                            <div class="row mt-3">
                                <div class="col-md-5 col-5 text-left pl-3">
                                    <a href='<?php echo base_url('admin/customer'); ?>' type="button" class="btn-floating mt-0 btn-lg blue-gradient ml-3 waves-effect waves-light"><i class="fa fa-user" aria-hidden="true"></i></a>
                                </div>
                                <div class="col-md-7 col-7 text-right pr-30">
                                    <h5 class="ml-4 mb-2 font-bold"><?php echo $total_customer; ?></h5>
                                    <p class="font-small grey-text"><?php echo translate('total'); ?> <?php echo translate('customer'); ?></p>
                                </div>
                            </div>
                            <!--/.Card Data-->
                        </div>
                        <!--/.Card-->
                    </div>
                    <!--Grid column-->
                    <!--Grid column-->
                    <div class="col-xl-3 col-md-3">
                        <!--Card-->
                        <div class="card">
                            <!--Card Data-->
                            <div class="row mt-3">
                                <div class="col-md-5 col-5 text-left pl-3">
                                    <a href='<?php echo base_url('admin/vendor'); ?>' type="button" class="btn-floating mt-0 btn-lg deep-orange ml-3 waves-effect waves-light"><i class="fa fa-user-plus" aria-hidden="true"></i></a>
                                </div>
                                <div class="col-md-7 col-7 text-right pr-30">
                                    <h5 class="ml-4 mb-2 font-bold"><?php echo $total_vendor; ?></h5>
                                    <p class="font-small grey-text"><?php echo translate('total'); ?> <?php echo translate('vendor'); ?></p>
                                </div>
                            </div>
                            <!--/.Card Data-->
                        </div>
                        <!--/.Card-->
                    </div>
                    <!--Grid column-->

                    <div class="col-xl-3 col-md-3">
                        <div class="card">
                            <!--Card Data-->
                            <div class="row mt-3">
                                <div class="col-md-5 col-5 text-left pl-3">
                                    <a type="button" href='<?php echo base_url('admin/manage-appointment'); ?>' class="btn-floating mt-0 btn-lg warning-color ml-3 waves-effect waves-light"><i class="fa fa-users" aria-hidden="true"></i></a>
                                </div>
                                <div class="col-md-7 col-7 text-right pr-30">
                                    <h5 class="ml-4 mb-2 font-bold"><?php echo $total_appointment; ?></h5>
                                    <p class="font-small grey-text"><?php echo translate('appointment'); ?></p>
                                </div>
                            </div>
                            <!--/.Card Data-->
                        </div>
                        <!--/.Card-->
                    </div>
                    <!--Grid column-->

                    <!--Grid column-->
                    <div class="col-xl-3 col-md-3">
                        <!--Card-->
                        <div class="card">
                            <!--Card Data-->
                            <div class="row mt-3">
                                <div class="col-md-4 col-4 text-left pl-3">
                                    <a type="button" href='<?php echo base_url('admin/payout-request'); ?>' class="btn-floating mt-0 btn-lg success-color ml-3 waves-effect waves-light"><i class="fa fa-money" aria-hidden="true"></i></a>
                                </div>
                                <div class="col-md-8 col-8 text-right pr-30">
                                    <h5 class="ml-4 mb-2 font-bold"><?php echo isset($total_payout_request) ? $total_payout_request : 0; ?></h5>
                                    <p class="font-small grey-text"><?php echo translate('payout_request'); ?></p>
                                </div>
                            </div>
                            <!--/.Card Data-->
                        </div>
                        <!--/.Card-->
                    </div>
                    <!--Grid column-->

                </div>
            </section>
            <!-- Card Color Section -->

            <?php if (isset($appointment_data) && count($appointment_data) > 0): ?>
                <div class="card">
                    <div class="card-body">
                        <p style="font-size: 18px;" class="text-left"><?php echo translate('upcoming_appointment'); ?></p>
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
                                    foreach ($appointment_data as $key => $row):
                                        ?>
                                        <tr>
                                            <td class="text-center"><?php echo $key + 1; ?></td>
                                            <td class="text-center"><?php echo ($row['title']); ?></td>
                                            <td class="text-center"><?php echo ($row['first_name']) . " " . ($row['last_name']); ?></td>
                                            <td class="text-center"><?php echo convertToHoursMins($row['slot_time']); ?></td>
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
                                    endforeach;
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <section class="mb-2">
                <div class="row">
                    <?php if (isset($unverified_vendor) && count($unverified_vendor) > 0): ?>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body  pt-2">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p style="font-size: 18px;" class="text-left"><?php echo translate('unverified') . " " . translate('vendor'); ?></p>
                                        </div>
                                        <div class="col-md-6">
                                            <a class="btn btn-primary m-0 pull-right" href="<?php echo base_url('admin/unverified-vendor'); ?>"><?php echo translate('approve'); ?></a>
                                        </div>
                                    </div>
                                    <hr class="mt-0">
                                    <div class="table-responsive">
                                        <table class="table mdl-data-table">
                                            <thead>
                                                <tr>
                                                    <th class="text-center font-bold dark-grey-text">#</th>
                                                    <th class="text-center font-bold dark-grey-text"><?php echo translate('name'); ?></th>
                                                    <th class="text-center font-bold dark-grey-text"><?php echo translate('company_name'); ?></th>
                                                    <th class="text-center font-bold dark-grey-text"><?php echo translate('verification'); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $i = 1;
                                                if (isset($unverified_vendor) && count($unverified_vendor) > 0) {
                                                    foreach ($unverified_vendor as $row) {

                                                        $profile_status = "";

                                                        if ($row['profile_status'] == 'V') {
                                                            $profile_status = translate('approved');
                                                        } elseif ($row['profile_status'] == 'N') {
                                                            $profile_status = translate('unverified');
                                                        } elseif ($row['profile_status'] == 'R') {
                                                            $profile_status = translate('rejected');
                                                        }
                                                        ?>
                                                        <tr>
                                                            <td class="text-center"><?php echo $i; ?></td>
                                                            <td class="text-center"><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
                                                            <td class="text-center"><?php echo $row['company_name']; ?></td>
                                                            <td class="text-center"><?php echo $profile_status; ?></td>
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
                    <?php endif; ?>

                    <?php if (isset($pending_appointment) && count($pending_appointment) > 0): ?>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body pt-2">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p style="font-size: 18px;" class="text-left"><?php echo translate('pending') . " " . translate('appointment'); ?></p>
                                        </div>
                                        <div class="col-md-6">
                                            <a class="btn btn-primary m-0 pull-right" href="<?php echo base_url('admin/manage-appointment?status=P'); ?>"><?php echo translate('approve'); ?></a>
                                        </div>
                                    </div>
                                    <hr class="mt-0">
                                    <div class="table-responsive">
                                        <table class="table mdl-data-table">
                                            <thead>
                                                <tr>
                                                    <th class="text-center font-bold dark-grey-text">#</th>
                                                    <th class="text-center font-bold dark-grey-text"><?php echo translate('service'); ?></th>
                                                    <th class="text-center font-bold dark-grey-text"><?php echo translate('customer_name'); ?></th>
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
                                                            <td class="text-center"><?php echo ($row['first_name']) . " " . ($row['last_name']); ?></td>
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

                    <?php if (isset($pending_event) && count($pending_event) > 0): ?>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body pt-2">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p style="font-size: 18px;" class="text-left"><?php echo translate('pending') . " " . translate('appointment'); ?></p>
                                        </div>
                                        <div class="col-md-6">
                                            <a class="btn btn-primary m-0 pull-right" href="<?php echo base_url('admin/event-booking?status=P'); ?>"><?php echo translate('approve'); ?></a>
                                        </div>
                                    </div>
                                    <hr class="mt-0">
                                    <div class="table-responsive">
                                        <table class="table mdl-data-table">
                                            <thead>
                                                <tr>
                                                    <th class="text-center font-bold dark-grey-text">#</th>
                                                    <th class="text-center font-bold dark-grey-text"><?php echo translate('event'); ?></th>
                                                    <th class="text-center font-bold dark-grey-text"><?php echo translate('customer_name'); ?></th>
                                                    <th class="text-center font-bold dark-grey-text"><?php echo translate('ticket'); ?></th>
                                                    <th class="text-center font-bold dark-grey-text"><?php echo translate('event') . " " . translate('date'); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $cnt = 1;
                                                if (isset($pending_event) && count($pending_event) > 0) {
                                                    foreach ($pending_event as $row) {
                                                        ?>
                                                        <tr>
                                                            <td class="text-center"><?php echo $cnt; ?></td>
                                                            <td class="text-center"><?php echo ($row['title']); ?></td>
                                                            <td class="text-center"><?php echo ($row['first_name']) . " " . ($row['last_name']); ?></td>
                                                            <td class="text-center"><?php echo ($row['event_booked_seat']); ?></td>
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
                <?php if (get_site_setting('enable_membership') == 'Y' && count($membership_vendor) > 0): ?>
                    <div class="card">
                        <div class="card-body">
                            <p style="font-size: 18px;" class="text-left"><?php echo translate('vendor') . " " . translate('membership') . " " . translate('validity'); ?></p>
                            <hr>
                            <div class="table-responsive">
                                <table class="table mdl-data-table">
                                    <thead>
                                        <tr>
                                            <th class="text-center font-bold dark-grey-text">#</th>
                                            <th class="text-center font-bold dark-grey-text"><?php echo translate('name'); ?></th>
                                            <th class="text-center font-bold dark-grey-text"><?php echo translate('company_name'); ?></th>
                                            <th class="text-center font-bold dark-grey-text"><?php echo translate('expire_date'); ?></th>
                                            <th class="text-center font-bold dark-grey-text"><?php echo translate('status'); ?></th>
                                            <th class="text-center font-bold dark-grey-text"><?php echo translate('action'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($membership_vendor as $key => $row):
                                            ?>
                                            <tr>
                                                <td class="text-center"><?php echo $key + 1; ?></td>
                                                <td class="text-center"><?php echo ($row['first_name']) . " " . ($row['last_name']); ?></td>
                                                <td class="text-center"><?php echo $row['company_name']; ?></td>
                                                <td class="text-center"><?php echo get_formated_date($row['membership_till'], 'N'); ?></td>
                                                <td class="text-center"><?php echo print_vendor_status($row['status']); ?></td>
                                                <td class="text-center"><a data-toggle="modal" onclick='ReminderAction(this)' data-target="#remainder-membership" data-id="<?php echo (int) $row['id']; ?>" class="btn btn-warning font_size_12" title="<?php echo translate('send_mail'); ?>"><?php echo translate('reminder'); ?></a></td>
                                            </tr>
                                            <?php
                                        endforeach;
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </section>
        </div>
        <!-- End Container -->
    </div>
    <!-- End Content -->
</div>
<div class="modal fade" id="remainder-membership">
    <div class="modal-dialog">
        <div class="modal-content">
            <?php
            $attributes = array('id' => 'membership_form', 'name' => 'membership_form', 'method' => "post");
            echo form_open('admin/send-membership-reminder', $attributes);
            ?>
            <input type="hidden" id="vendor_id_hd" name="vendor_id_hd"/>
            <div class="modal-header">
                <h4 class="modal-title" style="font-size: 18px;"><?php echo translate('reminder'); ?></h4>
                <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <p id="confirm_msg" style="font-size: 15px;"><?php echo translate('send_membership_reminder'); ?></p>
            </div>
            <div class="modal-footer">
                <button  class="btn btn-primary font_size_12" type="submit" ><?php echo translate('yes'); ?></button>
                <button data-dismiss="modal" class="btn btn-danger font_size_12" type="button"><?php echo translate('no'); ?></button>
            </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- End dashboard -->
<?php include VIEWPATH . 'admin/footer.php'; ?>
<script>
    function ReminderAction(e) {
        $('#vendor_id_hd').val($(e).data('id'));
    }
</script>

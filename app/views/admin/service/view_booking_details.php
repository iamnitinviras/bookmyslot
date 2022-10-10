<?php
$appointment_booking_date = $event_data[0]['start_date'] . " " . $event_data[0]['start_time'];
$get_all_booked_adons = array();
if (isset($event_data[0]['addons_id']) && $event_data[0]['addons_id'] != ""):
    $get_all_booked_adons = get_service_addons_by_id($event_data[0]['addons_id']);
endif;


if ($this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) == 'V') {
    include VIEWPATH . 'vendor/header.php';
    $folder_name = 'vendor';
} else {
    include VIEWPATH . 'admin/header.php';
    $folder_name = 'admin';
}
?>

<div class="dashboard-body">
    <!-- Start Content -->
    <div class="content">
        <!-- Start Container -->
        <div class="container-fluid">
            <section class="form-light px-2 sm-margin-b-20 ">
                <!-- Row -->
                <div class="row">
                    <div class="col-md-12 m-auto">
                        <?php $this->load->view('message'); ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="header bg-color-base p-2">
                                    <h3 class="black-text font-bold"><?php echo translate('view'); ?> <?php echo translate('appointment') . " " . translate('details'); ?></h3>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="header bg-color-base p-2 pull-right">
                                    <a href="javascript:void(0)" onclick="goBack()" class="btn btn-primary btn-sm"><i class="fa fa-backward"></i></a>
                                </div>
                            </div>
                        </div>


                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><?php echo translate('appointment') . " " . translate('booking'); ?> <?php echo translate('details'); ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false"><?php echo translate('service'); ?> <?php echo translate('details'); ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false"><?php echo translate('customer'); ?> <?php echo translate('details'); ?></a>
                            </li>
                        </ul>

                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <div class="">
                                    <div class="card">
                                        <div class="p-3">
                                            <h5 class="black-text mb-0 "></h5>
                                            <hr/>
                                        </div>
                                        <div class="card-body resp_mx-0 pt-0">
                                            <div class="row">
                                                <div class="col-md-3 m-auto">
                                                    <p><?php echo translate('appointment') . " " . translate('instructions'); ?> : </p>
                                                </div>
                                                <div class="col-md-9 m-auto">
                                                    <span><?php echo $event_data[0]['description']; ?></span>
                                                </div>
                                            </div>
                                            <hr class='hr_margin_5'/>
                                            <div class="row">
                                                <div class="col-md-3 m-auto">
                                                    <p><?php echo translate('date'); ?> : </p>
                                                </div>
                                                <div class="col-md-9 m-auto">
                                                    <span><?php echo get_formated_date($event_data[0]['start_date'] . " " . $event_data[0]['start_time']); ?></span>
                                                </div>
                                            </div>
                                            <hr class='hr_margin_5'/>
                                            <div class="row">
                                                <div class="col-md-3 m-auto">
                                                    <p><?php echo translate('total') . " " . translate('payment'); ?> : </p>
                                                </div>
                                                <div class="col-md-9 m-auto">
                                                    <span><?php echo isset($event_data[0]['payment_type']) && $event_data[0]['payment_type'] == 'F' ? translate("free") : price_format($event_data[0]['final_price']); ?></span>
                                                </div>
                                            </div>
                                            <hr class='hr_margin_5'/>
                                            <div class="row">
                                                <div class="col-md-3 m-auto">
                                                    <p><?php echo translate('payment') . " " . translate('status'); ?> : </p>
                                                </div>
                                                <div class="col-md-9 m-auto">
                                                    <span><?php echo check_appointment_pstatus($event_data[0]['payment_status']); ?></span>
                                                </div>
                                            </div>
                                            <hr class='hr_margin_5'/>
                                            <div class="row">
                                                <div class="col-md-3 m-auto">
                                                    <p><?php echo translate('booking') . " " . translate('status'); ?> : </p>
                                                </div>
                                                <div class="col-md-9 m-auto">
                                                    <span><?php echo check_appointment_status($event_data[0]['status']); ?></span>
                                                </div>
                                            </div>
                                            <hr class='hr_margin_5'/>
                                            <?php
                                            $staff_member = "-";
                                            if ($event_data[0]['staff_id'] != 0) {
                                                $staff_data = get_staff_by_id($event_data[0]['staff_id'])[0];
                                                $staff_member = $staff_data['first_name'] . " " . $staff_data['last_name'];
                                            }
                                            ?>
                                            <?php if (isset($event_data[0]['staff_id']) && $event_data[0]['staff_id'] > 0): ?>
                                                <div class="row">
                                                    <div class="col-md-3 m-auto">
                                                        <p><?php echo translate('staff'); ?> : </p>
                                                    </div>
                                                    <div class="col-md-9 m-auto">
                                                        <span><?php echo $staff_member; ?></span>
                                                    </div>
                                                </div>
                                                <hr class='hr_margin_5'/>
                                            <?php endif; ?>

                                            <?php if (isset($get_all_booked_adons) && count($get_all_booked_adons) > 0): ?>

                                                <div class="row">

                                                    <div class="col-md-3 m-auto">
                                                        <p><?php echo translate('service') . " " . translate('add_ons'); ?></p>
                                                    </div>
                                                    <div class="col-md-9 m-auto">
                                                        <p></p>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12 m-auto">
                                                        <div class="table-responsive">
                                                            <table class="table mdl-data-table">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="text-center font-bold dark-grey-text"><?php echo translate('title'); ?></th>
                                                                        <th class="text-center font-bold dark-grey-text"><?php echo translate('description'); ?></th>
                                                                        <th class="text-center font-bold dark-grey-text"><?php echo translate('price'); ?></th>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    foreach ($get_all_booked_adons as $key => $row) {
                                                                        ?>
                                                                        <tr>
                                                                            <td class="text-center"><?php echo $row['title']; ?></td>
                                                                            <td class="text-center"><?php echo ($row['details']); ?></td>
                                                                            <td class="text-center"><?php echo price_format($row['price']); ?></td>
                                                                        </tr>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class='hr_margin_5'/>
                                            <?php endif; ?>

                                            <div class="row">
                                                <?php if ($event_data[0]['type'] == 'S' && $event_data[0]['status'] != 'C' && $appointment_booking_date >= date("Y-m-d H:i:s")) { ?>
                                                    <div class="col-md-6">
                                                        <a href="<?php echo base_url($folder_name . '/change-booking-time/' . $event_data[0]['id'] . '/' . $event_data[0]['event_id']) ?>" class="btn btn-block btn-success p-1"><i class="fa fa-clock-o p-2"></i><?php echo translate('change') . " " . translate('appointment') . " " . translate('time'); ?></a>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>


                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <div class="card">
                                    <div class="p-3">
                                        <h5 class="black-text mb-0 "></h5>
                                        <hr/>
                                    </div>
                                    <div class="card-body resp_mx-0 pt-0">
                                        <div class="row">
                                            <div class="col-md-3 m-auto">
                                                <p><?php echo translate('category'); ?> : </p>
                                            </div>
                                            <div class="col-md-9 m-auto">
                                                <span><?php echo $event_data[0]['category_title']; ?></span>
                                            </div>
                                        </div>
                                        <hr class='hr_margin_5'/>
                                        <div class="row">
                                            <div class="col-md-3 m-auto">
                                                <p><?php echo translate('title'); ?> : </p>
                                            </div>
                                            <div class="col-md-9 m-auto">
                                                <span><?php echo $event_data[0]['Event_title']; ?></span>
                                            </div>
                                        </div>
                                        <hr class='hr_margin_5'/>
                                        <div class="row">
                                            <div class="col-md-3 m-auto">
                                                <p><?php echo translate('company') . " " . translate('name'); ?> : </p>
                                            </div>
                                            <div class="col-md-9 m-auto">
                                                <span><?php echo $event_data[0]['company_name']; ?></span>
                                            </div>
                                        </div>
                                        <hr class='hr_margin_5'/>
                                        <div class="row">
                                            <div class="col-md-3 m-auto">
                                                <p><?php echo translate('location'); ?> : </p>
                                            </div>
                                            <div class="col-md-9 m-auto">
                                                <span><?php echo $event_data[0]['loc_title']; ?></span>
                                            </div>
                                        </div>
                                        <hr class='hr_margin_5'/>
                                        <div class="row">
                                            <div class="col-md-3 m-auto">
                                                <p><?php echo translate('city'); ?> : </p>
                                            </div>
                                            <div class="col-md-9 m-auto">
                                                <span><?php echo $event_data[0]['city_title']; ?></span>
                                            </div>
                                        </div>
                                        <hr class='hr_margin_5'/>
                                    </div>
                                </div>

                            </div>
                            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab"><div class="card">
                                    <div class="p-3">
                                        <h5 class="black-text mb-0 "></h5>
                                        <hr/>
                                    </div>
                                    <div class="card-body resp_mx-0 pt-0">
                                        <div class="row">
                                            <div class="col-md-3 m-auto">
                                                <p><?php echo translate('name'); ?> : </p>
                                            </div>
                                            <div class="col-md-9 m-auto">
                                                <span><?php echo $event_data[0]['Customer_name']; ?></span>
                                            </div>
                                        </div>
                                        <hr class='hr_margin_5'/>
                                        <div class="row">
                                            <div class="col-md-3 m-auto">
                                                <p><?php echo translate('email'); ?> : </p>
                                            </div>
                                            <div class="col-md-9 m-auto">
                                                <span><?php echo $event_data[0]['Customer_email']; ?></span>
                                            </div>
                                        </div>
                                        <hr class='hr_margin_5'/>
                                        <div class="row">
                                            <div class="col-md-3 m-auto">
                                                <p><?php echo translate('phone'); ?> : </p>
                                            </div>
                                            <div class="col-md-9 m-auto">
                                                <?php if ($event_data[0]['Customer_phone'] != '') { ?>
                                                    <span><?php echo $event_data[0]['Customer_phone']; ?></span>
                                                <?php } else { ?>
                                                    <span>N/A</span>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <hr class='hr_margin_5'/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Col -->
            </section>
        </div>
        <!--Row-->
        <!-- End Login-->
    </div>
</div>
<script src="<?php echo $this->config->item('js_url'); ?>module/customer.js" type="text/javascript"></script>
<?php include VIEWPATH . 'admin/footer.php'; ?>
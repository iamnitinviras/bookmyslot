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
                                <h4 class="card-title"><?php echo $title; ?></h4>
                                <div class="page-title-box pb-0 d-sm-flex">
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>"><?php echo translate('dashboard'); ?></a></li>
                                            <li class="breadcrumb-item"><?php echo translate('customer'); ?></li>
                                            <li class="breadcrumb-item active"><?php echo translate('customer'); ?> <?php echo translate('booking'); ?></li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
        <!-- end row -->
    </div>
<script src="<?php echo $this->config->item('js_url'); ?>module/customer.js" type='text/javascript'></script>
<?php include VIEWPATH . 'admin/footer.php'; ?>
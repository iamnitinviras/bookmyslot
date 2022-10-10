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
                            <h3 class="black-text font-bold mb-0"><?php echo translate('event'); ?> <?php echo translate('booking'); ?> <?php echo translate('report'); ?></h3>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <div class="">
                                    <form class="form" role="form" method="GET" id="appointment_filter">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <select name="event" id="event" class="form-control" onchange="this.form.submit();" style="display: block !important">
                                                        <option value=""><?php echo translate('all') ?> <?php echo translate('event') ?></option> 
                                                        <?php foreach ($appointment_event as $val): ?>
                                                            <option <?php echo (isset($_REQUEST['event']) && $_REQUEST['event'] == $val['event_id']) ? "selected='selected'" : ""; ?> value="<?php echo $val['event_id'] ?>"><?php echo $val['title'] ?></option> 
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <?php if (($this->session->userdata('Type_' . ucfirst($this->uri->segment(1)))) && $this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) != 'V') { ?>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <select name="vendor" id="vendor" class="form-control" onchange="this.form.submit();" style="display: block !important">
                                                            <option value=""><?php echo translate('vendor') ?></option> 

                                                            <?php foreach ($appointment_vendor as $val): ?>
                                                                <option <?php echo (isset($_REQUEST['vendor']) && $_REQUEST['vendor'] == $val['id']) ? "selected='selected'" : ""; ?> value="<?php echo $val['id'] ?>"><?php echo ($val['company_name']); ?></option> 
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <select name="customer" id="customer" class="form-control" onchange="this.form.submit();" style="display: block !important">
                                                        <option value=""><?php echo translate('all') . " " . translate('customer'); ?></option> 
                                                        <?php foreach ($customer_list as $val): ?>
                                                            <option <?php echo (isset($_REQUEST['customer']) && $_REQUEST['customer'] == $val['id']) ? "selected='selected'" : ""; ?> value="<?php echo $val['id'] ?>"><?php echo $val['first_name'] . " " . $val['last_name']; ?></option> 
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <select name="status" id="status" class="form-control" onchange="this.form.submit();" style="display: block !important">
                                                        <option value=""><?php echo translate('status') ?></option> 
                                                        <option value="A" <?php echo (isset($_REQUEST['status']) && $_REQUEST['status'] == 'A') ? "selected='selected'" : ""; ?>><?php echo translate('approved') ?></option> 
                                                        <option value="P" <?php echo (isset($_REQUEST['status']) && $_REQUEST['status'] == 'P') ? "selected='selected'" : ""; ?>><?php echo translate('pending') ?></option> 
                                                        <option value="R" <?php echo (isset($_REQUEST['status']) && $_REQUEST['status'] == 'R') ? "selected='selected'" : ""; ?>><?php echo translate('Rejected') ?></option> 
                                                        <option value="C" <?php echo (isset($_REQUEST['status']) && $_REQUEST['status'] == 'C') ? "selected='selected'" : ""; ?>><?php echo translate('completed') ?></option> 
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <select name="city" id="city" class="form-control" onchange="this.form.submit();" style="display: block !important">
                                                        <option value=""><?php echo translate('all') . " " . translate('city') ?></option> 
                                                        <?php foreach ($city_list as $val): ?>
                                                            <option <?php echo (isset($_REQUEST['city']) && $_REQUEST['city'] == $val['city_id']) ? "selected='selected'" : ""; ?> value="<?php echo $val['city_id'] ?>"><?php echo ($val['city_title']); ?></option> 
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <select name="type" id="type" class="form-control" onchange="this.form.submit();" style="display: block !important">
                                                        <option value=""><?php echo translate('type') ?></option> 
                                                        <option value="F" <?php echo (isset($_REQUEST['type']) && $_REQUEST['type'] == 'F') ? "selected='selected'" : ""; ?>><?php echo translate('free') ?></option> 
                                                        <option value="P" <?php echo (isset($_REQUEST['type']) && $_REQUEST['type'] == 'P') ? "selected='selected'" : ""; ?>><?php echo translate('paid') ?></option> 
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <select name="month" id="month" class="form-control" onchange="this.form.submit();"  style="display: block !important">
                                                    <option value=""><?php echo translate('all') . " " . translate('month'); ?></option>
                                                    <option value='1' <?php echo isset($month) && $month == '1' ? 'selected' : ''; ?>><?php echo translate('January'); ?></option>
                                                    <option value='2' <?php echo isset($month) && $month == '2' ? 'selected' : ''; ?>><?php echo translate('february'); ?></option>
                                                    <option value='3' <?php echo isset($month) && $month == '3' ? 'selected' : ''; ?>><?php echo translate('march'); ?></option>
                                                    <option value='4' <?php echo isset($month) && $month == '4' ? 'selected' : ''; ?>><?php echo translate('march'); ?></option>
                                                    <option value='5' <?php echo isset($month) && $month == '5' ? 'selected' : ''; ?>><?php echo translate('may'); ?></option>
                                                    <option value='6' <?php echo isset($month) && $month == '6' ? 'selected' : ''; ?>><?php echo translate('june'); ?></option>
                                                    <option value='7' <?php echo isset($month) && $month == '7' ? 'selected' : ''; ?>><?php echo translate('july'); ?></option>
                                                    <option value='8' <?php echo isset($month) && $month == '8' ? 'selected' : ''; ?>><?php echo translate('august'); ?></option>
                                                    <option value='9' <?php echo isset($month) && $month == '9' ? 'selected' : ''; ?>><?php echo translate('september'); ?></option>
                                                    <option value='10' <?php echo isset($month) && $month == '10' ? 'selected' : ''; ?>><?php echo translate('october'); ?></option>
                                                    <option value='11' <?php echo isset($month) && $month == '11' ? 'selected' : ''; ?>><?php echo translate('november'); ?></option>
                                                    <option value='12' <?php echo isset($month) && $month == '12' ? 'selected' : ''; ?>><?php echo translate('december'); ?></option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <select name="year" id="year" class="form-control" onchange="this.form.submit();" style="display: block !important">
                                                        <option value=""><?php echo translate('select_year') ?></option> 
                                                        <?php foreach ($year_list as $val): ?>
                                                            <option <?php echo (isset($year) && $year == $val['year']) ? "selected='selected'" : ""; ?> value="<?php echo $val['year'] ?>"><?php echo ($val['year']); ?></option> 
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <a class="btn btn-info btn-sm" href="<?php echo base_url($folder_name . '/event-booking-report') ?>"><i class="fa fa-refresh"></i></a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <hr/>
                                <div class="table-responsive">
                                    <table class="table mdl-data-table">
                                        <thead>
                                            <tr>
                                                <th class="text-center font-bold dark-grey-text">#</th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('event'); ?></th>
                                                <?php if (isset($folder_name) && $folder_name == 'admin'): ?>
                                                    <th class="text-center font-bold dark-grey-text"><?php echo translate('vendor'); ?></th>
                                                <?php endif; ?>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('customer_name'); ?></th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('ticket'); ?></th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('event') . " " . translate('date'); ?></th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('vendor') . " " . translate('amount'); ?></th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('fee'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $total_vendor_amount = 0;
                                            $total_admin_price = 0;

                                            if (isset($appointment_data) && count($appointment_data) > 0) {
                                                foreach ($appointment_data as $key => $row) {
                                                    $total_vendor_amount = $total_vendor_amount + $row['vendor_price'];
                                                    $total_admin_price = $total_admin_price + $row['admin_price'];
                                                    ?>
                                                    <tr>
                                                        <td class="text-center"><?php echo $key + 1; ?></td>
                                                        <td class="text-center"><?php echo ($row['title']); ?></td>
                                                        <?php if (isset($folder_name) && $folder_name == 'admin'): ?>
                                                            <td class="text-center"><?php echo ($row['company_name']); ?></td>
                                                        <?php endif; ?>
                                                        <td class="text-center"><?php echo ($row['first_name']) . " " . ($row['last_name']); ?></td>
                                                        <td class="text-center"><?php echo $row['event_booked_seat']; ?></td>
                                                        <td class="text-center"><?php echo get_formated_date($row['start_date'] . " " . $row['start_time']); ?></td>
                                                        <?php if ($row['price'] == 0): ?>
                                                            <td class="text-center">
                                                                <span class="badge badge-success"><?php echo translate('free'); ?></span>
                                                            </td>
                                                            <td class="text-center">
                                                                <span class="badge badge-success"><?php echo translate('free'); ?></span>
                                                            </td>
                                                        <?php else: ?>
                                                            <td class="text-center">
                                                                <span class="badge badge-success"><?php echo price_format($row['vendor_price']); ?></span>
                                                            </td>
                                                            <td class="text-center">
                                                                <span class="badge badge-success"><?php echo price_format($row['admin_price']); ?></span>
                                                            </td>
                                                        <?php endif; ?>

                                                    </tr>
                                                <?php }
                                                ?>
                                                <tr>
                                                <?php if (isset($folder_name) && $folder_name == 'admin'): ?>
                                                    <td colspan="5"></td>
                                                <?php else: ?>
                                                    <td colspan="4"></td>
                                                <?php endif;?>
                                                    
                                                    <td class="text-center" ><b><?php echo translate('total'); ?></b></td>
                                                    <td class="text-center"><?php echo price_format($total_vendor_amount); ?></td>
                                                    <td class="text-center"><?php echo price_format($total_admin_price); ?></td>
                                                </tr>
                                            <?php }else {
                                                ?>
                                                <tr class="text-center">
                                                    <td colspan="9"><?php echo translate('no_record_found') ?></td>
                                                </tr>
                                            <?php } ?>
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
<?php
if ($this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) == 'V') {
    include VIEWPATH . 'vendor/footer.php';
} else {
    include VIEWPATH . 'admin/footer.php';
}
?>
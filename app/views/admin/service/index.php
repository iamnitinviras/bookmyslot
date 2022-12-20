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
                                    <a href='<?php echo base_url($folder_name.'/service/add'); ?>'class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2"><i class="mdi mdi-plus me-1"></i> <?php echo translate('add'); ?> <?php echo translate('service'); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="form-group">
                                <form class="form" role="form" method="GET" id="appointment_filter" action="<?php echo base_url($folder_name . '/service') ?>">
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
                                                <a class="btn btn-info btn-sm" href="<?php echo base_url($folder_name . '/service') ?>"><i class="fa fa-refresh"></i></a>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </form>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-12 m-auto">
                                <?php $this->load->view('message'); ?>

                                <div class="table-responsive">
                                    <table class="table table-bordered dt-responsive nowrap w-100" id="datatable">
                                        <thead>
                                        <tr>
                                            <th class="text-center font-bold dark-grey-text">#</th>
                                            <th class="text-center font-bold dark-grey-text"><?php echo translate('title'); ?></th>
                                            <th class="text-center font-bold dark-grey-text"><?php echo translate('price'); ?></th>
                                            <th class="text-center font-bold dark-grey-text"><?php echo translate('total_booking'); ?></th>
                                            <th class="text-center font-bold dark-grey-text"><?php echo translate('add_ons'); ?></th>
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
                                                    } else if ($row['status'] == "SS") {
                                                        $status_string = '<span class="badge badge-info">' . translate('service_suspended') . '</span>';
                                                    } else {
                                                        $status_string = '<span class="badge label-danger">' . translate('inactive') . '</span>';
                                                    }
                                                    if ($this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) == 'V') {
                                                        $update_url = 'vendor/service/edit/' . $row['id'];
                                                        $manage_url = 'vendor/manage-appointment/' . $row['id'];
                                                    } else {
                                                        $update_url = 'admin/service/edit/' . $row['id'];
                                                        $manage_url = 'admin/manage-appointment/' . $row['id'];
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td class="text-center"><?php echo $key + 1; ?></td>
                                                        <td class="text-left hover-me">
                                                            <?php echo $row['title']; ?><br/>
                                                            <span class="badge badge-success"><?php echo $row['company_name']; ?></span>
                                                            <div class="hover-short-desc">
                                                                <div class="row">
                                                                    <div class="col-md-6 resp_my-0">
                                                                        <ul class="ticket_info list-inline">
                                                                            <li class="stat">
                                                                                <span class="stat-label"><?php echo translate('category'); ?> : </span>
                                                                                <span class="stat-value"><?php echo $row['category_title']; ?></span>
                                                                            </li>
                                                                            <li class="stat">
                                                                                <span class="stat-label"><?php echo translate('vendor'); ?> : </span>
                                                                                <span class="stat-value"><?php echo $row['company_name']; ?></span>
                                                                            </li>
                                                                            <li class="stat">
                                                                                <span class="stat-label"><?php echo translate('location') . "/" . translate('city'); ?> : </span>
                                                                                <span class="stat-value"><?php echo $row['loc_title'] . "/" . $row['city_title']; ?></span>
                                                                            </li>
                                                                            <li class="stat">
                                                                                <span class="stat-label"><?php echo translate('status'); ?> : </span>
                                                                                <span class="stat-value"><?php echo $status_string; ?></span>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                    <div class="col-md-6 resp_my-0">
                                                                        <ul class="ticket_info right_side list-inline">

                                                                            <li class="stat">
                                                                                <span class="stat-label"><?php echo translate('price'); ?> : </span>
                                                                                <span class="stat-value"><?php echo isset($row['payment_type']) && $row['payment_type'] == 'F' ? translate("free") : price_format($row['price']); ?></span>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>

                                                        <td class="text-center">
                                                            <?php echo isset($row['payment_type']) && $row['payment_type'] == 'F' ? translate("free") : price_format($row['price']); ?>
                                                        </td>
                                                        <td class="text-center"><a class="btn btn-primary font-bold font-size-16 m-0" style="padding: 0.2rem 1.6rem;" href="<?php echo base_url($manage_url); ?>"><?php echo get_slote_count($row['id']); ?></a></td>
                                                        <td class="text-center">
                                                            <?php if ($row['payment_type'] == 'P'): ?>
                                                                <?php if ($this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) == 'V') : ?>
                                                                    <a href="<?php echo base_url('vendor/service/addons/' . $row['id']); ?>" class="btn-warning btn-sm" title="<?php echo translate('manage') . " " . translate('add_ons'); ?>" data-toggle="tooltip" data-placement="top"><?php echo translate('manage') . " " . translate('add_ons'); ?></a>
                                                                <?php else: ?>
                                                                    <a href="<?php echo base_url('admin/service/addons/' . $row['id']); ?>" class="btn-warning btn-sm" title="<?php echo translate('manage') . " " . translate('add_ons'); ?>" data-toggle="tooltip" data-placement="top"><?php echo translate('manage') . " " . translate('add_ons'); ?></a>
                                                                <?php endif; ?>
                                                            <?php else: ?>
                                                                --
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="text-center"><?php echo $status_string; ?></td>
                                                        <td class="td-actions text-center">
                                                            <a href="<?php echo base_url($update_url); ?>" class="text-success" title="<?php echo translate('edit'); ?>" data-bs-toggle="tooltip" data-bs-placement="top"><i class="mdi mdi-pencil font-size-18"></i></a>
                                                            <a href="javascript:void(0)" data-bs-toggle="modal" onclick='DeleteRecord(this)' data-bs-target="#delete-record" data-id="<?php echo (int) $row['id']; ?>" class="text-danger" title="<?php echo translate('delete'); ?>">
                                                                <i class="mdi mdi-delete font-size-18"></i>
                                                            </a>
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
<?php include VIEWPATH . 'admin/header.php'; ?>
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
                        <div class="header bg-color-base">
                            <div class="d-flex">
                                <span style="width: 70%;" class="text-left">
                                    <h3 class="black-text font-bold pt-3"><?php echo translate('manage'); ?> <?php echo translate('package'); ?></h3>
                                </span> 
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table mdl-data-table">
                                        <thead>
                                            <tr>
                                                <th class="text-center font-bold dark-grey-text">#</th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('title'); ?></th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('price'); ?></th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('validity'); ?></th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('status'); ?></th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('action'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 1;
                                            if (isset($package_data) && count($package_data) > 0) {
                                                foreach ($package_data as $row) {

                                                    $status_string = "";
                                                    if ($row['status'] == "A") {
                                                        $status_string = '<span class="badge badge-success">' . translate('active') . '</span>';
                                                    } else {
                                                        $status_string = '<span class="badge badge-danger">' . translate('inactive') . '</span>';
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td class="text-center"><?php echo $i; ?></td>
                                                        <td class="text-center"><?php echo $row['title']; ?></td>
                                                        <td class="text-center"><?php echo price_format(number_format($row['price'], 0)); ?></td>
                                                        <td class="text-center"><?php echo $row['package_month']; ?></td>
                                                        <td class="text-center"><?php echo $status_string; ?></td>
                                                        <td class="td-actions text-center">
                                                            <a href="<?php echo base_url('admin/update-package/' . $row['id']); ?>" class="btn-danger btn-floating btn-sm blue-gradient" title="<?php echo translate('edit'); ?>"><i class="fa fa-pencil"></i></a>
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
                    <button data-dismiss="modal" class="btn blue-gradient btn-rounded pull-left" type="button"><?php echo $this->lang->line('Close'); ?></button>
                    <a class="btn purple-gradient btn-rounded" href="javascript:void(0)" id="RecordDelete" ><?php echo $this->lang->line('Confirm'); ?></a>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<script src="<?php echo $this->config->item('js_url'); ?>module/package.js" type='text/javascript'></script>
<?php include VIEWPATH . 'admin/footer.php'; ?>
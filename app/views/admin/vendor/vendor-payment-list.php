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
                            <h3 class="black-text font-bold mb-0"><?php echo translate('vendor_Payment'); ?></h3>
                        </div>
                        
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table mdl-data-table" id="example">
                                        <thead>
                                            <tr>
                                                <th class="text-center font-bold dark-grey-text">#</th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('name'); ?></th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('title'); ?></th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('category'); ?></th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('price'); ?></th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('payment_type'); ?></th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('transfer_status'); ?></th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('event_creater'); ?></th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('created_date'); ?></th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('action'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (isset($vendor_payment) && count($vendor_payment) > 0) {
                                                foreach ($vendor_payment as $payment_key => $payment_row) {
                                                    if ($payment_row['transfer_status'] == "S") {
                                                        $status_string = '<span class="badge badge-success">' . translate('success') . '</span>';
                                                    } else {
                                                        $status_string = '<span class="badge badge-danger">' . translate('pending') . '</span>';
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td class="text-center"><?php echo $payment_key + 1; ?></td>
                                                        <td class="text-center"><?php echo ($payment_row['first_name']) . " " . ($payment_row['last_name']); ?></td>
                                                        <td class="text-center"><?php echo $payment_row['title']; ?></td>
                                                        <td class="text-center"><?php echo $payment_row['category_title']; ?></td>
                                                        <td class="text-center"><?php echo price_format(number_format($payment_row['price'], 0)); ?></td>
                                                        <td class="text-center"><?php echo $payment_row['payment_method']; ?></td>
                                                        <td class="text-center"><?php echo $status_string; ?></td>
                                                        <td class="text-center"><?php echo ($payment_row['cre_first_name']) . " " . ($payment_row['cre_last_name']); ?></td>
                                                        <td class="text-center"><?php echo get_formated_date($payment_row['created_on'], "N"); ?></td>
                                                        <td class="text-center">
                                                            <?php if ($payment_row['transfer_status'] == 'P') { ?>
                                                                <a id="" data-toggle="modal" onclick='DeleteRecord(this)' data-target="#delete-record" data-id="<?php echo (int) $payment_row['id']; ?>" class="btn btn-sm blue-gradient font-bold waves-effect waves-light"><?php echo translate('send'); ?></a>
                                                                <?php
                                                            } else {
                                                                echo '-';
                                                            }
                                                            ?>
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
                    <button data-dismiss="modal" class="btn blue-gradient btn-rounded pull-left" type="button"><?php echo translate('close'); ?></button>
                    <a class="btn purple-gradient btn-rounded" href="javascript:void(0)" id="RecordDelete" ><?php echo translate('confirm'); ?></a>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<script>
    function DeleteRecord(element) {
        var id = $(element).attr('data-id');
        var title = 'Send Vendor Payment';
        $("#some_name").html(title);
        $("#confirm_msg").html("Are you sure you want to send payment?");
        $("#record_id").val(id);
    }
    $('#RecordDelete').on('click', function () {
        var id = $("#record_id").val();
        $.ajax({
            url: site_url + "admin/send-vendor-payment/" + id,
            type: "post",
            data: {token_id: csrf_token_name},
            beforeSend: function () {
                $("body").preloader({
                    percent: 10,
                    duration: 15000
                });
            },
            success: function (data) {
                window.location.reload();
            }
        });
    });
</script>
<?php
include VIEWPATH . 'admin/footer.php';
?>
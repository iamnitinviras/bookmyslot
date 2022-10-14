<?php
include VIEWPATH . 'admin/header.php';
?>
<div class="dashboard-body">

    <!-- Start Content -->
    <div class="content">
        <!-- Start Container -->
        <div class="container-fluid">
            <section class="form-light px-2 sm-margin-b-20">
                <!-- Row -->
                <div class="row">
                    <div class="col-md-12 m-auto">
                        <?php $this->load->view('message'); ?>

                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="black-text font-bold mb-0"><?php echo translate('appointment_payment_history') ?></h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table mdl-data-table" id="example">
                                        <thead>
                                            <tr>
                                                <th class="text-center font-bold dark-grey-text">#</th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('vendor_name'); ?></th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('payment_gateway'); ?></th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('service'); ?></th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('customer_name'); ?></th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('total') . " " . translate('amount'); ?></th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('action'); ?></th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 1;

                                            if (isset($payment_data) && count($payment_data) > 0) {
                                                foreach ($payment_data as $row) {
                                                    ?>
                                                    <tr>
                                                        <td class="text-center"><?php echo $i; ?></td>
                                                        <td class="text-center"><?php echo ($row['company_name']); ?></td>
                                                        <td class="text-center"><?php echo isset($row['payment_method']) ? $row['payment_method'] : "NA"; ?></td>
                                                        <td class="text-center"><?php echo isset($row['event_name']) && $row['event_name'] != NULL ? "" . $row['event_name'] : "NA"; ?></td>
                                                        <td class="text-center"><?php echo isset($row['customer_name']) && $row['customer_name'] != NULL ? "" . $row['customer_name'] : "NA"; ?></td>
                                                        <td class="text-center"><?php echo price_format($row['payment_price']); ?></td>
                                                        <td class="text-center">
                                                            <a href="<?php echo base_url('appointment-payment-details/' . (int) $row['id']); ?>"  data-direction="right" class="btn btn-info bookslide" title="<?php echo translate('view_details'); ?>"><?php echo translate('details'); ?></a>
                                                            <?php if ($row['payment_status'] == 'P') { ?>
                                                                <a id="" data-toggle="modal" onclick='UpdatePaymentStatus(this)' data-target="#update_details" data-id="<?php echo (int) $row['id']; ?>" class="btn btn-amber" title="Post"><?php echo translate('process'); ?></a>
                                                                <?php
                                                            }
                                                            ?>
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
<div class="modal fade" id="update_details">
    <div class="modal-dialog">
        <div class="modal-content">
            <?php
            $attributes = array('id' => 'UpdateRecordForm', 'name' => 'UpdateRecordForm', 'method' => "post");
            echo form_open('', $attributes);
            ?>
            <input type="hidden" id="record_id"/>
            <div class="modal-header">
                <h4 id='some_name' class="modal-title" style="font-size: 18px;"><?php echo translate('update_payment_status'); ?></h4>
                <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="payment_gateway"><?php echo translate('update_payment_status'); ?></label>
                    <select id="payment_gateway" name="payment_gateway"required="" class="form-control" style="display: block !important;">
                        <option value=""><?php echo translate('select') . " " . translate('status'); ?></option>
                        <option value="paid"><?php echo translate('payment_received') ?></option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <a class="btn btn-primary font_size_12" href="javascript:void(0)" id="UpdateStatusBtn" ><?php echo translate('save'); ?></a>
                <button data-dismiss="modal" class="btn btn-danger font_size_12" type="button"><?php echo translate('cancel'); ?></button>
            </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<?php
include VIEWPATH . 'admin/footer.php';
?>
<script>

    function UpdatePaymentStatus($value) {
        var id = $($value).attr('data-id');
        $("#record_id").val(id);
    }

    $("#UpdateStatusBtn").on("click", function (e) {
        var UpdateRecordForm = $("#UpdateRecordForm").valid();
        var formData = $("#UpdateRecordForm").serialize();
        if (UpdateRecordForm == true) {
            var record_id = $("#record_id").val();
            $.ajax({
                url: base_url + "admin/payment_status_update/" + record_id,
                type: "post",
                data: formData,
                beforeSend: function () {
                    $("body").preloader({
                        percent: 10,
                        duration: 15000
                    });
                },
                success: function (responseJSON) {
                    window.location.reload();
                }
            });
        }

    });
    $('.bookslide').slidePanel();
</script>
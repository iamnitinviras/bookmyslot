<?php
include VIEWPATH . 'admin/header.php';
$get_current_currency = get_current_currency();
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
                            <h3 class="black-text font-bold mb-0"><?php echo translate('payout_request') ?></h3>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table mdl-data-table" id="example">
                                        <thead>
                                            <tr>
                                                <th class="text-center font-bold dark-grey-text">#</th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('vendor_name'); ?></th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('payment_gateway'); ?></th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('amount'); ?></th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('cash_payment_fee'); ?></th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('status'); ?></th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('request_date'); ?></th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('action'); ?></th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 1;
                                            if (isset($payment_data) && count($payment_data) > 0) {
                                                foreach ($payment_data as $row) {
                                                    $status = "";
                                                    if ($row['status'] == 'P') {
                                                        $status = "<span class='badge badge-warning'>" . translate('pending') . "</span>";
                                                    } else if ($row['status'] == 'S') {
                                                        $status = "<span class='badge badge-success'>" . translate('payout_successful') . "</span>";
                                                    } else if ($row['status'] == 'H') {
                                                        $status = "<span class='badge badge-info'>" . translate('on_hold') . "</span>";
                                                    } else if ($row['status'] == 'F') {
                                                        $status = "<span class='badge badge-danger'>" . translate('failed') . "</span>";
                                                    }

                                                    $other_charge = "";

                                                    if (isset($row['other_charge']) && $row['other_charge'] != 0) {
                                                        $other_charge = " + " . price_format($row['other_charge']);
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td class="text-center"><?php echo $i; ?></td>
                                                        <td class="text-center"><?php echo $row['vendor_name']; ?></td>
                                                        <td class="text-center"><?php echo isset($row['choose_payment_gateway']) ? $row['choose_payment_gateway'] : "NA"; ?></td>
                                                        <td class="text-center"><?php echo price_format($row['amount']); ?></td>
                                                        <td class="text-center"><?php echo price_format($row['cash_payment']); ?></td>
                                                        <td class="text-center"><?php echo $status; ?></td>
                                                        <td class="text-center"><?php echo get_formated_date($row['created_date']); ?></td>

                                                        <td class="text-center">
                                                            <a href="<?php echo base_url('payout-request-details/' . (int) $row['id']); ?>"  data-direction="right" class="btn btn-info font_size_12 bookslide" title="<?php echo translate('view_details'); ?>"><?php echo translate('details'); ?></a>
                                                            <?php if ($row['status'] != 'S'): ?>
                                                                <a id="" data-toggle="modal" onclick='UpdateStatus(this)' data-amount="<?php echo $row['amount']; ?>" data-cpayment="<?php echo isset($row['choose_payment_gateway']) ? $row['choose_payment_gateway'] : ""; ?>" data-target="#update_details" data-id="<?php echo (int) $row['id']; ?>" class="btn btn-primary font_size_12" title="Post"><?php echo translate('process'); ?></a>
                                                            <?php endif; ?>
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
            <input type="hidden" id="main_request_price"/>
            <div class="modal-header">
                <h4 id='some_name' class="modal-title" style="font-size: 18px;"><?php echo translate('payout_request'); ?></h4>
                <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="payment_gateway"><?php echo translate('payment_gateway'); ?></label>
                    <select id="payment_gateway" name="payment_gateway"required="" class="form-control" style="display: block !important;">
                        <option value="PayPal">PayPal</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="payment_gateway_fee"><?php echo translate('payment_gateway_fee_in_percentage'); ?>:</label>
                    <input type="number" id="payment_gateway_fee" name="payment_gateway_fee" value="0" class="form-control" placeholder="<?php echo translate('payment_gateway_fee_in_percentage'); ?>" required=""/>
                </div>
                <div class="form-group">
                    <label for="updated_amount"><?php echo translate('updated_payment_amount'); ?>:</label>
                    <input type="number" readonly="" id="updated_amount" name="updated_amount" value="" class="form-control" placeholder="<?php echo translate('updated_payment_amount'); ?>" required=""/>
                </div>
                <div class="form-group">
                    <label for="updated_amount"><?php echo translate('other_charge') . " (" . translate('in') . " " . $get_current_currency['currency_code'] . ")" ?>:</label>
                    <input type="number" min="0" id="other_charge" name="other_charge" value="0" class="form-control" placeholder="<?php echo translate('other_charge') . " (" . translate('in') . " " . $get_current_currency['currency_code'] . ")"; ?>"/>
                </div>
                <div class="form-group">
                    <label for="reference_no"><?php echo translate('reference_no'); ?>:</label>
                    <input id="reference_no" name="reference_no" value="" class="form-control" placeholder="<?php echo translate('reference_no'); ?>" required=""/>
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
    $('.bookslide').slidePanel();
    function payment_gateway_fee() {

        var payment_gateway_fee = $("#payment_gateway_fee").val();
        var other_charge = $("#other_charge").val();
        var amount = $("#main_request_price").val();

        var calcPrice = (amount - (amount * (payment_gateway_fee / 100)));
        $("#updated_amount").val(calcPrice - other_charge);

    }

    function UpdateStatus($this) {
        var record_id = ($($this).attr('data-id'));
        var gateway = ($($this).attr('data-cpayment'));
        var amount = ($($this).attr('data-amount'));
        $("#payment_gateway").val(gateway);
        $("#main_request_price").val(amount);
        $("#payment_gateway").attr("disabled", true);
        $("#record_id").val(record_id);
        $("#updated_amount").val(amount);
    }

    $("#other_charge").on("blur", function (e) {
        payment_gateway_fee();
    });
    $("#payment_gateway_fee").on("blur", function (e) {
        payment_gateway_fee();
    });
    $("#UpdateStatusBtn").on("click", function (e) {
        var UpdateRecordForm = $("#UpdateRecordForm").valid();
        var formData = $("#UpdateRecordForm").serialize();
        if (UpdateRecordForm == true) {
            var record_id = $("#record_id").val();
            $.ajax({
                url: base_url + "admin/payment_update/" + record_id,
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
</script>
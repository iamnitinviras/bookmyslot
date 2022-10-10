<?php
include VIEWPATH . 'vendor/header.php';
$cash_payment_vendor = get_cash_payment_vendor();
?>
<!-- start dashboard -->
<div class="dashboard-body">
    <!-- Start Content -->
    <div class="content pt-0">
        <!-- Start Container -->
        <div class="container-fluid">
            <!-- Start Section -->
            <div class="row">
                <div class="col-md-12">
                    <?php $this->load->view('message'); ?>
                </div>
            </div>

            <div class="alert alert-info mt-3" role="alert"><?php echo translate('payout_notice') . price_format($minimum_vendor_payout); ?></div>

            <?php if (isset($cash_payment_vendor) && $cash_payment_vendor > 0): ?>
                <div class="alert alert-warning mt-3" role="alert"><?php echo price_format($cash_payment_vendor); ?> will be deducted from your withdrawable balance as service fee for cash payment.</div>
            <?php endif; ?>

            <!-- Card Color Section -->
            <section class="mb-2">
                <div class="row">

                    <!--Grid column-->
                    <div class="col-xl-4 col-md-4">

                        <!--Card-->
                        <div class="card">
                            <!--Card Data-->
                            <div class="row mt-3">
                                <div class="col-md-4 col-4 text-left pl-3">
                                    <a href='javascript:void(0)' type="button" class="btn-floating mt-0 btn-lg blue-gradient ml-3 waves-effect waves-light"><i class="fa fa-dollar" aria-hidden="true"></i></a>
                                </div>
                                <div class="col-md-8 col-8 text-right pr-30">
                                    <h5 class="ml-4 mb-2 font-bold"><?php echo isset($total_withdrawable) ? price_format($total_withdrawable) : price_format(0); ?></h5>
                                    <p class="font-small grey-text"><?php echo translate('withdrawable_balance'); ?></p>
                                </div>
                            </div>
                            <!--/.Card Data-->
                        </div>
                        <!--/.Card-->
                    </div>
                    <!--Grid column-->
                    <!--Grid column-->
                    <div class="col-xl-4 col-md-4">
                        <!--Card-->
                        <div class="card">
                            <!--Card Data-->
                            <div class="row mt-3">
                                <div class="col-md-5 col-5 text-left pl-3">
                                    <a href='javascript:void(0)' type="button" class="btn-floating mt-0 btn-lg deep-orange ml-3 waves-effect waves-light"><i class="fa fa-money" aria-hidden="true"></i></a>
                                </div>
                                <div class="col-md-7 col-7 text-right pr-30">
                                    <h5 class="ml-4 mb-2 font-bold"><?php echo isset($total_earning) ? price_format($total_earning) : price_format(0); ?></h5>
                                    <p class="font-small grey-text"><?php echo translate('total') . " " . translate('earnings'); ?></p>
                                </div>
                            </div>
                            <!--/.Card Data-->
                        </div>
                        <!--/.Card-->
                    </div>
                    <!--Grid column-->

                    <?php if (isset($total_withdrawable) && $total_withdrawable >= $minimum_vendor_payout): ?>
                        <div class="col-xl-3 col-md-3">
                            <div class="card">
                                <!--Card Data-->
                                <div class="row mt-3">
                                    <div class="col-md-5 col-5 text-left pl-3">
                                        <a type="button" href='javascript:void(0)' data-toggle="modal" data-target="#paymentRequest" class="btn-floating mt-0 btn-lg warning-color ml-3 waves-effect waves-light"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                    </div>
                                    <div class="col-md-7 col-7 text-right pr-30">
                                        <h5 class="ml-4 mb-2 font-bold"><a data-toggle="modal" data-target="#paymentRequest" ><?php echo translate('payout_request'); ?></a></h5>
                                    </div>
                                </div>
                                <!--/.Card Data-->
                            </div>
                            <!--/.Card-->
                        </div>
                    <?php endif; ?>

                    <!--Grid column-->

                </div>
            </section>
            <!-- Card Color Section -->

            <div class="card">
                <div class=" card mytablerecord">
                    <div class="card-header"><?php echo translate('payout_request'); ?></div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="table mdl-data-table">
                                <thead>
                                    <tr>
                                        <th class="text-center font-bold dark-grey-text">#</th>
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
                                    $post_status = '';

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
                                                $other_charge = " + $" . $row['other_charge'];
                                            }
                                            ?>
                                            <tr>
                                                <td class="text-center"><?php echo $i; ?></td>
                                                <td class="text-center"><?php echo isset($row['choose_payment_gateway']) ? $row['choose_payment_gateway'] : "NA"; ?></td>
                                                <td class="text-center"><?php echo price_format($row['amount']); ?></td>
                                                <td class="text-center"><?php echo price_format($row['cash_payment']); ?></td>
                                                <td class="text-center"><?php echo $status; ?></td>
                                                <td class="text-center"><?php echo get_formated_date($row['created_date']); ?></td>
                                                <td class="text-center"><a href="<?php echo base_url('payout-request-details/' . (int) $row['id']); ?>"  data-direction="right" class="btn btn-info font_size_12 bookslide" title="<?php echo translate('view_details'); ?>"><?php echo translate('details'); ?></a></td>
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
        <!-- End Container -->
    </div> 
    <!-- End Content -->
</div> 
<div class="modal fade" id="paymentRequest">
    <div class="modal-dialog">
        <div class="modal-content">
            <?php
            $attributes = array('id' => 'paymentrequestForm', 'name' => 'paymentrequestForm', 'method' => "post");
            echo form_open_multipart('vendor/payment-request-save', $attributes);
            ?>
            <?php if ($cash_payment_vendor > 0): ?>
                <input type="hidden" name="cash_payment_vendor" value="<?php echo $cash_payment_vendor; ?>"/>
            <?php endif; ?>
            <div class="modal-header">
                <h4 id='some_name' class="modal-title" style="font-size: 18px;"><?php echo translate('payout_request'); ?></h4>
                <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="payment_gateway"><?php echo translate('payment_gateway'); ?></label>
                    <select id="payment_gateway" name="payment_gateway" required=""  onchange="check_my_type(this.value)"  class="form-control" style="display: block !important;">
                        <option value=""><?php echo translate('select') . " " . translate('payment_gateway'); ?></option>
                        <option value="PayPal">PayPal</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="payment_id"><?php echo translate('payout_reference'); ?></label>
                    <input id="payment_gateway_ref"  name="payment_gateway_ref" value="" class="form-control" placeholder="<?php echo translate('payout_reference'); ?>" required="">
                </div>
                <div class="form-group">
                    <label for="payout_amount"><?php echo translate('payout_amount'); ?></label>
                    <input id="payout_amount" readonly="" type="number" name="payout_amount" value="<?php echo isset($total_withdrawable) ? $total_withdrawable - $cash_payment_vendor : 0; ?>" class="form-control" placeholder="<?php echo translate('payout_amount'); ?>" required="">
                </div>
                <div class="form-group">
                    <div class="alert alert-info" role="alert"><?php echo translate('gateway_fee_note') ?></div>
                </div>
            </div>
            <div class="modal-footer">

                <a class="btn btn-primary font_size_12" href="javascript:void(0)" id="SaveRequestBtn" ><?php echo translate('submit'); ?></a>
                <button data-dismiss="modal" class="btn btn-danger font_size_12" type="button"><?php echo translate('cancel'); ?></button>
            </div>
            <?php echo form_close(); ?>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- End dashboard -->
<?php include VIEWPATH . 'vendor/footer.php'; ?>
<script>
    $('.bookslide').slidePanel();
    function check_my_type(val) {
        if (val == "PayPal") {
            $("#payment_gateway_ref").attr("type", "email");
        } else {
            $("#payment_gateway_ref").attr("type", "text");
        }
    }

    $("#SaveRequestBtn").on("click", function (e) {
        var UpdateRecordForm = $("#paymentrequestForm").valid();
        if (UpdateRecordForm == true) {
            $('#loadingmessage').show();
            $("#paymentrequestForm").submit();
        } else {
            return false;
        }
    });
</script>
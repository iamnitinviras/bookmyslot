<?php
include VIEWPATH . 'front/header.php';
$type = $this->uri->segment(1);
$event_id = $this->uri->segment(2);
$current_date_month = date("m", strtotime($current_date));
$customer_id_sess = (int) $this->session->userdata('CUST_ID');


$disc_date = date("Y-m-d");
$event_price = get_price($event_data['id'], $disc_date);
//Get current set category
$get_current_currency = get_current_currency();
?>
<style>
    select{
        display: block !important;
    }
    #tablemdldatatable tr th:first-child{
        width: 35%;
    }
</style>
<input type="hidden" name="currency" id="currency" value="<?php echo $get_current_currency['currency_code']; ?>"/>
<div class="container container-min-height mb-3">
    <div class="mt-20">
        <?php $this->load->view('message'); ?>        
    </div>
    <h3 class="text-center mt-20"><?php echo $title; ?></h3>

    <!--    <div class="pointer">        -->
    <div class="card mb-3">
        <div class="row mx-0">
            <div class="col-md-3 px-0 m-0">
                <div class="image">
                    <?php
                    $event_img_file = '';
                    $event_img_Arr = json_decode($event_data['image']);
                    if (isset($event_img_Arr) && !empty($event_img_Arr)) {
                        $event_img = isset($event_img_Arr[0]) ? $event_img_Arr[0] : '';
                        if ($event_img != '') {

                            $original_filename = (pathinfo($event_img, PATHINFO_FILENAME));
                            $original_extension = (pathinfo($event_img, PATHINFO_EXTENSION));
                            $event_img_file = $original_filename . "_thumb" . "." . $original_extension;
                        }
                    }
                    if ($event_img_file != '' && file_exists(FCPATH . "assets/uploads/event/" . $event_img_file)) {
                        $img_src = base_url() . UPLOAD_PATH . "event/" . $event_img_file;
                    } else {
                        $img_src = base_url() . UPLOAD_PATH . "event/events.png";
                    }
                    ?>
                    <img src="<?php echo $img_src; ?>" class="img-fluid" >
                </div>
            </div>
            <div class="col-md-9 event-book_list">
                <div class="card-body">
                <!--<h3 class="mb-0"><?php echo date_default_timezone_get() . "(" . date("h:i A") . ")"; ?></h3>-->
                    <h3 class="text-left text-uppercase"><?php echo $event_data['title']; ?></h3>
                    <div class="event_details-list">
                        <p>
                            <span class="badge badge-secondary"><?php echo translate('time'); ?></span>
                            <small><i class="fa fa-clock-o pr-1"></i> <?php echo date("m/d/Y H:i a", strtotime($event_data['start_date'])); ?></small>
                        </p>
                        <p>
                            <span class="badge badge-secondary"><?php echo translate('venue'); ?></span>
                            <small><i class="fa fa-map-marker pr-1"></i> <?php echo $event_data['address']; ?></small>
                        </p>
                        <p>
                            <span class="badge badge-secondary"><?php echo translate('created_by'); ?></span>
                            <small><i class="fa fa-user pr-1"></i> <?php echo $event_data['Creater_name']; ?></small>
                        </p>
                    </div>
                </div>
            </div>
        </div>        
    </div>
    <div class="row">
        <div class="col-md-8" >
            <div class="card" style="border-top: 4px solid orange">
                <div class="card-body">
                    <?php
                    $attributes = array('id' => 'BookEventForm', 'name' => 'BookEventForm', 'method' => "post");
                    echo form_open('', $attributes);
                    ?>
                    <table class="table table-responsive-lg">
                        <tr>
                            <th class="text-center px-4"><?php echo translate('type'); ?></th>
                            <th class="text-center px-4"><?php echo translate('price'); ?></th>
                            <th class="text-center px-4"><?php echo translate('quantity'); ?></th>
                        </tr>
                        <tr>
                            <td class="text-center">
                                <label><?php echo $event_data['title']; ?></label>
                            </td>
                            <?php
                            if (isset($event_data['payment_type']) && $event_data['payment_type'] == 'P') {
                                $price = $event_data['price'];
                            } else {
                                $price = translate('free');
                            }
                            ?>
                            <td class="text-center">
                                <label> <?php echo $price; ?></label>
                                <input type="hidden"  id="price" value="<?php echo $price; ?>"  name="price" readonly/>
                            </td>
                            <td class="text-center">
                                <input type="number" class="form-control integers" min="1"  id="quantity" required value="" name="quantity" onchange="getQuantity(this)"/>
                            </td>
                        </tr>
                    </table>
                    <div class="my-4">
                        <label><?php echo translate('total'); ?> </label>
                        <input type="text" class="form-control" id="total" name="total" readonly value=""/>
                    </div>
                    <?php if ($customer_id_sess == 0) { ?>
                        <div class="position-r"  onclick="login_protected_modal(this)" data-message="<?php echo translate('login_required_for_book'); ?>">
                            <button type="button" class="btn btn-dark button_common"><?php echo translate('book'); ?></button>
                        </div>                           
                    <?php } else { ?>
                        <button  type="button" onclick="eventData(this)" class="btn btn-dark button_common"><?php echo translate('book'); ?></button>
                    <?php } ?>

                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">

                    <div class="organizer_content">
                        <h6>Organizer</h6>
                        <p>
                            <span><?php echo translate('created_by'); ?>:</span>
                            <small><?php echo $event_data['Creater_name']; ?></small>
                        </p>
                        <p>
                            <span><?php echo translate('time'); ?>:</span>
                            <small><?php echo date("m/d/Y H:i a", strtotime($event_data['start_date'])); ?></small>
                        </p>
                        <p>
                            <span><?php echo translate('venue'); ?>:</span>
                            <small><?php echo $event_data['address']; ?></small>
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<div id="confirm_model" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" id="confirm_back">
                <h4 class="modal-title w-100 text-center m-0"><?php echo translate("booking"); ?></h4>
                <button data-dismiss="modal" class="close">x</button>
            </div>
            <div class="modal-body" id="confirm_model_body">
                <?php
                $attributes = array('id' => 'BookForm', 'name' => 'BookForm', 'method' => "post");
                echo form_open(base_url('booking-oncash'), $attributes);
                ?>
                <input type="hidden" name="amount" id="amount" value="<?php echo isset($event_data['price']) ? $event_data['price'] : '0'; ?>">
                <input type="hidden" name="main_amount" id="main_amount" value="">
                <input type="hidden" id="user_slot_time" name="user_slot_time" value="<?php echo $event_data['slot_time']; ?>"/>
                <input type="hidden" id="event_id" name="event_id" value="<?php echo $event_data['event_id']; ?>"/>
                <input type="hidden" id="total_booked_seat" name="total_booked_seat" value=""/>
                <input type="hidden" id="start_date" name="start_date" value="<?php echo $event_data['start_date']; ?>"/>
                <input type="hidden" id="event_payment_type" name="event_payment_type" value="<?php echo isset($event_data['payment_type']) ? $event_data['payment_type'] : ""; ?>"/>
                <input type="hidden" id="first_name" name="first_name" value="<?php echo isset($customer_data) ? $customer_data['first_name'] : ""; ?>"/>
                <input type="hidden" id="last_name" name="last_name" value="<?php echo isset($customer_data) ? $customer_data['last_name'] : ""; ?>"/>
                <input type="hidden" id="email" name="email" value="<?php echo isset($customer_data) ? $customer_data['email'] : ""; ?>"/>
                <table class="table mdl-data-table" id="tablemdldatatable">
                    <tr>
                        <th><?php echo translate('title'); ?></th>
                        <th><?php echo $event_data['title']; ?></th>
                    </tr>
                    <tr>
                        <th><?php echo translate('slot_time'); ?></th>
                        <th><?php echo $event_data['slot_time'] . " " . translate('minute'); ?></th>
                    </tr>
                    <?php if (isset($event_data['payment_type']) && $event_data['payment_type'] == 'P'): ?>
                        <tr>
                            <th><?php echo translate('price'); ?></th>
                            <th id="discount_coupon_price"><?php echo price_format($event_price); ?></th>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <th><?php echo translate('price'); ?></th>
                            <th><?php echo translate('free'); ?></th>
                        </tr>
                    <?php endif; ?>

                    <tr>
                        <th><?php echo translate('event') . " " . ('date'); ?></th>
                        <th><?php echo date("m/d/Y H:i a", strtotime($event_data['start_date'])); ?></th>
                    </tr>
                    <tr>
                        <th><?php echo translate('total') . " " . ("ticket"); ?></th>
                        <th id="total_book_ticket"></th>
                    </tr>
                </table>
                <div class="form-group">
                    <label for="description"><?php echo translate('booking_note'); ?><small class="required">*</small></label>
                    <textarea type="text" class="form-control" rows="5" placeholder="<?php echo translate('booking_note'); ?>" id="description" name="description" style="height: auto" required></textarea>
                </div>
                <hr/>
                <?php if (isset($event_payment_type) && $event_payment_type == 'P'): ?>
                    <div class="form-group">
                        <p class="black-text" style="font-size: 17px;"><?php echo translate('payment_by'); ?><small class="required">*</small></p>

                        <!-- Set Cash ON method -->
                        <?php if (check_payment_method('on_cash')): ?>
                            <button type="button" onclick="valid_on_cash();" class="btn btn-primary"><?php echo translate('on_cash'); ?></button>
                        <?php endif; ?>

                        <!-- Set Stripe method -->
                        <?php if (check_payment_method('stripe')): ?>
                            <button type="button" onclick="valid_stripe();" class="btn btn-warning"><?php echo translate('stripe'); ?></button>
                        <?php endif; ?>

                        <!-- Set PayPal ON method -->
                        <?php if (check_payment_method('paypal')): ?>
                            <button type="button" onclick="valid_paypal();" class="btn btn-info"><?php echo translate('paypal'); ?></button>
                        <?php endif; ?>

                        <?php echo form_error('payment_method'); ?>
                    </div>
                <?php else: ?>
                    <button type="button" onclick="valid_free();" class="btn btn-dark button_common"><?php echo translate('submit'); ?></button>
                <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
</div>
<?php if (check_payment_method('stripe')) { ?>
    <script src="https://checkout.stripe.com/checkout.js"></script>
    <script type="text/javascript">
                        var handler = StripeCheckout.configure({
                            key: '<?php echo get_Stripepublish(); ?>',
                            image: '',
                            token: function (token) {
                                $('#loadingmessage').show();
                                $('#BookForm').append("<input type='hidden' name='stripeToken' value='" + token.id + "' />");
                                $("#BookForm").attr("action", base_url + "booking-stripe");
                                $("#BookForm").submit();
                            }
                        });
                        $(window).on('popstate', function () {
                            handler.close();
                        });</script>
<?php } ?>
<?php include VIEWPATH . 'front/footer.php'; ?>
<script>
    var CURRENCY = $("#currency").val();
    function getQuantity(e) {
        var qty = $(e).val();
        if (qty > 0) {
            var price = $("#price").val();
            if (price == 'Free') {
                price = 0;
            }
            var total = price * qty;
            $("#total").val(CURRENCY + " " + total);
        } else {
            alert("Please enter proper quantity");
            return false;
        }
    }

    $(document).ready(function () {
        $("#BookEventForm").validate({
            ignore: [],
            rules: {
                quantity: {
                    required: true
                },
            },
        });

    });
    function valid_free() {
        if ($("#BookForm").valid()) {
            $('#loadingmessage').show();
            $("#BookForm").attr("action", base_url + "booking-free");
            $("#BookForm").submit();
        }
    }
    function eventData(e) {
        if ($("#BookEventForm").valid()) {
            var total_ticket = $("#quantity").val();
            var total_amount = $("#total").val();
            $("#total_book_ticket").html(total_ticket);
            $("#main_amount").val(total_amount);
            $("#total_booked_seat").val(total_ticket);
            $("#confirm_model").modal('show');
        } else {
            return false;
        }
    }

    function valid_on_cash() {
        if ($("#BookForm").valid()) {
            $('#loadingmessage').show();
            $("#BookForm").submit();
        }
    }
    function valid_stripe() {
        if ($("#BookForm").valid()) {
            get_stripe();
        }
    }

    function get_stripe() {
        var payment_price = $("#amount").val();
        var first_name = $("#first_name").val();
        var last_name = $("#last_name").val();
        var email = $("#email").val();

        var total = parseInt(payment_price) * 100;
        handler.open({
            name: first_name + " " + last_name,
            email: email,
            amount: total
        });
    }
    // Close Checkout on page navigation
    $(window).on('popstate', function () {
        handler.close();
    });

    function valid_paypal() {
        if ($("#BookForm").valid()) {
            $('#loadingmessage').show();
            $("#BookForm").attr("action", base_url + "booking-paypal");
            $("#BookForm").submit();
        }
    }

</script>

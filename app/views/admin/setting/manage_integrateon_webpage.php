<?php
include VIEWPATH . 'admin/header.php';
$commission_percentage = isset($business_data->commission_percentage) ? $business_data->commission_percentage : set_value('commission_percentage');
$minimum_vendor_payout = isset($business_data->minimum_vendor_payout) ? $business_data->minimum_vendor_payout : set_value('minimum_vendor_payout');
?>
<style>
    .select-wrapper input.select-dropdown {
        color: black;
    }
</style>
<input type="hidden" name="token" id="token" value="<?php echo isset($token) ? $token : ""; ?>"/>
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

                        <div class="header bg-color-base p-3">
                            <h3 class="black-text font-bold mb-0">
                                <?php echo translate('manage'); ?> <?php echo translate('integrateon_webpage'); ?> <?php echo translate('setting'); ?>
                            </h3>
                        </div>

                        <div class="card">
                            <div class="card-body resp_mx-0">
                                <?php echo form_open('', array('name' => 'integration_form', 'id' => 'integration_form')); ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <p class="alert alert-info"><?php echo translate('integration_info'); ?></p>
                                    </div>
                                    <div class="col-md-6 ">
                                        <div class="form-group">
                                            <?php
                                            $options = array();
                                            $options['E'] = translate('event') . " " . translate('list');
                                            $options['S'] = translate('service') . " " . translate('list');

                                            $attributes = array('class' => 'kb-select initialized', 'id' => 'webpage', 'required' => 'required', 'onchange' => 'get_webpage(this)');
                                            echo form_dropdown('webpage', $options, $this->input->post('webpage'), $attributes);
                                            ?>
                                        </div>
                                        <div class="error" id="commission_percentage"></div>
                                    </div>
                                </div>
                                <div class="form-group">

                                    <?php if ($this->input->post('webpage') == 'S') {
                                        ?>
                                        <a href="<?php echo base_url('eservices/' . $token) ?>" class="btn btn-primary" target="_blank">Preview</a>
                                        <?php
                                    } else {
                                        ?>
                                        <a href="<?php echo base_url('eevents/' . $token) ?>" class="btn btn-primary" target="_blank">Preview</a>
                                    <?php }
                                    ?>
                                    <button onclick="copy_code(this);" type="button" class="btn btn-info">Copy</button>
                                </div>

                                <div class="form-group">
                                    <textarea id="content" class="form-control" readonly="readonly"><?php if ($this->input->post('webpage') == 'S') { ?><iframe id='frameBooking' width='100%' height='950' src='<?php echo base_url('eservices/' . $token) . '?embedded=true' ?>' frameborder='0' allowfullscreen></iframe><?php } else { ?><iframe id='frameBooking' width='100%' height='950' src='<?php echo base_url('eevents/' . $token) . '?embedded=true' ?>' frameborder='0' allowfullscreen></iframe><?php } ?></textarea>
                                </div>
                                <?php echo form_close(); ?>

                            </div>
                        </div>
                        <!--/Form with header-->
                    </div>
                    <!--Card-->
                </div>
                <!-- End Col -->
            </section>
        </div>
        <!--Row-->
        <!-- End Login-->
    </div>
</div>
<script src="<?php echo $this->config->item('js_url'); ?>module/sitesetting.js" type="text/javascript"></script>
<?php include VIEWPATH . 'admin/footer.php'; ?>

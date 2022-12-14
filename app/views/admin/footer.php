<!-- container-fluid -->
</div>
<!-- End Page-content -->

<!-- Delete Modal -->
<div class="modal fade bs-example-modal-center" id="delete-record">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <?php
            $attributes = array('id' => 'DeleteRecordForm', 'name' => 'DeleteRecordForm', 'method' => "post");
            echo form_open('', $attributes);
            ?>
            <input type="hidden" id="record_id"/>
            <div class="modal-header">
                <h5 id='some_name' class="modal-title" style="font-size: 18px;"><?php echo translate('delete'); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h5><?php echo translate('delete_confirm'); ?></h5>
            </div>
            <div class="modal-footer">
                <a class="btn btn-primary font_size_12" href="javascript:void(0)" id="RecordDelete" ><?php echo translate('confirm'); ?></a>
                <button data-bs-dismiss="modal" class="btn btn-danger font_size_12" type="button"><?php echo translate('close'); ?></button>
            </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <strong>&copy;</strong> <?php echo get_CompanyName() . " " . date("Y"); ?>
            </div>
        </div>
    </div>
</footer>
</div>
<!-- end main content-->

</div>
<!-- END layout-wrapper -->

<!-- JAVASCRIPT -->
<script src="<?php echo base_url('assets/admin/libs/bootstrap/js/bootstrap.bundle.min.js');?>"></script>
<script src="<?php echo $this->config->item('js_url'); ?>jquery.validate.min.js" type="text/javascript"></script>
<script src="<?php echo base_url('assets/admin/libs/metismenu/metisMenu.min.js');?>"></script>
<script src="<?php echo base_url('assets/admin/libs/simplebar/simplebar.min.js');?>"></script>
<script src="<?php echo base_url('assets/admin/libs/node-waves/waves.min.js');?>"></script>
<script src="<?php echo base_url('assets/admin/libs/feather-icons/feather.min.js');?>"></script>

<!-- Required datatable js -->
<script src="<?php echo base_url('assets/admin/libs/datatables.net/js/jquery.dataTables.min.js');?>"></script>
<script src="<?php echo base_url('assets/admin/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js');?>"></script>
<!-- pace js -->
<script src="<?php echo base_url('assets/admin/libs/pace-js/pace.min.js');?>"></script>

<!-- apexcharts -->
<script src="<?php echo base_url('assets/admin/libs/apexcharts/apexcharts.min.js');?>"></script>

<!-- Plugins js-->
<script src="<?php echo base_url('assets/admin/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js');?>"></script>
<script src="<?php echo base_url('assets/admin/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js');?>"></script>

<!-- dashboard init -->
<script src="<?php echo base_url('assets/admin/js/pages/dashboard.init.js');?>"></script>
<script src="<?php echo base_url('assets/admin/js/app.js');?>"></script>
<script>

</script>
</body>
</html>
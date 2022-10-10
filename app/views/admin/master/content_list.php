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
                            <div class="row">
                                <span class="col-md-9 col-9 m-0">
                                    <h3 class="black-text font-bold mb-0"><?php echo translate('manage'); ?> <?php echo translate('content_management'); ?></h3>
                                </span>  
                                <span class="col-md-3 col-3 text-right m-0">
                                    <a title="<?php echo translate('add') . " " . translate('content_management'); ?>" href='<?php echo base_url('admin/add-content'); ?>' class="btn-floating btn-sm btn-success m-0"><i class="fa fa-plus-circle"></i></a>
                                </span>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table mdl-data-table" id="example">
                                        <thead>
                                            <tr>
                                                <th class="text-center font-bold dark-grey-text">#</th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('title'); ?></th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('status'); ?></th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('created_date'); ?></th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('action'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (isset($app_faq) && count($app_faq) > 0) {
                                                foreach ($app_faq as $key => $row) {
                                                    if ($row['status'] == "A") {
                                                        $status_string = '<span class="badge badge-success">' . translate('active') . '</span>';
                                                    } else {
                                                        $status_string = '<span class="badge badge-danger">' . translate('inactive') . '</span>';
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td class="text-center"><?php echo $key + 1; ?></td>
                                                        <td class="text-center"><?php echo $row['title']; ?></td>
                                                        <td class="text-center"><?php echo $status_string; ?></td>
                                                        <td class="text-center"><?php echo get_formated_date($row['created_on'], "N"); ?></td>
                                                        <td class="td-actions text-center">
                                                            <a href="<?php echo base_url('admin/update-content/' . $row['id']); ?>" class="btn btn-primary font_size_12" title="<?php echo translate('edit'); ?>" data-toggle="tooltip" data-placement="top"><i class="fa fa-pencil"></i></a>
                                                            <span class="d-inline-block" title="<?php echo translate('delete'); ?>" data-toggle="tooltip" data-placement="top"><a id="" data-toggle="modal" onclick='DeleteRecord(this)' data-target="#delete-record" data-id="<?php echo (int) $row['id']; ?>" class="btn btn-danger font_size_12"><i class="fa fa-trash"></i></a></span>
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
                    <a class="btn btn-primary font_size_12" href="javascript:void(0)" id="RecordDelete" ><?php echo translate('confirm'); ?></a>
                    <button data-dismiss="modal" class="btn btn-danger font_size_12" type="button"><?php echo translate('close'); ?></button>
                    
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<script src="<?php echo $this->config->item('js_url'); ?>module/page_content.js" type="text/javascript"></script>
<?php
include VIEWPATH . 'admin/footer.php';
?>
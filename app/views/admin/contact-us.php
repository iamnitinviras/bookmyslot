<?php
include VIEWPATH . 'admin/header.php';
?>
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
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 m-auto">
                            <?php $this->load->view('message'); ?>

                            <div class="table-responsive">
                                <table class="table table-bordered dt-responsive nowrap w-100" id="datatable">
                                    <thead>
                                    <tr>
                                        <th class="text-center font-bold dark-grey-text">#</th>
                                        <th class="text-center font-bold dark-grey-text"><?php echo translate('name'); ?></th>
                                        <th class="text-center font-bold dark-grey-text"><?php echo translate('email'); ?></th>
                                        <th class="text-center font-bold dark-grey-text"><?php echo translate('phone'); ?></th>
                                        <th class="text-center font-bold dark-grey-text"><?php echo translate('subject'); ?></th>
                                        <th class="text-center font-bold dark-grey-text"><?php echo translate('message'); ?></th>
                                        <th class="text-center font-bold dark-grey-text"><?php echo translate('request_date'); ?></th>
                                        <th class="text-center font-bold dark-grey-text"><?php echo translate('action'); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if (isset($app_contact_us) && count($app_contact_us) > 0) {
                                        foreach ($app_contact_us as $mem_key => $mem_row) {
                                            ?>
                                            <tr>
                                                <td class="text-center"><?php echo $mem_key + 1; ?></td>
                                                <td class="text-center"><?php echo ucfirst($mem_row['name']); ?></td>
                                                <td class="text-center"><?php echo $mem_row['email']; ?></td>
                                                <td class="text-center"><?php echo $mem_row['phone']; ?></td>
                                                <td class="text-center"><?php echo $mem_row['subject']; ?></td>
                                                <td class="text-center"><?php echo $mem_row['message']; ?></td>
                                                <td class="text-center"><?php echo get_formated_date($mem_row['created_on'], "N"); ?></td>
                                                <td class="text-center">
                                                    <a href="javascript:void(0)" data-bs-toggle="modal" data-id="<?php echo $mem_row['id']; ?>" data-email="<?php echo $mem_row['email']; ?>" data-name="<?php echo $mem_row['name']; ?>" data-subject="<?php echo $mem_row['subject']; ?>" onclick='send_reply(this)' data-bs-target="#appointment-record" data-id="<?php echo (int) $row['id']; ?>" class="btn-floating btn-sm blue-gradient"><i class="fa fa-reply"></i></a>
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
            </div>
        </div>
    </div>
    <!-- end row -->
</div>

<div class="modal fade" id="appointment-record">
    <div class="modal-dialog">
        <div class="modal-content">
            <?php
            $attributes = array('id' => 'SendreplyForm', 'name' => 'SendreplyForm', 'method' => "post");
            echo form_open(base_url('admin/send_reply'), $attributes);
            ?>
            <input type="hidden" id="record_id" name="record_id"/>
            <div class="modal-header">
                <h4 class="modal-title" style="font-size: 18px;"><?php echo translate('send') . " " . translate('reply'); ?></h4>
                <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="reply_name"><?php echo translate('name'); ?></label>
                    <input type="text" readonly="" required="" name="reply_name" id="reply_name" class="form-control"/>
                </div>
                <div class="form-group">
                    <label for="reply_email"><?php echo translate('email'); ?></label>
                    <input type="text" readonly="" name="reply_email" required="" id="reply_email" class="form-control"/>
                </div>
                <div class="form-group">
                    <label for="reply_subject"><?php echo translate('subject'); ?></label>
                    <input type="text" id="reply_subject" name="reply_subject" required="" class="form-control"/>
                </div>
                <div class="form-group">
                    <label for="reply_text"><?php echo translate('message'); ?></label>
                    <textarea id="reply_text" required="" name="reply_text" class="form-control"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <a class="btn btn-primary font_size_12" href="javascript:void(0)" onclick="submit_button();"><?php echo translate('send'); ?></a>
                <button data-dismiss="modal" class="btn btn-danger font_size_12" type="button"><?php echo translate('close'); ?></button>
            </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<?php
include VIEWPATH . 'admin/footer.php';
?>
<script>
    function send_reply($this) {
        $('#SendreplyForm')[0].reset();
        var email = $($this).data('email');
        var name = $($this).data('name');
        var id = $($this).data('id');
        var subject = $($this).data('subject');
        $("#reply_name").val(name);
        $("#reply_email").val(email);
        $("#record_id").val(id);
        $("#reply_subject").val("Re:" + subject);
    }
    function submit_button() {
        var SendreplyForm = $("#SendreplyForm").valid();
        if (SendreplyForm) {
            $("#SendreplyForm").submit();
        }
    }
</script>  
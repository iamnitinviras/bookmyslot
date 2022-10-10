<?php
include VIEWPATH . 'admin/header.php';
$folder_name = 'admin';
?>
<input id="folder_name" name="folder_name" type="hidden" value="<?php echo isset($folder_name) && $folder_name != '' ? $folder_name : ''; ?>"/>
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
                                <span class="col-md-9 m-0">
                                    <h3 class="black-text font-bold mb-0"><?php echo translate('translate') . " " . ($language_data['title']) . " " . translate('words'); ?></h3>
                                </span>  
                                <span class="col-md-3 text-right m-0">
                                    <a href='#' data-toggle="modal" data-target="#exampleModal" class="btn btn-primary"><?php echo translate('add') . " " . translate('new') . " " . translate('word') ?></a>
                                    <a href='<?php echo base_url('admin/manage-language'); ?>' class="btn-floating btn-sm btn-success m-0"><i class="fa fa-backward"></i></a>
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
                                                <th width="350" class="text-center font-bold dark-grey-text"><?php echo translate('action'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (isset($words) && count($words) > 0) {

                                                foreach ($words as $key => $row) {
                                                    ?>
                                                    <tr>
                                                        <td class="text-center"><?php echo $key + 1; ?></td>
                                                        <td class="text-left">
                                                            <?php echo ($row['english']); ?><br/><br/>
                                                            <input autocomplete="off" id="db_field_<?php echo $row['id']; ?>" value="<?php echo isset($row[$language_data['db_field']]) ? stripslashes($row[$language_data['db_field']]) : ""; ?>" name="translated_word[]" class="form-control"/>
                                                        </td>

                                                        <td class="td-actions text-center" w>
                                                            <a href="javascript:void(0)" data-id="<?php echo trim($row['id']); ?>" data-field="<?php echo trim($language_data['db_field']); ?>" class="btn btn-primary font_size_12" onclick="save_translated_lang(this)" title="<?php echo translate('translate_word'); ?>"><?php echo translate('save'); ?></a>
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
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><?php echo translate('add') . " " . translate('new') . " " . translate('word') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div id="lang_error_msg"></div>

                <?php
                $attributes = array('id' => 'AddNewLangWord', 'name' => 'AddNewLangWord', 'method' => "post");
                echo form_open('admin/add-new-lang-word', $attributes);
                ?>
                <div class="form-group">
                    <label for="default_text" class="col-form-label"><?php echo translate('word') ?>:</label>
                    <input required="" autocomplete="off" type="text" id="default_text" name="default_text" class="form-control">
                </div>
                <hr/>
                <?php foreach ($act_language_data as $lval): ?>
                    <div class="form-group">
                        <label for="<?php echo $lval['db_field']; ?>" class="col-form-label"><?php echo $lval['title']; ?></label>
                        <input required=""  autocomplete="off" type="text" class="form-control" id="<?php echo $lval['db_field']; ?>" name="<?php echo $lval['db_field']; ?>">
                    </div>
                <?php endforeach; ?>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo translate('close'); ?></button>
                <button type="button" onclick="add_new_word()" class="btn btn-primary"><?php echo translate('submit'); ?></button>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo $this->config->item('js_url'); ?>module/language.js" type='text/javascript'></script>
<?php
include VIEWPATH . 'admin/footer.php';
?>
<script>
                    function add_new_word() {
                        if ($('#AddNewLangWord').valid()) {
                            $.ajax({
                                type: "POST",
                                url: base_url + "admin/add-new-lang-word",
                                data: $('#AddNewLangWord').serialize(),
                                beforeSend: function () {
                                    $('.loadingmessage').show();
                                },
                                success: function (res) {
                                    $('.loadingmessage').hide();
                                    if (res == true) {
                                        $('#AddNewLangWord')[0].reset();
                                        window.location.reload();
                                    } else {
                                        $("#lang_error_msg").html(res);
                                    }

                                }
                            });
                        }
                    }
</script>
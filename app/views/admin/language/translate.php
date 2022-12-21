<?php
include VIEWPATH . 'admin/header.php';
$folder_name = 'admin';
?>
<input id="folder_name" name="folder_name" type="hidden" value="<?php echo isset($folder_name) && $folder_name != '' ? $folder_name : ''; ?>"/>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6 col-xl-6">
                            <h4 class="card-title"><?php echo translate('translate') . " " . ($language_data['title']) . " " . translate('words'); ?></h4>
                            <div class="page-title-box pb-0 d-sm-flex">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>"><?php echo translate('dashboard'); ?></a></li>
                                        <li class="breadcrumb-item"><a href="<?php echo base_url('admin/language'); ?>"><?php echo translate('language'); ?></a></li>
                                        <li class="breadcrumb-item active"><?php echo translate('translate') . " " . ($language_data['title']) . " " . translate('words'); ?></li>
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
            </div>
        </div>
    </div>
    <!-- end row -->
</div>
<script src="<?php echo $this->config->item('js_url'); ?>module/language.js" type='text/javascript'></script>
<?php
include VIEWPATH . 'admin/footer.php';
?>

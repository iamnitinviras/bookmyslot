<?php include VIEWPATH . 'admin/header.php'; ?>

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
                                            <th class="text-center font-bold dark-grey-text"><?php echo translate('title'); ?></th>
                                            <th class="text-center font-bold dark-grey-text"><?php echo translate('price'); ?></th>
                                            <th class="text-center font-bold dark-grey-text"><?php echo translate('validity'); ?></th>
                                            <th class="text-center font-bold dark-grey-text"><?php echo translate('status'); ?></th>
                                            <th class="text-center font-bold dark-grey-text"><?php echo translate('action'); ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $i = 1;
                                        if (isset($package_data) && count($package_data) > 0) {
                                            foreach ($package_data as $row) {

                                                $status_string = "";
                                                if ($row['status'] == "A") {
                                                    $status_string = '<span class="badge bg-success">' . translate('active') . '</span>';
                                                } else {
                                                    $status_string = '<span class="badge bg-danger">' . translate('inactive') . '</span>';
                                                }
                                                ?>
                                                <tr>
                                                    <td class="text-center"><?php echo $i; ?></td>
                                                    <td class="text-center"><?php echo $row['title']; ?></td>
                                                    <td class="text-center"><?php echo price_format(number_format($row['price'], 0)); ?></td>
                                                    <td class="text-center"><?php echo $row['package_month']; ?></td>
                                                    <td class="text-center"><?php echo $status_string; ?></td>
                                                    <td class="td-actions text-center">
                                                        <a href="<?php echo base_url('admin/update-package/' . $row['id']); ?>" class="btn-danger btn-floating btn-sm blue-gradient" title="<?php echo translate('edit'); ?>">
                                                            <i class="mdi mdi-pencil font-size-18"></i>
                                                        </a>
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
                </div>
            </div>
        </div>
        <!-- end row -->
    </div>

<script src="<?php echo $this->config->item('js_url'); ?>module/package.js" type='text/javascript'></script>
<?php include VIEWPATH . 'admin/footer.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6 col-xl-6">
                            <h4 class="card-title"><?php echo translate('city'); ?></h4>
                            <div class="page-title-box pb-0 d-sm-flex">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>"><?php echo translate('dashboard'); ?></a></li>
                                        <li class="breadcrumb-item active"><?php echo translate('city'); ?></li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-6 text-end add-new-btn-parent">
                            <div class="text-sm-end">
                                <a href='<?php echo base_url($folder_name.'/add-city'); ?>'class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2"><i class="mdi mdi-plus me-1"></i> <?php echo translate('add'); ?> <?php echo translate('city'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 m-auto">
                            <?php $this->load->view('message'); ?>

                            <div class="table-responsive">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end row -->
</div>


<a href="<?php echo base_url('admin/update-city/' . $row['city_id']); ?>" class="text-success"><i class="mdi mdi-pencil font-size-18"></i></a>
<a href="javascript:void(0);" data-bs-toggle="modal" onclick='DeleteRecord(this)' data-bs-target="#delete-record" data-id="<?php echo (int) $row['city_id']; ?>"  class="text-danger"><i class="mdi mdi-delete font-size-18"></i></a>


<table class="table table-bordered dt-responsive nowrap w-100" id="datatable">

    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="city_status" id="active" value="A" <?php echo $active; ?>>
        <label class="form-check-label" for="active"><?php echo translate('active'); ?></label>
    </div>
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="city_status" id="inactive"  value='I' <?php echo $inactive; ?>>
        <label class="form-check-label" for="inactive"><?php echo translate('inactive'); ?></label>
    </div>



    <div class="d-flex flex-wrap gap-2">
        <button type="submit" class="btn btn-primary"><?php echo translate('save'); ?></button>
        <a href="<?php echo base_url($folder_name.'/city'); ?>" class="btn btn-outline-secondary"><?php echo translate('cancel'); ?></a>
    </div>
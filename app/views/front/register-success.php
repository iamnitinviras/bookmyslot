<?php
include VIEWPATH . 'front/header.php';
?>
<div class="mt-5 mb-3">
    <div class="container container-min-height">
        <div class="row">
            <div class="col-md-8 mx-auto text-center">
                <div class="card">
                    <div class="card-body">
                        <div class="rgister_success_box">
                            <img src="<?php echo base_url(img_path . '/check_circle.png'); ?>" alt="check icon img" class="img-fluid" />
                            <h3 class="thanks-title">
                                <?php echo $this->session->flashdata('msg'); ?>
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include VIEWPATH . 'front/footer.php'; ?>

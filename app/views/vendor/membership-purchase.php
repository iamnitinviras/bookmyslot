<?php
include VIEWPATH . 'vendor/header.php';
?>
<style>
    
    .package_list i {
        margin: 25px 0 0;
        font-size: 2.2rem;
        border: 2px solid #ccc;
        color: #fff;
        /* padding: 20px; */
        border-radius: 50%;
        height: 75px;
        width: 75px;
        line-height: 75px;
        text-align: center;
        vertical-align: middle;
    }

    .package_list h3 {
        margin: 20px 0;
        color: #fff;
    }
    .basic_price .package_list {
        background: linear-gradient(45deg, #303f9f 0%, #1976D2 100%);
        padding-bottom: 1px;
        border: 1px solid #2163c1;
    }
    .medium_price .package_list {
        background: linear-gradient(45deg, #ff6f00 0%, #fec413 100%);
        padding-bottom: 1px;
        border: 1px solid #ff971c;
    }
    .standard_price .package_list {
        background: linear-gradient(45deg, #da27f9 0%, #1baff2 100%);
        padding-bottom: 1px;
        border: 1px solid #8265f6;
    }
</style>
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
                            <h3 class="black-text font-bold mb-0">
                                <?php echo translate('membership_purchase') ?>
                            </h3>
                        </div>
                        
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <?php
                                    if (isset($package_data) && count($package_data) > 0) {
                                        foreach ($package_data as $key => $value) {
                                            ?>
                                            <div class="col-md-4 text-center package_card">
                                                <div class="card pt-0 basic_price">
                                                    <div class="card-body">
                                                        <div class="package_list mb-3">
                                                            <i class="fa fa-home"></i>
                                                            <h3><?php echo $value['title']; ?></h3>
                                                        </div>
                                                        <ul class="list-group list-inline text-center">
                                                            <li class="list-group-item mb-3 borderx-none"><strong><?php echo translate('amount'); ?> <?php echo price_format($value['price']); ?></strong></li>
                                                        </ul>
                                                        <ul class="list-group list-inline text-center">
                                                            <li class="list-group-item mb-3 borderx-none"><?php echo translate('validity'); ?> - <b><?php echo $value['package_month']; ?> <?php echo translate('month'); ?></b></li>
                                                        </ul>
                                                        <?php if(isset($value['description']) && $value['description']!=""): ?>
                                                            <ul class="list-group list-inline text-center">
                                                                <li class="list-group-item mb-3 borderx-none"><?php echo $value['description']; ?></li>
                                                            </ul>
                                                        <?php endif; ?>
                                                        
                                                        <a href="<?php echo base_url('vendor/purchase-details/' . $value['id']); ?>" class="btn btn-info waves-effect"><?php echo translate('purchase')." ".translate('now'); ?></a>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php }
                                        ?>
                                    <?php } ?>
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
<?php
include VIEWPATH . 'vendor/footer.php';
?>
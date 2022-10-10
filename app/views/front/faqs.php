<?php include VIEWPATH . 'front/header.php'; ?>
<div class="my-3">
    <div class="container container-min-height">
        <div class="header text-center">
            <h2><?php echo translate('faqs') ?></h2>
        </div>
        <div class="faqs-wappers">
            <div id="accordion">
                <div class="panel-group">

                    <?php if (isset($app_faq) && count($app_faq) > 0): ?>

                        <?php
                        $i = 0;
                        foreach ($app_faq as $val):
                            ?>
                            <div class="panel panel-default">
                                <div class="panel-heading" id="headingOne<?php echo $val['id']; ?>">
                                    <div class="panel-title"> 
                                        <a class="accordion-toggle" data-toggle="collapse" data-target="#collapseOne<?php echo $val['id']; ?>" aria-expanded="<?php echo ($i == 0) ? "true" : "false"; ?>" href="#collapseOne<?php echo $val['id']; ?>">
                                            <?php echo $val['title']; ?>
                                            <i class="fa fa-angle-down pull-right"></i>
                                        </a>
                                    </div>
                                </div>
                                <div id="collapseOne<?php echo $val['id']; ?>" class="panel-collapse collapse <?php echo ($i == 0) ? "show" : ""; ?>" aria-labelledby="headingOne<?php echo $val['id']; ?>" data-parent="#accordion">
                                    <div class="panel-body card-body">
                                        <div class="media accordion-inner">
                                            <div class="media-body">
                                                <p><?php echo nl2br($val['description']); ?></p>                      
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                            $i++;
                        endforeach;
                        ?>
                    <?php else: ?>
                        <p class="text-center"><?php echo translate('no_record_found'); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include VIEWPATH . 'front/footer.php'; ?>

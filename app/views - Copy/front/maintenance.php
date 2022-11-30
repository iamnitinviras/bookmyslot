<?php include VIEWPATH . 'front/front-header.php'; ?>
<style>
    body { text-align: center; padding: 150px; }
    .maintenance_logo {
        text-align: center;
        font-size: 70px;
        color: #ccc;
        box-shadow: 0 0 4px 1px #ccc;
        margin: 0 auto;
        width: 90px;
        border-radius: 50px;
        margin-bottom: 20px;
    }
    h1 { font-size: 50px; }
    body { font: 20px Helvetica, sans-serif; color: #333; }
    article { display: block; text-align: left; width: 650px; margin: 0 auto; }
    a { color: #dc8100; text-decoration: none; }
    a:hover { color: #333; text-decoration: none; }
    
    @media (max-width:991px){
        body { padding: 2rem 0px; }
      article {  width: 100%; padding: 20px }
    }
</style>

<section class="form-light">

    <div class="mt-3">
        <article>
            <div class="maintenance_logo">
                <i class="fa fa-gear"></i>
            </div>

            <!--Header-->
            <div class="header">
                <h1 class="mb-3 text-center"><?php echo translate('we_will_be_back_soon!'); ?></h1>
            </div>
            <!--Header-->
            <div class="maintenance_content">
                <p style="font-size: 16px;"><?php echo translate('Sorry_for_the_inconvenience_but_we_are_performing_some_maintenance_at_the_moment.') ?> <?php echo translate('if_you_need_to_you_can_always') ?> <a href="mailto:<?php echo get_site_setting('company_email1'); ?>"><?php echo translate('contact-us') ?></a>, <?php echo translate('otherwise_we_will_be_back_online_shortly!') ?></p>
                <p>&mdash; The <?php echo (get_CompanyName()); ?> Team</p>
            </div>
        </article>
    </div>
    <!--/Form with header-->

</section>
<?php include VIEWPATH . 'front/front-footer.php'; ?>
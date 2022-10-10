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
                <h1 class="mb-3 text-center">Something went wrong.</h1>
            </div>
            <!--Header-->

        </article>
    </div>
    <!--/Form with header-->

</section>
<?php include VIEWPATH . 'front/front-footer.php'; ?>
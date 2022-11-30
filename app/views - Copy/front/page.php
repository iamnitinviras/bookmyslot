<?php
include VIEWPATH . 'front/header.php';
?>
<style>
    p img{
        width: 100%;
    }
</style>
<div class="my-3">
    <div class="container container-min-height">
        <div class="header">
            <h2 class="text-center"><?php echo isset($title) ? ($title) : ""; ?></h2>
            <?php echo isset($description) ? $description : ""; ?>
        </div>
    </div>
</div>

<?php include VIEWPATH . 'front/footer.php'; ?>

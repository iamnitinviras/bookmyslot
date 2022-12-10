<?php
if ($this->session->flashdata('msg_class') == "success") {
    ?>
    <div class="alert alert-success alert-dismissible  alert-message mt-10">
        <i class="mdi mdi-check-all me-2"></i>
        <?php echo $this->session->flashdata('msg'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <script>
        window.setTimeout(function () {
            $(".alert-message").fadeTo(1500, 0).slideUp(500, function () {
                $(this).remove();
            });
        }, 5000);
    </script>  

    <?php
} else if ($this->session->flashdata('msg_class') == "failure") {
    ?>
    <div class="alert alert-danger alert-message mt-10">
        <i class="mdi mdi-block-helper me-2"></i>
        <?php echo $this->session->flashdata('msg'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <script>
        window.setTimeout(function () {
            $(".alert-message").fadeTo(1500, 0).slideUp(500, function () {
                $(this).remove();
            });
        }, 5000);
    </script>  
    <?php
} else if ($this->session->flashdata('msg_class') == "info") {
    ?>
    <div class="alert alert-info alert-message mt-10">
        <?php echo $this->session->flashdata('msg'); ?>
    </div>
    <script>
        window.setTimeout(function () {
            $(".alert-message").fadeTo(1500, 0).slideUp(500, function () {
                $(this).remove();
            });
        }, 5000);
    </script>  
    <?php
}
?>

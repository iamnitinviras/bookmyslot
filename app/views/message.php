<?php
if ($this->session->flashdata('msg_class') == "success") {
    ?>
    <div class="alert alert-success alert-message mt-10">
        <strong><?php echo $this->session->flashdata('msg'); ?></strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
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

</div>
<footer class="page-footer pt-0 lr-page">
    <!-- Copyright -->
    <div class="footer-copyright">
        <div class="dashboard-body pt-0 text-center">
            <div class="container-fluid">
                <strong>&copy;</strong> <?php echo get_CompanyName() . " " . date("Y"); ?>
            </div>
        </div>
    </div>
    <!-- Copyright -->
</footer>
<script src="<?php echo $this->config->item('js_url'); ?>popper.min.js"></script>
<script src="<?php echo $this->config->item('js_url'); ?>bootstrap.min.js"></script>
<script src="<?php echo $this->config->item('js_url'); ?>bootstrap-timepicker.js"></script>
<script src="<?php echo $this->config->item('js_url'); ?>module/bookmyslot.js"></script>
<script src="<?php echo $this->config->item('js_url'); ?>sidebar.js"></script>
<script src="<?php echo $this->config->item('js_url'); ?>datepicker.js"></script>
<script src="<?php echo $this->config->item('js_url'); ?>jquery-slidePanel.js"></script>
<script src="<?php echo $this->config->item('js_url'); ?>module/admin_panel.js"></script>

<!-- Summer Note -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote-lite.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote-lite.js"></script>
<script>
    $(document).ready(function () {
        $('#example').DataTable({
            columnDefs: [
                {
                    targets: [0, 1, 2],
                    className: 'mdl-data-table__cell--non-numeric'
                }
            ]
        });
    });
    $('#summornote_div_id').summernote({
        minHeight: 300,
        callbacks: {
            onImageUpload: function (files, editor, welEditable) {
                sendFile(files[0], editor, welEditable);
            }
        }
    });
    function sendFile(file, editor, welEditable) {
        data = new FormData();
        data.append("file", file);
        $.ajax({
            data: data,
            type: "POST",
            url: "<?php echo base_url('upload-summernote-image'); ?>",
            cache: false,
            contentType: false,
            processData: false,
            success: function (url) {
                $('#summornote_div_id').summernote("insertImage", url);
            }
        });
    }
    $('.note-video-clip').each(function () {
        var tmp = $(this).wrap('<p/>').parent().html();
        $(this).parent().html('<div class="embed-responsive embed-responsive-16by9">' + tmp + '</div>');
    });
</script>
</body>
</html>
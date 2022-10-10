<?php
$folder_name = 'admin';
if (isset($login_type) && $login_type == 'V') {
    $folder_name = 'vendor';
}
include VIEWPATH . $folder_name . '/header.php';

$productPoints = array();
foreach ($product_data as $value) {
    $productPoints[$value['month']] = $value['total'];
}
for ($i = 1; $i <= 12; $i++) {
    if (!array_key_exists($i, $productPoints)) {
        $productPoints[$i] = 0;
    }
}

ksort($productPoints);
$month_array = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
foreach ($productPoints as $key => $val) {
    $productPoints_label[] = $month_array[$key - 1];
    $productPoints_view[] = $val;
    $max_product = max($productPoints_view);
}
?>
<script src="<?php echo $this->config->item('js_url'); ?>module/Chart.min.js" type='text/javascript'></script>
<style>
    .select-wrapper span.caret {
        top: 18px;
        color: black;
    }
    .select-wrapper input.select-dropdown {
        color: black;
    }
</style>
<div class="dashboard-body">
    <!-- Start Content -->
    <div class="content">
        <!-- Start Container -->
        <div class="container-fluid ">
            <section class="form-light px-2 sm-margin-b-20">
                <!-- Row -->
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="card p-4">
                            <?php
                            $attributes = array('id' => 'AppointmentReport', 'name' => 'AppointmentReport', 'method' => "post");
                            echo form_open(base_url($folder_name . '/appointment-report'), $attributes);
                            ?>
                            <div class="row">
                                <?php if (isset($folder_name) && $folder_name == 'admin') { ?>
                                    <div class="col-md-4">
                                        <select class="kb-select initialized" name="vendor_id">
                                            <option value=""><?php echo translate('select_vendor'); ?></option>
                                            <?php
                                            if (isset($vendor_list) && count($vendor_list) > 0) {
                                                foreach ($vendor_list as $vendor_value) {
                                                    ?>
                                                    <option value="<?php echo $vendor_value['id']; ?>" <?php echo isset($vendor_id) && $vendor_id == $vendor_value['id'] ? 'selected' : ''; ?>><?php echo ($vendor_value['first_name']) . " " . ($vendor_value['last_name']); ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                <?php } ?>
                                <div class="col-md-4">
                                    <select class="kb-select initialized" name="year">
                                        <option value=""><?php echo translate('select_year'); ?></option>
                                        <?php
                                        if (isset($year_data) && count(array_filter($year_data)) > 0) {
                                            $min = $year_data['min'];
                                            $max = $year_data['max'];
                                            for ($i = $min; $i <= $max; $i++) {
                                                ?>
                                                <option value="<?php echo $i; ?>" <?php echo isset($year) && $year == $i ? 'selected' : ''; ?>><?php echo $i; ?></option>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <option value="<?php echo date('Y'); ?>" <?php echo isset($year) && $year == date('Y') ? 'selected' : ''; ?>><?php echo date('Y'); ?></option>
                                        <?php }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <button class="btn btn-success" name="report_search" id="report_search" type="submit"><i class="fa fa-search-plus"></i></button>
                                    <button class="btn btn-danger" type="button" onclick="window.location.href = '<?php echo base_url($folder_name . '/appointment-report'); ?>'"><i class="fa fa-refresh"></i></button>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="card p-4">
                            <div style="width:100%;">
                                <h3 class="text-center"><?php echo translate('monthly_appointment'); ?></h3>
                                <canvas id="canvas_product"></canvas>
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

<script>

    // New Product Chart
    var canvas = document.getElementById('canvas_product');
    var data = {
        labels: <?php echo json_encode($productPoints_label, JSON_NUMERIC_CHECK); ?>,
        datasets: [
            {
                label: "<?php echo translate('appointment'); ?>",
                fill: false,
                lineTension: 0.1,
                backgroundColor: "rgba(0,150,0,0.4)",
                borderColor: "rgba(0,150,0,1)",
                borderCapStyle: 'butt',
                borderDash: [],
                borderDashOffset: 0.0,
                borderJoinStyle: 'miter',
                pointBorderColor: "rgba(0,150,0,1)",
                pointBackgroundColor: "#fff",
                pointBorderWidth: 1,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: "rgba(75,192,192,1)",
                pointHoverBorderColor: "rgba(220,220,220,1)",
                pointHoverBorderWidth: 2,
                pointRadius: 5,
                pointHitRadius: 10,
                data: <?php echo json_encode($productPoints_view, JSON_NUMERIC_CHECK); ?>,
            }
        ]
    };

    function adddata() {
        myLineChart.data.datasets[0].data[7] = 60;
        myLineChart.data.labels[7] = "Newly Added";
        myLineChart.update();
    }

    var option = {
        showLines: true,
        scales: {
            xAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: '<?php echo translate('month'); ?>'
                    }
                }],
            yAxes: [{
                    display: true,
                    ticks: {
                        min: 0,
                        max: <?php echo isset($max_product) && $max_product > 10 ? $max_product : 10; ?>,
                    },
                    scaleLabel: {
                        display: true,
                        labelString: '<?php echo translate('appointment'); ?>'
                    }
                }]
        }
    };
    var myLineChart = Chart.Line(canvas, {
        data: data,
        options: option
    });


</script>
<?php include VIEWPATH . $folder_name . '/footer.php'; ?>
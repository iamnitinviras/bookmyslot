<?php
include VIEWPATH . 'admin/header.php';

$productPoints = array();
foreach ($product_data as $value) {
    $day_value = date('d', strtotime($value['created_on']));
    $productPoints[$day_value] = $value['total'];
}

if ($month != '' || $year != '') {
    if ($month != '' && $year != '') {
        if ($month == date('m') && $year == date('Y')) {
            $current_days = unixtojd(mktime(0, 0, 0, 6, date('d')));
            $total_days = cal_days_in_month(CAL_GREGORIAN, $month, date('Y'));
            $total_days = $total_days - ($total_days - cal_from_jd($current_days, CAL_GREGORIAN)['day']);
        } else {
            $total_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        }
    } elseif ($month != '') {
        if ($month == date('m')) {
            $current_days = unixtojd(mktime(0, 0, 0, 6, date('d')));
            $total_days = cal_days_in_month(CAL_GREGORIAN, $month, date('Y'));
            $total_days = $total_days - ($total_days - cal_from_jd($current_days, CAL_GREGORIAN)['day']);
        } else {
            $total_days = cal_days_in_month(CAL_GREGORIAN, $month, date('Y'));
        }
    } elseif ($year != '') {
        $total_days = cal_days_in_month(CAL_GREGORIAN, date('m'), $year);
    }
} else {
    $total_days = cal_days_in_month(CAL_GREGORIAN, date('m') - 1, date('Y'));
}

for ($i = 1; $i <= $total_days; $i++) {
    if (!array_key_exists($i, $productPoints)) {
        $productPoints[$i] = 0;
    }
}

ksort($productPoints);

foreach ($productPoints as $key => $val) {
    $productPoints_label[] = $key;
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
                            $attributes = array('id' => 'VendorReport', 'name' => 'VendorReport', 'method' => "post");
                            echo form_open(base_url('admin/report'), $attributes);
                            ?>
                            <div class="row">
                                <div class="col-md-4">
                                    <select class="kb-select select-month initialized" name="month">
                                        <option value=""><?php echo translate('select_month'); ?></option>
                                        <option value='1' <?php echo isset($month) && $month == '1' ? 'selected' : ''; ?>><?php echo translate('January'); ?></option>
                                        <option value='2' <?php echo isset($month) && $month == '2' ? 'selected' : ''; ?>><?php echo translate('february'); ?></option>
                                        <option value='3' <?php echo isset($month) && $month == '3' ? 'selected' : ''; ?>><?php echo translate('march'); ?></option>
                                        <option value='4' <?php echo isset($month) && $month == '4' ? 'selected' : ''; ?>><?php echo translate('march'); ?></option>
                                        <option value='5' <?php echo isset($month) && $month == '5' ? 'selected' : ''; ?>><?php echo translate('may'); ?></option>
                                        <option value='6' <?php echo isset($month) && $month == '6' ? 'selected' : ''; ?>><?php echo translate('june'); ?></option>
                                        <option value='7' <?php echo isset($month) && $month == '7' ? 'selected' : ''; ?>><?php echo translate('july'); ?></option>
                                        <option value='8' <?php echo isset($month) && $month == '8' ? 'selected' : ''; ?>><?php echo translate('august'); ?></option>
                                        <option value='9' <?php echo isset($month) && $month == '9' ? 'selected' : ''; ?>><?php echo translate('september'); ?></option>
                                        <option value='10' <?php echo isset($month) && $month == '10' ? 'selected' : ''; ?>><?php echo translate('october'); ?></option>
                                        <option value='11' <?php echo isset($month) && $month == '11' ? 'selected' : ''; ?>><?php echo translate('november'); ?></option>
                                        <option value='12' <?php echo isset($month) && $month == '12' ? 'selected' : ''; ?>><?php echo translate('december'); ?></option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select class="kb-select initialized" name="year">
                                        <option value=""><?php echo translate('select_month'); ?></option>
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
                                    <button class="btn btn-danger" type="button" onclick="window.location.href = '<?php echo base_url('admin/report'); ?>'"><i class="fa fa-refresh"></i></button>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="card p-4">
                            <div style="width:100%;">
                                <h3 class="text-center"><?php echo translate('monthly_new_vendor'); ?></h3>
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
                label: "<?php echo translate('new_vendor'); ?>",
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
                        labelString: '<?php echo translate('date'); ?>'
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
                        labelString: '<?php echo translate('vendor'); ?>'
                    }
                }]
        }
    };
    var myLineChart = Chart.Line(canvas, {
        data: data,
        options: option
    });


</script>
<?php include VIEWPATH . 'admin/footer.php'; ?>
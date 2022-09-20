<?php /*
<!--<script>$(document).ready(function() { load('panel/vacancies/widget'); });</script>-->
<!--<script>$(function() { load('panel/vacancies/widget'); });</script>-->
*/ ?>

<!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
<link href="<?= _SITEDIR_ ?>public/css/modules-widgets.css" rel="stylesheet" type="text/css">

<style>
    .table {
        margin-bottom: 0;
    }

    .table td, .table th {
        padding: 10px;
    }

    .widget_mm {
        min-height: 280px;
        max-height: 280px;
    }

    @media only screen and (max-width: 768px) {
        .widget_mm {
            max-height: 320px;
        }
    }
</style>

<div class="layout-px-spacing">
    <div class="row layout-top-spacing">

        <!-- Left col -->
        <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12" style="padding: 0;">
            <!-- Stats -->
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">

                <div class="widget-two">
                    <div class="widget-content">

                        <div class="w-numeric-value">
                            <div class="w-content">
                                <span class="w-value">Stats</span>
                                <span class="w-numeric-title">Source: Website</span>
                            </div>

                            <a class="btn__circle pointer fas fa-wrench" href="{URL:panel/settings/dashboard}"></a>
                        </div>

                        <div class="widget__scroll_box widget_mm">
                            <div class="w-chart">
                                <?php
//                                $preparedArray = [
//                                    [7,3,1,5,0,2,2,4,9,7],
//                                    [0,2,2,4,9,7,7,3,1,5],
//                                    [0,0,1,0,4,2,9,1,4,4],
//                                    [6,1,2,1,0,4,6,9,15,7],
//                                    [1,3,1,2,0,1,3,1,1,7],
//                                    [5,1,1,0,0,2,2,4,15,27]
//                                ];
                                $k = 0;
                                foreach ($this->statistics as $statistic) {
//                                    $statistic->data = $preparedArray[$k];
                                    $k++;
                                    ?>
                                    <div class="w-chart-section">
                                        <div class="w-detail">
                                            <a <?= ($statistic->link ? 'href="{URL:' . $statistic->link . '}"' : '') ?> class="w-title"><?= $statistic->title; ?></a>
                                            <p class="w-stats"><?= defaultValue($statistic->count, 0); ?></p>
                                        </div>
                                        <div class="w-chart-render-one">
                                            <div id="total_users_<?= $k; ?>"></div>
                                        </div>

                                        <script>
                                            $(document).ready(function($) {
                                                try {
                                                    // Total Visits
                                                    var spark<?= $k; ?> = {
                                                        chart: {
                                                            id: 'total_users_<?= $k; ?>',
                                                            group: 'sparks<?= $k; ?>',
                                                            type: 'line',
                                                            height: 80,
                                                            sparkline: {
                                                                enabled: true
                                                            },
                                                            dropShadow: {
                                                                enabled: true,
                                                                top: 3,
                                                                left: 1,
                                                                blur: 3,
                                                                color: '#009688',
                                                                opacity: 0.6,
                                                            }
                                                        },
                                                        series: [{
                                                            data: [<?php
                                                                $setArr = [];
                                                                foreach ($statistic->data as $key => $v) {
                                                                    $setArr[] = '{x: "'.$key.'", y: '.$v.'}';
                                                                }
                                                                echo implode(', ', $setArr);
                                                                ?>]
                                                            <?php /*data: [<?= implode(', ', $statistic->data) ?>]*/ ?>
                                                        }],
                                                        stroke: {
                                                            curve: 'smooth',
                                                            width: 2,
                                                        },
                                                        markers: {
                                                            size: 0
                                                        },
                                                        grid: {
                                                            padding: {
                                                                top: 35,
                                                                bottom: 0,
                                                                left: 40
                                                            }
                                                        },
                                                        colors: ['#009688'],
                                                        tooltip: {
                                                            x: {
                                                                show: false
                                                            },
                                                            y: {
                                                                title: {
                                                                    formatter: function(value, opts) {
                                                                        return (
                                                                            opts.w.config.series[opts.seriesIndex].data[opts.dataPointIndex].x
                                                                        )
                                                                    }
                                                                }
                                                            }
                                                        },
                                                        responsive: [
                                                            {
                                                                breakpoint: 1351,
                                                                options: {
                                                                    chart: {
                                                                        height: 95,
                                                                    },
                                                                    grid: {
                                                                        padding: {
                                                                            top: 35,
                                                                            bottom: 0,
                                                                            left: 0
                                                                        }
                                                                    },
                                                                },
                                                            },
                                                            {
                                                                breakpoint: 1200,
                                                                options: {
                                                                    chart: {
                                                                        height: 80,
                                                                    },
                                                                    grid: {
                                                                        padding: {
                                                                            top: 35,
                                                                            bottom: 0,
                                                                            left: 40
                                                                        }
                                                                    },
                                                                },
                                                            },
                                                            {
                                                                breakpoint: 576,
                                                                options: {
                                                                    chart: {
                                                                        height: 95,
                                                                    },
                                                                    grid: {
                                                                        padding: {
                                                                            top: 35,
                                                                            bottom: 0,
                                                                            left: 0
                                                                        }
                                                                    },
                                                                },
                                                            }
                                                        ]
                                                    }

                                                    // Total Visits
                                                    crt_<?= $k; ?> = new ApexCharts(document.querySelector("#total_users_<?= $k; ?>"), spark<?= $k; ?>);
                                                    crt_<?= $k; ?>.render();
                                                } catch (e) {
                                                    // statements
                                                    console.log(e);
                                                }
                                            });
                                        </script>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Right col -->
        <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12" style="padding: 0;">

            <!-- Latest Vacancies -->
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">

                <div class="widget-two">
                    <div class="widget-content">
                        <div class="w-numeric-value">
                            <div class="w-content">
                                <span class="w-value w-vacancies">
                                    <a id="latest_vacancies" class="pointer active" onclick="load('panel/sort_vacancies', 'field=time')">Latest Vacancies</a> / <a id="top_vacancies" class="pointer" onclick="load('panel/sort_vacancies', 'field=views')">Top Vacancies</a>
                                </span>
                                <span class="w-numeric-title">Source: Website</span>
                            </div>
                            <div class="w-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bar-chart-2"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                            </div>
                        </div>

                        <div class="widget__scroll_box widget_mm">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th><div class="th-content">Ref</div></th>
                                        <th><div class="th-content">Title</div></th>
                                        <th><div class="th-content">Posted</div></th>
                                    </tr>
                                    </thead>
                                    <tbody id="vacancies_result">

                                    <?php foreach ($this->vacancies as $item) { ?>
                                        <tr>
                                            <td class="max_w_80" title="#<?= $item->ref; ?>"><div class="td-content customer-name mini_mize">#<?= $item->ref; ?></div></td>
                                            <td>
                                                <div class="td-content product-brand">
                                                    <a class="title" href="{URL:panel/vacancies/edit/<?= $item->id; ?>}" target="_blank">
                                                        <?= $item->title; ?>
                                                    </a>
                                                </div>
                                            </td>
                                            <td><div class="td-content"><?= date("d/m/Y", $item->time); ?></div></td>
                                        </tr>
                                    <?php } ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <?php /*
                <div class="widget-two widget_mm">
                    <!--<div class="widget widget-table-two widget_mm">-->

                    <div class="widget-heading flex-btw">
                        <h5 class="">Latest Vacancies</h5>
                    </div>

                    <div class="text-center widget__scroll_box" style="">

                    <div class="widget-content">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th><div class="th-content">Ref</div></th>
                                    <th><div class="th-content">Title</div></th>
                                    <th><div class="th-content">Posted</div></th>
                                </tr>
                                </thead>
                                <tbody>

                                <?php foreach ($this->vacancies as $item) { ?>
                                    <tr>
                                        <td class="max_w_80" title="#<?= $item->ref; ?>"><div class="td-content customer-name mini_mize">#<?= $item->ref; ?></div></td>
                                        <td>
                                            <div class="td-content product-brand">
                                                <a class="title" href="{URL:panel/vacancies/edit/<?= $item->id; ?>}" target="_blank">
                                                    <?= $item->title; ?>
                                                </a>
                                            </div>
                                        </td>
                                        <td><div class="td-content"><?= date("d/m/Y", $item->time); ?></div></td>
                                    </tr>
                                <?php } ?>

                                </tbody>
                            </table>
                        </div>
                    </div>

                    </div>

                </div>
                */ ?>
            </div>
        </div>


        <!-- Website Visitors -->
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget-two">
                <div class="widget-content">
                    <div class="w-numeric-value">
                        <div class="w-content">
                            <span class="w-value">Website Visitor Statistics</span>
                            <span class="w-numeric-title">Source: Google Analytics</span>
                        </div>
                        <div class="w-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bar-chart-2"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                        </div>
                    </div>

                    <div class="text-center" style="padding: 0 16px 16px;">
                        <div id="new_users_chart">
                            <div class="spinner-border text-warning align-self-center"></div>
                            <div id="new_users_canvas"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Devices -->
        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12 layout-spacing">
            <div class="widget-two">
                <div class="widget-content">
                    <div class="w-numeric-value">
                        <div class="w-content">
                            <span class="w-value">Devices</span>
                            <span class="w-numeric-title">Source: Google Analytics</span>
                        </div>
                        <div class="w-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bar-chart-2"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                        </div>
                    </div>

                    <div class="text-center" style="padding: 0 16px 16px;">
                        <div id="devices_chart">
                            <div class="spinner-border text-warning align-self-center"></div>
                            <div id="devices_canvas"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Countries -->
        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12 layout-spacing">
            <div class="widget-two">
                <div class="widget-content">
                    <div class="w-numeric-value">
                        <div class="w-content">
                            <span class="w-value">Countries</span>
                            <span class="w-numeric-title">Source: Google Analytics</span>
                        </div>
                        <div class="w-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bar-chart-2"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                        </div>
                    </div>

                    <div class="text-center" style="padding: 0 16px 16px;">
                        <div id="country_chart">
                            <div class="spinner-border text-warning align-self-center"></div>
                            <div id="country_canvas"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Traffic Sources -->
        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12 layout-spacing">
            <div class="widget-two">
                <div class="widget-content">
                    <div class="w-numeric-value">
                        <div class="w-content">
                            <span class="w-value">Traffic Sources</span>
                            <span class="w-numeric-title">Source: Google Analytics</span>
                        </div>
                        <div class="w-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bar-chart-2"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                        </div>
                    </div>

                    <div class="text-center" style="padding: 0 16px 16px;">
                        <div id="sources_chart">
                            <div class="spinner-border text-warning align-self-center"></div>
                            <div id="sources_canvas"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Visited Pages -->
        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12 layout-spacing">
            <div class="widget-two">
                <div class="widget-content">
                    <div class="w-numeric-value">
                        <div class="w-content">
                            <span class="w-value">Top Visited Pages</span>
                            <span class="w-numeric-title">Source: Google Analytics</span>
                        </div>
                        <div class="w-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bar-chart-2"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                        </div>
                    </div>



                    <div class="text-center widget__scroll_box">
                        <div id="top_div">
                            <div class="spinner-border text-warning align-self-center"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bounce Rate per Page -->
        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12 layout-spacing">
            <div class="widget-two">
                <div class="widget-content">
                    <div class="w-numeric-value">
                        <div class="w-content">
                            <span class="w-value">Bounce Rate per Page</span>
                            <span class="w-numeric-title">Source: Google Analytics</span>
                        </div>
                        <div class="w-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bar-chart-2"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                        </div>
                    </div>

                    <div class="text-center widget__scroll_box">
                        <div id="bounce_div">
                            <div class="spinner-border text-warning align-self-center"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Avg Session Duration per Page -->
        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12 layout-spacing">
            <div class="widget-two">
                <div class="widget-content">
                    <div class="w-numeric-value">
                        <div class="w-content">
                            <span class="w-value">Avg Session Duration per Page</span>
                            <span class="w-numeric-title">Source: Google Analytics</span>
                        </div>
                        <div class="w-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bar-chart-2"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                        </div>
                    </div>

                    <div class="text-center widget__scroll_box">
                        <div id="duration_div">
                            <div class="spinner-border text-warning align-self-center"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>


<!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
<script src="<?= _SITEDIR_ ?>public/plugins/apex/apexcharts.min.js"></script>
<!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->


<script>
    // Active Vacancies Sorting
    const vacanciesSortItems = document.querySelectorAll('.w-vacancies a');

    vacanciesSortItems.forEach((item, i) => {
        item.addEventListener('click', function () {
            let prevItem = document.querySelector('.w-vacancies a.active');

            prevItem.classList.remove('active');
            item.classList.add('active');
        })
    })

    console.log(vacanciesSortItems)

    // New users
    function new_users_chart() {
        <?php if (!$this->ga1) { ?>
            $.get('{URL:panel/analytics/ajax/new_users}', {}, function (json) {
                $('#new_users_chart').find('div.spinner-border').hide();
                $('#new_users_chart').find('#new_users_canvas').show();

                if (!json.error) //!JSON.parse(json).error
                    chartNewUsers(json);
                else
                    $('#new_users_chart').text(JSON.parse(json).error);
            });
        <?php } else { ?>
            $('#new_users_chart').find('div.spinner-border').hide();
            $('#new_users_chart').find('#new_users_canvas').show();
            chartNewUsers(<?= $this->ga1 ?>);
        <?php } ?>
    }

    function chartNewUsers(json) {
        var datetime = [], data_new_users = [], data_returning_users = [], date_string = "";
        $(json).each(function (i, row) {
            date_string = moment(row.date, "YYYYMMDD").toISOString();
            if (row['user_type'] === "New Visitor") {
                data_new_users.push(row['new_users']);
                if (datetime.indexOf(date_string) === -1)
                    datetime.push(date_string);
            } else if (row['user_type'] === "Returning Visitor") {
                data_returning_users.push(row['users']);
                if (datetime.indexOf(date_string) === -1)
                    datetime.push(date_string);
            }
        });

        var chart = new ApexCharts(
            document.querySelector("#new_users_canvas"),
            {
                chart: {
                    height: 300,
                    type: 'area',
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 2,
                },
                series: [{
                    name: 'New Visitor',
                    data: data_new_users
                }, {
                    name: 'Returning Visitor',
                    data: data_returning_users
                }],

                xaxis: {
                    type: 'datetime',
                    categories: datetime,
                },
                tooltip: {
                    x: {
                        format: 'dd/MM/yy'
                    },
                }
            }
        );

        chart.render();
    }

    // Devices, Countries, Sources
    function devices_chart() {
        const source = 'devices';
        <?php if (!$this->ga2) { ?>
            bar_chart(source, 'Devices');
        <?php } else { ?>
            $('#' + source + '_chart').find('div.spinner-border').hide();
            $('#' + source + '_chart').find('#' + source + '_canvas').show();
            chartBar(source, <?= $this->ga2 ?>);
        <?php } ?>
    }

    function country_chart() {
        const source = 'country';
        <?php if (!$this->ga3) { ?>
            bar_chart(source, 'Countries');
        <?php } else { ?>
            $('#' + source + '_chart').find('div.spinner-border').hide();
            $('#' + source + '_chart').find('#' + source + '_canvas').show();
            chartBar(source, <?= $this->ga3 ?>);
        <?php } ?>
    }

    function sources_chart() {
        const source = 'sources';
        <?php if (!$this->ga4) { ?>
            bar_chart(source, 'Traffic Sources');
        <?php } else { ?>
            $('#' + source + '_chart').find('div.spinner-border').hide();
            $('#' + source + '_chart').find('#' + source + '_canvas').show();
            chartBar(source, <?= $this->ga4 ?>);
        <?php } ?>
    }

    function bar_chart(source, title) {
        $.get('{URL:panel/analytics/ajax/}' + source, {}, function (json) {
            $('#' + source + '_chart').find('div.spinner-border').hide();
            $('#' + source + '_chart').find('#' + source + '_canvas').show();

            if (!json.error)
                chartBar(source, json);
            else
                $('#chart').text(json.error);
        });
    }

    function chartBar(source, json) {
        var data = [];
        var labels = [];

        $(json).each(function (i, row) {
            labels.push(row['base']);
            data.push(row['ga:visits']);
        });

        var chart = new ApexCharts(
            document.querySelector("#" + source + '_canvas'),
            {
                colors : ['#f9af04', '#888ea8'],
                chart: {
                    height: 350,
                    type: 'bar',
                },
                plotOptions: {
                    bar: {
                        horizontal: true,
                    }
                },
                dataLabels: {
                    enabled: false
                },
                series: [{
                    data: data
                }],
                xaxis: {
                    categories: labels,
                }
            }
        );

        chart.render();
    }

    // Top, Bounce, Duration
    function top_list() {
        const divID = '#top_div';
        $(divID).html('<i class="fas fa-spinner fa-spin"></i>');

        <?php if (!$this->ga5) { ?>
            $.get('{URL:panel/analytics/ajax/top}', {}, function (json) {
                $(divID).empty();
                if (!json.error)
                    listStatRows(json, divID, 'pageviews', '');
                else
                    $(divID).text(json.error);
            });
        <?php } else { ?>
            $(divID).empty();
            listStatRows(<?= $this->ga5 ?>, divID, 'pageviews', '');
        <?php } ?>
    }

    function bounce_list() {
        const divID = '#bounce_div';
        $(divID).html('<i class="fas fa-spinner fa-spin"></i>');

        <?php if (!$this->ga6) { ?>
            $.get('{URL:panel/analytics/ajax/bounceRate}', {}, function (json) {
                $(divID).empty();
                if (!json.error)
                    listStatRows(json, divID, 'bounceRate', '%');
                else
                    $(divID).text(json.error);
            });
        <?php } else { ?>
            $(divID).empty();
            listStatRows(<?= $this->ga6 ?>, divID, 'bounceRate', '%');
        <?php } ?>
    }

    function duration_list() {
        const divID = '#duration_div';
        $(divID).html('<i class="fas fa-spinner fa-spin"></i>');

        <?php if (!$this->ga7) { ?>
            $.get('{URL:panel/analytics/ajax/avgSessionDuration}', {}, function (json) {
                $(divID).empty();
                if (!json.error)
                    listStatRows(json, divID, 'avgSessionDuration', ' seconds');
                else
                    $(divID).text(json.error);
            });
        <?php } else { ?>
            $(divID).empty();
            listStatRows(<?= $this->ga7 ?>, divID, 'avgSessionDuration', ' seconds');
        <?php } ?>
    }

    function listStatRows(json, divID, field, suffix) {
        $(json).each(function (i, row) {
            $(divID).append('<div class="stat__row">' +
                '<span class="title">' + row.path + '</span>' +
                '<span class="date">' + row[field] + suffix + '</span>' +
                '</div>');
        });
    }


    $(function () {
        new_users_chart();
        devices_chart();
        country_chart();
        sources_chart();
        top_list();
        bounce_list();
        duration_list();
    });
</script>
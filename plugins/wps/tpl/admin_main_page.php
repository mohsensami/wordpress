<div class="wrap">
    <h2>آمار بازدید کاربران</h2>
    <div class="dashboard-widgets-wrap">
        <div id="dashboard-widgets" class="metabox-holder">
            <div id="postbox-container-1" class="postbox-container">
                <div id="normal-sortables" class="meta-box-sortables ui-sortable">
                    <div id="dashboard_right_now" class="postbox">
                        <button type="button" class="handlediv button-link" aria-expanded="false"><span
                                class="screen-reader-text">تغییر وضعیت پنل: در یک نگاه</span><span
                                class="toggle-indicator" aria-hidden="true"></span></button>
                        <h2 class="hndle ui-sortable-handle"><span>خلاصه آمار بازدید</span></h2>
                        <div class="inside">
                            <div class="main">
                                <p>
                                    <span>یازدید کل :</span>
                                    <span><?php echo $totalStatistics->total_visits; ?></span>
                                </p>
                                <p>
                                    <span>بازدید یکتای کل : </span>
                                    <span><?php echo $totalStatistics->total_unique_visits; ?></span>
                                </p>
                                <p>
                                    <span>بازدید کل امروز : </span>
                                    <span><?php echo $todayStatitics->total_visits; ?></span>
                                </p>
                                <p>
                                    <span>بازدید یکتای امروز : </span>
                                    <span><?php echo $todayStatitics->unique_visits; ?></span>
                                </p>
                                <p>
                                    <span>بازدید کل دیروز :  </span>
                                    <span><?php echo intval( $yesterdayStatitics->total_visits ); ?></span>
                                </p>
                                <p>
                                    <span>بازدید یکتای دیروز : </span>
                                    <span><?php echo intval( $yesterdayStatitics->unique_visits ); ?></span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="postbox-container-2" class="postbox-container">
                <div id="normal-sortables" class="meta-box-sortables ui-sortable">
                    <div id="dashboard_right_now" class="postbox">
                        <button type="button" class="handlediv button-link" aria-expanded="false"><span
                                class="screen-reader-text">تغییر وضعیت پنل: در یک نگاه</span><span
                                class="toggle-indicator" aria-hidden="true"></span></button>
                        <h2 class="hndle ui-sortable-handle"><span>نمودار آمار بازدید بر اساس بازدید کل</span></h2>
                        <div class="inside">
                            <div class="main">
                                <div class="form-actions">
                                    <form action="" method="GET">
                                        <input type="hidden" name="page" value="wps/wps-stat.php">
                                        <input type="text" name="startDate" class="selectDate">
                                        <input type="text" name="endDate" class="selectDate">
                                        <input type="submit" name="filterChart" value="فیلتر کردن">
                                    </form>
                                </div>
                                <canvas id="wpsChart" width="400" height="400"></canvas>
                                <script>
                                    var ctx = document.getElementById("wpsChart");
                                    var myChart = new Chart(ctx, {
                                        type: 'line',
                                        data: {
                                            labels: <?php echo json_encode($visitsDates); ?>,
                                            datasets: [
                                                {
                                                label: 'بازدید کل',
                                                backgroundColor: "rgba(68,214,237,0.2)",
                                                borderColor: "rgba(14,187,214,1)",
                                                borderWidth: 1,
                                                hoverBackgroundColor: "rgba(68,214,237,0.4)",
                                                hoverBorderColor: "rgba(14,187,214,1)",
                                                data: <?php echo json_encode($totalVisits); ?>
                                            },
                                            {
                                                label: 'بازدید unique',
                                                    backgroundColor: "rgba(199 ,239 ,153,0.2)",
                                                borderColor: "#72d384",
                                                borderWidth: 1,
                                                hoverBackgroundColor: "rgba(68,214,237,0.4)",
                                                hoverBorderColor: "rgba(14,187,214,1)",
                                                data: <?php echo json_encode($uniqueVisits); ?>
                                            }
                                            ]
                                        },
                                        options: {
                                            scales: {
                                                yAxes: [{
                                                    ticks: {
                                                        beginAtZero:true
                                                    }
                                                }]
                                            }
                                        }
                                    });
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
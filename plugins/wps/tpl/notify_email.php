<!doctype html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>ایمیل اطلاع رسانی وب سایت</title>
</head>
<style>

    body{
        background: #f9f9f9;
        margin:0;
        padding: 0;
        font-size:100%;
        font-family:Tahoma;
        direction: rtl;
    }
    .wrapper{
        width: 90%;
        background: #FFFFFF;
        margin: 20px auto;
        border:1px solid #eaeaea;
        -webkit-border-radius:5px;
        -moz-border-radius:5px;
        border-radius:5px;
        text-align: center;
    }
    .content{
        margin: 20px;
    }
</style>
<body>
    <div class="wrapper">
        <p>گزارش بازدید روزانه از وب سایت</p>
        <div class="content">
            <p>
                <span>بازدید کل امروز : </span>
                <span>#totalVisits#</span>
            </p>
            <p>
                <span>بازدید unique امروز : </span>
                <span>#uniqueVisits#</span>
            </p>
        </div>
        <?php //$email_content = get_option('wps_daily_report_email'); ?>
    </div>
</body>
</html>
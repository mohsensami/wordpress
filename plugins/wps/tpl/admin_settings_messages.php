<table class="form-table">
    <tr valign="top">
        <th scope="row">
            <label for="wps_daily_report_sms">متن پیامک گزارش روزانه : </label>
        </th>
        <td>

            <textarea name="wps_daily_report_sms"
                      id="wps_daily_report_sms"
                      cols="60" rows="10"
            ><?php echo $wps_daily_report_sms; ?></textarea>
            <div><span>کدهای قابل استفاده :</span><p>
                    <span>بازدید کل:</span>
                    <span>#totalVisits#</span>
                    <span>بازدید تک :</span>
                    <span>#uniqueVisits#</span>
                </p></div>
        </td>
    </tr>
    <tr valign="top">
        <th scope="row">
            <label for="wps_daily_report_email">محتوای ایمیل گزارش روزانه : </label>
        </th>
        <td>
                        <textarea name="wps_daily_report_email"
                                  id="wps_daily_report_email"
                                  cols="60" rows="10"
                        ><?php echo $wps_daily_report_email; ?></textarea>
            <div><span>کدهای قابل استفاده :</span><p>
                    <span>بازدید کل:</span>
                    <span>#totalVisits#</span>
                    <span>بازدید تک :</span>
                    <span>#uniqueVisits#</span>
                </p></div>
        </td>
    </tr>
</table>
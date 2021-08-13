<div class="wrap">
    
    <?php
        
        $hmcs_styleKey = 'hmcs_style_key';
        $hmcs_scriptKey = 'hmcs_script_key';
    
        if (isset($_POST['hmcs_save'])) {
            
            $hmcs_style = trim($_POST['hmcs_custom_style']);
            $hmcs_script = trim($_POST['hmcs_custom_script']);
            
            if ( update_option($hmcs_styleKey, $hmcs_style) || update_option($hmcs_scriptKey, $hmcs_script)) {
                echo '<div id="messaeg" class="updated">تنظیمات با موفقیت ذخیره شد.</div>';
            } else {
                echo '<div id="messaeg" class="error">اطلاعاتی ذخیره نشد.</div>';
            }
            
        }
        
        $hmcs_custom_style = get_option($hmcs_styleKey);
        $hmcs_custom_script = str_replace('\\', '', get_option($hmcs_scriptKey));
    
    ?>
    
    <h1>تنظیمات استایل</h1>
    <form action="" method="post">
        <table class="form-table">
            <tr>
                <th scope="row"><label for="cstyle">استایل سفارشی:</label></th>
                <td>
                    <textarea class="ltr" name="hmcs_custom_style" rows="10" cols="60"><?php echo $hmcs_custom_style ? $hmcs_custom_style : '';?></textarea>
                </td>
            </tr>
            
            <tr>
                <th scope="row"><label for="cscript">اسکریپت سفارشی:</label></th>
                <td>
                    <textarea class="ltr" name="hmcs_custom_script" rows="10" cols="60"><?php echo $hmcs_custom_script ? $hmcs_custom_script : '';?></textarea>
                </td>
            </tr>
            
            <tr>
                <th scope="row">
                    <input type="submit" value="ذخیره" name="hmcs_save" class="button-primary"/>
                </th>
                <td>
                </td>
            </tr>
            
        </table>
    </form>
</div>
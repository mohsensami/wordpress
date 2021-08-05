<?php
/*
 * plugin name: جملات ناب
 * Plugin URI: 
 * Author: محسن سامی
 * Author URI: 
 * Version: 1.0.0
 * Description: جملات ناب در فوتر سایت قرار میگیرد که می توانید با استایل دهی مکان آن را به راحتی تغییر دهید
 * Lincence: GPLv2
 * Licence URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html
 */
/*
Copyright (C) 2021  Mohsen Sami

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

defined('ABSPATH') || exit;

add_action( 'wp_footer', 'show_random_messages' );

function show_random_messages() {
    $messages = array(
        'هر کس که از هنر تاثیر پذیرفته باشد می داند که ارزش هنر به مثابه ی سرچشمه ی لذت و تسلی خاطر در زندگانی حدی ندارد. (زیگموند فروید)',
        'جائی‌که مردم از دولتشان بترسند، ظلم حاکم است؛ جائی‌که دولت از مردم بترسد، آزادی وجود دارد. (توماس جفرسون)',
        'مرگ به‌همگی ما لبخند می‌زند، تنها کاری که می‌توان انجام داد این است که لبخندش را با لبخند پاسخ گوییم. (مارکوس اورلیوس)',
        'ضعیف هرگز نمی‌تواند ببخشد. بخشش ویژگی نیرومند است. (مهاتما گاندی)',
        'اشخاص زیرک از بدبختی‌ها و گرفتاری‌های دیگران آگاه و بیدار می‌شوند، ولی اشخاص نادان از بلاهای خود عبرت نمی‌گیرند. (بنجامین فرانکلین)',
        'زندگی برای کسانی که فکر می‌کنند یک کمدی و برای کسانی که احساس می‌کنند یک تراژدی است. (هوراس والپول)',
        'ترس از عشق، ترس از زندگی است و آنان که از عشق دوری می‌کنند مردگانی بیش نیستند. (برتراند راسل)',
        'از کسانیکه میکوشند آرزوهایت را کوچک و بی ارزش جلوه دهند،دوری کن. مردم کوچک آرزوهای دیگران راکوچک میشمارند،ولی افرادبزرگ به تو میگویند که تو هم میتوانی بزرگ باشی',
        'زندگی مبارزه‌ای دائم بین فرد بودن و عضوی از جامعه بودن است. (شرمن الکسی)',
        'انسان به واسطه ضربان قلبش زنده نیست، حیات او به غذا و خوراک وابسته نیست، او به لطف خدا زنده است. (جی‌پی‌واسوانی)',
        'به جرأت می‌توان ادعا کرد که زندگانی حقیقی عده‌ای از مشاهیر و رجال بعد از مرگشان شروع شده‌است. (ساموئل اسمایلز)',
        'اندیشیدن در زندگی‌ مان نقش مهمی دارد. اندیشیدن به نگرش‌ مان شکل می‌دهد. با اندیشه‌ مان می‌توانیم زندگی‌ مان را دگرگون کنیم. (جی‌پی‌واسوانی)',
        'زیاد زیستن تقریباً آرزوی همه کس است، ولی خوب زیستن آرمان یک عده معدود. (لنگستون هیوز)',
    );
    $messageIndex = rand( 0, (count($messages) - 1 ));

    $messages = $messages[$messageIndex];
    $nl = PHP_EOL;

    echo '<p class="show_random_messages" style="position:fixed;bottom:0;padding:5px;background:#8c8c8c;color:#fff;z-index:99;width: 100%;text-align: center;font-size: 12px;">' . $messages . '</p>';
    // <script type="text/javascript">
    // jQuery(document).ready(function($){
    //     $(window).scroll(function(){
    //         if( $(this) ).scrollTop() > 50 ) {
    //             $('p.show_random_messages').fadeOut(2000);
    //         }
    //     });
    // });
    // </script>
}
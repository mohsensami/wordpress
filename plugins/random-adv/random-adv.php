<?php
/*
 * plugin name: تبلیغات تصادفی
 * Plugin URI: 
 * Author: محسن سامی
 * Author URI: 
 * Version: 1.0.0
 * Description: تبلیغات خود را در پوشه عکس ها قرار دهید
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

add_action( 'wp_footer', 'add_custom_ads' );

function add_custom_ads() {

$advs = array(
    array(
        'image'     =>      'adv-01.jpg',
        'link'      =>      'http://google.com',
        'title'     =>      'title 01'
    ),
    array(
        'image'     =>      'adv-02.jpg',
        'link'      =>      'http://google.com',
        'title'     =>      'title 02'
    ),
    array(
        'image'     =>      'adv-03.jpg',
        'link'      =>      'http://google.com',
        'title'     =>      'title 03'
    ),
    array(
        'image'     =>      'adv-04.jpg',
        'link'      =>      'http://google.com',
        'title'     =>      'title 04'
    ),
    array(
        'image'     =>      'adv-05.jpg',
        'link'      =>      'http://google.com',
        'title'     =>      'title 05'
    ),
    array(
        'image'     =>      'adv-06.jpg',
        'link'      =>      'http://google.com',
        'title'     =>      'title 06'
    ),
    array(
        'image'     =>      'adv-07.jpg',
        'link'      =>      'http://google.com',
        'title'     =>      'title 07'
    ),
    array(
        'image'     =>      'adv-08.jpg',
        'link'      =>      'http://google.com',
        'title'     =>      'title 08'
    ),
    array(
        'image'     =>      'adv-09.jpg',
        'link'      =>      'http://google.com',
        'title'     =>      'title 09'
    ),
    array(
        'image'     =>      'adv-10.jpg',
        'link'      =>      'http://google.com',
        'title'     =>      'title 10'
    )
);

    $imgaeIndex = rand(0, count($advs)-1);

    $image = plugins_url('img/' . $advs[$imgaeIndex]['image'] , __FILE__ );
    $link = $advs[$imgaeIndex]['link'];
    $title = $advs[$imgaeIndex]['title'];

    echo '<a style="position:fixed;left:0;bottom:0;z-index: 9;" href="' . $link .'"><img src="' . $image . '"></a>';

}
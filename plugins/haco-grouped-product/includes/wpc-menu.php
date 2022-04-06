<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WPCleverMenu' ) ) {
	class WPCleverMenu {
		function __construct() {
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		}

		function admin_menu() {
			add_menu_page(
				'WPClever',
				'محصولات گروهی',
				'manage_options',
				'wpclever',
				array( &$this, 'welcome_content' ),
				WPC_URI . 'assets/images/menu.svg',
				26
			);
			add_submenu_page( 'wpclever', 'درباره ما', 'درباره ما', 'manage_options', 'wpclever' );
		}

		function welcome_content() {
			?>
            <div class="wpclever_welcome_page wrap">
                <h1>پلاگین محصولات گروهی هاکوپیان</h1>
                <div class="notice">
					<h2 class="title">موارد توسعه داده شده</h2>
                    <p>
                        این پلاگین در بخش اضافه کردن محصولات بیش از 3 محصول و همچنین در نمایش آن به کاربران برای بیش از سه محصول و همچنین در قسمت ویژگی هایی که برای محصولات نمایش می دهد برای قسمت تنخوری که فقط یک تنخوری برای هر محصول بود
                    </p>
                    
					
					<table>
						<tr>
							<td><img src="<?php echo WPC_URI . 'assets/images/screenshot1.JPG'; ?>"></td>
							<td><img src="<?php echo WPC_URI . 'assets/images/screenshot2.JPG'; ?>"></td>
						</tr>
					</table>
			
                </div>
                
            </div>
			<?php
		}
	}

	new WPCleverMenu();
}
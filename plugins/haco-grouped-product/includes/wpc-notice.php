<?php
defined( 'ABSPATH' ) || exit;

$theme = wp_get_theme();

if ( ! empty( $theme['Name'] ) && ( strpos( $theme['Name'], 'WPC' ) !== false ) ) {
	return;
}

if ( ! class_exists( 'WPCleverNotice' ) ) {
	class WPCleverNotice {
		function __construct() {
			add_action( 'admin_notices', array( $this, 'admin_notice' ) );
			add_action( 'admin_init', array( $this, 'notice_ignore' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'notice_scripts' ) );
		}

		function notice_scripts() {
			wp_enqueue_style( 'wpclever-notice', WOOSG_URI . 'assets/css/notice.css' );
		}

		function admin_notice() {
			global $current_user, $current_screen;
			$user_id = $current_user->ID;

			if ( ! $current_screen || ! isset( $current_screen->base ) || ( strpos( $current_screen->base, 'wpclever' ) === false ) ) {
				return;
			}

			if ( ! get_user_meta( $user_id, 'wpclever_wpcstore_ignore', true ) ) {
				?>
                <div class="wpclever-notice notice">
                    <div class="wpclever-notice-thumbnail">
                        <a href="<?php echo home_url() ?>" target="_blank">
                            <img src="<?php echo esc_url( WOOSG_URI . 'assets/images/logo.png' ); ?>" alt="WPCstore"/>
                        </a>
                    </div>
                    <div class="wpclever-notice-text">
                        <h3>پلاگین محصولات گروهی هاکوپیان</h3>
                        <ul class="wpclever-notice-ul">
                            <li>
                                <a href="https://hacoupian.net/" target="_blank">
                                    <span class="dashicons dashicons-desktop"></span> مشاهده سایت
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo home_url() ?>" target="_blank">
                                    <span class="dashicons dashicons-external"></span> فروشگاه
                                </a>
                            </li>
                            <li>
								<?php
								if ( function_exists( 'wc_get_current_admin_url' ) ) {
									$ignore_url = add_query_arg( 'wpclever_wpcstore_ignore', '1', wc_get_current_admin_url() );
								} else {
									$ignore_url = admin_url( '?wpclever_wpcstore_ignore=1' );
								}
								?>
                                <a href="<?php echo esc_url( $ignore_url ); ?>"
                                   class="dashicons-dismiss-icon">
                                    <span class="dashicons dashicons-welcome-comments"></span> پنهان
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
				<?php
			}
		}

		function notice_ignore() {
			global $current_user;
			$user_id = $current_user->ID;

			if ( isset( $_GET['wpclever_wpcstore_ignore'] ) ) {
				if ( $_GET['wpclever_wpcstore_ignore'] == '1' ) {
					update_user_meta( $user_id, 'wpclever_wpcstore_ignore', 'true' );
				} else {
					delete_user_meta( $user_id, 'wpclever_wpcstore_ignore' );
				}
			}
		}
	}

	new WPCleverNotice();
}
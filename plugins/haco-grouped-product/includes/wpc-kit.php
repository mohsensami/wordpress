<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WPCleverKit' ) ) {
	class WPCleverKit {
		protected static $_plugins = array(
			'woo-product-bundle'             => array(
				'name' => 'WPC Product Bundles for WooCommerce',
				'slug' => 'woo-product-bundle',
				'file' => 'wpc-product-bundles.php'
			),
			'wpc-composite-products'         => array(
				'name' => 'WPC Composite Products for WooCommerce',
				'slug' => 'wpc-composite-products',
				'file' => 'wpc-composite-products.php'
			),
			'wpc-grouped-product'            => array(
				'name' => 'WPC Grouped Product for WooCommerce',
				'slug' => 'wpc-grouped-product',
				'file' => 'wpc-grouped-product.php'
			),
			'woo-bought-together'            => array(
				'name' => 'WPC Frequently Bought Together for WooCommerce',
				'slug' => 'woo-bought-together',
				'file' => 'wpc-frequently-bought-together.php'
			),
			'woo-smart-compare'              => array(
				'name' => 'WPC Smart Compare for WooCommerce',
				'slug' => 'woo-smart-compare',
				'file' => 'wpc-smart-compare.php'
			),
			'woo-smart-quick-view'           => array(
				'name' => 'WPC Smart Quick View for WooCommerce',
				'slug' => 'woo-smart-quick-view',
				'file' => 'wpc-smart-quick-view.php'
			),
			'woo-smart-wishlist'             => array(
				'name' => 'WPC Smart Wishlist for WooCommerce',
				'slug' => 'woo-smart-wishlist',
				'file' => 'wpc-smart-wishlist.php'
			),
			'woo-fly-cart'                   => array(
				'name' => 'WPC Fly Cart for WooCommerce',
				'slug' => 'woo-fly-cart',
				'file' => 'wpc-fly-cart.php'
			),
			'wpc-force-sells'                => array(
				'name' => 'WPC Force Sells for WooCommerce',
				'slug' => 'wpc-force-sells',
				'file' => 'wpc-force-sells.php'
			),
			'woo-added-to-cart-notification' => array(
				'name' => 'WPC Added To Cart Notification for WooCommerce',
				'slug' => 'woo-added-to-cart-notification',
				'file' => 'wpc-added-to-cart-notification.php'
			),
			'wpc-ajax-add-to-cart'           => array(
				'name' => 'WPC AJAX Add to Cart for WooCommerce',
				'slug' => 'wpc-ajax-add-to-cart',
				'file' => 'wpc-ajax-add-to-cart.php'
			),
			'wpc-product-quantity'           => array(
				'name' => 'WPC Product Quantity for WooCommerce',
				'slug' => 'wpc-product-quantity',
				'file' => 'wpc-product-quantity.php'
			),
			'wpc-variations-radio-buttons'   => array(
				'name' => 'WPC Variations Radio Buttons for WooCommerce',
				'slug' => 'wpc-variations-radio-buttons',
				'file' => 'wpc-variations-radio-buttons.php'
			),
			'wpc-product-tabs'               => array(
				'name' => 'WPC Product Tabs for WooCommerce',
				'slug' => 'wpc-product-tabs',
				'file' => 'wpc-product-tabs.php'
			),
			'woo-product-timer'              => array(
				'name' => 'WPC Product Timer for WooCommerce',
				'slug' => 'woo-product-timer',
				'file' => 'wpc-product-timer.php'
			),
			'wpc-countdown-timer'            => array(
				'name' => 'WPC Countdown Timer for WooCommerce',
				'slug' => 'wpc-countdown-timer',
				'file' => 'wpc-countdown-timer.php'
			),
			'wpc-product-table'              => array(
				'name' => 'WPC Product Table for WooCommerce',
				'slug' => 'wpc-product-table',
				'file' => 'wpc-product-table.php'
			),
			'wpc-name-your-price'            => array(
				'name' => 'WPC Name Your Price for WooCommerce',
				'slug' => 'wpc-name-your-price',
				'file' => 'wpc-name-your-price.php'
			)
		);

		function __construct() {
			// admin scripts
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

			// settings page
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		}

		function admin_scripts() {
			wp_enqueue_style( 'wpckit-backend', WPC_URI . 'assets/kit/css/backend.css' );
			wp_enqueue_script( 'wpckit-backend', WPC_URI . 'assets/kit/js/backend.js', array(
				'jquery'
			) );
		}



		function settings_page() {
			add_thickbox();
			?>
            
			<?php
		}

		public function is_plugin_installed( $plugin, $premium = false ) {
			if ( $premium ) {
				return file_exists( WP_PLUGIN_DIR . '/' . $plugin['slug'] . '-premium/' . $plugin['file'] );
			} else {
				return file_exists( WP_PLUGIN_DIR . '/' . $plugin['slug'] . '/' . $plugin['file'] );
			}
		}

		public function is_plugin_active( $plugin, $premium = false ) {
			if ( $premium ) {
				return is_plugin_active( $plugin['slug'] . '-premium/' . $plugin['file'] );
			} else {
				return is_plugin_active( $plugin['slug'] . '/' . $plugin['file'] );
			}
		}

		public function install_plugin_link( $plugin ) {
			return wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=' . $plugin['slug'] ), 'install-plugin_' . $plugin['slug'] );
		}

		public function activate_plugin_link( $plugin, $premium = false ) {
			if ( $premium ) {
				return wp_nonce_url( admin_url( 'admin.php?page=wpclever-kit&action=activate&plugin=' . $plugin['slug'] . '-premium/' . $plugin['file'] . '#' . $plugin['slug'] ), 'activate-plugin_' . $plugin['slug'] . '-premium/' . $plugin['file'] );
			} else {
				return wp_nonce_url( admin_url( 'admin.php?page=wpclever-kit&action=activate&plugin=' . $plugin['slug'] . '/' . $plugin['file'] . '#' . $plugin['slug'] ), 'activate-plugin_' . $plugin['slug'] . '/' . $plugin['file'] );
			}
		}

		public function deactivate_plugin_link( $plugin, $premium = false ) {
			if ( $premium ) {
				return wp_nonce_url( admin_url( 'admin.php?page=wpclever-kit&action=deactivate&plugin=' . $plugin['slug'] . '-premium/' . $plugin['file'] . '#' . $plugin['slug'] ), 'deactivate-plugin_' . $plugin['slug'] . '-premium/' . $plugin['file'] );
			} else {
				return wp_nonce_url( admin_url( 'admin.php?page=wpclever-kit&action=deactivate&plugin=' . $plugin['slug'] . '/' . $plugin['file'] . '#' . $plugin['slug'] ), 'deactivate-plugin_' . $plugin['slug'] . '/' . $plugin['file'] );
			}
		}
	}

	new WPCleverKit();
}

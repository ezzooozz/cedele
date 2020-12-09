<?php
/*
 * Plugin Name: Finale - WooCommerce Sales Countdown Timer & Discount Plugin
 * Plugin URI: https://xlplugins.com/finale-woocommerce-sales-countdown-timer-discount-plugin/
 * Description: Finale lets you create scheduled one time or recurring campaigns. It induces urgency with visual elements such as Countdown Timer and Counter Bar to motivate users to place an order.
 * Version: 2.17.1
 * Author: XLPlugins
 * Author URI: https://www.xlplugins.com
 * License: GPLv3 or later
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: finale-woocommerce-sales-countdown-timer-discount-plugin
 * XL: True
 * XLTOOLS: True
 * Requires at least: 4.2.1
 * Tested up to: 5.3.2
 * WC requires at least: 3.0
 * WC tested up to: 3.9.1
 *
 * Finale - WooCommerce Sales Countdown Timer & Discount Plugin is free software.
 * You can redistribute it and/or modify it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * Finale - WooCommerce Sales Countdown Timer & Discount Plugin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Finale - WooCommerce Sales Countdown Timer & Discount Plugin. If not, see <http://www.gnu.org/licenses/>.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WCCT_Core' ) ) :

	class WCCT_Core {

		/** @var WCCT_Core */
		public static $_instance = null;
		private static $_registered_entity = array(
			'active'   => array(),
			'inactive' => array(),
		);

		/** @var WCCT_Campaign */
		public $public;

		/** @var WCCT_Appearance */
		public $appearance;

		/** @var WCCT_Cart */
		public $cart;

		/** @var WCCT_stock */
		public $stock;

		/** @var WCCT_discount */
		public $discount;

		/** @var WCCT_XL_Support */
		public $xl_support;

		/** @var WCCT_Shortcode */
		public $shortcode;

		/** @var WCCT_XL_Coupons */
		public $coupons;

		/** @var bool Dependency check property */
		private $is_dependency_exists = true;
		public $is_mobile = true;
		public $is_tablet = true;
		public $is_desktop = true;

		public function __construct() {

			/** Load important variables and constants */
			$this->define_plugin_properties();

			/** Load dependency classes like woo-functions.php */
			$this->load_dependencies_support();

			/** Run dependency check to check if dependency available */
			$this->do_dependency_check();
			if ( $this->is_dependency_exists ) {

				/** Loads all the hooks */
				$this->load_hooks();

				/** Initiates and loads XL start file */
				$this->load_xl_core_classes();

				/** Include common classes */
				$this->include_commons();

				/** Initialize common hooks and functions */
				$this->initialize_common();

				/** Maybe load admin if admin screen */
				$this->maybe_load_admin();
			}
		}

		/** Defining constants */
		public function define_plugin_properties() {
			define( 'WCCT_VERSION', '2.17.1' );
			define( 'WCCT_MIN_WC_VERSION', '3.0' );
			define( 'WCCT_FULL_NAME', 'Finale - WooCommerce Sales Countdown Timer & Discount Plugin' );
			define( 'WCCT_PLUGIN_FILE', __FILE__ );
			define( 'WCCT_PLUGIN_DIR', __DIR__ );
			define( 'WCCT_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
			define( 'WCCT_PURCHASE', 'xlplugin' );
			define( 'WCCT_SHORT_SLUG', 'wcct' );
			define( 'WCCT_SLUG', 'finale-woocommerce-sales-countdown-timer-discount-plugin' );
		}

		public function load_dependencies_support() {
			/** Setting up WooCommerce Dependency Classes */
			require_once( plugin_dir_path( WCCT_PLUGIN_FILE ) . 'woo-includes/woo-functions.php' );
		}

		public function do_dependency_check() {
			if ( ! wcct_is_woocommerce_active() ) {
				add_action( 'admin_notices', array( $this, 'wcct_wc_not_installed_notice' ) );
				$this->is_dependency_exists = false;
			}
		}

		public function load_hooks() {
			$this->wcct_mobile_check();

			/** Initializing Functionality */
			add_action( 'plugins_loaded', array( $this, 'wcct_init' ), 0 );

			add_action( 'plugins_loaded', array( $this, 'wcct_register_classes' ), 1 );
			/** Initialize Localization */
			add_action( 'init', array( $this, 'wcct_init_localization' ) );

			/** Redirecting Plugin to the settings page after activation */
			add_action( 'activated_plugin', array( $this, 'wcct_settings_redirect' ) );

			add_action( 'xl_loaded', array( $this, 'wcct_load_xl_core_require_files' ), 10, 1 );
		}

		public function load_xl_core_classes() {
			/** Setting Up XL Core */
			require_once( plugin_dir_path( WCCT_PLUGIN_FILE ) . 'start.php' );
		}

		public function include_commons() {
			/** Loading Common Class */
			include_once plugin_dir_path( WCCT_PLUGIN_FILE ) . 'WCCT_EDD_License_Handler.php';

			require plugin_dir_path( WCCT_PLUGIN_FILE ) . 'includes/wcct-common.php';
			require plugin_dir_path( WCCT_PLUGIN_FILE ) . 'compatibilities/class-wcct-compatibilities.php';
			require plugin_dir_path( WCCT_PLUGIN_FILE ) . 'includes/wcct-xl-support.php';
		}

		public function initialize_common() {
			/** Firing Init to init basic Functions */
			WCCT_Common::init();
		}

		public function maybe_load_admin() {
			/** Dashboard and Administrative Functionality */
			if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {

				require_once( plugin_dir_path( WCCT_PLUGIN_FILE ) . 'admin/wcct-admin.php' );
				require_once( plugin_dir_path( WCCT_PLUGIN_FILE ) . 'admin/class-wcct-wizard.php' );
			}
		}

		public function wcct_register_classes() {
			$load_classes = self::get_registered_class();

			if ( is_array( $load_classes ) && count( $load_classes ) > 0 ) {
				foreach ( $load_classes as $access_key => $class ) {
					$this->$access_key = $class::get_instance();
				}
				do_action( 'wcct_loaded' );
			}
		}

		public static function get_registered_class() {
			return self::$_registered_entity['active'];
		}

		public static function register( $short_name, $class, $overrides = null ) {
			/** Ignore classes that have been marked as inactive */
			if ( in_array( $class, self::$_registered_entity['inactive'], true ) ) {
				return;
			}

			/** Mark classes as active. Override existing active classes if they are supposed to be overridden */
			$index = array_search( $overrides, self::$_registered_entity['active'], true );
			if ( false !== $index ) {
				self::$_registered_entity['active'][ $index ] = $class;
			} else {
				self::$_registered_entity['active'][ $short_name ] = $class;
			}

			/** Mark overridden classes as inactive. */
			if ( ! empty( $overrides ) ) {
				self::$_registered_entity['inactive'][] = $overrides;
			}
		}

		/**
		 * @return null|WCCT_Core
		 */
		public static function get_instance() {
			if ( null === self::$_instance ) {
				self::$_instance = new self;
			}

			return self::$_instance;
		}

		public function wcct_init_localization() {
			load_plugin_textdomain( 'finale-woocommerce-sales-countdown-timer-discount-plugin', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
		}

		/**
		 * Added redirection on plugin activation
		 *
		 * @param $plugin
		 */
		public function wcct_settings_redirect( $plugin ) {
			if ( ! defined( 'WP_CLI' ) && wcct_is_woocommerce_active() && class_exists( 'WooCommerce' ) ) {
				if ( plugin_basename( __FILE__ ) === $plugin ) {
					wp_safe_redirect( add_query_arg( array(
						'page' => 'wc-settings',
						'tab'  => WCCT_Common::get_wc_settings_tab_slug(),
					), admin_url( 'admin.php' ) ) );
					exit;
				}
			}
		}

		/**
		 * Checking WooCommerce dependency and then loads further
		 */
		public function wcct_init() {
			if ( wcct_is_woocommerce_active() && class_exists( 'WooCommerce' ) ) {

				global $woocommerce;
				if ( ! version_compare( $woocommerce->version, WCCT_MIN_WC_VERSION, '>=' ) ) {
					add_action( 'admin_notices', array( $this, 'wcct_wc_version_check_notice' ) );

					return;
				}
				if ( isset( $_GET['wcct_disable'] ) && 'yes' === $_GET['wcct_disable'] && is_user_logged_in() && current_user_can( 'administrator' ) ) { // WPCS: input var ok, CSRF ok.
					return;
				}

				require plugin_dir_path( WCCT_PLUGIN_FILE ) . 'includes/wcct-triggers-data.php';
				if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
				} else {
					require plugin_dir_path( WCCT_PLUGIN_FILE ) . 'includes/wcct-api.php';

					require plugin_dir_path( WCCT_PLUGIN_FILE ) . 'includes/wcct-campaign-public.php';
					require plugin_dir_path( WCCT_PLUGIN_FILE ) . 'includes/wcct-appearance.php';
					require plugin_dir_path( WCCT_PLUGIN_FILE ) . 'includes/wcct-cart.php';
					require plugin_dir_path( WCCT_PLUGIN_FILE ) . 'includes/wcct-discount.php';
					require plugin_dir_path( WCCT_PLUGIN_FILE ) . 'includes/wcct-shortcodes.php';
					require plugin_dir_path( WCCT_PLUGIN_FILE ) . 'includes/wcct-stock.php';
					require plugin_dir_path( WCCT_PLUGIN_FILE ) . 'includes/wcct-xl-coupons.php';
				}
				require plugin_dir_path( WCCT_PLUGIN_FILE ) . 'includes/wcct-themes-helper.php';

				require_once plugin_dir_path( WCCT_PLUGIN_FILE ) . 'includes/wcct-merge-tags.php';
				require_once plugin_dir_path( WCCT_PLUGIN_FILE ) . 'includes/wcct-events.php';
			}
		}

		public function wcct_mobile_check() {
			require plugin_dir_path( WCCT_PLUGIN_FILE ) . 'includes/wcct-mobile-detect.php';

			$wcct_mobile_detect = new WCCT_Mobile_Detect;
			if ( $wcct_mobile_detect->isMobile() ) {
				/** mobile or tablet */
				if ( $wcct_mobile_detect->isTablet() ) {
					$this->is_mobile  = false;
					$this->is_tablet  = true;
					$this->is_desktop = false;
				} else {
					$this->is_mobile  = true;
					$this->is_tablet  = false;
					$this->is_desktop = false;
				}
			} else {
				/** desktop */
				$this->is_mobile  = false;
				$this->is_tablet  = false;
				$this->is_desktop = true;
			}
		}

		/**
		 * Registering Notices
		 */
		public function wcct_wc_version_check_notice() {
			?>
            <div class="error">
                <p>
					<?php
					/* translators: %1$s: Min required woocommerce version */
					printf( __( '<strong> Attention: </strong>Finale requires WooCommerce version %1$s or greater. Kindly update the WooCommerce plugin.', 'finale-woocommerce-sales-countdown-timer-discount-plugin' ), WCCT_MIN_WC_VERSION );
					?>
                </p>
            </div>
			<?php
		}

		public function wcct_wc_not_installed_notice() {
			?>
            <div class="error">
                <p>
					<?php
					echo __( '<strong> Attention: </strong>WooCommerce is not installed or activated. Finale is a WooCommerce Extension and would only work if WooCommerce is activated. Please install the WooCommerce Plugin first.', 'finale-woocommerce-sales-countdown-timer-discount-plugin' );
					?>
                </p>
            </div>
			<?php
		}

		public function wcct_load_xl_core_require_files( $get_global_path ) {
			if ( file_exists( $get_global_path . 'includes/class-xl-cache.php' ) ) {
				require_once $get_global_path . 'includes/class-xl-cache.php';
			}
			if ( file_exists( $get_global_path . 'includes/class-xl-transients.php' ) ) {
				require_once $get_global_path . 'includes/class-xl-transients.php';
			}
			if ( file_exists( $get_global_path . 'includes/class-xl-file-api.php' ) ) {
				require_once $get_global_path . 'includes/class-xl-file-api.php';
			}

		}
	}

endif;

/**
 * Global Common function to load all the classes
 *
 * @param bool $debug
 *
 * @return WCCT_Core
 */
if ( ! function_exists( 'WCCT_Core' ) ) {

	function WCCT_Core( $debug = false ) {
		return WCCT_Core::get_instance();
	}
}

require plugin_dir_path( __FILE__ ) . 'includes/wcct-logging.php';

/**
 * Collect PHP fatal errors and save it in the log file so that it can be later viewed
 * @see register_shutdown_function
 */
if ( ! function_exists( 'xlplugins_collect_errors' ) ) {
	function xlplugins_collect_errors() {
		$error = error_get_last();
		if ( E_ERROR === $error['type'] ) {
			xlplugins_force_log( $error['message'] . PHP_EOL, 'fatal-errors.txt' );
		}
	}

	register_shutdown_function( 'xlplugins_collect_errors' );
}

$GLOBALS['WCCT_Core'] = WCCT_Core();

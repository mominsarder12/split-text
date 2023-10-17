<?php

/**
 * Plugin Name: Split Text
 * Description: Simple Text Split animation plugin.
 * Version:     1.0.0
 * Author:      Momin Sarder
 * Author URI:  https://developers.elementor.com/
 * Text Domain: stp
 * 
 * Elementor tested up to: 3.15.0
 * Elementor Pro tested up to: 3.15.0
 */

if (!defined('ABSPATH')) {
	die(__('direct access is not allowed', 'stp'));
}

function plugin_scripts_enqueue()
{
	/**
	 * split assets
	 * 
	 */
	wp_enqueue_style('split-main', plugins_url('/assets/css/split-main.css', __FILE__), array(), '1.0.0', 'all');


	wp_enqueue_script('ms_custom_gspa-main', plugins_url('/assets/js/ms_gsap.min.js', __FILE__), array(), '1.0.0', true);
	wp_enqueue_script('ms_custom-scroll-triger', plugins_url('/assets/js/ms_spliting.min.js', __FILE__), array(), '1.0.0', true);
	wp_enqueue_script('ms_custom-split-text', plugins_url('/assets/js/ms_trigger.min.js', __FILE__), array(), '1.0.0', true);
	wp_enqueue_script('ms_custom-split-main', plugins_url('/assets/js/spite-main.js', __FILE__), array(), '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'plugin_scripts_enqueue');

function split_require_load()
{

	// Load plugin file
	require_once(__DIR__ . '/includes/widgets-manager.php');
	require_once(__DIR__ . '/includes/controls-manager.php');
}
add_action('plugins_loaded', 'split_require_load');

// final classes

final class Plugin
{

	/**
	 * Addon Version
	 *
	 * @since 1.0.0
	 * @var string The addon version.
	 */
	const VERSION = '1.0.0';

	/**
	 * Minimum Elementor Version
	 *
	 * @since 1.0.0
	 * @var string Minimum Elementor version required to run the addon.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '3.7.0';

	/**
	 * Minimum PHP Version
	 *
	 * @since 1.0.0
	 * @var string Minimum PHP version required to run the addon.
	 */
	const MINIMUM_PHP_VERSION = '7.3';


	/**
	 * Instance
	 *
	 * @since 1.0.0
	 * @access private
	 * @static
	 * @var \Elementor_Test_Addon\Plugin The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 * @return \Elementor_Test_Addon\Plugin An instance of the class.
	 */
	public static function instance()
	{

		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructor
	 *
	 * Perform some compatibility checks to make sure basic requirements are meet.
	 * If all compatibility checks pass, initialize the functionality.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct()
	{

		if ($this->is_compatible()) {
			add_action('elementor/init', [$this, 'init']);
		}
	}

	/**
	 * Compatibility Checks
	 *
	 * Checks whether the site meets the addon requirement.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function is_compatible()
	{

		// Check if Elementor is installed and activated
		if (!did_action('elementor/loaded')) {
			add_action('admin_notices', [$this, 'admin_notice_missing_main_plugin']);
			return false;
		}

		// Check for required Elementor version
		if (!version_compare(ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=')) {
			add_action('admin_notices', [$this, 'admin_notice_minimum_elementor_version']);
			return false;
		}

		// Check for required PHP version
		if (version_compare(PHP_VERSION, self::MINIMUM_PHP_VERSION, '<')) {
			add_action('admin_notices', [$this, 'admin_notice_minimum_php_version']);
			return false;
		}

		return true;
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_missing_main_plugin()
	{

		if (isset($_GET['activate'])) unset($_GET['activate']);

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__('"%1$s" requires "%2$s" to be installed and activated.', 'stp'),
			'<strong>' . esc_html__('Split Heading addon', 'stp') . '</strong>',
			'<strong>' . esc_html__('Elementor', 'stp') . '</strong>'
		);

		printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version()
	{

		if (isset($_GET['activate'])) unset($_GET['activate']);

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'stp'),
			'<strong>' . esc_html__('Split Heading addon', 'stp') . '</strong>',
			'<strong>' . esc_html__('Elementor', 'stp') . '</strong>',
			self::MINIMUM_ELEMENTOR_VERSION
		);

		printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required PHP version.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_minimum_php_version()
	{

		if (isset($_GET['activate'])) unset($_GET['activate']);

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'stp'),
			'<strong>' . esc_html__('Split Heading addon', 'stp') . '</strong>',
			'<strong>' . esc_html__('PHP', 'stp') . '</strong>',
			self::MINIMUM_PHP_VERSION
		);

		printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
	}
	/**
	 * Initialize
	 *
	 * Load the addons functionality only after Elementor is initialized.
	 *
	 * Fired by `elementor/init` action hook.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function init()
	{
		load_plugin_textdomain('stp');

		add_action('elementor/widgets/register', [$this, 'register_widgets']);
		// add_action( 'elementor/controls/register', [ $this, 'register_controls' ] );


	}

	/**
	 * Register Widgets
	 *
	 * Load widgets files and register new Elementor widgets.
	 *
	 * Fired by `elementor/widgets/register` action hook.
	 *
	 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
	 */
	public function register_widgets($widgets_manager)
	{

		require_once(__DIR__ . '/includes/widgets/split-text.php');
		

		$widgets_manager->register(new SplitText());

	}
}

\Plugin::instance();

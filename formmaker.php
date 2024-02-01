<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://https://formmaker.co.in/
 * @since             1.0.0
 * @package           Formmaker
 *
 * @wordpress-plugin
 * Plugin Name:       Hubspot Multi Step FormMaker
 * Plugin URI:        https://https://formmaker.co.in/
 * Description:       Transform your form-filling experience with the brilliant Multi-Step Form HubSpot plugin! 🌟 Elevate your interactions with an exciting and efficient transformation that takes your WordPress forms to new heights. 🚀

 * Version:           1.0.0
 * Author:            Ecoweb
 * Author URI:        https://https://formmaker.co.in//
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       formmaker
 * Domain Path:       /languages
 */


// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'FORMMAKER_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-formmaker-activator.php
 */
function activate_formmaker() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-formmaker-activator.php';
	Formmaker_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-formmaker-deactivator.php
 */
function deactivate_formmaker() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-formmaker-deactivator.php';
	Formmaker_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_formmaker' );
register_deactivation_hook( __FILE__, 'deactivate_formmaker' );


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-formmaker.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function formmaker_shortcode_function($atts) {
    // Extract shortcode attributes
    $atts = shortcode_atts(
        array(
            'formid' => '', // Default value if not provided
        ),
        $atts,
        'formmaker'
    );

    // Sanitize and get the formid
    $formid = sanitize_text_field($atts['formid']);

    // Check if the formid is provided
    if (empty($formid)) {
        return '<p>Error: Please provide a formid attribute.</p>';
    }

    // Enqueue the JavaScript file
    wp_enqueue_script('formmaker-script', 'https://formmaker.co.in/embed/formaker.js', array(), null, true);

	// Replace 'formmaker-style' with a unique prefix for your plugin
    wp_enqueue_style('formmaker-style',   'https://formmaker.co.in/embed/formaker.css', array(), FORMMAKER_VERSION);		

    // Output the script and div with the dynamic formid
    ob_start();
    ?>
    <script type="module">
        document.addEventListener('DOMContentLoaded', function () {
            const script = document.createElement('script');
            script.type = 'module';
            script.src = 'https://formmaker.co.in/embed/formaker.js';
            document.head.appendChild(script);

            const rootDiv = document.getElementById('root');
            if (rootDiv) {
                rootDiv.setAttribute('data-formid', '<?php echo esc_attr($formid); ?>');
            }
        });
    </script>

    <div id="root" data-formid="<?php echo esc_attr($formid); ?>"></div>
    <?php
    return ob_get_clean();
}

// Register the shortcode
add_shortcode('formmaker', 'formmaker_shortcode_function');

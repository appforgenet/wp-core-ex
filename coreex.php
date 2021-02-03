<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.app-forge.net
 * @since             1.0.0
 * @package           Coreex
 *
 * @wordpress-plugin
 * Plugin Name:       App-Forge CoreEx
 * Plugin URI:        https://www.app-forge.net/wordpress/coreex
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            App-Forge
 * Author URI:        https://www.app-forge.net
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       coreex
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'COREEX_VERSION', '1.0.0' );
define( 'COREEX_PLUGIN_BASE', plugin_basename(__FILE__) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-coreex-activator.php
 */
function activate_coreex() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-coreex-activator.php';
	Coreex_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-coreex-deactivator.php
 */
function deactivate_coreex() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-coreex-deactivator.php';
	Coreex_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_coreex' );
register_deactivation_hook( __FILE__, 'deactivate_coreex' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-coreex.php';
require plugin_dir_path( __FILE__ ) . 'includes/models/wpcore.php'; 

function coreex_autoload($class_name)
{
    
    //die;

    // if( strpos( 'stdClass',$class_name) !== false)
    // {
    //     return;
    // }
    // else 
    if ( 'appforge\coreex' === substr( $class_name, 0, 15 ) ) 
    {
        // echo $class_name.'<br />';

        $path = substr( strtolower($class_name), 16 );
        $parts = explode('\\', $path);
        $path = dirname( __FILE__ );

        foreach($parts as $part)
            $path .= '/'.$part;

        //$file     = $class_name;
        //$path     = dirname( __FILE__ ) . '/includes/models/';
        //$filepath = $path . $file . '.php';
        $classfile = $path.'.php';

        // If we didn't match one of our rules, bail!
        if ( ! file_exists( $classfile ) ) {
            return;
        }

        require $classfile;
    }
}

spl_autoload_register( 'coreex_autoload' );

/** Required core files */
// require plugin_dir_path( __FILE__ ) . 'includes/request.php';
// require plugin_dir_path( __FILE__ ) . 'includes/database.php';
// require plugin_dir_path( __FILE__ ) . 'includes/wpcore.php';
// require plugin_dir_path( __FILE__ ) . 'includes/generator/generator.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_coreex() {

	$plugin = new Coreex();
	$plugin->run();

}
appforge\coreex\includes\models\WPCore::init();
run_coreex();

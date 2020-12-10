<?php
/**
 * Plugin Name: Newmoji
 * Plugin URI: https://github.com/BenjaminGuV/Newmoji
 * Description: Your post generates emotions, count the reactions with this simple plugin | Tus post genera emociones, contabiliza las reacciones con este sencillo plugin
 * Version: 1.0.0
 * Requires at least: 5.5.3
 * Requires PHP: 7.2.24
 * Author: Benjamin Guerrero
 * Author URI: https://nanos.pw/
 * License: MIT
 * License URI: https://opensource.org/licenses/MIT
*/



//PATH
define( 'NWMJ_NEWMOJI__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'NWMJ_NEWMOJI__PLUGIN_URL', plugin_dir_url( __FILE__ ) );


//includes libs functions
require_once plugin_dir_path(__FILE__) . 'includes/newmoji-functions.php';

//menu admin
add_action( 'admin_menu', 'nwmj_newmoji_add_my_admin_link' );

//print html
add_action( 'the_content', 'nwmj_newmoji_print_html' );

 
//load css and js
add_action('wp_enqueue_scripts', 'nwmj_newmoji_callback_scripts');

//ajax request
// Hook para usuarios no logueados
add_action('wp_ajax_nopriv_save_newmoji_ajax', 'nwmj_newmoji_save_ajax');

// Hook para usuarios logueados
add_action('wp_ajax_save_newmoji_ajax', 'nwmj_newmoji_save_ajax');



//install tables
register_activation_hook(__FILE__, 'nwmj_newmoji_installer');


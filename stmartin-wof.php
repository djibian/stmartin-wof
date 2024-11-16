<?php

/**
 * Plugin Name: stmartin-wof
 * Description: Wordpress Object Framework Plugin to help developing custom post types or custom taxonomies.
 * Author: Emmanuel BLANCHARD et Oz WP team S2
 * Version: 0.1.0
 *
 * Ce fichier porte le nom du plugin (du répertoire du plugin)
 * => C'est le point d'entrée du plugin
 */

use StMartinWof\Plugin;

require __DIR__ .'/autoload.php';

// Chargement du fichier de langue lors du chargement du plugin
function stmartin_wof_init() {
    $plugin_rel_path = basename( dirname( __FILE__ ) ) . '/languages'; /* Relative to WP_PLUGIN_DIR */
    load_plugin_textdomain( 'stmartin-wof', false, $plugin_rel_path );
}
add_action('init', 'stmartin_wof_init', 1);

// $plugin = new Plugin();
// $plugin->register();


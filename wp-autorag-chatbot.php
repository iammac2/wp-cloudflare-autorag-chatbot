
<?php
/*
Plugin Name: WP Cloudflare AutoRAG Chatbot
Description: Adds a shortcode that renders a chatbot UI backed by Cloudflare AutoRAG. Includes a server-side proxy to avoid exposing your API token.
Version: 1.3
Author: neuno.ai
Author URI: https://neuno.ai
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: autorag-chatbot
Domain Path: /languages
*/

defined( 'ABSPATH' ) || exit;

define( 'AUTORAG_CB_DIR', plugin_dir_path( __FILE__ ) );
define( 'AUTORAG_CB_URL', plugin_dir_url( __FILE__ ) );

require_once AUTORAG_CB_DIR . 'settings.php';
require_once AUTORAG_CB_DIR . 'proxy.php';

/**
 * Enqueue front‑end assets.
 */
function autorag_chatbot_enqueue_assets() {
    wp_enqueue_style(
        'autorag-chatbot-css',
        AUTORAG_CB_URL . 'chatbot.css',
        [],
        '1.3'
    );

    wp_enqueue_script(
        'autorag-chatbot-js',
        AUTORAG_CB_URL . 'chatbot.js',
        [],
        '1.3',
        true
    );

    wp_localize_script(
        'autorag-chatbot-js',
        'AutoRAGConfig',
        [
            'endpoint'    => esc_url_raw( rest_url( 'autorag/v1/search' ) ),
            'placeholder' => esc_html__( 'Ask a question…', 'autorag-chatbot' ),
        ]
    );
}
add_action( 'wp_enqueue_scripts', 'autorag_chatbot_enqueue_assets' );

/**
 * Shortcode that renders the chat UI.
 */
function autorag_chatbot_shortcode() {
    ob_start();
    ?>
    <div id="autorag-chatbot">
        <div id="chat-window" style="max-height:300px;overflow:auto;border:1px solid #ddd;padding:10px"></div>
        <input type="text" id="chat-input" placeholder="<?php echo esc_attr__( 'Ask a question…', 'autorag-chatbot' ); ?>" style="width:100%;margin-top:8px">
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'autorag_chatbot', 'autorag_chatbot_shortcode' );

/**
 * Load translations.
 */
function autorag_chatbot_load_textdomain() {
    load_plugin_textdomain( 'autorag-chatbot', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'autorag_chatbot_load_textdomain' );
?>

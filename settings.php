
<?php
defined( 'ABSPATH' ) || exit;

/**
 * Register settings and fields.
 */
function autorag_chatbot_register_settings() {

    register_setting(
        'autorag_chatbot_settings',
        'autorag_chatbot_settings',
        [
            'sanitize_callback' => 'autorag_chatbot_sanitize_options',
            'default'           => [],
        ]
    );

    add_settings_section(
        'autorag_chatbot_main',
        __( 'Cloudflare credentials', 'autorag-chatbot' ),
        '__return_false',
        'autorag-chatbot'
    );

    $fields = [
        'account_id' => [ 'label' => __( 'Account ID', 'autorag-chatbot' ), 'type' => 'text' ],
        'rag_slug'   => [ 'label' => __( 'RAG Slug', 'autorag-chatbot' ),   'type' => 'text' ],
        'api_token'  => [ 'label' => __( 'API Token', 'autorag-chatbot' ),  'type' => 'password' ],
    ];

    foreach ( $fields as $key => $field ) {
        add_settings_field(
            $key,
            $field['label'],
            'autorag_chatbot_render_input',
            'autorag-chatbot',
            'autorag_chatbot_main',
            [
                'label_for' => "autorag_chatbot_{$key}",
                'type'      => $field['type'],
                'option_key'=> $key,
            ]
        );
    }
}
add_action( 'admin_init', 'autorag_chatbot_register_settings' );

/**
 * Sanitize all options in one pass.
 */
function autorag_chatbot_sanitize_options( $raw ) {
    $clean = [];
    foreach ( [ 'account_id', 'rag_slug', 'api_token' ] as $key ) {
        if ( isset( $raw[ $key ] ) ) {
            $clean[ $key ] = sanitize_text_field( $raw[ $key ] );
        }
    }
    return $clean;
}

/**
 * Generic input renderer for the settings fields.
 */
function autorag_chatbot_render_input( $args ) {
    $options = get_option( 'autorag_chatbot_settings', [] );
    $key     = $args['option_key'];
    $val     = $options[ $key ] ?? '';
    printf(
        '<input id="%1$s" name="autorag_chatbot_settings[%2$s]" type="%3$s" value="%4$s" class="regular-text" />',
        esc_attr( $args['label_for'] ),
        esc_attr( $key ),
        esc_attr( $args['type'] ),
        esc_attr( $val )
    );
}

/**
 * Settings page markup.
 */
function autorag_chatbot_settings_page() {
    ?>
    <div class="wrap">
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:24px">
            <img src="<?php echo esc_url( AUTORAG_CB_URL . 'assets/neuno_colour_no_background.png' ); ?>" alt="" style="width:56px;height:56px;border-radius:8px;">
            <h1 style="margin:0;font-size:26px;">
                <?php esc_html_e( 'WP Cloudflare AutoRAG Chatbot', 'autorag-chatbot' ); ?>
                <span style="display:block;font-size:14px;font-weight:400;color:#666"><?php esc_html_e( 'by neuno.ai', 'autorag-chatbot' ); ?></span>
            </h1>
        </div>

        <?php
        // embed quick start file if exists
        $quick = AUTORAG_CB_DIR . 'quick-start-guide.php';
        if ( file_exists( $quick ) ) {
            include $quick;
        }
        ?>

        <form method="post" action="options.php" style="margin-top:30px">
            <?php
            settings_fields( 'autorag_chatbot_settings' );
            do_settings_sections( 'autorag-chatbot' );
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

/**
 * Add the plugin settings page to the Settings menu.
 */
function autorag_chatbot_add_menu() {
    add_options_page(
        __( 'AutoRAG Chatbot', 'autorag-chatbot' ),
        __( 'AutoRAG Chatbot', 'autorag-chatbot' ),
        'manage_options',
        'autorag-chatbot',
        'autorag_chatbot_settings_page'
    );
}
add_action( 'admin_menu', 'autorag_chatbot_add_menu' );

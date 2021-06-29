<?php
/**
 * Plugin Name: DZ Upload
 * Description: Upload Image with Dropzone in Media Folder with WP REST API
 * Version: 1.0.0
 * Author: Cyril Gouv
 * Text Domain: cg-dzupload
 */


if ( !defined('ABSPATH') ) {
    echo 'WTF !!!';
    exit;
}


class DzUpload {
    public function __construct() {
        // Add Assets
        add_action( 'wp_enqueue_scripts', [$this, 'load_assets'] );
        // Shortcode
        add_shortcode( 'cg-dzUpload', [$this, 'output'] );
    }

    public function load_assets() {
        // CSS
        wp_enqueue_style( 'dropzone', '//rawgit.com/enyo/dropzone/master/dist/dropzone.css' );
        wp_enqueue_style( 'dzUpload', plugin_dir_url( __FILE__ ) . '/css/dz-upload.css', [], 1, 'all' );
        // JS
        wp_enqueue_script( 'dropzone', '//rawgit.com/enyo/dropzone/master/dist/dropzone.js' );
        wp_enqueue_script( 'dzUpload', plugin_dir_url( __FILE__ ) . '/js/dz-upload.js', ['jQuery'], 1, true );
        // WP API
        wp_enqueue_script( 'wp-api' );
        wp_localize_script(
            'wp-api',
            'WP_API_Settings',
            array(
                'root'        => esc_url_raw( rest_url() ),
                'nonce'       => wp_create_nonce( 'wp_rest' ),
                'title'       => 'Media Title',
                'description' => 'Media Description',
                'alt_text'    => 'Media Alt Text',
                'caption'     => 'Media Caption'
            )
        );
    }

    public function output() {
        ob_start();
        $url = rest_url() . 'wp/v2/media';
        ?>
        <div class="dz-container">
            <h1>Upload Now</h1>
            <form action="<?= $url ?>" method="POST" class="dropzone dz" id="dropzone-wordpress-rest-api-form">
                <div class="dz-message needsclick">
                    Déposez vos fichiers à upload.<br>
                    <span class="note needsclick">(Files are uploaded to uploads/yyyy/mm)</span>
                </div>
                <input type="hidden" name="action" value="submit_dropzonejs">
            </form>
        </div>
        <?php
        ob_end_clean();
    }
}

new DzUpload;


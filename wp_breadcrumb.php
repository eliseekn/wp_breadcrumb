<?php

/**
 * Plugin Name: WP Breadcrumb
 * Theme URI: https://github.com/eliseekn/wp_breadcrumb
 * Description: Breadcrumb plugin for WordPress.
 * Version: 1.0.0
 * Author: eliseekn
 * Author URI: https://github.com/eliseekn
 */

function wp_breadcrumb_options_page()
{
?>
    <div class="wrap">
        <h1>WP Breadcrumb Options</h1>

        <form action="options.php" method="post">
            <?php settings_errors() ?>
            <?php settings_fields('wp_breadcrumb_setting') ?>

            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label for="wp_breadcrumb_separator">Separator</label></th>
                    <td><input type="text" name="wp_breadcrumb_separator" id="wp_breadcrumb_separator" value="<?php echo get_option('wp_breadcrumb_separator') ?>"></td>
                </tr>

                <tr valign="top">
                    <th scope="row"><label for="wp_breadcrumb_search_text">Search results text</label></th>
                    <td><input type="text" name="wp_breadcrumb_search_text" id="wp_breadcrumb_search_text" value="<?php echo get_option('wp_breadcrumb_search_text') ?>"></td>
                </tr>

                <tr valign="top">
                    <th scope="row"><label for="wp_breadcrumb_style">Custom style</label></th>
                    <td><textarea name="wp_breadcrumb_style" id="wp_breadcrumb_style" rows="10"><?php echo get_option('wp_breadcrumb_style') ?></textarea></td>
                </tr>
            </table>

            <?php submit_button() ?>
        </form>
    </div>
<?php
}

function wp_breadcrumb_setup_menu()
{
    add_menu_page(
        'WP Breadcrumb Options',
        'WP Breadcrumb',
        'manage_options',
        'wp_breadcrumb',
        'wp_breadcrumb_options_page',
        '',
        60   
    );
}

function wp_breadcrumb_setup_settings()
{
    register_setting('wp_breadcrumb_setting', 'wp_breadcrumb_separator', ['default' => '&#187;']);
    register_setting('wp_breadcrumb_setting', 'wp_breadcrumb_search_text', ['default' => 'Search results for']);
    register_setting('wp_breadcrumb_setting', 'wp_breadcrumb_style', [
        'default' => '
            .wp-breadcrumb {
                display: flex;
                flex-wrap: wrap;
                padding-bottom: 1em;
                list-style: none
            } 
            
            .wp-breadcrumb-link {

            } 
            
            .wp-breadcrumb-separator {

            }
            '
        ]
    );
}

function wp_breadcrumb_setup_css()
{
    echo '<style>' . get_option('wp_breadcrumb_style') . '</style>';
}

function wp_breadcrumb_html()
{
	echo '
        <div class="wp-breadcrumb">
            <a class="wp-breadcrumb-link" href="' . home_url() . '" rel="nofollow">Home</a>
    ';

	if ( is_category() || is_single() ) {
		echo '<span class="wp-breadcrumb-separator">&nbsp;&nbsp;' . get_option('wp_breadcrumb_separator') . '&nbsp;&nbsp;</span>';

		the_category (' - ');

        if (is_single()) {
            echo '<span class="wp-breadcrumb-separator">&nbsp;&nbsp;' . get_option('wp_breadcrumb_separator') . '&nbsp;&nbsp;</span>';
            the_title();
        }
    } elseif ( is_page() ) {
        echo '<span class="wp-breadcrumb-separator">&nbsp;&nbsp;' . get_option('wp_breadcrumb_separator') . '&nbsp;&nbsp;</span>';
        echo the_title();
    } elseif ( is_search())  {
        echo '<span class="wp-breadcrumb-separator">&nbsp;&nbsp;' . get_option('wp_breadcrumb_separator') . '&nbsp;&nbsp</span>' . get_option('wp_breadcrumb_search_text') . ' "';
        echo the_search_query();
        echo '"';
    }

    echo '</div>';
}

add_action( 'admin_init', 'wp_breadcrumb_setup_settings' );
add_action( 'admin_menu', 'wp_breadcrumb_setup_menu' );
add_action( 'wp_print_styles', 'wp_breadcrumb_setup_css' );
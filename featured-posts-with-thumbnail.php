<?php
/*
 * @package Featured Posts
 * @author Nando Pappalardo e Giustino Borzacchiello
 * @version {{VERSIONE}}
 */
/*
  Plugin Name: Featured Post with thumbnail
  Plugin URI: http://www.yourinspirationweb.com/en/wordpress-plugin-featured-posts-with-thumbnails-highlighting-your-best-articles/
  Description: This widget allows you to add in your blog's sidebar a list of featured post with thumbanil.
  Author: Nando Pappalardo e Giustino Borzacchiello
  Version: {{VERSIONE}}
  Author URI: http://en.yourinspirationweb.com/

  USAGE:

  LICENCE:

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.

 */


/*==============================================================================
 * Definizioni costanti
 *============================================================================*/

/* Constants definitions for WP < 2.6
 * http://codex.wordpress.org/Determining_Plugin_and_Content_Directories
 */
if (!defined('WP_CONTENT_URL')) {
    define('WP_CONTENT_URL', site_url() . '/wp-content');
}

if (!defined('WP_CONTENT_DIR')) {
    define('WP_CONTENT_DIR', ABSPATH . 'wp-content');
}

if (!defined('WP_PLUGIN_URL')) {
    define('WP_PLUGIN_URL', WP_CONTENT_URL . '/plugins');
}

if (!defined('WP_PLUGIN_DIR')) {
    define('WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins');
}

define('YIW_TEXT_DOMAIN', 'featured-post');
define('FPWT_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('FPWT_PLUGIN_URL', plugins_url('featured-posts'));

require_once FPWT_PLUGIN_PATH . '/includes/yiw-featured-post-widget.php';
require_once FPWT_PLUGIN_PATH . '/includes/admin.php';


class Featured_Posts_With_Thumbnail
{
    function __construct()
    {
        add_action('init', array($this, 'plugin_textdomain'));

        if (function_exists('add_theme_support')) {
            add_theme_support('post-thumbnails');
        }

        add_action('admin_menu', array($this, 'register_featured_metabox'));
        add_action('new_to_publish', array($this, 'set_unset_featured_post'));
        add_action('save_post', array($this, 'set_unset_featured_post'));

        add_action('wp_enqueue_scripts', array($this, 'register_styles'));
        add_action('admin_enqueue_scripts', array($this, 'register_admin_scripts'));
        add_action('widgets_init', array($this, 'register_widget'));
    }

    public function plugin_textdomain()
    {
        $language_files_path = dirname(plugin_basename(__FILE__)) . '/language';
        load_plugin_textdomain(YIW_TEXT_DOMAIN, false, $language_files_path);
    }

    public function register_styles()
    {
        wp_enqueue_style('featured-post', FPWT_PLUGIN_URL . '/featured-post.css');

    }

    public function register_admin_scripts()
    {
        wp_enqueue_script(
            'yiw_widget_script',
            FPWT_PLUGIN_URL . '/js/yiw_widget_script.js',
            array('jquery'),
            false,
            true
        );
    }

    public function register_widget()
    {
        register_widget('Featured_Posts_Widget');
    }

    public function register_featured_metabox()
    {
        add_meta_box('post_info',
            __('Featured', YIW_TEXT_DOMAIN),
            array($this, 'render_featured_metabox'),
            'post',
            'side',
            'high');
    }

    public function render_featured_metabox()
    {
        global $post;
        $is_featured = get_post_meta($post->ID, '_yiw_featured_post', true) ? 'yes' : 'no';
        echo self::render(
            plugin_dir_path(__FILE__) . '/views/metabox.php',
            array(
                'is_featured' => $is_featured
            )
        );
    }

    public function set_unset_featured_post($post_ID)
    {
        //TODO POST validation
        $articolo = get_post($post_ID);
        if (isset($_POST['insert_featured_post'])) {
            if ($_POST['insert_featured_post'] == 'yes') {
                update_post_meta($articolo->ID, '_yiw_featured_post', 1);
            }
            elseif ($_POST['insert_featured_post'] == 'no') {
                delete_post_meta($articolo->ID, '_yiw_featured_post');
            }
        }
    }

    /**
     * Mostra i post in evidenza
     * Show featured posts using unordered list
     *
     * @param mixed $args
     *
     * $args:
     *         title => the title displayed
     *         numberposts => number of featured posts shown
     *         orderby => order type: http://codex.wordpress.org/Template_Tags/get_posts
     *         widththumb => width of post's thumbnail
     *         heightthumb => height of post's thumbnail
     *         beforetitle => opening tag before for title
     *         aftertittle => closing tag for title
     */
    public static function echo_posts_list($args = null)
    {
        echo self::render(
            plugin_dir_path(__FILE__) . '/views/featured-posts-list.php',
            array(
                'featured_posts' => self::get_featured_posts($args)
            )
        );
    }

    private static function get_featured_posts($args = null)
    {

        /**
         *  Merging default values with user selected settings
         */
        $fp = wp_parse_args(
            $args,
            array(
                'title' => 'Featured Posts',
                'numberposts' => 5,
                'orderby' => 'DESC',
                'widththumb' => 73,
                'heightthumb' => 73,
                'beforetitle' => '<h3>',
                'aftertitle' => '</h3>',
                'show' => 'featured',
                'category' => 'uncategorized'
            )
        );
        //TODO inserire widththumb e heightthumb

        /* List's title */
        if (!empty($fp['title'])) {
            echo $fp['beforetitle'] . $fp['title'] . $fp['aftertitle'];
        }

        /*
         * Modificare i parametri di questa query per mostrare/escludere
         * categorie, pagine.
         * If you want to exclude categories and/or pages modify this query
         * properly
         * Info: http://codex.wordpress.org/Template_Tags/get_posts
         *
         */
        if ((strcmp($fp['show'], 'category') == 0) && ($fp['category'])) {
            $get_posts_query = array(
                'category' => $fp['category'],
                'numberposts' => $fp['numberposts'],
                'orderby' => $fp['orderby']
            );
        }
        else {
            //TODO mettere la meta_key in un attributo
            $get_posts_query = array(
                'meta_key' => '_yiw_featured_post',
                'meta_value' => 1,
                'numberposts' => $fp['numberposts'],
                'orderby' => $fp['orderby']
            );
        }
        return WP_Query($get_posts_query);
    }

    public static function render($path, $data)
    {
        ($data) ? extract($data, EXTR_SKIP) : null;

        ob_start();
        include($path);
        $template = ob_get_clean();

        return $template;
    }
}

new Featured_Posts_With_Thumbnail();

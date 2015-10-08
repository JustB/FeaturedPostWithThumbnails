<?php
/*
 * @package Featured Posts
 * @author Nando Pappalardo e Giustino Borzacchiello
 * @version 1.6.0
 */
/*
  Plugin Name: Featured Post with thumbnail
  Plugin URI: http://www.yourinspirationweb.com/en/wordpress-plugin-featured-posts-with-thumbnails-highlighting-your-best-articles/
  Description: This widget allows you to add in your blog's sidebar a list of featured post with thumbnail.
  Author: Nando Pappalardo e Giustino Borzacchiello
  Version: 1.6.0
  Author URI: http://en.yourinspirationweb.com/
  Text Domain: featured-post
  Domain Path: /language
  USAGE:

  LICENSE:

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


add_action( 'plugins_loaded', 'featured_posts_load_plugin_textdomain' );
function featured_posts_load_plugin_textdomain() {
	load_plugin_textdomain( 'featured-post', false, basename( dirname( __FILE__ ) ) . '/language/' );
}

define( 'FEATURED_POST_DIR', plugin_dir_path( __FILE__ ) );
define( 'FEATURED_POST_URL', plugin_dir_url( __FILE__ ) );

function bm_my_post_thumbnail_html( $html, $post_id, $thumbnail_id, $size = '' ) {

	if ( empty( $html ) ) {

		$values = get_children(
			array(
				'post_parent'    => $post_id,
				'post_type'      => 'attachment',
				'post_mime_type' => 'image',
				'order'          => 'ASC',
				'orderby'        => 'menu_order',
				'numberposts'    => 1,
			)
		);

		if ( $values ) {
			foreach ( $values as $child_id => $attachment ) {
				$html = wp_get_attachment_image( $child_id, $size );
				break;
			}
		}

	}

	return $html;

}

add_filter( 'post_thumbnail_html', 'bm_my_post_thumbnail_html', 10, 4 );
/* ----------------------------------------------------------------------- *
 * WIDGET SECTION
 * ----------------------------------------------------------------------- */

add_action( 'widgets_init', 'featured_posts_widget' );
function featured_posts_widget() {
	register_widget( 'Featured_posts' );
}

class Featured_posts extends WP_Widget {

	/**
	 *
	 * @var string widget classname
	 */
	private $classname = 'widget_featured-posts';
	/**
	 *
	 * @var string widget description
	 */
	private $description = "This widget allows you to add in your blog's sidebar
a list of featured posts.";
	/**
	 *
	 * @var integer widget width
	 */
	private $width = 300;
	/**
	 *
	 * @var integer widget height
	 */
	private $height = 350;
	/**
	 *
	 * @var string widget title
	 */
	private $widgetName = 'Featured Posts';
	/**
	 *
	 * @var integer default thumbnails width
	 */
	private $defaultThumbWidth = '73';
	/**
	 *
	 * @var integer default thumbnails height
	 */
	private $defaultThumbHeight = '73';

	/**
	 * Costruttore.
	 */
	function __construct() {
		/* Impostazione del widget */
		$widget_ops = array(
			'classname'   => $this->classname,
			'description' => __( $this->description )
		);

		/* Impostazioni di controllo del widget */
		$control_ops = array(
			'width'   => $this->width,
			'height'  => $this->height,
			'id_base' => $this->classname
		);

		/* Creiamo il widget */
		parent::__construct( $this->classname,
			__( $this->widgetName, 'featured-post' ), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );
		$arguments = array(
			'title'       => $instance['title'],
			'numberposts' => $instance['showposts'],
			'orderby'     => $instance['orderby'],
			'widththumb'  => $instance['width-thumb'],
			'heightthumb' => $instance['height-thumb'],
			'beforetitle' => $before_title,
			'aftertitle'  => $after_title,
			'show'        => $instance['show'],
			'category'    => $instance['category']
		);
		/* Before widget (definito dal tema). */
		echo $before_widget;
		featured_posts_YIW( $arguments );
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		/* Strip tags (if needed) and update the widget settings. */
		$instance['title']        = strip_tags( $new_instance['title'] );
		$instance['showposts']    = $new_instance['showposts'];
		$instance['orderby']      = $new_instance['orderby'];
		$instance['width-thumb']  = $new_instance['width-thumb'];
		$instance['height-thumb'] = $new_instance['height-thumb'];
		$instance['show']         = $new_instance['show'];
		$instance['category']     = $new_instance['category'];

		return $instance;
	}

	private function showTitleForm( $instance ) {
		echo '<p>';
		echo '<label for="' . $this->get_field_id( 'title' ) . '">' .
		     _e( 'Title:', 'featured-post' ) . '</label>';
		echo '<input id="' . $this->get_field_id( 'title' ) . '" name="' .
		     $this->get_field_name( 'title' ) . '" value="' . $instance['title'] .
		     '" style="width:100%;" class="widefat" />';
		echo '</p>';
	}

	private function showNumberPostsForm( $instance ) {
		echo '<p>';
		echo '<label for="' . $this->get_field_id( 'showposts' ) . '">' .
		     _e( 'How many posts do you want to display?', 'featured-post' ) . '</label>';
		echo '<select name="' . $this->get_field_name( 'showposts' ) .
		     '" id="' . $this->get_field_id( 'showposts' ) . '" >';
		for ( $i = 0; $i <= 100; $i ++ ) {
			echo '<option class="level-0" value="' . $i . '"' . selected( $instance['showposts'], $i ) . '>' . $i . '</option>';
		}
		echo '</select>';
		echo '</p>';
	}

	private function showOrderTypeForm( $instance ) {
		echo '<p>';
		echo '<label for="' . $this->get_field_id( 'orderby' ) . '">' .
		     __( 'Choose type of order:', 'featured-post' ) . '</label>';
		echo '<select name="' . $this->get_field_name( 'orderby' ) . '" id="' .
		     $this->get_field_id( 'orderby' ) . '" >';
		echo '<option class="level-0" value="rand" ' . selected( $instance['orderby'], 'random' ) . ' >' .
		     __( 'Random', 'featured-post' ) . '</option>';
		echo '<option class="level-0" value="title" ' . selected( $instance['orderby'], 'title' ) . ' >' .
		     __( 'Title', 'featured-post' ) . '</option>';
		echo '<option class="level-0" value="date" ' . selected( $instance['orderby'], 'date' ) . ' >' .
		     __( 'Date', 'featured-post' ) . '</option>';
		echo '<option class="level-0" value="author" ' . selected( $instance['orderby'], 'author' ) . ' >' .
		     __( 'Author', 'featured-post' ) . '</option>';
		echo '<option class="level-0" value="modified" ' . selected( $instance['orderby'], 'modified' ) . ' >' .
		     __( 'Modified', 'featured-post' ) . '</option>';
		echo '<option class="level-0" value="ID" ' . selected( $instance['orderby'], 'ID' ) . ' >' .
		     __( 'ID', 'featured-post' ) . '</option>';
		echo '</select>';
		echo '</p>';
	}

	/**
	 *
	 * @param <type> $instance
	 */
	private function showWidthHeightForm( $instance ) {
		echo '<p>';
		echo '<label for="' . $this->get_field_id( 'width-thumb' ) . '">' .
		     __( 'Width Thumbnail', 'featured-post' ) . '</label>';
		echo '<input id="' . $this->get_field_id( 'width-thumb' ) .
		     '" name="' . $this->get_field_name( 'width-thumb' ) . '" value="' .
		     $instance['width-thumb'] . '" style="width:20%;" class="widefat" />';
		echo '</p>';
		echo '<p>';
		echo '<label for="' . $this->get_field_id( 'height-thumb' ) . '">' .
		     __( 'Height Thumbnail', 'featured-post' ) . '</label>';
		echo '<input id="' . $this->get_field_id( 'height-thumb' ) .
		     '" name="' . $this->get_field_name( 'height-thumb' ) . '" value="' .
		     $instance['height-thumb'] . '" style="width:20%;" class="widefat" />';
		echo '</p>';
	}

	private function showFeaturedOrCategory( $instance ) {
		echo '<p id="yiw_featured_post_show">';
		echo '<label for="' . $this->get_field_id( 'show' ) . '">' . _e( "Featured or category?", 'featured-post' ) . '</label>';
		echo '<select id="' . $this->get_field_id( 'show' ) . '" name="' . $this->get_field_name( 'show' ) . '">';
		echo '<option value="featured" ' . selected( $instance['show'], 'featured' ) . '>Featured</option>';
		echo '<option value="category" ' . selected( $instance['show'], 'category' ) . '>Category</option>';
		echo '</select>';
		echo '</p>';

		echo '<p id="yiw_featured_post_category">';
		echo '<label for="' . $this->get_field_id( 'category' ) . '">' . _e( 'Category', 'featured-post' ) . '</label>';
		echo '<select id="' . $this->get_field_id( 'category' ) . '" name="' . $this->get_field_name( 'category' ) . '">';
		$categories = get_categories();
		foreach ( $categories as $c ) {
			echo '<option value="' . $c->cat_ID . '" ' . selected( $instance['category'], $c->cat_ID ) . '>' . $c->name . '</option>';
		}
		echo '</select>';
		echo '</p>';
	}

	public function form( $instance ) {
		/* Impostazioni di default del nostro widget */
		$defaults = array(
			'title'        => __( $this->widgetName, 'featured-post' ),
			'showposts'    => '',
			'orderby'      => '',
			'width-thumb'  => $this->defaultThumbWidth,
			'height-thumb' => $this->defaultThumbHeight,
			'show'         => 'featured',
			'category'     => 'uncategorized'
		);

		$instance = wp_parse_args( (array) $instance, $defaults );
		$this->showTitleForm( $instance );
		$this->showNumberPostsForm( $instance );
		$this->showOrderTypeForm( $instance );
		$this->showWidthHeightForm( $instance );
		$this->showFeaturedOrCategory( $instance );
	}

}

//end class Featured_posts

function YIW_featured_post_css() {
	wp_enqueue_style( 'featured-post-css', FEATURED_POST_URL . 'featured-post.css' );
}

add_action( 'wp_enqueue_scripts', 'YIW_featured_post_css' );


/**
 * Recupera la prima immagine del post
 * Returns the first image in the post
 *
 */
function catch_that_image() {
	global $post, $posts;

	$num_images = preg_match_all( '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches );

	if ( $num_images > 0 ) {
		$first_img = $matches[1][0];
	} else {
		$first_img = FEATURED_POST_URL . "images/default.gif";
	}

	return $first_img;
}

/**
 * Mostra i post in evidenza
 * Show featured posts using unordered list
 *
 * @param mixed $args
 *
 * $args:
 *        title => the title displayed
 *        numberposts => number of featured posts shown
 *        orderby => order type: http://codex.wordpress.org/Template_Tags/get_posts
 *        widththumb => width of post's thumbnail
 *        heightthumb => height of post's thumbnail
 *        beforetitle => opening tag before for title
 *        aftertittle => closing tag for title
 */
function featured_posts_YIW( $args = null ) {

	$defaults = array(
		'title'       => 'Featured Posts',
		'numberposts' => 5,
		'orderby'     => 'DESC',
		'widththumb'  => 73,
		'heightthumb' => 73,
		'beforetitle' => '<h3>',
		'aftertitle'  => '</h3>',
		'show'        => 'featured',
		'category'    => 'uncategorized'
	);

	/**
	 *  Merging default values with user selected settings
	 */
	$fp           = wp_parse_args( $args, $defaults );
	$title        = $fp['title'];
	$showposts    = $fp['numberposts'];
	$orderby      = $fp['orderby'];
	$width_thumb  = $fp['widththumb'];
	$height_thumb = $fp['heightthumb'];
	$before_title = $fp['beforetitle'];
	$after_title  = $fp['aftertitle'];
	$show         = $fp['show'];
	$cat_ID       = $fp['category'];

	/* List's title */
	if ( ! empty( $title ) ) {
		echo $before_title . $title . $after_title;
	}

	/*
	 * Modificare i parametri di questa query per mostrare/escludere
	 * categorie, pagine.
	 * If you want to exclude categories and/or pages modify this query
	 * properly
	 * Info: http://codex.wordpress.org/Template_Tags/get_posts
	 *
	 */
	$get_posts_query = array(
		'numberposts' => $showposts,
		'orderby'     => $orderby
	);
	if ( ( strcmp( $show, 'category' ) == 0 ) && ( $cat_ID ) ) {
		$get_posts_query['category'] = $cat_ID;
	} else {
		$get_posts_query['meta_key']   = 'featured';
		$get_posts_query['meta_value'] = 1;
	}
	$featured_posts = get_posts( $get_posts_query );
	?>

	<ul id="yiw-featured-post">
		<?php foreach ( $featured_posts as $post ) :
			setup_postdata( $post );
			?>
			<li>
				<a href="<?php the_permalink() ?>" class="featured-thumb">
					<?php if ( ( function_exists( 'the_post_thumbnail' ) ) && ( has_post_thumbnail() ) ) :
						$image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' ); ?>
						<img
							src="<?php echo FEATURED_POST_URL ?>scripts/timthumb.php?src=<?php echo $image[0] ?>&amp;h=<?php echo $height_thumb ?>&amp;w=<?php echo $width_thumb ?>&amp;zc=1"
							class="alignleft" alt="<?php the_title(); ?>"/>

					<?php else :
						$img = wp_get_image_editor( catch_that_image() );
						if( ! is_wp_error( $img ) ) {
							$img->resize( $width_thumb, $height_thumb );
						}

						?>
						<img
							src="<?php echo FEATURED_POST_URL ?>scripts/timthumb.php?src=<?php echo catch_that_image() ?>&amp;h=<?php echo $height_thumb ?>&amp;w=<?php echo $width_thumb ?>&amp;zc=1"
							class="alignleft" alt="<?php the_title(); ?>"/>
					<?php endif; ?>
				</a>

				<h4 class="featured-title">
					<a href="<?php the_permalink() ?>"><?php the_title(); ?></a>
				</h4>
			</li>
		<?php endforeach; ?>
	</ul>

	<?php
}

/* END featured_posts_YIW */

/**
 * Aggiunge/rimuove il campo personalizzato featured
 * Add/remove featured custom field
 *
 * @param integer $post_ID
 */
function YIW_add_featured( $post_ID ) {
	$articolo = get_post( $post_ID );
	if ( isset( $_POST['insert_featured_post'] ) ) {
		if ( $_POST['insert_featured_post'] == 'yes' ) {
			add_post_meta( $articolo->ID, 'featured', 1, true ) ||
			update_post_meta( $articolo->ID, 'featured', 1 );
		} elseif ( $_POST['insert_featured_post'] == 'no' ) {
			delete_post_meta( $articolo->ID, 'featured' );
		}
	}
}

/**
 *
 * Mostra il form featured nella sezione "Scrivi Post"
 * Shows featured form in "Write Post" section
 */
function YIW_post_box() {
	global $post;
	$yes      = '';
	$no       = '';
	$featured = get_post_meta( $post->ID, 'featured', 1 );
	if ( $featured ) {
		$yes = 'selected="selected"';
	} else {
		$no = 'selected="selected"';
	}
	echo '<label for="insert_featured_post">' .
	     __( 'Featured post?', 'featured-post' ) . '</label>';
	echo '<select name="insert_featured_post" id="insert_featured_post">';
	echo '<option value="yes" ' . $yes . ' >' .
	     __( 'Yes', 'featured-post' ) . '</option>';
	echo '<option value="no" ' . $no . ' >' .
	     __( 'No ', 'featured-post' ) . '</option>';
	echo '</select>';
}

function my_post_options_box() {
	add_meta_box( 'post_info', __( 'Featured', 'featured-post' ),
		'YIW_post_box', 'post', 'side', 'high' );
}

add_action( 'admin_menu', 'my_post_options_box' );
add_action( 'new_to_publish', 'YIW_add_featured' );
add_action( 'save_post', 'YIW_add_featured' );


/*
 * aggiunge colonna nella pagina modifica dei post
 *
 * Il filtro 'manage_posts_columns' permette di aggiungere o rimuovere una
 * colonna dalla sezione "Modifica Post".
 * Per aggiungerla, basta fare come sotto,
 * ovvero aggiungere un elemento all'array $defaults, che ha come valore
 * l'intestazione della colonna.
 * Per rimuoverla si può usare unset($defaults['nomeColonna'])
 *
 * È molto importante ritornare l'array $defaults, come per tutti i filter
 */
add_filter( 'manage_posts_columns', 'yiw_add_column' );

function yiw_add_column( $defaults ) {
	$defaults['yiw-featured'] = __( 'Featured', 'featured-post' );

	return $defaults;
}

/*
 * Recupera dal database tutti i post che hanno il custom field featured
 * attivato
 * FIXME mi sono accorto che il nome del nostro custom field, featured, è
 * veramente troppo comune. Bisognerebbe cambiarlo e metterlo in una variabile
 * però così facendo, bisognerebbe aggiornare tutti i post
 */
add_action( 'manage_posts_custom_column', 'yiw_featured_column', 10, 2 );

function yiw_featured_column( $column_name, $id ) {
	if ( $column_name == 'yiw-featured' ) {
		global $wpdb;
		$queryStr = 'SELECT meta_value FROM ' . $wpdb->prefix . 'postmeta ';
		$queryStr .= 'WHERE meta_key="featured" and post_id=' . $id;
		$result = $wpdb->get_results( $queryStr, ARRAY_A );
		if ( isset( $result[0] ) && ( $result[0]['meta_value'] == '1' ) ) {
			_e( "Yes", 'featured-post' );
		} else {
			_e( "No", 'featured-post' );
		}
	}
}


?>

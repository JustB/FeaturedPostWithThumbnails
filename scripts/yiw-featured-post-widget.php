<?php
/**
 * WIDGET SECTION
 * ----------------------------------------------------------------------- */

add_action('widgets_init', 'yiw_widget_featured_posts');
function yiw_widget_featured_posts() {
	register_widget('Featured_posts');
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
	private $description = '';
	/**
	 *
	 * @var integer widget width
	 */
	private $width = 200;
	/**
	 *
	 * @var integer widget height
	 */
	private $height = 350;
	/**
	 *
	 * @var string widget title
	 */
	private $widgetName = '';
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
        $this->description = __("This widget allows you to add in your blog's sidebar a list of featured posts.",
            YIW_TEXT_DOMAIN);
        $this->widgetName = __('Featured Posts', YIW_TEXT_DOMAIN);

        parent::__construct(
            $this->classname,
            __($this->widgetName, YIW_TEXT_DOMAIN),
            array(
                'classname' => $this->classname,
                'description' => __($this->description)
            ),
            array(
                'width' => $this->width,
                'height' => $this->height,
                'id_base' => $this->classname
            )
        );
	}

	function widget($args, $instance) {
		extract($args);
		$arguments = array(
			 'title' => $instance['title'],
			 'numberposts' => $instance['showposts'],
			 'order-by' => $instance['order-by'],
			 'widththumb' => $instance['width-thumb'],
			 'heightthumb' => $instance['height-thumb'],
			 'beforetitle' => $before_title,
			 'aftertitle' => $after_title,
			 'show' => $instance['show'],
			 'category' => $instance['category']
		);
		global $featured_post_plugin_path;
		/* Before widget (definito dal tema). */
		echo $before_widget;
		featured_posts_YIW($arguments);
		echo $after_widget;
	}

	function update($new_instance, $old_instance) {
		/* Strip tags (if needed) and update the widget settings. */
		$old_instance['title'] = strip_tags($new_instance['title']);
		$old_instance['showposts'] = $new_instance['showposts'];
		$old_instance['order-by'] = $new_instance['order-by'];
		$old_instance['width-thumb'] = $new_instance['width-thumb'];
		$old_instance['height-thumb'] = $new_instance['height-thumb'];
		$old_instance['show'] = $new_instance['show'];
		$old_instance['category'] = $new_instance['category'];
		return $old_instance;
	}

	public function form($instance) {
		/* Impostazioni di default del nostro widget */
		$defaults = array(
			 'title' => __($this->widgetName, YIW_TEXT_DOMAIN),
			 'showposts' => '',
             'order-by' => 'ID',
			 'width-thumb' => $this->defaultThumbWidth,
			 'height-thumb' => $this->defaultThumbHeight,
			 'show' => 'featured',
			 'category' => 'uncategorized'
        );

		$instance = wp_parse_args(
            (array) $instance,
            $defaults
        );
		include( plugin_dir_path(__FILE__) . '/../views/admin.php');
	}

}

//end class Featured_posts
?>

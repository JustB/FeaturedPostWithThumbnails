<?php
/**
 * @package Featured Posts
 * @author Nando Pappalardo e Giustino Borzacchiello
 * @version {{VERSIONE}}
 */
?>
<ul id="yiw-featured-post">
    <?php while( $featured_posts->have_posts() ) : $featured_posts->the_post();
    ?>
    <li>
        <a href="<?php the_permalink() ?>" class="featured-thumb">
            <?php if ( function_exists('the_post_thumbnail') && has_post_thumbnail() ) : ?>
            <img src="<?php echo wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' ) ?>" class="alignleft" alt="<?php the_title(); ?>" />
            <?php else: ?>
            <img src="<?php echo FPWT_PLUGIN_URL . '/images/default.gif' ?>" class="alignleft" alt="<?php the_title(); ?>" />
            <?php endif; ?>
        </a>

        <h4 class="featured-title">
            <a href="<?php the_permalink() ?>"><?php the_title(); ?></a>
        </h4>
    </li>
    <?php endwhile; ?>
</ul>
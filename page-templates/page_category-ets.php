<?php
/**
 * Template Name: Categoria ETS
 */

 get_header();

 $layout = sydney_blog_layout();
 do_action('sydney_before_content'); ?>

<div id="primary" class="content-area col-md-9 <?php echo esc_attr( $layout ); ?>">
	<main id="main" class="site-main" role="main">
		<?php
		$args = array(
			'post_type' => 'post',
			'post_status' => 'publish',
			'category_name' => 'ets',
			'posts_per_page' => 5,
			'is_archive' => true,
		);

		$arr_posts = new WP_Query( $args );
		if ($arr_posts->have_posts()) : ?>
			<div class="posts-layout">
				<?php
				while ( $arr_posts->have_posts() ) :
					$arr_posts->the_post();
					get_template_part( 'content', get_post_format() );
				endwhile; ?>
			</div>
		<?php
		else :
			get_template_part( 'content', 'none' );
		endif;?>
	</main>
</div>

<script type="text/javascript" src="https://andreelloconsulting.it/wp-includes/js/imagesloaded.min.js?ver=3.2.0"></script>
<script type="text/javascript" src="https://andreelloconsulting.it/wp-includes/js/masonry.min.js?ver=3.3.2"></script>
<script type="text/javascript" src="https://andreelloconsulting.it/wp-content/themes/sydney/js/masonry-init.js?ver=4.9.8"></script>

<script>
	document.body.classList.remove("page");
</script>

<?php do_action('sydney_after_content'); ?>

<?php get_footer(); ?>

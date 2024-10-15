<?php
get_header();
getBanner(['title' => 'All Events', 'subtitle' => 'Check out whast going on']);
?>

<div class="container container--narrow page-section">
	<?php
	while (have_posts()) {
		the_post();
		get_template_part('template-parts/event', 'card');
		wp_reset_postdata();
		echo paginate_links();
	};
	?>
</div>

<?php get_footer(); ?>
<?php
get_header();
while (have_posts()) {
	the_post();
	getBanner();
?>

	<div class="container container--narrow page-section">
		<div class="metabox metabox--position-up metabox--with-home-link">
			<p>
				<a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('program') ?>"><i class="fa fa-home" aria-hidden="true"></i>All Programs</a> <span class="metabox__main"><?php the_title() ?></span>
			</p>
		</div>
		<div class="generic-content">
			<p><?php the_field('main_body_content') ?></p>
		</div>
		<?php
		$relatedProfessors = new WP_Query(
			array(
				'post_type' => 'professor',
				'posts_per_page' => '-1',
				'orderby' => 'title',
				'order' => 'ASC',
				'meta_query' => array(
					array(
						'key' => 'related_programs',
						'compare' => 'LIKE',
						'value' => '"' . get_the_ID() . '"'
					)
				)

			)
		);
		if ($relatedProfessors->have_posts()) {
			echo '<hr class="section-break">';
			echo '<h2 class="headling headline--medium">Our ' . get_the_title() . ' Professors </h2>';

			echo '<ul class="professor-cards">';
			while ($relatedProfessors->have_posts()) {
				$relatedProfessors->the_post(); ?>
				<li class="professor-card__list-item"><a class="professor-card" href="<?php the_permalink() ?>">
						<img class="professor-card__image" src="<?php the_post_thumbnail_url('16/9') ?>" alt="">
						<span class="professor-card__name"><?php the_title() ?> </span>
					</a></li>
		<?php }
			wp_reset_postdata();

			echo '</ul>';
		}
		?>




		<?php
		$today = date('Ymd');
		$upcomingEvents = new WP_Query(
			array(
				'post_type' => 'event',
				'posts_per_page' => '2',
				'meta_key' => 'event_date',
				'orderby' => 'meta_value_num',
				'order' => 'ASC',
				'meta_query' => array(
					array(
						'key' => 'event_date',
						'compare' => '>=',
						'value' => $today,
						'type' => 'numeric'
					),
					array(
						'key' => 'related_programs',
						'compare' => 'LIKE',
						'value' => '"' . get_the_ID() . '"'
					)
				)

			)
		);
		if ($upcomingEvents->have_posts()) {
			echo '<hr class="section-break">';
			echo '<h2 class="headling headline--medium">Upcoming ' . get_the_title() . ' Events </h2>';


			while ($upcomingEvents->have_posts()) {
				$upcomingEvents->the_post();
				get_template_part('template-parts/event', 'card');
			}
			wp_reset_postdata();
		}

		$relatedCampuses = get_field('related_campus');
		if ($relatedCampuses) {
			echo '<hr class="section-break">';
			echo '<h2 class="headline headline--medium">' . get_the_title() . ' is available at these campuses</h2>';
			echo '<ul class="min-list link-list">';
			foreach ($relatedCampuses as $campus) { ?>
				<a <?php echo get_the_permalink($campus) ?>><?php echo get_the_title($campus) ?> </a>
		<?php }
			echo '</ul>';
		}

		?>

	</div>
<?php
}
get_footer();
?>
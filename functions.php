<?php
require get_theme_file_path('/includes/search-route.php');

function styleLoader()
{
	wp_enqueue_style('main_styles', get_stylesheet_directory_uri() . '/build/index.css');
	wp_enqueue_style('additional_styles', get_stylesheet_directory_uri() . '/build/style-index.css');
	wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
	wp_register_style('google', 'https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap');
	wp_enqueue_style('google');
	wp_enqueue_script('main-university-javascript', get_theme_file_uri('/build/index.js'), array('jquery'), 1.0, true);
	wp_enqueue_script('maps', '//maps.googleapis.com/maps/api/js?key=', null, '1.0');

	wp_localize_script('main-university-javascript', 'university', [
		'root' => get_site_url(),
	]);
}


add_action('wp_enqueue_scripts', 'styleLoader');

function features()
{
	add_theme_support('title-tag');
	add_theme_support('post-thumbnails');
	add_image_size('16/9', 400, 250);
	add_image_size('pageBanner', 1500, 350);
	register_nav_menu('headerMenu', 'Header Menu');
	register_nav_menu('Footer-locations-menu', 'footer-locations-menu');
	register_nav_menu('Footer-location-2', 'footer-location-2');
};


add_action('after_setup_theme', 'features');

function university_adjust_queries($query)
{
	if (!is_admin() && is_post_type_archive('event') && $query->is_main_query()) {
		$today = date('Ymd');
		$query->set('meta_key', 'event_date');
		$query->set('orderby', 'meta_value_num');
		$query->set('order', 'ASC');
		$query->set('meta_query', array(
			array(
				'key' => 'event_date',
				'compare' => '>=',
				'value' => $today,
				'type' => 'numeric'
			)
		));
	}
}
add_action('pre_get_posts', 'university_adjust_queries');

function programs_adjust_queries($query)
{
	if (!is_admin() && is_post_type_archive('program') && $query->is_main_query()) {
		$query->set('orderby', 'title');
		$query->set('order', 'ASC');
		$query->set('posts_per_page', -1);
	}
}

add_action('pre_get_posts', 'programs_adjust_queries');


function campus_adjust_queries($query)
{
	if (!is_admin() && is_post_type_archive('campus') && $query->is_main_query()) {
		$query->set('posts_per_page', -1);
	}
}

add_action('pre_get_posts', 'campus_adjust_queries');




function getBanner($args = null)
{

	if (!isset($args['title'])) {
		$args['title'] = get_the_title();
	}
	if (!isset($args['photo'])) {
		if (get_field('banner_photo') && !is_archive() && !is_home()) {
			$args['photo'] = get_field('banner_photo')['sizes']['pageBanner'];
		} else {
			$args['photo'] = get_theme_file_uri('/images/library-hero.jpg');
		}
	}
	if (!isset($args['subtitle'])) {
		$args['subtitle'] = get_field('subtitle');
	};
?>
	<div class="page-banner">
		<div class="page-banner__bg-image" style="background-image: url(<?php echo $args['photo'] ?>)"></div>
		<div class="page-banner__content container container--narrow">
			<h1 class="page-banner__title"><?php echo $args['title'] ?></h1>
			<div class="page-banner__intro">
				<p><?php echo $args['subtitle'] ?></p>
			</div>
		</div>
	</div>
<?php };

function my_acf_google_map_api($api)
{

	$api['key'] = 'AIzaSyDYFCzn5B7AC_RCQxK6SxWl2QrE4hb-H2A';

	return $api;
}

add_filter('acf/fields/google_map/api', 'my_acf_google_map_api');

function custom_rest()
{
	register_rest_field('post', 'author_name', array(
		'get_callback' =>
		function () {
			return get_the_author();
		}
	));
};

add_action('rest_api_init', 'custom_rest');

<?php

function styleLoader()
{
	wp_enqueue_style('main_styles', get_stylesheet_directory_uri() . '/build/index.css');
	wp_enqueue_style('additional_styles', get_stylesheet_directory_uri() . '/build/style-index.css');
	wp_enqueue_style('font-awesome', 'https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.6.0/css/fontawesome.min.css');
	wp_register_style('google', 'https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap');
	wp_enqueue_style('google');
	wp_enqueue_script('main-university-javascript', get_theme_file_uri('/build/index.js'), array('jquery'), 1.0, true);
}

function features()
{
	add_theme_support('title-tag');
	register_nav_menu('headerMenu', 'Header Menu');
	register_nav_menu('Footer-locations-menu', 'footer-locations-menu');
	register_nav_menu('Footer-location-2', 'footer-location-2');
};

add_action('wp_enqueue_scripts', 'styleLoader');
add_action('after_setup_theme', 'features');

<?php 

// Hide the Admin Bar
add_filter('show_admin_bar', '__return_false');


// Add support for Menus
add_theme_support('menus');


// Add support for Post Thumbnails
add_theme_support('post-thumbnails');


// News Sidebar
register_sidebar(array(
  'name'         => __( 'News Sidebar', $news_sidebar ),
  'id'           => 'news-sidebar',
  'class'        => 'sidebar',
  'description'  => __( 'Widgets in this area will be shown on the news page sidebar.' ),
  'before_title' => '<h3>',
  'after_title'  => '</h3>'
));


// Equipment Sales
add_action('init', 'cptui_register_my_cpt_equipment_sales');
function cptui_register_my_cpt_equipment_sales() {
register_post_type('equipment_sales', array(
'label' => 'Equipment Sales',
'description' => '',
'public' => true,
'show_ui' => true,
'show_in_menu' => true,
'capability_type' => 'post',
'map_meta_cap' => true,
'hierarchical' => false,
'rewrite' => array('slug' => 'equipment_sales', 'with_front' => true),
'query_var' => true,
'supports' => array('title','editor','excerpt','trackbacks','custom-fields','comments','revisions','thumbnail','author','page-attributes','post-formats'),
'labels' => array (
  'name' => 'Equipment Sales',
  'singular_name' => 'Equipment',
  'menu_name' => 'Equipment Sales',
  'add_new' => 'Add Equipment',
  'add_new_item' => 'Add New Equipment',
  'edit' => 'Edit',
  'edit_item' => 'Edit Equipment',
  'new_item' => 'New Equipment',
  'view' => 'View Equipment',
  'view_item' => 'View Equipment',
  'search_items' => 'Search Equipment Sales',
  'not_found' => 'No Equipment Sales Found',
  'not_found_in_trash' => 'No Equipment Sales Found in Trash',
  'parent' => 'Parent Equipment',
)
) ); }


// Print Services
add_action('init', 'cptui_register_my_cpt_print_services');
function cptui_register_my_cpt_print_services() {
register_post_type('print_services', array(
'label' => 'Print Services',
'description' => '',
'public' => true,
'show_ui' => true,
'show_in_menu' => true,
'capability_type' => 'post',
'map_meta_cap' => true,
'hierarchical' => false,
'rewrite' => array('slug' => 'print_services', 'with_front' => true),
'query_var' => true,
'supports' => array('title','editor','excerpt','trackbacks','custom-fields','comments','revisions','thumbnail','author','page-attributes','post-formats'),
'labels' => array (
  'name' => 'Print Services',
  'singular_name' => 'Service',
  'menu_name' => 'Print Services',
  'add_new' => 'Add Service',
  'add_new_item' => 'Add New Service',
  'edit' => 'Edit',
  'edit_item' => 'Edit Service',
  'new_item' => 'New Service',
  'view' => 'View Service',
  'view_item' => 'View Service',
  'search_items' => 'Search Print Services',
  'not_found' => 'No Print Services Found',
  'not_found_in_trash' => 'No Print Services Found in Trash',
  'parent' => 'Parent Service',
)
) ); }


// About Us
add_action('init', 'cptui_register_my_cpt_about_us');
function cptui_register_my_cpt_about_us() {
register_post_type('about_us', array(
'label' => 'About Us',
'description' => '',
'public' => true,
'show_ui' => true,
'show_in_menu' => true,
'capability_type' => 'post',
'map_meta_cap' => true,
'hierarchical' => false,
'rewrite' => array('slug' => 'about_us', 'with_front' => true),
'query_var' => true,
'supports' => array('title','editor','excerpt','trackbacks','custom-fields','comments','revisions','thumbnail','author','page-attributes','post-formats'),
'labels' => array (
  'name' => 'About Us',
  'singular_name' => 'About Us',
  'menu_name' => 'About Us',
  'add_new' => 'Add About Us',
  'add_new_item' => 'Add New About Us',
  'edit' => 'Edit',
  'edit_item' => 'Edit About Us',
  'new_item' => 'New About Us',
  'view' => 'View About Us',
  'view_item' => 'View About Us',
  'search_items' => 'Search About Us',
  'not_found' => 'No About Us Found',
  'not_found_in_trash' => 'No About Us Found in Trash',
  'parent' => 'Parent About Us',
)
) ); }


// Testimonials
add_action('init', 'cptui_register_my_cpt_testimonial');
function cptui_register_my_cpt_testimonial() {
register_post_type('testimonial', array(
'label' => 'Testimonials',
'description' => '',
'public' => true,
'show_ui' => true,
'show_in_menu' => true,
'capability_type' => 'post',
'map_meta_cap' => true,
'hierarchical' => false,
'rewrite' => array('slug' => 'testimonial', 'with_front' => true),
'query_var' => true,
'supports' => array('title','editor','excerpt','trackbacks','custom-fields','comments','revisions','thumbnail','author','page-attributes','post-formats'),
'labels' => array (
  'name' => 'Testimonials',
  'singular_name' => 'Testimonial',
  'menu_name' => 'Testimonials',
  'add_new' => 'Add Testimonial',
  'add_new_item' => 'Add New Testimonial',
  'edit' => 'Edit',
  'edit_item' => 'Edit Testimonial',
  'new_item' => 'New Testimonial',
  'view' => 'View Testimonial',
  'view_item' => 'View Testimonial',
  'search_items' => 'Search Testimonials',
  'not_found' => 'No Testimonials Found',
  'not_found_in_trash' => 'No Testimonials Found in Trash',
  'parent' => 'Parent Testimonial',
)
) ); }


// Brands Taxonomy
add_action('init', 'cptui_register_my_taxes_brands');
function cptui_register_my_taxes_brands() {
register_taxonomy( 'brands',array (
  0 => 'equipment_sales',
),
array( 'hierarchical' => false,
	'label' => 'Brands',
	'show_ui' => true,
	'query_var' => true,
	'show_admin_column' => false,
	'labels' => array (
  'search_items' => 'Brand',
  'popular_items' => '',
  'all_items' => '',
  'parent_item' => '',
  'parent_item_colon' => '',
  'edit_item' => '',
  'update_item' => '',
  'add_new_item' => '',
  'new_item_name' => '',
  'separate_items_with_commas' => '',
  'add_or_remove_items' => '',
  'choose_from_most_used' => '',
)
) ); 
}
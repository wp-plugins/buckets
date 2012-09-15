<?php
/* 
Plugin Name: Buckets
Plugin URI: http://www.matthewrestorff.com
Description: A Widget Alternative. Add reusable content inside of content. On a per page basis.
Author: Matthew Restorff
Version: 0.1.5
Author URI: http://www.matthewrestorff.com 
*/  



/*--------------------------------------------------------------------------------------
*
*	Buckets
*
*	@author Matthew Restorff
* 
*-------------------------------------------------------------------------------------*/
$version = '0.1.5';
add_action('init', 'init');
add_action('admin_menu', 'admin_menu');
add_action( 'admin_head', 'admin_head' );
add_shortcode( 'bucket', 'buckets_shortcode' );


/*--------------------------------------------------------------------------------------
*
*	init
*
*	@author Matthew Restorff
* 
*-------------------------------------------------------------------------------------*/

function init() 
{

	$labels = array(
	    'name' => __( 'Buckets', 'buckets' ),
		'singular_name' => __( 'Bucket', 'buckets' ),
	    'add_new' => __( 'Add New' , 'buckets' ),
	    'add_new_item' => __( 'Add New Bucket' , 'buckets' ),
	    'edit_item' =>  __( 'Edit Bucket' , 'buckets' ),
	    'new_item' => __( 'New Bucket' , 'buckets' ),
	    'view_item' => __('View Bucket', 'buckets'),
	    'search_items' => __('Search Buckets', 'buckets'),
	    'not_found' =>  __('No Buckets found', 'buckets'),
	    'not_found_in_trash' => __('No Buckets found in Trash', 'buckets'), 
	);

	register_post_type('buckets', array(
		'labels' => $labels,
		'public' => true,
		'show_ui' => true,
		'_builtin' =>  false,
		'capability_type' => 'page',
		'hierarchical' => true,
		'rewrite' => false,
		'query_var' => "buckets",
		'supports' => array(
			'title', 'editor', 'revisions',
		),
		'show_in_menu'	=> true,
	));
	
	// Make Sure ACF is loaded
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	if (is_plugin_active('advanced-custom-fields/acf.php')) 
	{
		remove_post_type_support( 'buckets', 'editor' );
		load_first();
		register_field('Buckets_field', WP_PLUGIN_DIR . '/buckets/fields/buckets.php');
	}

}



/*--------------------------------------------------------------------------------------
*
*	admin_menu
*
*	@author Matthew Restorff
* 
*-------------------------------------------------------------------------------------*/

function admin_menu()
{
	//add_submenu_page('edit.php?post_type=buckets', __('Manage','acf'), __('Manage','acf'), 'manage_options','manage-buckets', 'manage_buckets');
}



/*--------------------------------------------------------------------------------------
*
*	admin_head
*
*	@author Matthew Restorff
* 
*-------------------------------------------------------------------------------------*/

function admin_head()
{
	global $version;
	wp_enqueue_style('bucket-icons', plugins_url('',__FILE__) . '/css/icons.css?v=' . $version);
	if (isset($GLOBALS['post_type']) && $GLOBALS['post_type'] == 'buckets')
	{
		wp_enqueue_script('clipboard', plugins_url('',__FILE__) . '/js/zclip.js?v=' . $version);
		wp_enqueue_script('buckets', plugins_url('',__FILE__) . '/js/buckets.js?v=' . $version);
		wp_enqueue_style('buckets', plugins_url('',__FILE__) . '/css/buckets.css?v=' . $version);
		if ($GLOBALS['pagenow'] == 'post.php')
		{
			add_meta_box('buckets-shortcode', 'Shortcode', 'shortcode_meta_box', 'buckets', 'normal', 'high');
		}
	}
}



/*--------------------------------------------------------------------------------------
*
*	manage_buckets
*
*	@author Matthew Restorff
* 
*-------------------------------------------------------------------------------------*/

function manage_buckets()
{
	include('admin/manage.php');
}



/*--------------------------------------------------------------------------------------
*
*	buckets_shortcode
*
*	@author Matthew Restorff
* 
*-------------------------------------------------------------------------------------*/

function buckets_shortcode($arg) 
{
	$return = get_bucket($arg['id'], true);
	return $return;
}



/*--------------------------------------------------------------------------------------
*
*	shortcode_meta_box
*
*	@author Matthew Restorff
* 
*-------------------------------------------------------------------------------------*/

function shortcode_meta_box()
{
	include('admin/shortcode.php');
}




/*--------------------------------------------------------------------------------------
*
*	get_bucket
*	outputs the bucket template
*
*	@author Matthew Restorff
*	@params id - post id of the bucket element
*	@params sc - if called from a shortcode the content needs to be put into a variable to output in the correct place
* 
*-------------------------------------------------------------------------------------*/

function get_bucket($id, $sc = false)
{

	$post = wp_get_single_post($id);
	$return = ($post->post_content != '') ? $post->post_content : '';

	//If ACF is Active perform some wizardry
	if (is_plugin_active('advanced-custom-fields/acf.php')) {
		while(has_sub_field("buckets", $id)) {
			$layout = get_row_layout();
		    if ($sc == true) { ob_start(); }

		    $file = str_replace(' ', '', $layout) . '.php';
		    $path = (file_exists(TEMPLATEPATH . '/buckets/' . $file)) ? TEMPLATEPATH . '/buckets/' . $file : WP_PLUGIN_DIR . '/buckets/templates/' . $file;
		    if (file_exists($path)) {
		    	include($path);
		    } else {
		    	return 'Bucket template does not exist.';
		    }

		    if ($sc == true) { $return .= ob_get_contents(); }

		    if ($sc == true) { ob_end_clean(); }
		}
	}
    return $return;
}



/*--------------------------------------------------------------------------------------
*
*	load_first
*	Loads the buckets plugin before the ACF plugin to ensure compatibility
*
*	@author Matthew Restorff
* 
*-------------------------------------------------------------------------------------*/

function load_first() 
{
	$this_plugin = 'buckets/buckets.php';
	$active_plugins = get_option('active_plugins');
	$this_plugin_key = array_search($this_plugin, $active_plugins);
	if ($this_plugin_key) 
	{
		array_splice($active_plugins, $this_plugin_key, 1);
		array_unshift($active_plugins, $this_plugin);
		update_option('active_plugins', $active_plugins);
	}
}




?>
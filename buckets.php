<?php
/* 
Plugin Name: Buckets
Plugin URI: http://www.matthewrestorff.com
Description: A Widget Alternative. Add reusable content inside of content. On a per page basis.
Author: Matthew Restorff
Version: 0.1.8
Author URI: http://www.matthewrestorff.com 
*/  


/*--------------------------------------------------------------------------------------
*
*	Buckets
*
*	@author Matthew Restorff
* 
*-------------------------------------------------------------------------------------*/
$bucket_version = '0.1.8';
add_action('init', 'buckets_init');
add_action( 'admin_head', 'buckets_admin_head' );
add_shortcode( 'bucket', 'buckets_shortcode' );


/*--------------------------------------------------------------------------------------
*
*	init
*
*	@author Matthew Restorff
* 
*-------------------------------------------------------------------------------------*/

function buckets_init() 
{

	// Setup Buckets
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

	// Create TinyMCE Button
	create_tinymce_button();


	
	// Make Sure ACF is loaded
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	if (is_plugin_active('advanced-custom-fields/acf.php')) 
	{
		remove_post_type_support( 'buckets', 'editor' );
		load_first();
		register_field('Buckets_field', WP_PLUGIN_DIR . '/buckets/fields/buckets.php');
		create_bucket_field_groups();
	}

}



/*--------------------------------------------------------------------------------------
*
*	create_tinymce_button
*
*	@author Matthew Restorff
* 
*-------------------------------------------------------------------------------------*/

function create_tinymce_button()
{	
	// Don't bother doing this stuff if the current user lacks permissions
   if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
     return;
 
   // Add only in Rich Editor mode
   if ( get_user_option('rich_editing') == 'true') {
      add_filter( 'mce_external_plugins', 'add_plugin' );
      add_filter( 'mce_buttons', 'register_button' );
   }
}

function add_plugin($plugin_array) {   
   $plugin_array['buckets'] = plugins_url() . '/buckets/js/tinymce/bucketshortcode.js';
   return $plugin_array;
}

function register_button($buttons) {
   array_push( $buttons, "|", "buckets" );
   return $buttons;
}





/*--------------------------------------------------------------------------------------
*
*	create_field_group
*
*	@author Matthew Restorff
* 
*-------------------------------------------------------------------------------------*/

function create_bucket_field_groups()
{


	// See if the field group "Buckets" exists already. 
	$arr = (array)get_page_by_title('Buckets', OBJECT, 'acf');

	if (empty($arr)) {
		$buckets = array(
			'post_title'  => 'Buckets',
			'post_name'   => 'acf_buckets',
			'post_status' => 'publish',
			'post_type'   => 'acf',
			'comment_status' => 'closed',
			'ping_status' => 'closed'
		);
		$post_id = wp_insert_post($buckets);

		add_post_meta($post_id, '_edit_last', '1');
		add_post_meta($post_id, 'field_bucketskey777', 'a:10:{s:3:"key";s:19:"field_bucketskey777";s:5:"label";s:7:"Buckets";s:4:"name";s:7:"buckets";s:4:"type";s:16:"flexible_content";s:12:"instructions";s:0:"";s:8:"required";s:1:"0";s:7:"layouts";a:1:{i:0;a:4:{s:5:"label";s:13:"Visual Editor";s:4:"name";s:13:"visual_editor";s:7:"display";s:5:"table";s:10:"sub_fields";a:1:{i:0;a:7:{s:5:"label";s:7:"Content";s:4:"name";s:7:"content";s:4:"type";s:7:"wysiwyg";s:7:"toolbar";s:4:"full";s:12:"media_upload";s:3:"yes";s:3:"key";s:19:"field_50402dcb0fb1b";s:8:"order_no";s:1:"0";}}}}s:10:"sub_fields";a:1:{i:0;a:1:{s:3:"key";s:19:"field_50402dbe9787c";}}s:12:"button_label";s:12:"+ Add Bucket";s:8:"order_no";s:1:"0";}');
		add_post_meta($post_id, 'allorany', 'all');
		add_post_meta($post_id, 'rule', 'a:4:{s:5:"param";s:9:"post_type";s:8:"operator";s:2:"==";s:5:"value";s:7:"buckets";s:8:"order_no";s:1:"0";}');
		add_post_meta($post_id, 'position', 'normal');
		add_post_meta($post_id, 'layout', 'no_box');
		add_post_meta($post_id, 'hide_on_screen', 'a:9:{i:0;s:11:"the_content";i:1;s:7:"excerpt";i:2;s:13:"custom_fields";i:3;s:10:"discussion";i:4;s:8:"comments";i:5;s:4:"slug";i:6;s:6:"author";i:7;s:6:"format";i:8;s:14:"featured_image";}');

		$sidebars = array(
			'post_title'  => 'Sidebars',
			'post_name'   => 'acf_sidebars',
			'post_status' => 'publish',
			'post_type'   => 'acf',
			'comment_status' => 'closed',
			'ping_status' => 'closed'
		);
		$post_id = wp_insert_post($sidebars);

		add_post_meta($post_id, '_edit_last', '1');
		add_post_meta($post_id, 'field_bucketskey778', 'a:8:{s:5:"label";s:7:"Sidebar";s:4:"name";s:7:"sidebar";s:4:"type";s:13:"buckets_field";s:12:"instructions";s:0:"";s:8:"required";s:1:"0";s:3:"max";s:0:"";s:3:"key";s:19:"field_bucketskey778";s:8:"order_no";s:1:"0";}');
		add_post_meta($post_id, 'allorany', 'all');
		add_post_meta($post_id, 'rule', 'a:4:{s:5:"param";s:9:"post_type";s:8:"operator";s:2:"==";s:5:"value";s:4:"page";s:8:"order_no";s:1:"0";}');
		add_post_meta($post_id, 'position', 'normal');
		add_post_meta($post_id, 'layout', 'no_box');
		add_post_meta($post_id, 'hide_on_screen', '');
	} else {
		// The Buckets Field group already exists
	}

}





/*--------------------------------------------------------------------------------------
*
*	admin_head
*
*	@author Matthew Restorff
* 
*-------------------------------------------------------------------------------------*/

function buckets_admin_head()
{
	global $bucket_version;

	if (isset($GLOBALS['post_type']) && $GLOBALS['post_type'] == 'buckets')
	{
		wp_enqueue_script('clipboard', plugins_url('',__FILE__) . '/js/zclip.js?v=' . $bucket_version);
		wp_enqueue_style('buckets', plugins_url('',__FILE__) . '/css/buckets.css?v=' . $bucket_version);
		if ($GLOBALS['pagenow'] == 'post.php' && !isset($_GET['popup']))
		{
			add_meta_box('buckets-shortcode', 'Shortcode', 'shortcode_meta_box', 'buckets', 'normal', 'high');
		}
		
	}

	wp_enqueue_style('bucket-icons', plugins_url('',__FILE__) . '/css/icons.css?v=' . $bucket_version);
	wp_enqueue_script('buckets', plugins_url('',__FILE__) . '/js/buckets.js?v=' . $bucket_version);
	
	if (isset($_GET['popup'])){
		wp_enqueue_style('buckets-popup', plugins_url('',__FILE__) . '/css/popup.css?v=' . $bucket_version);
		wp_enqueue_script('buckets-popup', plugins_url('',__FILE__) . '/js/popup.js?v=' . $bucket_version);
	}
	
	// The WP Thickbox dimensions are hard coded into the media-upload. With this we strip it and make our own. 
	wp_deregister_script( 'media-upload' );
	wp_enqueue_script(
	    'media-upload', 
	    plugins_url('',__FILE__) . '/js/media-upload.js?v=' . $bucket_version, 
	    array( 'thickbox' )
	);
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
	$return = get_bucket($arg['id']);
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

function get_bucket($id)
{

	$post = get_post($id);
	$return = ($post->post_content != '') ? wpautop($post->post_content) : '';

	//If ACF is Active perform some wizardry
	if (is_plugin_active('advanced-custom-fields/acf.php')) {
		while(has_sub_field("buckets", $id)) {
			$layout = get_row_layout();
		    ob_start(); 

		    $file = str_replace(' ', '', $layout) . '.php';
		    $path = (file_exists(TEMPLATEPATH . '/buckets/' . $file)) ? TEMPLATEPATH . '/buckets/' . $file : WP_PLUGIN_DIR . '/buckets/templates/' . $file;
		    if (file_exists($path)) {
		    	include($path);
		    } else {
		    	echo 'Bucket template does not exist.'; 
		    }

		    $return = ob_get_clean(); 
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
<?php

class Buckets_field extends acf_Field
{
	
	/*--------------------------------------------------------------------------------------
	*
	*	Constructor
	*
	*	@author Elliot Condon
	*	@since 1.0.0
	*	@updated 2.2.0
	* 
	*-------------------------------------------------------------------------------------*/
	
	function __construct($parent)
	{
    	parent::__construct($parent);
    	
    	$this->name = 'buckets_field';
		$this->title = __("Buckets Sidebar",'acf');
		
		add_action('wp_ajax_acf_get_bucket_results', array($this, 'acf_get_bucket_results'));
   	}
   	
   	
   	/*--------------------------------------------------------------------------------------
	*
	*	acf_get_relationship_results
	*
	*	@author Elliot Condon
	*   @description: Generates HTML for Left column relationship results
	*   @created: 5/07/12
	* 
	*-------------------------------------------------------------------------------------*/
	
   	function acf_get_bucket_results()
   	{
   		// vars
		$options = array(
			'post_type'	=>	'buckets',
			'posts_per_page' => 10,
			'paged' => 0,
			'orderby' => 'title',
			'order' => 'ASC',
			'post_status' => array('publish', 'private', 'draft', 'inherit', 'future'),
			'suppress_filters' => false,
			's' => '',
		);
		$ajax = isset( $_POST['action'] ) ? true : false;

		
		// override options with posted values
		if( $ajax )
		{
			$options = array_merge($options, $_POST);
		}
		
		
		// search
		if( $options['s'] )
		{
			$options['like_title'] = $options['s'];
			
			add_filter( 'posts_where', array($this, 'posts_where'), 10, 2 );
		}
		
		unset( $options['s'] );
		
		
		// load the posts
		$posts = get_posts( $options );
		if( $posts )
		{
			foreach( $posts  as $post )
			{
				// right aligned info
				$layout = false;
				$title = '<span class="relationship-item-info">';
					while(has_sub_field('buckets', $post->ID)){
						$layout = str_replace('_', ' ', get_row_layout());
					}
					$title .= $layout;
					
				$title .= '</span>';
				
				// find title. Could use get_the_title, but that uses get_post(), so I think this uses less Memory
				$title .= apply_filters( 'the_title', $post->post_title, $post->ID );

				// status
				if($post->post_status != "publish")
				{
					$title .= " ($post->post_status)";
				}
				
				echo '<li><span class="edit" data-url="' . get_admin_url() . 'post.php?post=' . $post->ID . '&action=edit&popup=true&KeepThis=true&TB_iframe=true&height=200&width=200">Edit</span><a href="' . get_permalink($post->ID) . '" data-post_id="' . $post->ID . '">' . $title .  '<span class="add"></span></a></li>';
			}
		}
		
		
		// die?
		if( $ajax )
		{
			die();
		}
		
	}
	
	
   	/*--------------------------------------------------------------------------------------
	*
	*	admin_print_scripts / admin_print_styles
	*
	*	@author Elliot Condon
	*	@since 3.0.0
	* 
	*-------------------------------------------------------------------------------------*/
	
	function admin_print_scripts()
	{
		wp_enqueue_script(array(
			'jquery-ui-sortable',
		));
	}
	
	function admin_print_styles()
	{
  
	}
   		
	
	/*--------------------------------------------------------------------------------------
	*
	*	create_field
	*
	*	@author Elliot Condon
	*	@since 2.0.5
	*	@updated 2.2.0
	* 
	*-------------------------------------------------------------------------------------*/
	
	function create_field($field)
	{
		// vars
		$defaults = array(
			'post_type'	=>	'buckets',
			'max' 		=>	-1,
		);
		
		$field = array_merge($defaults, $field);
		
		
		// validate types
		$field['max'] = (int) $field['max'];
		
		
		// row limit <= 0?
		if( $field['max'] <= 0 )
		{
			$field['max'] = 9999;
		}
		
		$field['type'] = 'relationship';
		?>
<div class="acf_buckets" data-max="<?php echo $field['max']; ?>" data-s="" data-paged="1" data-post_type="buckets">
	
	<!-- Hidden Blank default value -->
	<input type="hidden" name="<?php echo $field['name']; ?>" value="" />
	
	<!-- Template for value -->
	<script type="text/html" class="tmpl-li">
	<li>
		<span class="edit" data-url="<?php echo get_admin_url() ?>post.php?post={post_id}&action=edit&popup=true&TB_iframe=1">Edit</span>
		<a href="#" data-post_id="{post_id}">{title}<span class="remove"></span></a>
		<input type="hidden" name="<?php echo $field['name']; ?>[]" value="{post_id}" />
	</li>
	</script>
	<!-- / Template for value -->
	
	<!-- Left List -->

	<div class="relationship_left">
		<table class="widefat">
			<thead>
				<tr>
					<th>
						<label class="relationship_label" for="relationship_<?php echo $field['name']; ?>"><?php _e("Search",'acf'); ?>...</label>
						<input class="relationship_search" type="text" id="relationship_<?php echo $field['name']; ?>" />
						<div class="clear_relationship_search"></div>
					</th>
				</tr>
			</thead>
		</table>
		<ul class="bl relationship_list">
			<li class="load-more">
				<div class="acf-loading"></div>
			</li>
		</ul>
		<a href="<?php echo bloginfo('url'); ?>/wp-admin/post-new.php?post_type=buckets&popup=true&TB_iframe=1" title="New Bucket" class="button-primary new-bucket thickbox">Add New</a>
	</div>
	<!-- /Left List -->
	
	<!-- Right List -->
	<div class="relationship_right">
		<ul class="bl relationship_list">
		<?php

		if( $field['value'] )
		{
			foreach( $field['value'] as $post )
			{
				
				// find title. Could use get_the_title, but that uses get_post(), so I think this uses less Memory
				$title = apply_filters( 'the_title', $post->post_title, $post->ID );


				// status
				if($post->post_status == "private" || $post->post_status == "draft")
				{
					$title .= " ($post->post_status)";
				}
				
				echo '<li>
					<span class="edit" data-url="' . get_admin_url() . 'post.php?post=' . $post->ID . '&action=edit&popup=true&TB_iframe=1">Edit</span>
					<a href="javascript:;" class="" data-post_id="' . $post->ID . '">' . $title . '<span class="remove"></span></a>
					<input type="hidden" name="' . $field['name'] . '[]" value="' . $post->ID . '" />
				</li>';
			}
		}
			
		?>
		</ul>
		
	</div>
	<!-- / Right List -->
	<div style="clear: both;"></div>
</div>


		<?php

	
	}
	
	
	/*--------------------------------------------------------------------------------------
	*
	*	create_options
	*
	*	@author Elliot Condon
	*	@since 2.0.6
	*	@updated 2.2.0
	* 
	*-------------------------------------------------------------------------------------*/
	
	function create_options($key, $field)
	{
		// vars
		$defaults = array(
			'post_type'	=>	'buckets',
			'max' 		=>	'',
			'taxonomy' 	=>	array('all'),
		);
		
		$field = array_merge($defaults, $field);
		
		?>
		
		<tr class="field_option field_option_<?php echo $this->name; ?>">
			<td class="label">
				<label><?php _e("Maximum posts",'acf'); ?></label>
			</td>
			<td>
				<?php 
				$this->parent->create_field(array(
					'type'	=>	'text',
					'name'	=>	'fields['.$key.'][max]',
					'value'	=>	$field['max'],
				));
				?>
			</td>
		</tr>
		<?php
	}
	

	/*--------------------------------------------------------------------------------------
	*
	*	get_value
	*
	*	@author Elliot Condon
	*	@since 3.3.3
	* 
	*-------------------------------------------------------------------------------------*/
	
	function get_value($post_id, $field)
	{
		// get value
		$value = parent::get_value($post_id, $field);
				
		// empty?
		if( !$value )
		{
			return $value;
		}
		
		
		// Pre 3.3.3, the value is a string coma seperated
		if( !is_array($value) )
		{
			$value = explode(',', $value);
		}
		
		
		// empty?
		if( empty($value) )
		{
			return $value;
		}
		

		
		// find posts (DISTINCT POSTS)
		$posts = get_posts(array(
			'numberposts' => -1,
			'post__in' => $value,
			'post_type'	=>	get_post_types( array('public' => true) ),
			'post_status' => array('publish', 'private', 'draft', 'inherit', 'future'),
		));

		
		$ordered_posts = array();
		foreach( $posts as $post )
		{	
			// create array to hold value data
			$ordered_posts[ $post->ID ] = $post;
		}
		
		
		// override value array with attachments
		foreach( $value as $k => $v)
		{
			$value[ $k ] = $ordered_posts[ $v ];

		}
						
		// return value
		return $value;	
	}
	


	function get_value_for_api($post_id, $field)
	{
		// get value
		$value = parent::get_value($post_id, $field);
				
		// empty?
		if( !$value )
		{
			return $value;
		}
		
		
		// Pre 3.3.3, the value is a string coma seperated
		if( !is_array($value) )
		{
			$value = explode(',', $value);
		}
		
		
		// empty?
		if( empty($value) )
		{
			return $value;
		}
		

		
		// find posts (DISTINCT POSTS)
		$posts = get_posts(array(
			'numberposts' => -1,
			'post__in' => $value,
			'post_type'	=>	get_post_types( array('public' => true) ),
			'post_status' => array('publish', 'private', 'draft', 'inherit', 'future'),
		));

		
		$ordered_posts = array();
		foreach( $posts as $post )
		{	
			// create array to hold value data
			$ordered_posts[ $post->ID ] = $post;
		}
				
		// override value array with attachments
		foreach( $value as $k => $v)
		{
			$buckets .= get_bucket($v);
			$value[ $k ] = $ordered_posts[ $v ];

		}
		

		return $buckets;

	}

	
}

?>
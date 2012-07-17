<?php

/*
 *	Advanced Custom Fields - New field template
 *	
 *	Create your field's functionality below and use the function:
 *	register_field($class_name, $file_path) to include the field
 *	in the acf plugin.
 *
 *	Documentation: 
 *
 */
 
 
class Buckets_field extends acf_Field
{

	/*--------------------------------------------------------------------------------------
	*
	*	Constructor
	*	- This function is called when the field class is initalized on each page.
	*	- Here you can add filters / actions and setup any other functionality for your field
	*
	*	@author Elliot Condon
	*	@since 2.2.0
	* 
	*-------------------------------------------------------------------------------------*/
	
	function __construct($parent)
	{
		// do not delete!
    	parent::__construct($parent);
    	
    	// set name / title
    	$this->name = 'buckets_field'; // variable name (no spaces / special characters / etc)
		$this->title = __("Bucket Area",'acf'); // field label (Displayed in edit screens)
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
		/*
		#field = Array ( [key] => field_4fbe5b6d8d3e4 [label] => Left Sidebar [name] => fields[field_4fbe5b6d8d3e4] [type] => buckets_field [instructions] => [required] => 0 [max] => -1 [order_no] => 0 [value] => 969,913 [class] => buckets_field )
		*/

		$field['max'] = isset($field['max']) ? $field['max'] : '-1';

		$posts = get_posts(array(
			'numberposts' 	=> 	-1,
			'post_type'		=>	'buckets',
			'orderby'		=>	'title',
			'order'			=>	'ASC',
			'post_status' => array('publish', 'private', 'draft'),
		));
		
		
		$values_array = array();
		if($field['value'] != "")
		{
			$temp_array = explode(',', $field['value']);
			foreach($temp_array as $p)
			{
				// if the post doesn't exist, continue
				if(!get_the_title($p)) continue;
				
				$values_array[] = $p;
			}
		}
		
		
		
		
		
		?>
		<div class="acf_relationship" data-max="<?php echo $field['max']; ?>">
			
			<input type="hidden" name="<?php echo $field['name']; ?>" value="<?php echo implode(',', $values_array); ?>" />
			
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
				<div class="relationship_list">
				<?php
				if($posts)
				{
					foreach($posts as $post)
					{
						if(!get_the_title($post->ID)) continue;
						
						$class = in_array($post->ID, $values_array) ? 'hide' : '';
						
						$title = get_the_title($post->ID);
						// status
						if($post->post_status == "private" || $post->post_status == "draft")
						{
							$title .= " ($post->post_status)";
						}
						
						echo '<a href="javascript:;" class="' . $class . '" data-post_id="' . $post->ID . '">' . $title . '<span class="add"></span></a>';
					}
				}
				?>
				</div>
			</div>
			
			<div class="relationship_right">
				<div class="relationship_list">
				<?php
				$temp_posts = array();
				
				if($posts)
				{
					foreach($posts as $post)
					{
						$temp_posts[$post->ID] = $post;
					}
				}
				
				if($temp_posts)
				{
					foreach($values_array as $value)
					{
						if(!isset($temp_posts[$value]))
						{
							continue;
						}
						
						$post = $temp_posts[$value];
						
						$title = get_the_title($post->ID);
						// status
						if($post->post_status == "private" || $post->post_status == "draft")
						{
							$title .= " ($post->post_status)";
						}
						
						echo '<a href="javascript:;" class="" data-post_id="' . $temp_posts[$value]->ID . '">' . $title . '<span class="remove"></span></a>';
						unset($temp_posts[$value]);
					}
					
					foreach($temp_posts as $id => $post)
					{
						$title = get_the_title($post->ID);
						// status
						if($post->post_status == "private" || $post->post_status == "draft")
						{
							$title .= " ($post->post_status)";
						}
						
						echo '<a href="javascript:;" class="hide" data-post_id="' . $post->ID . '">' . $title . '<span class="remove"></span></a>';
					}
				}
					
				?>
				</div>
			</div>
			
			
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
		// defaults
		$field['max'] = isset($field['max']) ? $field['max'] : '-1';
		?>
		<tr class="field_option field_option_<?php echo $this->name; ?>">
			<td class="label">
				<label><?php _e("Maximum Buckets",'acf'); ?></label>
				<p class="description"><?php _e("Set to -1 for infinite",'acf'); ?></p>
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
	*	get_value_for_api
	*
	*	@author Elliot Condon
	*	@since 3.0.0
	* 
	*-------------------------------------------------------------------------------------*/
	
	function get_value_for_api($post_id, $field)
	{
		// vars
		$value = parent::get_value($post_id, $field);
		$return = false;
		
		if(!$value || $value == "")
		{
			return $return;
		}
		
		$value = explode(',', $value);
		
		if(is_array($value))
		{
			$buckets = array();
			foreach($value as $v)
			{
				$buckets[] = get_post($v);
			}
		}
		else
		{
			$buckets = array(get_post($value));
		}

		foreach ($buckets as $row) {
			$return .= get_bucket($row->ID);
		}

		return $return;
	}
	

	
}

?>